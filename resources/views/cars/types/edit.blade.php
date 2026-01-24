@extends('layouts.app')

{{-- Customize layout sections --}}
@section('subtitle', 'Vehicle Types')
@section('content_header_title', 'Vehicle Types')
@section('content_header_subtitle', 'Edit')

{{-- Content body --}}
@section('content_body')
<form action="{{ route('cars.types.update', $vehicleType->id) }}" method="POST" enctype="multipart/form-data">
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
            </div>

        </div>
        <div class="col-md-3">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        Icon
                    </h3>
                </div>

                <!-- Card Body -->
                <div class="card-body">
                    <div class="form-group">
                        <label for="image_url">Upload Image (600x320px)</label>
                        <div class="custom-file">
                            <input
                                type="file"
                                class="custom-file-input @error('icon_url') is-invalid @enderror"
                                id="featureImage"
                                name="icon_url">
                            <label class="custom-file-label" for="featureImage">
                                Choose file
                            </label>
                            @error('icon_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Show current image preview --}}
                        @if($vehicleType->feature_image_url)
                        <div class="mt-3">
                            <p>Current Image:</p>
                            <img src="{{ $vehicleType->feature_image_url }}"
                                alt="Feature Image"
                                class="img-fluid rounded"
                                style="max-height: 200px;">
                        </div>
                        @endif
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.querySelector('#featureImage');
        fileInput.addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : 'Choose file';
            e.target.nextElementSibling.textContent = fileName;
        });
    });
</script>
@endpush