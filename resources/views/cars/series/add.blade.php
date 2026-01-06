@extends('layouts.app')

{{-- Customize layout sections --}}

@section('subtitle', 'Vehicle Series')
@section('content_header_title', 'Vehicle Series')
@section('content_header_subtitle', 'Add New')

{{-- Content body: main page content --}}

@section('content_body')
<!-- resources/views/posts/create.blade.php -->
<form action="{{ route('cars.series.store') }}" method="POST">
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
                    <div class="form-group">
                        <label for="maker_id">
                            Vehicle Maker <span class="text-danger">*</span>
                        </label>
                        <select
                            name="maker_id"
                            id="maker_id"
                            class="form-control @error('maker_id') is-invalid @enderror"
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
{{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@endpush

{{-- Push extra scripts --}}

@push('js')
{{-- <script></script> --}}
@endpush