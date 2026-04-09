@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="flash-message mb-2"></div>
    <div class="d-flex align-items-center gap-2 my-3">
        <h1 class="mb-0">MaxScale Servers</h1>
        <button id="add-maxscale" name="add-maxscale" class="btn btn-success btn-sm ms-2"
                data-bs-toggle="modal" data-bs-target="#config">+ Add API</button>
    </div>

    @if (empty($settings))
        <div class="alert alert-info">Add a MaxScale server to begin.</div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-striped table-hover align-middle mb-0" id="apis-table">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>API URL</th>
                            <th>Username</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="configs-list">
                        @foreach ($settings as $setting)
                        <tr id="setting{{ $setting->id }}">
                            <td>
                                {{ $setting->name }}
                                @if($setting->selected)
                                    <span class="badge bg-success ms-1">Active</span>
                                @endif
                            </td>
                            <td><code>{{ $setting->api_url }}</code></td>
                            <td>{{ $setting->username }}</td>
                            <td>
                                @if(!$setting->selected)
                                    <button class="btn btn-success btn-sm select" value="{{ $setting->id }}">Select</button>
                                @endif
                                <button class="btn btn-info btn-sm edit-maxscale" value="{{ $setting->id }}">Edit</button>
                                <button class="btn btn-danger btn-sm delete-maxscale" value="{{ $setting->id }}">Delete</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Config Modal --}}
<div class="modal fade" id="config" tabindex="-1" aria-labelledby="configModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="configModalLabel">MaxScale API Editor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info py-2 small">
                    URL format: <code>http://host:port/v1/</code> &nbsp;|&nbsp; Use HTTP not HTTPS unless SSL is configured on MaxScale.
                </div>
                <form id="add-api-form" name="add-api-form" novalidate>
                    @csrf
                    <div class="row mb-3">
                        <label for="api_name" class="col-sm-3 col-form-label">Name</label>
                        <div class="col-sm-9">
                            <input type="hidden" name="setting_id" id="setting_id">
                            <input type="text" class="form-control" id="api_name" name="api_name" placeholder="My MaxScale">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="api_url" class="col-sm-3 col-form-label">API URL</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="api_url" name="api_url" placeholder="http://host:8989/v1/">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="api_username" class="col-sm-3 col-form-label">Username</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="api_username" name="api_username" placeholder="admin">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="api_password" class="col-sm-3 col-form-label">Password</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" id="api_password" name="api_password">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" name="add-api" id="add-api" value="add">Save changes</button>
                        <input type="hidden" id="api_id" name="api_id" value="0">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#apis-table').DataTable({ pageLength: 25 });
    });
</script>
@endsection
