@extends('layouts.app')

@section('content')
<script>
    var times    = {!! $times !!};
    var sum_conn = {!! $sum_conn !!};
    var sum_ops  = {!! $sum_ops !!};

    var config = {
        type: 'line',
        data: {
            labels: times,
            datasets: [
                {
                    data: sum_conn,
                    label: "Current Connections",
                    borderColor: "#20c997",
                    backgroundColor: "rgba(32,201,151,0.08)",
                    fill: true,
                    tension: 0.3
                },
                {
                    data: sum_ops,
                    label: "Current Operations",
                    borderColor: "#fd7e14",
                    backgroundColor: "rgba(253,126,20,0.08)",
                    fill: true,
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            if (Number.isInteger(value)) return value;
                        }
                    }
                }
            }
        }
    };

    document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('line').getContext('2d');
        window.myChart = new Chart(ctx, config);
    });
</script>

<div class="container-fluid px-4">
    <div class="flash-message mb-2"></div>
    <div class="d-flex align-items-center gap-2 my-3">
        <h1 class="mb-0">DB Servers</h1>
        <button id="btn-add" name="btn-add" class="btn btn-success btn-sm ms-2">+ Add Server</button>
    </div>

    <div class="card mb-4">
        <div class="card-header">Connections &amp; Operations (last hour)</div>
        <div class="card-body">
            <canvas id="line" height="80"></canvas>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-striped table-hover align-middle mb-0" id="servers-table">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Address</th>
                            <th>Port</th>
                            <th>Protocol</th>
                            <th>State</th>
                            <th>Conns</th>
                            <th>Total Conns</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="servers-list">
                        @foreach($servers['data'] ?? [] as $srv)
                        @php
                            $sid     = $srv['id'];
                            $params  = $srv['attributes']['parameters'] ?? [];
                            $stats   = $srv['attributes']['statistics'] ?? [];
                            $state   = $srv['attributes']['state'] ?? '';
                            $stateClass = str_contains($state, 'Running') || str_contains($state, 'Master') ? 'success'
                                        : (str_contains($state, 'Maintenance') ? 'warning' : 'secondary');
                        @endphp
                        <tr id="server{{ $sid }}">
                            <td>
                                <a href="{{ route('servers.show', $sid) }}" class="btn btn-primary btn-sm">{{ $sid }}</a>
                            </td>
                            <td>{{ $params['address'] ?? '' }}</td>
                            <td>{{ $params['port'] ?? '' }}</td>
                            <td><code>{{ $params['protocol'] ?? '' }}</code></td>
                            <td><span class="badge bg-{{ $stateClass }}">{{ $state }}</span></td>
                            <td>{{ $stats['connections'] ?? 0 }}</td>
                            <td>{{ $stats['total_connections'] ?? 0 }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <div class="dropdown">
                                        <button class="btn btn-warning btn-sm dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                            State
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><button type="button" class="dropdown-item master" value="{{ $sid }}">master</button></li>
                                            <li><button type="button" class="dropdown-item slave" value="{{ $sid }}">slave</button></li>
                                            <li><button type="button" class="dropdown-item maintenance" value="{{ $sid }}">maintenance</button></li>
                                            <li><button type="button" class="dropdown-item running" value="{{ $sid }}">running</button></li>
                                            <li><button type="button" class="dropdown-item synced" value="{{ $sid }}">synced</button></li>
                                            <li><button type="button" class="dropdown-item drain" value="{{ $sid }}">drain</button></li>
                                            <li><button type="button" class="dropdown-item ndb" value="{{ $sid }}">ndb</button></li>
                                            <li><button type="button" class="dropdown-item stale" value="{{ $sid }}">stale</button></li>
                                        </ul>
                                    </div>
                                    <button class="btn btn-info btn-sm open-modal" value="{{ $sid }}">Edit</button>
                                    <button class="btn btn-danger btn-sm delete-server" value="{{ $sid }}">Delete</button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Server Edit / Add Modal --}}
<div class="modal fade" id="server" tabindex="-1" aria-labelledby="serverModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="serverModalLabel">Server Editor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addserver" name="addserver" novalidate>
                    @csrf
                    <div class="row mb-3">
                        <label for="server_id" class="col-sm-3 col-form-label">Server ID</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="server_id" name="server_id" placeholder="my-server-01">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="address" class="col-sm-3 col-form-label">Address</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="address" name="address" placeholder="0.0.0.0">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="port" class="col-sm-3 col-form-label">Port</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="port" name="port" placeholder="3306">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="protocol" class="col-sm-3 col-form-label">Protocol</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="protocol" name="protocol" placeholder="MariaDBBackend">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="ssl_key" class="col-sm-3 col-form-label">SSL Key</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="ssl_key" name="ssl_key" placeholder="Optional">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="ssl_cert" class="col-sm-3 col-form-label">SSL Cert</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="ssl_cert" name="ssl_cert" placeholder="Optional">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="ssl_ca_cert" class="col-sm-3 col-form-label">SSL CA Cert</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="ssl_ca_cert" name="ssl_ca_cert" placeholder="Optional">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="services" class="col-sm-3 col-form-label">Services</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="services" name="services" placeholder="Comma-separated">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="monitors" class="col-sm-3 col-form-label">Monitors</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="monitors" name="monitors" placeholder="Comma-separated">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="btn-save" value="add">Save changes</button>
                        <input type="hidden" id="server_id_hidden" name="server_id_hidden" value="0">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#servers-table').DataTable({ pageLength: 25 });
    });
</script>
@endsection
