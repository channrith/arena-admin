@extends('layouts.app')

{{-- Customize layout sections --}}
@section('subtitle', 'Posts')
@section('content_header_title', 'Posts')
@section('content_header_subtitle', 'Edit')

{{-- Content body --}}
@section('content_body')
<!-- resources/views/posts/edit.blade.php -->
<form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-md-9">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        Edit Post
                    </h3>
                </div>

                <!-- Card Body -->
                <div class="card-body">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input
                            type="text"
                            name="title"
                            id="title"
                            class="form-control @error('title') is-invalid @enderror"
                            placeholder="Enter post title ..."
                            value="{{ old('title', $post->title) }}"
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
                            placeholder="Enter post summary..."
                            required>{{ old('summary', $post->summary) }}</textarea>
                        @error('summary')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="content">Content</label>
                        <textarea
                            id="summernote"
                            name="content"
                            class="form-control @error('content') is-invalid @enderror">{{ old('content', $post->content) }}</textarea>
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
                                {{ old('is_special', isset($post) && $post->is_special ? 'checked' : '') }}>
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
                        <label>Status</label>
                        @if($post->status==="approved")
                        <span class="badge badge-success">{{ $post->status }}</span>
                        @elseif($post->status==="pending")
                        <span class="badge badge-info">{{ $post->status }}</span>
                        @elseif($post->status==="rejected")
                        <span class="badge badge-danger">{{ $post->status }}</span>
                        @else
                        <span class="badge badge-warning">{{ $post->status }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label>Publish date</label>
                        <div class="input-group date" id="reservationdatetime" data-target-input="nearest">
                            <input
                                type="text"
                                name="published_at"
                                class="form-control datetimepicker-input"
                                data-target="#reservationdatetime"
                                value="{{ old('published_at', $post->published_at ? $post->published_at->format('Y/m/d h:i A') : '') }}" />
                            <div class="input-group-append" data-target="#reservationdatetime" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="card-footer text-right">
                    <a href="{{ route('posts.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update
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
                        <div class="custom-file">
                            <input
                                type="file"
                                class="custom-file-input @error('feature_image') is-invalid @enderror"
                                id="featureImage"
                                name="feature_image">
                            <label class="custom-file-label" for="featureImage">
                                Choose file
                            </label>
                            @error('feature_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Show current image preview --}}
                        @if($post->currentTranslation->feature_image_url)
                        <div class="mt-3">
                            <p>Current Image:</p>
                            <img src="{{ $post->currentTranslation->feature_image_url }}"
                                alt="Feature Image"
                                class="img-fluid rounded"
                                style="max-height: 200px;">
                        </div>
                        @endif
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
                            value="{{ old('translator_name', $post->translator_name) }}">
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
                            value="{{ old('source', $post->source) }}">
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
<link rel="stylesheet" href="/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
<link rel="stylesheet" href="/plugins/summernote/summernote-bs4.min.css">
@endpush

{{-- Push extra scripts --}}
@push('js')
<script src="/plugins/moment/moment.min.js"></script>
<script src="/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="/plugins/summernote/summernote-bs4.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.querySelector('#featureImage');
        fileInput.addEventListener('change', function(e) {
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