@extends('layouts.app')

{{-- Customize layout sections --}}

@section('subtitle', 'Manufacturers')
@section('content_header_title', 'Car Makers')
@section('content_header_subtitle', 'Add New')

{{-- Content body: main page content --}}

@section('content_body')
<!-- resources/views/posts/create.blade.php -->
<form action="{{ route('cars.makers.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row">
        <div class="col-md-9">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        Manufacturer information
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
                        <label for="title">Maker Name <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="Enter post name ..."
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
            </div>

            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        Banner
                    </h3>
                </div>

                <!-- Card Body -->
                <div class="card-body">
                    <div class="form-group">
                        <div class="custom-file">
                            <input
                                type="file"
                                class="custom-file-input @error('banner_url') is-invalid @enderror"
                                id="bannerImage"
                                name="banner_url">
                            <label class="custom-file-label" for="bannerImage">
                                Choose file
                            </label>
                            @error('banner_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mt-3 text-center">
                            <img id="bannerPreview" src="#" alt="Banner preview" class="img-fluid rounded d-none">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea
                            name="description"
                            id="description"
                            rows="2"
                            class="form-control @error('description') is-invalid @enderror"
                            placeholder="Enter banner description...">{{ old('description') }}</textarea>
                        @error('description')
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
                        Logo
                    </h3>
                </div>

                <!-- Card Body -->
                <div class="card-body">
                    <div class="form-group">
                        <label for="image_url">Upload Image (600x300 px)</label>
                        <div class="custom-file">
                            <input
                                type="file"
                                class="custom-file-input @error('logo_url') is-invalid @enderror"
                                id="featureImage"
                                name="logo_url">
                            <label class="custom-file-label" for="featureImage">Choose file</label>
                            @error('logo_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-3 text-center">
                        <img id="imagePreview" src="#" alt="Preview" class="img-fluid rounded d-none" style="max-height: 120px;">
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.querySelector('#featureImage');
        fileInput.addEventListener('change', function(e) {
            const [file] = e.target.files;
            const preview = document.getElementById('imagePreview');
            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.classList.remove('d-none');
            } else {
                preview.classList.add('d-none');
            }

            const fileName = e.target.files[0] ? e.target.files[0].name : 'Choose file';
            e.target.nextElementSibling.textContent = fileName;
        });

        const bannerInput = document.querySelector('#bannerImage');
        bannerInput.addEventListener('change', function(e) {
            const [file] = e.target.files;
            const preview = document.getElementById('bannerPreview');
            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.classList.remove('d-none');
            } else {
                preview.classList.add('d-none');
            }

            const fileName = e.target.files[0] ? e.target.files[0].name : 'Choose file';
            e.target.nextElementSibling.textContent = fileName;
        });
    });
</script>
@endpush