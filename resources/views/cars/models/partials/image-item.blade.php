<div class="card mb-3 image-item p-3 border">

    <div class="d-flex justify-content-between">
        <strong>Image Item</strong>
        <button type="button" class="btn btn-sm btn-danger remove-image">x</button>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="form-group mt-2">
                <label>Alt Text <span class="text-danger">*</span></label>
                <input type="text" name="images[alt_text][]"
                    value="{{ $image->alt_text ?? '' }}"
                    class="form-control" required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mt-2">
                <label>Sequence</label>
                <input type="number" name="images[sequence][]"
                    value="{{ $image->sequence ?? 0 }}"
                    class="form-control">
            </div>
        </div>
    </div>

    <div class="form-group mt-2">
        <label>Image</label>

        @if(!empty($image))
        <img src="{{ $baseUrl . $image->image_url }}" class="img-fluid preview-img mb-2" style="max-height:150px;">
        @else
        <img class="img-fluid preview-img mb-2" style="max-height:150px; display:none;">
        @endif

        <input type="file"
            name="images[image][]"
            class="form-control image-image-input">
    </div>

    @if(!empty($image))
    <input type="hidden" name="images[id][]" value="{{ $image->id }}">
    @else
    <input type="hidden" name="images[id][]" value="">
    @endif
</div>