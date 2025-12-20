@extends('admin.layouts.app')

@section('title', 'Create Offer')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary">&larr; Back to Offers</a>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h4 class="mb-0">Create New Offer</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Offer Title *</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="e.g., 50% OFF, Free Shipping" required>
                    @error('title')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Display Order *</label>
                    <input type="number" name="order" class="form-control" value="{{ old('order', 0) }}" required>
                    <small class="text-muted">Lower numbers appear first</small>
                    @error('order')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Offer Description *</label>
                <textarea name="description" class="form-control" rows="2" placeholder="e.g., On all summer collection items" required>{{ old('description') }}</textarea>
                @error('description')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Action Button Text *</label>
                    <input type="text" name="button_text" class="form-control" value="{{ old('button_text', 'Shop Now') }}" placeholder="e.g., Shop Now, View Deals" required>
                    @error('button_text')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Button Link *</label>
                    <input type="text" name="button_link" class="form-control" value="{{ old('button_link', '/products') }}" placeholder="e.g., /products, /categories/men" required>
                    @error('button_link')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Card Background - Start Color *</label>
                    <input type="color" name="gradient_from" class="form-control" value="{{ old('gradient_from', '#667eea') }}" required>
                    <small class="text-muted">Choose attractive colors for the offer card</small>
                    @error('gradient_from')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Card Background - End Color *</label>
                    <input type="color" name="gradient_to" class="form-control" value="{{ old('gradient_to', '#764ba2') }}" required>
                    <small class="text-muted">Creates gradient effect with start color</small>
                    @error('gradient_to')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <div class="form-check">
                    <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Active</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Create Offer</button>
        </form>
    </div>
</div>
@endsection
