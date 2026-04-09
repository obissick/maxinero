@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="flash-message mb-3"></div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header text-white fw-semibold" style="background:linear-gradient(135deg,#1a2a3a 0%,#1c6e7e 100%);">
                    {{ $user->name }}
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Email</span>
                        <span>{{ $user->email }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Joined</span>
                        <span class="small">{{ $user->created_at->format('M j, Y') }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Last Updated</span>
                        <span class="small">{{ $user->updated_at->format('M j, Y') }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted small">MaxScale Servers</span>
                        <span class="badge bg-primary rounded-pill">{{ $apis }}</span>
                    </li>
                </ul>
                <div class="card-footer bg-light">
                    <form id="deleteForm" method="POST" action="{{ route('profile.destroy', $user->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm w-100"
                                onclick="return confirm('Delete your account? This cannot be undone.')">Delete Account</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">Edit Profile</div>
                <div class="card-body p-4">
                    <form id="updateForm" method="POST" action="{{ route('profile.update', $user->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Name</label>
                            <input type="text" class="form-control" name="name" id="name" value="{{ $user->name }}">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input type="email" class="form-control" name="email" id="email" value="{{ $user->email }}">
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold">New Password <small class="text-muted fw-normal">(leave blank to keep current)</small></label>
                            <input type="password" class="form-control" name="password" id="password">
                        </div>

                        <button type="submit" class="btn btn-success">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection