@extends('layouts.app')

@section('content')
@php
    $srv    = $server['data'];
    $params = $srv['attributes']['parameters'] ?? [];
    $stats  = $srv['attributes']['statistics'] ?? [];
    $state  = $srv['attributes']['state'] ?? '';
    $stateClass = str_contains($state, 'Running') || str_contains($state, 'Master') ? 'success'
                : (str_contains($state, 'Maintenance') ? 'warning' : 'secondary');
@endphp
<div class="container-fluid px-4">
    <div class="flash-message mb-2"></div>
    <h1 class="mt-4">
        {{ $srv['id'] }}
        <span class="badge bg-{{ $stateClass }} fs-6 align-middle">{{ $state }}</span>
    </h1>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-2">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="text-muted small">Connections</div>
                    <div class="fs-4 fw-bold">{{ $stats['connections'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-2">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="text-muted small">Total Connections</div>
                    <div class="fs-4 fw-bold">{{ $stats['total_connections'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-2">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="text-muted small">Persistent</div>
                    <div class="fs-4 fw-bold">{{ $stats['persistent_connections'] ?? '—' }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-2">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="text-muted small">Active Ops</div>
                    <div class="fs-4 fw-bold">{{ $stats['active_operations'] ?? '—' }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-2">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="text-muted small">Routed Packets</div>
                    <div class="fs-4 fw-bold">{{ $stats['routed_packets'] ?? '—' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">Parameters</div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <tbody>
                            <tr><th class="w-40">Address</th><td>{{ $params['address'] ?? '' }}</td></tr>
                            <tr><th>Port</th><td>{{ $params['port'] ?? '' }}</td></tr>
                            <tr><th>Protocol</th><td><code>{{ $params['protocol'] ?? '' }}</code></td></tr>
                            <tr><th>Version</th><td>{{ $srv['attributes']['version_string'] ?? $srv['attributes']['version'] ?? '—' }}</td></tr>
                            @if(!empty($params['ssl_key']))
                            <tr><th>SSL Key</th><td><code>{{ $params['ssl_key'] }}</code></td></tr>
                            @endif
                            @if(!empty($params['ssl_cert']))
                            <tr><th>SSL Cert</th><td><code>{{ $params['ssl_cert'] }}</code></td></tr>
                            @endif
                            @if(!empty($params['ssl_ca_cert']))
                            <tr><th>SSL CA Cert</th><td><code>{{ $params['ssl_ca_cert'] }}</code></td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">Relationships</div>
                <div class="card-body">
                    <p class="mb-1 fw-semibold">Services</p>
                    <div class="mb-3">
                        @forelse($srv['relationships']['services']['data'] ?? [] as $rel)
                            <span class="badge bg-primary me-1">{{ $rel['id'] }}</span>
                        @empty
                            <span class="text-muted">None</span>
                        @endforelse
                    </div>
                    <p class="mb-1 fw-semibold">Monitors</p>
                    <div>
                        @forelse($srv['relationships']['monitors']['data'] ?? [] as $rel)
                            <span class="badge bg-success me-1">{{ $rel['id'] }}</span>
                        @empty
                            <span class="text-muted">None</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection