@extends('layouts.app')

{{-- Customize layout sections --}}
@section('subtitle', 'Vehicle Series')
@section('content_header_title', 'Vehicle Series')
@section('content_header_subtitle', 'Edit')

{{-- Content body --}}
@section('content_body')
<form action="{{ route('cars.series.update', $series->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-md-9">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        Edit Vehicle Series
                    </h3>
                </div>

                <!-- Card Body -->
                <div class="card-body">

                    {{-- Maker --}}
                    <div class="form-group">
                        <label>
                            Vehicle Maker <span class="text-danger">*</span>
                        </label>
                        <select
                            name="maker_id"
                            class="form-control @error('maker_id') is-invalid @enderror"
                            required>
                            <option value="">-- Select Maker --</option>
                            @foreach ($makers as $maker)
                            <option
                                value="{{ $maker->id }}"
                                {{ old('maker_id', $series->maker_id) == $maker->id ? 'selected' : '' }}>
                                {{ $maker->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('maker_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Type --}}
                    <div class="form-group">
                        <label>
                            Vehicle Type <span class="text-danger">*</span>
                        </label>
                        <select
                            name="type_id"
                            class="form-control @error('type_id') is-invalid @enderror"
                            required>
                            <option value="">-- Select Type --</option>
                            @foreach ($types as $type)
                            <option
                                value="{{ $type->id }}"
                                {{ old('type_id', $series->type_id) == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('type_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Series Name --}}
                    <div class="form-group">
                        <label>
                            Vehicle Series Name <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="e.g. Tacoma"
                            value="{{ old('name', $series->name) }}"
                            >
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Slug (read-only display) --}}
                    <div class="form-group">
                        <label>Slug</label>
                        <input
                            type="text"
                            class="form-control"
                            value="{{ $series->slug }}"
                            readonly>
                        <small class="text-muted">
                            Slug is generated automatically from Maker + Type + Series
                        </small>
                    </div>

                </div>

                <!-- Footer -->
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Series
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