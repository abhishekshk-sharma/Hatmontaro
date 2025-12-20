@extends('admin.layouts.app')

@section('title', 'Edit Banner')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Edit Banner</h1>
        <a href="{{ route('admin.page-banners.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.page-banners.update', $pageBanner) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">Page Type</label>
                            <input type="text" class="form-control" value="{{ ucfirst($pageBanner->page_type) }}" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Page Identifier</label>
                            <input type="text" class="form-control" value="{{ $pageBanner->page_identifier ?? 'AI Recommended' }}" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Title *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $pageBanner->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $pageBanner->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="banner_image" class="form-label">Banner Image</label>
                            <input type="file" class="form-control @error('banner_image') is-invalid @enderror" 
                                   id="banner_image" name="banner_image" accept="image/*">
                            @error('banner_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Recommended size: 1200x400px</small>
                        </div>
                        
                        @if($pageBanner->banner_image)
                        <div class="mb-3">
                            <label class="form-label">Current Banner</label>
                            <div>
                                <img src="{{ $pageBanner->banner_image_url }}" alt="Current Banner" 
                                     class="img-thumbnail" style="max-width: 300px;">
                            </div>
                        </div>
                        @endif
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                       value="1" {{ old('is_active', $pageBanner->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check"></i> Update Banner
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection