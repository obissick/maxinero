@extends('layouts.app')

@section('content')
<script>
    var times     = {!! $times !!};
    var avg_ctime = {!! $avg_ctime !!};

    var config = {
        type: 'line',
        data: {
            labels: times,
            datasets: [{
                data: avg_ctime,
                label: "Avg Connections",
                borderColor: "#3e95cd",
                backgroundColor: "rgba(62,149,205,0.08)",
                fill: true,
                tension: 0.3
            }]
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

@php
    $svc    = $service['data'];
    $svcAttr = $svc['attributes'] ?? [];
    $svcState = $svcAttr['state'] ?? '';
    $router   = $svcAttr['router'] ?? '';
    $queries  = $svcAttr['router_diagnostics']['queries'] ?? null;
@endphp

<div class="container-fluid px-4">
    <div class="flash-message mb-2"></div>
    <div class="d-flex align-items-center gap-2 my-3">
        <h1 class="mb-0">{{ $svc['id'] }}</h1>
        <span class="badge bg-{{ $svcState === 'Started' ? 'success' : 'secondary' }} fs-6 align-middle">{{ $svcState }}</span>
    </div>

    <div class="card mb-4">
        <div class="card-header">Avg Connections (last hour)</div>
        <div class="card-body">
            <canvas id="line" height="80"></canvas>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">Service Details</div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <tbody>
                            <tr><th class="w-40">Router</th><td><code>{{ $router }}</code></td></tr>
                            <tr><th>Total Connections</th><td>{{ $svcAttr['total_connections'] ?? 0 }}</td></tr>
                            <tr><th>Connections</th><td>{{ $svcAttr['connections'] ?? 0 }}</td></tr>
                            <tr><th>Started</th><td>{{ $svcAttr['started'] ?? '—' }}</td></tr>
                            @if($queries !== null)
                            <tr><th>Queries (diagnostics)</th><td>{{ $queries }}</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">Backend Servers</div>
                <div class="card-body">
                    @foreach($svc['relationships']['servers']['data'] ?? [] as $sr)
                        <span class="badge bg-primary me-1 mb-1">{{ $sr['id'] }}</span>
                    @endforeach
                    @if(empty($svc['relationships']['servers']['data'] ?? []))
                        <span class="text-muted">None</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex align-items-center gap-2 mb-3">
        <h2 class="mb-0">Listeners</h2>
        <button id="add-listener-button" name="add-listener-button" class="btn btn-success btn-sm ms-2"
                data-bs-toggle="modal" data-bs-target="#listener">+ Add Listener</button>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-striped table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Port</th>
                            <th>Protocol</th>
                            <th>Authenticator</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="listeners-list">
                        @foreach($listeners['data'] ?? [] as $lst)
                        @php
                            $lstParams = $lst['attributes']['parameters'] ?? $lst['attributes'] ?? [];
                            $lstAuth   = $lstParams['authenticator'] ?? $lst['attributes']['authenticator'] ?? '—';
                        @endphp
                        <tr id="listener{{ $lst['id'] }}">
                            <td>{{ $lst['id'] }}</td>
                            <td>{{ $lst['type'] ?? '' }}</td>
                            <td>{{ $lstParams['port'] ?? '' }}</td>
                            <td><code>{{ $lstParams['protocol'] ?? '' }}</code></td>
                            <td>{{ $lstAuth }}</td>
                            <td>
                                <button class="btn btn-danger btn-sm delete-listener" value="{{ $lst['id'] }}">Delete</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Listener Modal --}}
<div class="modal fade" id="listener" tabindex="-1" aria-labelledby="listenerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="listenerModalLabel">Listener Editor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addlistner" name="addlistener" novalidate>
                    @csrf
                    <div class="row mb-3">
                        <label for="listener_id" class="col-sm-4 col-form-label">Listener Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="listener_id" name="listener_id" placeholder="listener-01">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="listener_type" class="col-sm-4 col-form-label">Type</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="listener_type" name="listener_type" placeholder="listeners">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="address" class="col-sm-4 col-form-label">Address <small class="text-muted">(optional)</small></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="address" name="address" placeholder="0.0.0.0">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="port" class="col-sm-4 col-form-label">Port</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="port" name="port" placeholder="3306">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="protocol" class="col-sm-4 col-form-label">Protocol <small class="text-muted">(optional)</small></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="protocol" name="protocol" placeholder="MariaDBProtocol">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="auth" class="col-sm-4 col-form-label">Authenticator <small class="text-muted">(optional)</small></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="auth" name="auth">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="auth_options" class="col-sm-4 col-form-label">Auth Options <small class="text-muted">(optional)</small></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="auth_options" name="auth_options">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="ssl_key" class="col-sm-4 col-form-label">SSL Key <small class="text-muted">(optional)</small></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="ssl_key" name="ssl_key">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="ssl_cert" class="col-sm-4 col-form-label">SSL Cert <small class="text-muted">(optional)</small></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="ssl_cert" name="ssl_cert">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="ssl_ca_cert" class="col-sm-4 col-form-label">SSL CA Cert <small class="text-muted">(optional)</small></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="ssl_ca_cert" name="ssl_ca_cert">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="ssl_version" class="col-sm-4 col-form-label">SSL Version <small class="text-muted">(optional)</small></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="ssl_version" name="ssl_version">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="ssl_depth" class="col-sm-4 col-form-label">SSL Verify Depth <small class="text-muted">(optional)</small></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="ssl_depth" name="ssl_depth">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="add-listener" value="add">Save changes</button>
                        <input type="hidden" id="service_id" name="service_id" value="{{ $svc['id'] }}">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
