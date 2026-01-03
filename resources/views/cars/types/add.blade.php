@extends('layouts.app')

{{-- Customize layout sections --}}

@section('subtitle', 'Vehicle Types')
@section('content_header_title', 'Vehicle Types')
@section('content_header_subtitle', 'Add New')

{{-- Content body: main page content --}}

@section('content_body')
<!-- resources/views/posts/create.blade.php -->
<form action="{{ route('cars.types.store') }}" method="POST">
    @csrf

    <div class="row">
        <div class="col-md-9">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        Vehicle type information
                    </h3>
                </div>

                <!-- Card Body -->
                <div class="card-body">
                    <div class="form-group">
                        <label>Service</label>
                        <select class="form-control" disabled>
                            <option>AC Auto</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="title">
                            Vehicle Type Name <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="e.g. Truck, SUV, Sedan"
                            value="{{ old('name') }}"
                            required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="sequence">Item Ordering <span class="text-danger">*</span></label>
                        <input
                            type="number"
                            name="sequence"
                            id="sequence"
                            class="form-control @error('sequence') is-invalid @enderror"
                            placeholder="Enter ordering number ..."
                            value="{{ old('sequence') }}"
                            required>
                        @error('sequence')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Footer -->
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create
                    </button>
                </div>
            </div>

        </div>

    </div>
</form>

@stop

{{-- Push extra CSS --}}

@push('css')
{{-- Add here extra stylesheets --}}
{{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@endpush

{{-- Push extra scripts --}}

@push('js')
{{-- <script></script> --}}
@endpush