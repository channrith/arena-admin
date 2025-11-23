@extends('layouts.app')

{{-- Customize layout sections --}}

@section('subtitle', 'Video')
@section('content_header_title', 'Video')
@section('content_header_subtitle', 'Add New')

{{-- Content body: main page content --}}

@section('content_body')
<form action="{{ route('videos.store') }}" method="POST">
    @csrf

    <div class="row">
        <div class="col-md-9">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        Video Information
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
                            placeholder="Enter video title ..."
                            value="{{ old('title') }}">
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="youtube_url">Youtube URL <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            name="youtube_url"
                            id="youtube_url"
                            class="form-control @error('youtube_url') is-invalid @enderror"
                            placeholder="Enter youtube url ..."
                            value="{{ old('youtube_url') }}">
                        @error('youtube_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="sequence">Item Ordering</label>
                        <input
                            type="number"
                            name="sequence"
                            id="sequence"
                            class="form-control @error('sequence') is-invalid @enderror"
                            placeholder="Enter ordering number ..."
                            value="{{ old('sequence') }}">
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
                        Publish
                    </h3>
                </div>

                <!-- Card Body -->
                <div class="card-body">
                    <div class="form-group">
                        <label for="video_category_id">Video Categories <span class="text-danger">*</span></label>

                        <select name="video_category_id[]" id="video_category_id"
                            multiple="multiple"
                            data-placeholder="Select video categories"
                            class="form-control select2 @error('video_category_id') is-invalid @enderror"
                            style="width: 100%;">

                            @php
                            // Old input preserves selected values when validation fails
                            $selectedVideoCategories = old('video_category_id', []);
                            @endphp

                            @foreach ($videoCategories as $videoCategory)
                            <option value="{{ $videoCategory->id }}"
                                {{ in_array($videoCategory->id, $selectedVideoCategories) ? 'selected' : '' }}>
                                {{ $videoCategory->name }}
                            </option>
                            @endforeach
                        </select>

                        @error('video_category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input
                                class="custom-control-input"
                                type="checkbox"
                                id="activeCheckbox"
                                name="active"
                                value="1"
                                @checked(old('active', !$errors->any()))>
                            <label for="activeCheckbox" class="custom-control-label">Active</label>
                        </div>
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
<script src="/plugins/select2/js/select2.full.min.js"></script>
<script>
    $('.select2').select2();
</script>
@endpush