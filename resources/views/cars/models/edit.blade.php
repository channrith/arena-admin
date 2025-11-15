@extends('layouts.app')

{{-- Customize layout sections --}}

@section('subtitle', 'Car Models')
@section('content_header_title', 'Car Models')
@section('content_header_subtitle', 'Edit')

{{-- Content body: main page content --}}

@section('content_body')
<!-- resources/views/posts/create.blade.php -->
<form action="{{ route('cars.models.update', $vehicle->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-md-9">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        Edit Car Model
                    </h3>
                </div>

                <!-- Card Body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="maker_id">Car Maker <span class="text-danger">*</span></label>
                                <select name="maker_id"
                                    id="maker_id"
                                    class="form-control select2 @error('maker_id') is-invalid @enderror"
                                    style="height: 38px;">
                                    <option value="">-- Select Car Maker --</option>
                                    @foreach ($makers as $maker)
                                    <option value="{{ $maker->id }}" {{ old('maker_id', $vehicle->maker->id) == $maker->id ? 'selected' : '' }}>
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
                                <label for="year_of_production">Year of Production</label>
                                <select name="year_of_production"
                                    id="year_of_production"
                                    class="form-control @error('year_of_production') is-invalid @enderror">
                                    <option value="">-- Select Year --</option>
                                    @for ($year = now()->year - 3; $year <= now()->year + 2; $year++)
                                        <option value="{{ $year }}" {{ old('year_of_production', $vehicle->year_of_production) == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                        @endfor
                                </select>
                                @error('year_of_production')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name">Model Name <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="Enter model name ..."
                            value="{{ old('name', $vehicle->name) }}"
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
                                    {{ old('is_local_model', $vehicle->is_local_model ?? false) ? 'checked' : '' }}>
                                <label for="is_local_model" class="custom-control-label">Local</label>
                            </div>

                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input"
                                    type="checkbox"
                                    id="is_global_model"
                                    name="is_global_model"
                                    value="1"
                                    {{ old('is_global_model', $vehicle->is_global_model ?? false) ? 'checked' : '' }}>
                                <label for="is_global_model" class="custom-control-label">Global</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-outline card-success shadow-sm mt-3">
                <div class="card-header">
                    <h3 class="card-title">Car Specifications</h3>
                </div>
                <div class="card-body">
                    @foreach ($categories as $category)
                    <div class="spec-category border rounded p-3 mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="mb-0">{{ $category->name }}</h5>
                            <button type="button" class="btn btn-sm btn-outline-primary add-spec" data-category="{{ $category->id }}">
                                <i class="fas fa-plus"></i> Add Spec
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered spec-table mb-0" data-category="{{ $category->id }}">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width: 25%">Label</th>
                                        <th style="width: 25%">Label (Khmer)</th>
                                        <th>Value</th>
                                        <th style="width: 10%">Order</th>
                                        <th style="width: 5%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($category->specs->sortBy('sequence') as $spec)
                                    <tr>
                                        <input type="hidden" name="specs[{{ $category->id }}][{{ $loop->index }}][id]" value="{{ $spec->id }}">
                                        <td>
                                            <input type="text" name="specs[{{ $category->id }}][{{ $loop->index }}][label]" class="form-control" value="{{ old("specs.{$category->id}.{$loop->index}.label", $spec->label) }}" placeholder="Label">
                                        </td>
                                        <td>
                                            <input type="text" name="specs[{{ $category->id }}][{{ $loop->index }}][label_kh]" class="form-control" value="{{ old("specs.{$category->id}.{$loop->index}.label_kh", $spec->label_kh) }}" placeholder="Khmer Label">
                                        </td>
                                        <td>
                                            <input type="text" name="specs[{{ $category->id }}][{{ $loop->index }}][value]" class="form-control" value="{{ old("specs.{$category->id}.{$loop->index}.value", $spec->value) }}" placeholder="Value">
                                        </td>
                                        <td>
                                            <input type="number" name="specs[{{ $category->id }}][{{ $loop->index }}][sequence]" class="form-control" value="{{ old("specs.{$category->id}.{$loop->index}.sequence", $spec->sequence) }}" min="1">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-danger remove-spec">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        Feature Image
                    </h3>
                </div>

                <!-- Card Body -->
                <div class="card-body">
                    <div class="form-group">
                        <label for="image_url">Upload Image (1200x640px)</label>
                        <div class="custom-file">
                            <input
                                type="file"
                                class="custom-file-input @error('image_url') is-invalid @enderror"
                                id="featureImage"
                                name="image_url"
                                accept="image/*">
                            <label class="custom-file-label" for="featureImage">Choose file</label>
                            @error('image_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Show current image preview --}}
                        @if($vehicle->feature_image_url)
                        <div class="mt-3">
                            <p>Current Image:</p>
                            <img src="{{ $vehicle->feature_image_url }}"
                                alt="Feature Image"
                                class="img-fluid rounded"
                                style="max-height: 200px;">
                        </div>
                        @endif
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
            const fileName = e.target.files[0] ? e.target.files[0].name : 'Choose file';
            e.target.nextElementSibling.textContent = fileName;
        });
    });

    // Dynamic spec rows
    document.querySelectorAll('.add-spec').forEach(btn => {
        btn.addEventListener('click', () => {
            const categoryId = btn.dataset.category;
            const tableBody = document.querySelector(`.spec-table[data-category="${categoryId}"] tbody`);
            const rowCount = tableBody.querySelectorAll('tr').length;

            const newRow = document.createElement('tr');
            newRow.innerHTML = `
            <td><input type="text" name="specs[${categoryId}][${rowCount}][label]" class="form-control" placeholder="Label"></td>
            <td><input type="text" name="specs[${categoryId}][${rowCount}][label_kh]" class="form-control" placeholder="Khmer Label"></td>
            <td><input type="text" name="specs[${categoryId}][${rowCount}][value]" class="form-control" placeholder="Value"></td>
            <td><input type="number" name="specs[${categoryId}][${rowCount}][sequence]" class="form-control" value="${rowCount + 1}" min="1"></td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-danger remove-spec">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
            tableBody.appendChild(newRow);
        });
    });

    // Remove spec row
    document.addEventListener('click', e => {
        if (e.target.closest('.remove-spec')) {
            e.target.closest('tr').remove();
        }
    });
</script>
@endpush