@extends('layouts.app')

@section('subtitle', 'Profile')
@section('content_header_title', 'Change Password')

@section('content_body')
<div class="row">
    <div class="col-md-4">
        <div class="card card-outline card-primary">
            <div class="card-body">

                @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('profile.password.update') }}" method="POST">
                    @csrf

                    <div class="form-group mb-3">
                        <label>Current Password</label>
                        <input type="password" name="current_password"
                            class="form-control @error('current_password') is-invalid @enderror">
                        @error('current_password')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label>New Password</label>
                        <input type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label>Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Update Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop