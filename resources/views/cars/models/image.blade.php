@extends('layouts.app')

@section('subtitle', 'Car Photos')
@section('content_header_title', 'Car Photos')
@section('content_header_subtitle', 'Manage Photos')

@section('content_body')
<form action="{{ route('cars.models.images.update', $vehicle->id) }}"
    method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row">
        {{-- LEFT SIDE --}}
        <div class="col-md-9">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Model Information</h3>
                </div>
                <div class="card-body">

                    <div class="form-group">
                        <label>Model Name</label>
                        <input type="text" class="form-control"
                            value="{{ $vehicle->name }}" disabled>
                    </div>

                    <hr>

                    <h5>Images</h5>
                    <p class="text-muted">Add all available images for this vehicle model.</p>

                    <div id="image-list">
                        @foreach ($vehicle->images as $image)
                        @include('cars.models.partials.image-item', ['image' => $image, 'baseUrl' => $baseUrl])
                        @endforeach
                    </div>

                    <button type="button" class="btn btn-secondary mt-3" id="add-image-btn">
                        <i class="fas fa-plus-circle"></i> Add Another Image
                    </button>

                </div>
            </div>
        </div>

        {{-- RIGHT SIDE --}}
        <div class="col-md-3">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Feature Image</h3>
                </div>
                <div class="card-body">
                    @if($vehicle->feature_image_url)
                    <img src="{{ $vehicle->feature_image_url }}"
                        class="img-fluid rounded" style="max-height: 200px;">
                    @else
                    <p class="text-muted">No feature image.</p>
                    @endif
                </div>
                <div class="card-footer text-right">
                    <button class="btn btn-primary"><i class="fas fa-save"></i> Save Photos</button>
                </div>
            </div>
        </div>
    </div>
</form>

{{-- Hidden template --}}
<div id="image-template" class="d-none">
    @include('cars.models.partials.image-item', ['image' => null])
</div>

@stop

@push('js')
<script>
    document.getElementById('add-image-btn').addEventListener('click', function() {
        let template = document.getElementById('image-template').innerHTML;
        document.getElementById('image-list').insertAdjacentHTML('beforeend', template);
    });

    // Image preview
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('image-image-input')) {
            let imgPreview = e.target.closest('.image-item').querySelector('.preview-img');
            imgPreview.src = URL.createObjectURL(e.target.files[0]);
        }
    });

    // Remove item
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-image')) {
            e.target.closest('.image-item').remove();
        }
    });
</script>
@endpush