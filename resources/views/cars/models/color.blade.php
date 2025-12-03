@extends('layouts.app')

@section('subtitle', 'Car Colors')
@section('content_header_title', 'Car Colors')
@section('content_header_subtitle', 'Manage Colors')

@section('content_body')

<form action="{{ route('cars.models.colors.update', $vehicle->id) }}"
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

                    <h5>Colors</h5>
                    <p class="text-muted">Add all available colors for this vehicle model.</p>

                    <div id="color-list">
                        @foreach ($vehicle->colors as $color)
                        @include('cars.models.partials.color-item', ['color' => $color, 'baseUrl' => $baseUrl])
                        @endforeach
                    </div>

                    <button type="button" class="btn btn-secondary mt-3" id="add-color-btn">
                        <i class="fas fa-plus-circle"></i> Add Another Color
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
                    <button class="btn btn-primary"><i class="fas fa-save"></i> Save Colors</button>
                </div>
            </div>
        </div>
    </div>
</form>

{{-- Hidden template --}}
<div id="color-template" class="d-none">
    @include('cars.models.partials.color-item', ['color' => null])
</div>

@stop

@push('js')
<script>
    document.getElementById('add-color-btn').addEventListener('click', function() {
        let template = document.getElementById('color-template').innerHTML;
        document.getElementById('color-list').insertAdjacentHTML('beforeend', template);
    });

    // Image preview
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('color-image-input')) {
            let imgPreview = e.target.closest('.color-item').querySelector('.preview-img');
            imgPreview.src = URL.createObjectURL(e.target.files[0]);
        }
    });

    // Remove item
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-color')) {
            e.target.closest('.color-item').remove();
        }
    });
</script>
@endpush