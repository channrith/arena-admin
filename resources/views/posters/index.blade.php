@extends('layouts.app')

{{-- Customize layout sections --}}

@section('subtitle', 'Posters')
@section('content_header_title', 'Posters')
@section('content_header_subtitle', 'All')

{{-- Content body: main page content --}}

@section('content_body')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Responsive Hover Table</h3>

                <div class="card-tools">
                    <div class="input-group input-group-sm" style="width: 150px;">
                        <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap projects">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Title</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($posters as $key => $poster)
                        <tr>
                            <td>{{$key + 1}}</td>
                            <td>{{ $poster->title }}</td>
                            <td><img src="{{ $poster->image_url }}"
                                    alt="Feature Image"
                                    class="img-fluid rounded"
                                    style="max-height: 50px;"></td>
                            <td class="project-actions text-right">
                                <a class="btn btn-primary btn-sm" href="{{ route('posters.edit', $poster->id) }}">
                                    <i class="fas fa-pencil-alt"></i>
                                    Edit
                                </a>
                                <button
                                    type="button"
                                    class="btn btn-danger btn-sm btn-delete"
                                    data-id="{{ $poster->id }}"
                                    data-title="{{ $poster->title }}"
                                    data-toggle="modal"
                                    data-target="#modal-confirm-delete">
                                    <i class="fas fa-trash"></i>
                                    Delete
                                </button>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>

                <!-- Delete Confirmation Modal -->
                <div class="modal fade" id="modal-confirm-delete" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form id="deleteForm" method="POST" action="">
                            @csrf
                            @method('DELETE')

                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title" id="modalLabel">Confirm Delete</h5>
                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <p>Are you sure you want to delete this item?</p>
                                    <p><strong id="itemTitle"></strong></p>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                        Cancel
                                    </button>
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash"></i> Confirm Delete
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
                {{ $posters->links('vendor.pagination.custom') }}
            </div>
        </div>
        <!-- /.card -->
    </div>
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
    document.addEventListener('DOMContentLoaded', function() {
        const deleteModal = $('#modal-confirm-delete');
        const deleteForm = document.getElementById('deleteForm');
        const itemTitle = document.getElementById('itemTitle');

        $('.btn-delete').on('click', function() {
            const postId = $(this).data('id');
            const title = $(this).data('title');

            // Update modal text and form action
            itemTitle.textContent = title ? `Item: "${title}"` : '';
            deleteForm.action = `/posters/${postId}`; // RESTful route
        });
    });
</script>
@endpush