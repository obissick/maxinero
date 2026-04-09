@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="flash-message mb-2"></div>
    <div class="d-flex align-items-center gap-2 my-3">
        <h1 class="mb-0">MaxScale Users</h1>
        <button id="add-user-button" name="add-user-button" class="btn btn-success btn-sm ms-2"
                data-bs-toggle="modal" data-bs-target="#user">+ Add User</button>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-striped table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Account</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="users-list">
                        @foreach($users['data'] ?? [] as $usr)
                        <tr id="user{{ $usr['id'] }}">
                            <td>{{ $usr['id'] }}</td>
                            <td><span class="badge bg-{{ $usr['type'] === 'inet' ? 'primary' : 'secondary' }}">{{ $usr['type'] }}</span></td>
                            <td>{{ $usr['attributes']['account'] ?? '' }}</td>
                            <td>
                                @if($usr['type'] === 'inet')
                                    <button class="btn btn-danger btn-sm delete-user" name="delete-user" value="{{ $usr['id'] }}">Delete</button>
                                @else
                                    <button class="btn btn-warning btn-sm disable-user" name="disable-user" value="{{ $usr['id'] }}">Disable</button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Add User Modal --}}
<div class="modal fade" id="user" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Add MaxScale User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="adduser" name="adduser" novalidate>
                    @csrf
                    <div class="row mb-3">
                        <label for="user_id" class="col-sm-3 col-form-label">User ID</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="user_id" name="user_id" placeholder="username">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="password" class="col-sm-3 col-form-label">Password</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" id="password" name="address" placeholder="Password">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="account" class="col-sm-3 col-form-label">Account</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="account" name="account" placeholder="admin or basic">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="add-user" value="add">Save changes</button>
                        <input type="hidden" id="user" name="user" value="">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
