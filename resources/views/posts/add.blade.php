@extends('layouts.app')

{{-- Customize layout sections --}}

@section('subtitle', 'Posts')
@section('content_header_title', 'Posts')
@section('content_header_subtitle', 'Add New')

{{-- Content body: main page content --}}

@section('content_body')
<!-- resources/views/posts/create.blade.php -->
<form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row">
        <div class="col-md-9">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        Post Content
                    </h3>
                </div>

                <!-- Card Body -->
                <div class="card-body">
                    <div class="form-group">
                        <label for="service_id">Services <span class="text-danger">*</span></label>

                        <select name="service_id[]" id="service_id"
                            multiple="multiple"
                            data-placeholder="Select services"
                            class="form-control select2 @error('service_id') is-invalid @enderror"
                            style="width: 100%;">

                            @php
                            // Old input preserves selected values when validation fails
                            $selectedServices = old('service_id', []);
                            @endphp

                            @foreach ($services as $service)
                            <option value="{{ $service->id }}"
                                {{ in_array($service->id, $selectedServices) ? 'selected' : '' }}>
                                {{ $service->description }}
                            </option>
                            @endforeach
                        </select>

                        @error('service_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="form-group">
                        <label for="title">Title <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            name="title"
                            id="title"
                            class="form-control @error('title') is-invalid @enderror"
                            placeholder="Enter post title ..."
                            value="{{ old('title') }}">
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="summary">Summary <span class="text-danger">*</span></label>
                        <textarea
                            name="summary"
                            id="summary"
                            rows="3"
                            class="form-control @error('summary') is-invalid @enderror"
                            placeholder="Enter post summary...">{{ old('summary') }}</textarea>
                        @error('summary')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="content">Content <span class="text-danger">*</span></label>
                        <textarea
                            id="summernote"
                            name="content"
                            class="form-control @error('content') is-invalid @enderror">{{ old('content') }}</textarea>
                        @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input
                                class="custom-control-input"
                                type="checkbox"
                                id="specialCheckbox"
                                name="is_special"
                                value="1"
                                @checked(old('is_special', isset($post) ? $post->is_special : false))>
                            <label for="specialCheckbox" class="custom-control-label">Mark as special</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        Publish
                    </h3>
                </div>

                <!-- Card Body -->
                <div class="card-body">
                    <div class="form-group">
                        <label>Publish date</label>
                        <div class="input-group date" id="reservationdatetime" data-target-input="nearest">
                            <input
                                type="text"
                                name="published_at"
                                class="form-control datetimepicker-input"
                                data-target="#reservationdatetime"
                                value="{{ old('published_at') }}" />
                            <div class="input-group-append" data-target="#reservationdatetime" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Publish
                    </button>
                </div>
            </div>

            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        Feature Image
                    </h3>
                </div>

                <!-- Card Body -->
                <div class="card-body">
                    <div class="form-group">
                        <label for="image_url">Upload Image (1920x1080px)</label>
                        <div class="custom-file">
                            <input
                                type="file"
                                class="custom-file-input @error('feature_image') is-invalid @enderror"
                                id="featureImage"
                                name="feature_image">
                            <label class="custom-file-label" for="featureImage">Choose file</label>
                            @error('feature_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-3 text-center">
                        <img id="imagePreview" src="#" alt="Preview" class="img-fluid rounded d-none" style="max-height: 120px;">
                    </div>
                </div>
            </div>

            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        Source / Translations
                    </h3>
                </div>

                <!-- Card Body -->
                <div class="card-body">
                    <div class="form-group">
                        <label for="translator">Translator</label>
                        <input
                            type="text"
                            name="translator_name"
                            id="translator"
                            class="form-control @error('translator_name') is-invalid @enderror"
                            placeholder="Enter translator name ..."
                            value="{{ old('translator_name') }}">
                        @error('translator_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="source">Source</label>
                        <input
                            type="text"
                            name="source"
                            id="source"
                            class="form-control @error('source') is-invalid @enderror"
                            placeholder="Enter source ..."
                            value="{{ old('source') }}">
                        @error('source')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
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
<link rel="stylesheet" href="/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
<link rel="stylesheet" href="/plugins/summernote/summernote-bs4.min.css">
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

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #007bff;
        border-color: #006fe6;
        color: #fff;
        padding: 0 10px;
        margin-top: .31rem;
    }
</style>
@endpush

{{-- Push extra scripts --}}

@push('js')
<script src="/plugins/moment/moment.min.js"></script>
<script src="/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="/plugins/summernote/summernote-bs4.min.js"></script>
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

    $(function() {
        // Rich Text Editor
        $('#summernote').summernote()
    });

    $('#reservationdatetime').datetimepicker({
        format: 'YYYY/MM/DD hh:mm A',
        icons: {
            time: 'far fa-clock'
        }
    });
</script>
@endpush