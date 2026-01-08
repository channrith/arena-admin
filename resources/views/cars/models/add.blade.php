@extends('layouts.app')

{{-- Customize layout sections --}}

@section('subtitle', 'Car Models')
@section('content_header_title', 'Car Models')
@section('content_header_subtitle', 'Add New')

{{-- Content body: main page content --}}

@section('content_body')
<!-- resources/views/posts/create.blade.php -->
<form action="{{ route('cars.models.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row">
        <div class="col-md-9">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        Add Car Model
                    </h3>
                </div>

                <!-- Card Body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="maker_id">Car Maker <span class="text-danger">*</span></label>
                                <select name="maker_id"
                                    id="maker_id"
                                    class="form-control select2 @error('maker_id') is-invalid @enderror">
                                    <option value="">-- Select Car Maker --</option>
                                    @foreach ($makers as $maker)
                                    <option value="{{ $maker->id }}" {{ old('maker_id') == $maker->id ? 'selected' : '' }}>
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
                                <label for="series_id">Vehicle Series</label>
                                <select
                                    name="series_id"
                                    id="series_id"
                                    class="form-control select2 @error('series_id') is-invalid @enderror"
                                    disabled>
                                    <option value="">-- Select Series --</option>
                                </select>
                                @error('series_id')
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
                                        <option value="{{ $year }}" {{ old('year_of_production') == $year ? 'selected' : '' }}>
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
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <label class="font-weight-bold mb-0">Import Specs from CSV</label>

                        <button type="button"
                            class="btn btn-sm btn-outline-success"
                            id="downloadCsvTemplate">
                            <i class="fas fa-download"></i> Download CSV Template
                        </button>
                    </div>

                    <div class="mb-2">
                        <div class="custom-file">
                            <input
                                type="file"
                                class="custom-file-input"
                                id="specCsvFile"
                                accept=".csv">
                            <label class="custom-file-label" for="specCsvFile">
                                Choose CSV file
                            </label>
                        </div>
                        <small class="text-muted">
                            CSV columns: category_id, label, label_kh, value
                        </small>
                    </div>
                    @foreach ($categories as $category)
                    <div class="spec-category border rounded p-3 mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="mb-0">{{ $category['name'] }}</h5>
                            <button type="button" class="btn btn-sm btn-outline-primary add-spec" data-category="{{ $category['id'] }}">
                                <i class="fas fa-plus"></i> Add Spec
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered spec-table mb-0" data-category="{{ $category['id'] }}">
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
                                    {{-- Template row will be cloned via JS --}}
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
<script>
    document.getElementById('specCsvFile').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(event) {
            const text = event.target.result;
            parseCsvAndFillSpecs(text);
        };
        reader.readAsText(file);

        e.target.nextElementSibling.textContent = file.name;
    });

    function parseCsvRow(row) {
        const result = [];
        let current = '';
        let inQuotes = false;

        for (let i = 0; i < row.length; i++) {
            const char = row[i];

            if (char === '"' && row[i + 1] === '"') {
                current += '"';
                i++;
            } else if (char === '"') {
                inQuotes = !inQuotes;
            } else if (char === ',' && !inQuotes) {
                result.push(current.trim());
                current = '';
            } else {
                current += char;
            }
        }
        result.push(current.trim());

        return result;
    }


    function parseCsvAndFillSpecs(csvText) {
        const rows = csvText.trim().split('\n');
        const headers = rows.shift().split(',').map(h => h.trim());

        const requiredHeaders = ['category_id', 'label', 'label_kh', 'value'];
        for (const h of requiredHeaders) {
            if (!headers.includes(h)) {
                alert('Invalid CSV format');
                return;
            }
        }

        rows.forEach(row => {
            if (!row.trim()) return;

            const cols = parseCsvRow(row);
            const data = Object.fromEntries(headers.map((h, i) => [h, cols[i]]));

            const categoryId = data.category_id;
            const tableBody = document.querySelector(
                `.spec-table[data-category="${categoryId}"] tbody`
            );

            if (!tableBody) return;

            const rowCount = tableBody.querySelectorAll('tr').length;

            const tr = document.createElement('tr');
            tr.innerHTML = `
            <td>
                <input type="text"
                    name="specs[${categoryId}][${rowCount}][label]"
                    class="form-control"
                    value="${data.label || ''}">
            </td>
            <td>
                <input type="text"
                    name="specs[${categoryId}][${rowCount}][label_kh]"
                    class="form-control"
                    value="${data.label_kh || ''}">
            </td>
            <td>
                <input type="text"
                    name="specs[${categoryId}][${rowCount}][value]"
                    class="form-control"
                    value="${data.value || ''}">
            </td>
            <td>
                <input type="number"
                    name="specs[${categoryId}][${rowCount}][sequence]"
                    class="form-control"
                    value="${rowCount + 1}">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-danger remove-spec">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
            console.log(tr);

            tableBody.appendChild(tr);
        });
    }
</script>
<script>
    document.getElementById('downloadCsvTemplate').addEventListener('click', function() {

        // UTF-8 BOM (required for Khmer text in Excel)
        let csvContent = '\uFEFF';

        // Header row
        csvContent += 'category_id,label,label_kh,value\n';

        // Example rows per category
        @foreach($categories as $category)
        csvContent += '{{ $category['id'] }},Example Label,ឧទាហរណ៍,Example Value\n';
        @endforeach

        // Create Blob with UTF-8 encoding
        const blob = new Blob([csvContent], {
            type: 'text/csv;charset=utf-8;'
        });

        const url = URL.createObjectURL(blob);

        const link = document.createElement('a');
        link.href = url;
        link.download = 'car-specs-template-kh.csv';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });
</script>
<script>
    $('#maker_id').on('change', function() {
        const makerId = $(this).val();
        const seriesSelect = $('#series_id');

        seriesSelect.prop('disabled', true);
        seriesSelect.empty().append('<option value="">Loading...</option>');

        if (!makerId) {
            seriesSelect
                .empty()
                .append('<option value="">-- Select Series --</option>')
                .prop('disabled', true);
            return;
        }

        fetch(`/cars/series/by-maker/${makerId}`)
            .then(response => response.json())
            .then(data => {
                seriesSelect.empty().append('<option value="">-- Select Series --</option>');

                data.forEach(series => {
                    seriesSelect.append(
                        `<option value="${series.id}">${series.name}</option>`
                    );
                });

                seriesSelect.prop('disabled', false).trigger('change');
            })
            .catch(() => {
                seriesSelect
                    .empty()
                    .append('<option value="">Failed to load series</option>');
            });
    });
</script>

@endpush