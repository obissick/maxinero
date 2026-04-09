@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="flash-message mb-2"></div>

    <div class="d-flex align-items-center gap-2 my-3">
        <h1 class="mb-0">Services</h1>
        <button id="addservice" name="addservice" class="btn btn-success btn-sm ms-2"
                data-bs-toggle="modal" data-bs-target="#service">+ Add Service</button>
    </div>

    <div class="card mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-striped table-hover align-middle mb-0" id="services-table">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Router</th>
                            <th>State</th>
                            <th>Total Conns</th>
                            <th>Conns</th>
                            <th>Started</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="services-list">
                        @foreach($services['data'] ?? [] as $svc)
                        @php
                            $svid = $svc['id'];
                            $svcState = $svc['attributes']['state'] ?? '';
                        @endphp
                        <tr id="service{{ $svid }}">
                            <td>
                                <a href="{{ route('services.show', $svid) }}" class="btn btn-primary btn-sm">{{ $svid }}</a>
                            </td>
                            <td><code>{{ $svc['attributes']['router'] ?? '' }}</code></td>
                            <td id="state{{ $svid }}">
                                <span class="badge bg-{{ $svcState === 'Started' ? 'success' : 'secondary' }}">{{ $svcState }}</span>
                            </td>
                            <td>{{ $svc['attributes']['total_connections'] ?? 0 }}</td>
                            <td>{{ $svc['attributes']['connections'] ?? 0 }}</td>
                            <td><small>{{ $svc['attributes']['started'] ?? '' }}</small></td>
                            <td id="action{{ $svid }}">
                                @if($svcState === 'Started')
                                    <button class="btn btn-warning btn-sm stop-service" value="{{ $svid }}">Stop</button>
                                @else
                                    <button class="btn btn-success btn-sm start-service" value="{{ $svid }}">Start</button>
                                @endif
                                <button class="btn btn-info btn-sm edit-service" value="{{ $svid }}">Edit</button>
                                <button class="btn btn-danger btn-sm delete-service" value="{{ $svid }}">Delete</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex align-items-center gap-2 mb-3">
        <h1 class="mb-0">Monitors</h1>
        <button id="btn-add" name="btn-add" class="btn btn-success btn-sm ms-2"
                data-bs-toggle="modal" data-bs-target="#monitor">+ Add Monitor</button>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-striped table-hover align-middle mb-0" id="monitors-table">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Module</th>
                            <th>State</th>
                            <th>Servers</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="monitors-list">
                        @foreach($monitors['data'] ?? [] as $mon)
                        @php
                            $mid = $mon['id'];
                            $monState = $mon['attributes']['state'] ?? '';
                        @endphp
                        <tr id="monitor{{ $mid }}">
                            <td>{{ $mid }}</td>
                            <td>{{ $mon['type'] ?? '' }}</td>
                            <td><code>{{ $mon['attributes']['module'] ?? '' }}</code></td>
                            <td>
                                <span class="badge bg-{{ $monState === 'Running' ? 'success' : 'secondary' }}">{{ $monState }}</span>
                            </td>
                            <td>
                                @foreach($mon['relationships']['servers']['data'] ?? [] as $sr)
                                    <span class="badge bg-secondary me-1">{{ $sr['id'] }}</span>
                                @endforeach
                            </td>
                            <td>
                                @if($monState === 'Running')
                                    <button class="btn btn-warning btn-sm stop-monitor" value="{{ $mid }}">Stop</button>
                                @else
                                    <button class="btn btn-success btn-sm start-monitor" value="{{ $mid }}">Start</button>
                                @endif
                                <button class="btn btn-info btn-sm edit-monitor" value="{{ $mid }}">Edit</button>
                                <button class="btn btn-danger btn-sm delete-monitor" value="{{ $mid }}">Delete</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Monitor Modal --}}
<div class="modal fade" id="monitor" tabindex="-1" aria-labelledby="monitorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="monitorModalLabel">Monitor Editor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addmonitor" name="addmonitor" novalidate>
                    @csrf
                    <div class="row mb-3">
                        <label for="monitor_id" class="col-sm-3 col-form-label">Monitor Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="monitor_id" name="monitor_id" placeholder="ID">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="monitor_type" class="col-sm-3 col-form-label">Type</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="monitor_type" name="monitor_type" placeholder="monitors">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="module" class="col-sm-3 col-form-label">Module</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="module" name="module" placeholder="mariadbmon">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="monitor_interval" class="col-sm-3 col-form-label">Interval (ms)</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="monitor_interval" name="monitor_interval" placeholder="1000">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="monuser" class="col-sm-3 col-form-label">User</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="monuser" name="monuser" placeholder="user">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="monpass" class="col-sm-3 col-form-label">Password</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" id="monpass" name="monpass" placeholder="password">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="servers" class="col-sm-3 col-form-label">Servers</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="servers" name="servers" placeholder="Comma-separated">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="add-mon" value="add">Save changes</button>
                        <input type="hidden" id="monitorid" name="monitorid" value="0">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Service Modal --}}
<div class="modal fade" id="service" tabindex="-1" aria-labelledby="serviceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="serviceModalLabel">Service Editor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addservice" name="addservice" novalidate>
                    @csrf
                    <div class="row mb-3">
                        <label for="service_id" class="col-sm-3 col-form-label">Service Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="service_id" name="service_id" placeholder="ID">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="service_type" class="col-sm-3 col-form-label">Type</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="service_type" name="service_type" placeholder="services">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="service_module" class="col-sm-3 col-form-label">Router Module</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="service_module" name="service_module" placeholder="readwritesplit">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="user" class="col-sm-3 col-form-label">User</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="user" name="user" placeholder="db_user">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="password" class="col-sm-3 col-form-label">Password</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="add-service" value="add">Save changes</button>
                        <input type="hidden" id="serviceid" name="serviceid" value="0">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#services-table').DataTable({ pageLength: 25 });
        $('#monitors-table').DataTable({ pageLength: 25 });
    });
</script>
@endsection
