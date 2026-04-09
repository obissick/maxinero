@extends('layouts.app')

@section('content')
@php
    $attr = $maxscale['data']['attributes'] ?? [];
    $p    = $attr['parameters'] ?? [];
@endphp
<div class="container-fluid px-4">
    <div class="flash-message mb-2"></div>
    <div class="d-flex align-items-center justify-content-between my-3">
        <h1 class="mb-0">MaxScale</h1>
        <button type="button" class="btn btn-primary"
                data-bs-toggle="modal" data-bs-target="#favoritesModal">
            Log Info
        </button>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-2">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="text-muted small">Version</div>
                    <div class="fw-bold">{{ $attr['version'] ?? '—' }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-2">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="text-muted small">Uptime (s)</div>
                    <div class="fw-bold">{{ $attr['uptime'] ?? '—' }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-2">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="text-muted small">Threads</div>
                    <div class="fw-bold">{{ $p['threads'] ?? '—' }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="text-muted small">Started At</div>
                    <div class="fw-bold small">{{ $attr['started_at'] ?? '—' }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="text-muted small">Activated At</div>
                    <div class="fw-bold small">{{ $attr['activated_at'] ?? '—' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">Instance Info</div>
                <div class="card-body p-0">
                    <table class="table table-sm table-borderless mb-0">
                        <tbody>
                            <tr><th class="w-40">Commit</th><td><code>{{ $attr['commit'] ?? '—' }}</code></td></tr>
                            <tr><th>Query Classifier</th><td>{{ $p['query_classifier'] ?? '—' }}</td></tr>
                            <tr><th>Admin Host</th><td>{{ $p['admin_host'] ?? '—' }}</td></tr>
                            <tr><th>Admin Port</th><td>{{ $p['admin_port'] ?? '—' }}</td></tr>
                            <tr><th>Admin Auth</th><td>{{ isset($p['admin_auth']) ? ($p['admin_auth'] ? 'true' : 'false') : '—' }}</td></tr>
                            <tr><th>Admin Enabled</th><td>{{ isset($p['admin_enabled']) ? ($p['admin_enabled'] ? 'true' : 'false') : '—' }}</td></tr>
                            <tr><th>Admin GUI</th><td>{{ isset($p['admin_gui']) ? ($p['admin_gui'] ? 'true' : 'false') : '—' }}</td></tr>
                            <tr><th>Admin Log Auth Failures</th><td>{{ isset($p['admin_log_auth_failures']) ? ($p['admin_log_auth_failures'] ? 'true' : 'false') : '—' }}</td></tr>
                            @if(!empty($p['admin_ssl_key']))
                            <tr><th>Admin SSL Key</th><td><code>{{ $p['admin_ssl_key'] }}</code></td></tr>
                            @endif
                            @if(!empty($p['admin_ssl_cert']))
                            <tr><th>Admin SSL Cert</th><td><code>{{ $p['admin_ssl_cert'] }}</code></td></tr>
                            @endif
                            @if(!empty($p['admin_ssl_ca_cert']))
                            <tr><th>Admin SSL CA Cert</th><td><code>{{ $p['admin_ssl_ca_cert'] }}</code></td></tr>
                            @endif
                            @if(!empty($p['admin_pam_readonly_service']))
                            <tr><th>PAM Read-Only Service</th><td>{{ $p['admin_pam_readonly_service'] }}</td></tr>
                            @endif
                            @if(!empty($p['admin_pam_readwrite_service']))
                            <tr><th>PAM Read-Write Service</th><td>{{ $p['admin_pam_readwrite_service'] }}</td></tr>
                            @endif
                            <tr><th>Passive</th><td>{{ isset($p['passive']) ? ($p['passive'] ? 'true' : 'false') : '—' }}</td></tr>
                            <tr><th>Skip Permission Checks</th><td>{{ isset($p['skip_permission_checks']) ? ($p['skip_permission_checks'] ? 'true' : 'false') : '—' }}</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">Directories</div>
                <div class="card-body p-0">
                    <table class="table table-sm table-borderless mb-0">
                        <tbody>
                            @foreach([
                                'Library'          => 'libdir',
                                'Data'             => 'datadir',
                                'Process Data'     => 'process_datadir',
                                'Cache'            => 'cachedir',
                                'Config'           => 'configdir',
                                'Config Persist'   => 'config_persistdir',
                                'Config Module'    => 'module_configdir',
                                'PID'              => 'piddir',
                                'Log'              => 'logdir',
                                'Lang'             => 'langdir',
                                'Exec'             => 'execdir',
                                'Connector Plugin' => 'connector_plugindir',
                            ] as $label => $key)
                            @if(!empty($p[$key]))
                            <tr><th class="w-40">{{ $label }}</th><td><code class="small">{{ $p[$key] }}</code></td></tr>
                            @endif
                            @endforeach
                            @foreach([
                                'Auth Connect Timeout' => 'auth_connect_timeout',
                                'Auth Read Timeout'    => 'auth_read_timeout',
                                'Auth Write Timeout'   => 'auth_write_timeout',
                            ] as $label => $key)
                            @if(isset($p[$key]))
                            <tr><th>{{ $label }}</th><td>{{ $p[$key] }}</td></tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Log Modal --}}
<div class="modal fade" id="favoritesModal" tabindex="-1" aria-labelledby="favoritesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="favoritesModalLabel">Log Info</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <pre class="bg-dark text-light p-3 rounded" style="max-height:60vh;overflow:auto;"><code>{{ $log }}</code></pre>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-warning" name="flush" id="flush">Flush &amp; Rotate</button>
            </div>
        </div>
    </div>
</div>
@endsection
