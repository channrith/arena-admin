@extends('layouts.app')

{{-- Customize layout sections --}}

@section('subtitle', 'News')
@section('content_header_title', 'News')
@section('content_header_subtitle', 'Add New')

{{-- Content body: main page content --}}

@section('content_body')
<!-- resources/views/news/create.blade.php -->
<form action="{{ route('news.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        News Content
                    </h3>
                </div>

                <!-- Card Body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="featureImage">Feature Image</label>
                                <div class="custom-file">
                                    <input
                                        type="file"
                                        class="custom-file-input @error('feature_image') is-invalid @enderror"
                                        id="featureImage"
                                        name="feature_image"
                                        required>
                                    <label class="custom-file-label" for="featureImage">Choose file</label>
                                    @error('feature_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="title">Title</label>
                        <input
                            type="text"
                            name="title"
                            id="title"
                            class="form-control @error('title') is-invalid @enderror"
                            placeholder="Enter news title ..."
                            value="{{ old('title') }}"
                            required>
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="summary">Summary</label>
                        <textarea
                            name="summary"
                            id="summary"
                            rows="3"
                            class="form-control @error('summary') is-invalid @enderror"
                            placeholder="Enter news summary..."
                            required>{{ old('summary') }}</textarea>
                        @error('summary')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="content">Content</label>
                        <textarea
                            id="summernote"
                            name="content"
                            class="form-control @error('content') is-invalid @enderror">{{ old('content') }}</textarea>
                        @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Footer -->
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Submit
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
<link rel="stylesheet" href="/plugins/summernote/summernote-bs4.min.css">
@endpush

{{-- Push extra scripts --}}

@push('js')
<script src="/plugins/summernote/summernote-bs4.min.js"></script>
<script>
    $(function() {
        // Rich Text Editor
        $('#summernote').summernote()
    })
</script>
@endpush