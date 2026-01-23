@extends('layouts.app')

{{-- Customize layout sections --}}

@section('subtitle', 'Vehicle Series')
@section('content_header_title', 'Vehicle Series')
@section('content_header_subtitle', 'Add New')

{{-- Content body: main page content --}}

@section('content_body')
<form action="{{ route('cars.series.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row">
        <div class="col-md-9">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        Vehicle series information
                    </h3>
                </div>

                <!-- Card Body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="maker_id">
                                    Vehicle Maker <span class="text-danger">*</span>
                                </label>
                                <select
                                    name="maker_id"
                                    id="maker_id"
                                    class="form-control select2 @error('maker_id') is-invalid @enderror"
                                    required>
                                    <option value="">-- Select Maker --</option>
                                    @foreach ($makers as $maker)
                                    <option value="{{ $maker->id }}"
                                        {{ old('maker_id') == $maker->id ? 'selected' : '' }}>
                                        {{ $maker->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('maker_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="type_id">
                                    Vehicle Type <span class="text-danger">*</span>
                                </label>
                                <select
                                    name="type_id"
                                    id="type_id"
                                    class="form-control @error('type_id') is-invalid @enderror"
                                    required>
                                    <option value="">-- Select Type --</option>
                                    @foreach ($types as $type)
                                    <option value="{{ $type->id }}"
                                        {{ old('type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('type_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name">
                            Vehicle Series Name <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="e.g. Tacoma, Hilux, Fortuner"
                            value="{{ old('name') }}"
                            required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="title">Regional Market</label>
                        <div class="d-flex flex-wrap gap-3">
                            <div class="custom-control custom-checkbox mr-3">
                                <input class="custom-control-input"
                                    type="checkbox"
                                    id="is_local_model"
                                    name="is_local_model"
                                    value="1"
                                    {{ old('is_local_model') ? 'checked' : '' }}>
                                <label for="is_local_model" class="custom-control-label">Local</label>
                            </div>

                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input"
                                    type="checkbox"
                                    id="is_global_model"
                                    name="is_global_model"
                                    value="1"
                                    {{ old('is_global_model') ? 'checked' : '' }}>
                                <label for="is_global_model" class="custom-control-label">Global</label>
                            </div>
                        </div>
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
                        <div class="custom-file">
                            <input
                                type="file"
                                class="custom-file-input @error('image_url') is-invalid @enderror"
                                id="featureImage"
                                name="image_url">
                            <label class="custom-file-label" for="featureImage">Choose file</label>
                            @error('image_url')
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
<link rel="stylesheet" href="/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<style>
    .select2-container .select2-selection--single {
        height: 38px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        position: absolute;
        top: 5px;
    }
</style>
@endpush

{{-- Push extra scripts --}}

@push('js')
<script src="/plugins/select2/js/select2.full.min.js"></script>
<script>
    $('.select2').select2();

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
    });
</script>
@endpush