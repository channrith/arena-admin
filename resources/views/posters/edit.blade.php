@extends('layouts.app')

{{-- Customize layout sections --}}
@section('subtitle', 'Posters')
@section('content_header_title', 'Posters')
@section('content_header_subtitle', 'Edit')

{{-- Content body --}}
@section('content_body')
<form action="{{ route('posters.update', $poster->id) }}" method="POST" enctype="multipart/form-data">
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
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="category_id">Category <span class="text-danger">*</span></label>
                                <select name="category_id"
                                    id="category_id"
                                    class="form-control select2 @error('category_id') is-invalid @enderror"
                                    style="height: 38px;">
                                    <option value="">-- Select Category --</option>
                                    @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $poster->category->id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->title }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sequence">Item Ordering</label>
                            <input
                                type="number"
                                name="sequence"
                                id="sequence"
                                class="form-control @error('sequence') is-invalid @enderror"
                                placeholder="Enter ordering number ..."
                                value="{{ old('sequence', $poster->sequence) }}">
                            @error('sequence')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="title">Title <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            name="title"
                            id="title"
                            class="form-control @error('title') is-invalid @enderror"
                            placeholder="Enter poster title ..."
                            value="{{ old('title', $poster->title) }}"
                            required>
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="posterUrl">Url</label>
                        <input
                            type="text"
                            name="url"
                            id="posterUrl"
                            class="form-control @error('url') is-invalid @enderror"
                            placeholder="Enter poster url ..."
                            value="{{ old('url', $poster->url) }}"
                            required>
                        @error('url')
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
                        Poster Image
                    </h3>
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="custom-file">
                            <input
                                type="file"
                                class="custom-file-input @error('image_url') is-invalid @enderror"
                                id="featureImage"
                                name="image_url">
                            <label class="custom-file-label" for="featureImage">
                                Choose file
                            </label>
                            @error('image_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Show current image preview --}}
                        @if($poster->image_url)
                        <div class="mt-3">
                            <p>Current Image:</p>
                            <img src="{{ $poster->image_url }}"
                                alt="Feature Image"
                                class="img-fluid rounded"
                                style="max-height: 200px;">
                        </div>
                        @endif
                    </div>
                </div>

                <div class="card-footer text-right">
                    <a href="{{ route('posters.index') }}" class="btn btn-secondary">
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