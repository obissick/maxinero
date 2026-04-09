@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-9 col-lg-7 col-xl-6">
            <div class="card shadow-sm border-0 overflow-hidden">
                <div class="px-5 py-4" style="background:linear-gradient(135deg,#1a2a3a 0%,#1c6e7e 100%);">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:48px;height:48px;overflow:hidden;flex-shrink:0;">
                            <img src="{{ asset('img/logo.png') }}" height="48" style="max-width:none;width:auto;" alt="Maxinero"/>
                        </div>
                        <div>
                            <div class="text-white fw-bold fs-5 lh-1">Maxinero</div>
                            <div class="text-white-50 small">Reset your password</div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-5">
                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    <p class="text-muted small mb-4">Enter your email address and we'll send you a password reset link.</p>

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">{{ __('Email Address') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-lg fw-semibold text-white" style="background:linear-gradient(135deg,#1a2a3a,#1c6e7e);border:none;">{{ __('Send Reset Link') }}</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-light text-center py-3 border-0">
                    <span class="text-muted small"><a href="{{ route('login') }}">Back to sign in</a></span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
