<div class="card mb-3 color-item p-3 border">

    <div class="d-flex justify-content-between">
        <strong>Color Item</strong>
        <button type="button" class="btn btn-sm btn-danger remove-color">×</button>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="form-group mt-2">
                <label>Color Name <span class="text-danger">*</span></label>
                <input type="text" name="colors[color_name][]"
                    value="{{ $color->color_name ?? '' }}"
                    class="form-control" required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mt-2">
                <label>Color Code <span class="text-danger">*</span></label>
                <input type="text" name="colors[color_hex][]"
                    value="{{ $color->color_hex ?? '' }}"
                    class="form-control" required>
            </div>
        </div>
    </div>

    <div class="form-group mt-2">
        <label>Color Image</label>

        @if(!empty($color))
        <img src="{{ $baseUrl . $color->image_url }}" class="img-fluid preview-img mb-2" style="max-height:150px;">
        @else
        <img class="img-fluid preview-img mb-2" style="max-height:150px; display:none;">
        @endif

        <input type="file"
            name="colors[image][]"
            class="form-control color-image-input">
    </div>

    @if(!empty($color))
    <input type="hidden" name="colors[id][]" value="{{ $color->id }}">
    @else
    <input type="hidden" name="colors[id][]" value="">
    @endif
</div>