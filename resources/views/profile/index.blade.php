@extends('layouts.app')

@section('subtitle', 'Profile')
@section('content_header_title', 'Change Password')

@section('content_body')
<div class="row">
    <div class="col-md-4">
        <div class="card card-outline card-primary">
            <div class="card-body">
                <div class="card-header text-center">
                    <h3 class="card-title">User Profile</h3>
                </div>

                <div class="card-body">

                    @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf

                        <div class="form-group mb-3">
                            <label>Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                class="form-control @error('name') is-invalid @enderror" required>
                            @error('name')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label>Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                class="form-control @error('email') is-invalid @enderror" required>
                            @error('email')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop