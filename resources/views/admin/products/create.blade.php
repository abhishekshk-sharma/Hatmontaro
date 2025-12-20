@extends('admin.layouts.app')

@section('content')
<h3>Add Product</h3>
<form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label class="form-label">Name</label>
        <input name="name" class="form-control" value="{{ old('name') }}" required />
    </div>
    <div class="mb-3">
        <label class="form-label">Brand</label>
        <input name="brand" class="form-control" value="{{ old('brand') }}" placeholder="e.g., Nike, Adidas" />
        <div class="form-text">Optional - For caps, specify brand name</div>
    </div>
    <div class="mb-3">
        <label class="form-label">Slug</label>
        <input name="slug" class="form-control" value="{{ old('slug') }}" required />
    </div>
    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control">{{ old('description') }}</textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Price</label>
        <input name="price" class="form-control" value="{{ old('price') }}" required />
    </div>
    <div class="mb-3">
        <label class="form-label">Category</label>
        <select name="category_id" class="form-control">
            <option value="">-- none --</option>
            @foreach($categories as $c)
                <option value="{{ $c->id }}">{{ $c->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Style Type</label>
            <input name="style_type" class="form-control" value="{{ old('style_type','casual') }}" />
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Occasion</label>
            <input name="occasion" class="form-control" value="{{ old('occasion','casual') }}" />
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Color</label>
            <input name="color" class="form-control" value="{{ old('color','') }}" />
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Compare Price</label>
            <input name="compare_price" class="form-control" value="{{ old('compare_price') }}" />
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Stock Quantity</label>
            <input name="stock_quantity" type="number" class="form-control" value="{{ old('stock_quantity',0) }}" />
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Primary Image (Legacy)</label>
        <input type="file" name="image" class="form-control" accept="image/*,.svg" />
    </div>
    
    <div class="mb-3">
        <label class="form-label">Product Media (Images/Videos)</label>
        <input type="file" name="media[]" class="form-control" accept="image/*,video/*" multiple />
        <div class="form-text">Upload up to 10 images or videos. Supported: JPG, PNG, GIF, MP4, WEBM</div>
    </div>
    <div class="form-check mb-3">
        <input type="hidden" name="is_featured" value="0">
        <input type="checkbox" name="is_featured" value="1" class="form-check-input" id="f1">
        <label class="form-check-label" for="f1">Featured</label>
    </div>
    <div class="form-check mb-3">
        <input type="hidden" name="is_ai_recommended" value="0">
        <input type="checkbox" name="is_ai_recommended" value="1" class="form-check-input" id="f2">
        <label class="form-check-label" for="f2">AI Recommended</label>
    </div>
    <button class="btn btn-primary">Create</button>
</form>
@endsection
