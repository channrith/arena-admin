@extends('layouts.app')

{{-- Customize layout sections --}}

@section('subtitle', 'Posts')
@section('content_header_title', 'Coming soon...')

{{-- Content body: main page content --}}

@section('content_body')
<div class="row">
    <div class="col-12"></div>
</div>
@stop

{{-- Push extra CSS --}}

@push('css')
{{-- Add here extra stylesheets --}}
{{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@endpush

{{-- Push extra scripts --}}

@push('js')
<script>

</script>
@endpush