@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard</h1>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Stat cards --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card border-start border-primary border-4 h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small text-uppercase fw-semibold">Active Sessions</div>
                        <div class="display-6 fw-bold text-primary">{{ $count }}</div>
                    </div>
                    <div class="fs-1 text-primary opacity-25">&#9783;</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card border-start border-success border-4 h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small text-uppercase fw-semibold">Worker Threads</div>
                        <div class="display-6 fw-bold text-success">{{ $threads_count }}</div>
                    </div>
                    <div class="fs-1 text-success opacity-25">&#9881;</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Sessions and Threads tables --}}
    <div class="row g-4">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header d-flex align-items-center gap-2">
                    Sessions
                    <span class="badge bg-primary">{{ $count }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped table-hover mb-0" id="sessions">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Remote</th>
                                    <th>Service</th>
                                    <th>Idle (s)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sessions['data'] ?? [] as $session)
                                <tr>
                                    <td><span class="badge bg-info text-dark">{{ $session['id'] }}</span></td>
                                    <td>{{ $session['attributes']['user'] ?? '' }}</td>
                                    <td>{{ $session['attributes']['remote'] ?? '' }}</td>
                                    <td>{{ $session['relationships']['services']['data'][0]['id'] ?? '<em class="text-muted">—</em>' }}</td>
                                    <td>{{ $session['attributes']['idle'] ?? 0 }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card">
                <div class="card-header d-flex align-items-center gap-2">
                    Threads
                    <span class="badge bg-success">{{ $threads_count }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped table-hover mb-0" id="threads">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Reads</th>
                                    <th>Writes</th>
                                    <th>Errors</th>
                                    <th>Hangups</th>
                                    <th>Accepts</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($threads['data'] ?? [] as $thread)
                                <tr>
                                    <td><span class="badge bg-secondary">{{ $thread['id'] }}</span></td>
                                    <td>{{ $thread['attributes']['stats']['reads'] ?? 0 }}</td>
                                    <td>{{ $thread['attributes']['stats']['writes'] ?? 0 }}</td>
                                    <td>{{ $thread['attributes']['stats']['errors'] ?? 0 }}</td>
                                    <td>{{ $thread['attributes']['stats']['hangups'] ?? 0 }}</td>
                                    <td>{{ $thread['attributes']['stats']['accepts'] ?? 0 }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#sessions').DataTable({ ordering: false, pageLength: 25 });
        $('#threads').DataTable({ ordering: false });
    });
</script>
@endsection

