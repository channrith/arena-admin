@extends('layouts.app')

{{-- Customize layout sections --}}
@section('subtitle', 'Vehicle Types')
@section('content_header_title', 'Vehicle Types')
@section('content_header_subtitle', 'Edit')

{{-- Content body --}}
@section('content_body')
<!-- resources/views/posts/edit.blade.php -->
<form action="{{ route('cars.types.update', $vehicleType->id) }}" method="POST">
    @csrf
    @method('PUT')

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
                            <option>{{ $vehicleType->service->description }}</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="name">Vehicle Type Name <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="e.g. Truck, SUV, Sedan"
                            value="{{ old('name', $vehicleType->name) }}"
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
                            value="{{ old('sequence', $vehicleType->sequence) }}"
                            required>
                        @error('sequence')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Footer -->
                <div class="card-footer text-right">
                    <a href="{{ route('cars.types.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update
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