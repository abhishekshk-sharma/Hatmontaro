@extends('admin.layouts.app')

@section('content')
<h3>Edit Product</h3>
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label class="form-label">Name</label>
        <input name="name" class="form-control" value="{{ old('name', $product->name) }}" required />
    </div>
    <div class="mb-3">
        <label class="form-label">Brand</label>
        <input name="brand" class="form-control" value="{{ old('brand', $product->brand) }}" placeholder="e.g., Nike, Adidas" />
        <div class="form-text">Optional - For caps, specify brand name</div>
    </div>
    <div class="mb-3">
        <label class="form-label">Slug</label>
        <input name="slug" class="form-control" value="{{ old('slug', $product->slug) }}" required />
    </div>
    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control">{{ old('description', $product->description) }}</textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Price</label>
        <input name="price" class="form-control" value="{{ old('price', $product->price) }}" required />
    </div>
    <div class="mb-3">
        <label class="form-label">Category</label>
        <select name="category_id" class="form-control">
            <option value="">-- none --</option>
            @foreach($categories as $c)
                <option value="{{ $c->id }}" @if($product->category_id == $c->id) selected @endif>{{ $c->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Style Type</label>
            <input name="style_type" class="form-control" value="{{ old('style_type', $product->style_type) }}" />
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Occasion</label>
            <input name="occasion" class="form-control" value="{{ old('occasion', $product->occasion) }}" />
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Color</label>
            <input name="color" class="form-control" value="{{ old('color', $product->color) }}" />
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Compare Price</label>
            <input name="compare_price" class="form-control" value="{{ old('compare_price', $product->compare_price) }}" />
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Stock Quantity</label>
            <input name="stock_quantity" type="number" class="form-control" value="{{ old('stock_quantity', $product->stock_quantity) }}" />
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Primary Image (Legacy)</label>
        <input type="file" name="image" class="form-control" accept="image/*,.svg" />
        @if($product->image_url)
            <div class="mt-2">
                <img src="{{ $product->image_url }}" style="height:80px;object-fit:cover;" />
            </div>
        @endif
    </div>
    
    <div class="mb-3">
        <label class="form-label">Product Media (Images/Videos)</label>
        <input type="file" name="media[]" class="form-control" accept="image/*,video/*" multiple />
        <div class="form-text">Upload up to 10 images or videos. Supported: JPG, PNG, GIF, MP4, WEBM</div>
    </div>
    
    @if($product->media && $product->media->count() > 0)
    <div class="mb-3">
        <label class="form-label">Current Media</label>
        <div class="row g-2" id="mediaGrid">
            @foreach($product->media as $media)
            <div class="col-md-2" data-media-id="{{ $media->id }}">
                <div class="card">
                    @if($media->type === 'video')
                        <video src="{{ $media->url }}" class="card-img-top" style="height:100px;object-fit:cover;"></video>
                    @else
                        <img src="{{ $media->url }}" class="card-img-top" style="height:100px;object-fit:cover;" />
                    @endif
                    <div class="card-body p-1 text-center">
                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteMedia({{ $media->id }})">Delete</button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    <div class="form-check mb-3">
        <input type="hidden" name="is_featured" value="0">
        <input type="checkbox" name="is_featured" value="1" class="form-check-input" id="f1" @if($product->is_featured) checked @endif>
        <label class="form-check-label" for="f1">Featured</label>
    </div>
    <div class="card p-3 mb-3 bg-light border">
        <h5 class="fw-bold mb-3"><i class="bi bi-truck text-primary me-2"></i>Delivery Settings</h5>
        <div class="form-check mb-3">
            <input type="hidden" name="is_premium_delivery" value="0">
            <input type="checkbox" name="is_premium_delivery" value="1" class="form-check-input" id="is_premium_delivery" @if($product->is_premium_delivery) checked @endif>
            <label class="form-check-label fw-semibold" for="is_premium_delivery">
                Premium Delivery (<span class="text-primary fw-bold">hatmontaro +</span> Free Delivery)
            </label>
            <div class="form-text">When checked, product displays the hatmontaro+ badge with Free Delivery.</div>
        </div>
        <div class="mb-2">
            <label class="form-label fw-semibold">Delivery Time Promise</label>
            <input name="delivery_time" class="form-control" value="{{ old('delivery_time', $product->delivery_time ?? 'Tomorrow, 2 PM') }}" placeholder="e.g. Tomorrow, 2 PM or 2-3 Business Days" />
            <div class="form-text">Specify expected delivery time displayed on product cards (e.g., "Tomorrow, 2 PM", "Today by 9 PM", "2-3 Days").</div>
        </div>
    </div>
    
    <button class="btn btn-primary">Save</button>
</form>

<script>
function deleteMedia(id) {
    if (!confirm('Delete this media?')) return;
    fetch(`/admin/products/media/${id}`, {
        method: 'DELETE',
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
    }).then(r => r.json()).then(() => {
        document.querySelector(`[data-media-id="${id}"]`).remove();
    });
}
</script>
@endsection
