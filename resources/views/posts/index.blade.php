@extends('layouts.app')

{{-- Customize layout sections --}}

@section('subtitle', 'Posts')
@section('content_header_title', 'Posts')
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
                            <th>ID</th>
                            <th>Title</th>
                            <th></th>
                            <th>Published Date</th>
                            <th>Status</th>
                            <th>Author</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($posts as $key => $post)
                        <tr>
                            <td>{{$key + 1}}</td>
                            <td>{{ $post->currentTranslation->title }}</td>
                            <td>
                                @if($post->is_special)
                                <span class="badge badge-danger">Special</span>
                                @endif
                            </td>
                            <td>{{ $post->published_at ? $post->published_at->format('Y/m/d') : '' }}</td>
                            <td>
                                @if($post->status==="approved" && $post->display_status==="Published")
                                <span class="badge badge-success">{{ $post->display_status  }}</span>
                                @elseif($post->status==="approved" && $post->display_status==="Scheduled")
                                <span class="badge badge-primary">{{ $post->display_status  }}</span>
                                @elseif($post->status==="pending")
                                <span class="badge badge-info">{{ $post->status }}</span>
                                @elseif($post->status==="rejected")
                                <span class="badge badge-danger">{{ $post->status }}</span>
                                @else
                                <span class="badge badge-warning">{{ $post->status }}</span>
                                @endif

                            </td>
                            <td>{{ $post->author->name }}</td>
                            <td class="project-actions text-right">
                                <a class="btn btn-primary btn-sm" href="{{ route('posts.edit', $post->id) }}">
                                    <i class="fas fa-pencil-alt"></i>
                                    Edit
                                </a>
                                <button
                                    type="button"
                                    class="btn btn-danger btn-sm btn-delete"
                                    data-id="{{ $post->id }}"
                                    data-title="{{ $post->currentTranslation->title }}"
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
                                    <p>Are you sure you want to delete this post?</p>
                                    <p><strong id="postTitle"></strong></p>
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
                {{ $posts->links('vendor.pagination.custom') }}
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
        const postTitle = document.getElementById('postTitle');

        $('.btn-delete').on('click', function() {
            const postId = $(this).data('id');
            const title = $(this).data('title');

            // Update modal text and form action
            postTitle.textContent = title ? `Post: "${title}"` : '';
            deleteForm.action = `/posts/${postId}`; // RESTful route
        });
    });
</script>
@endpush