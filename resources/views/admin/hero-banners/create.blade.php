@extends('admin.layouts.app')

@section('title', 'Create Hero Banner')

@section('content')
<div class="page-header">
    <h1 class="page-title">Create Hero Banner</h1>
    <p class="page-subtitle">Add a new hero banner to homepage</p>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <strong>Upload Failed:</strong>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.hero-banners.store') }}" method="POST" enctype="multipart/form-data" id="heroBannerForm">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Order</label>
                        <input type="number" name="order" class="form-control" value="{{ old('order', 0) }}" required>
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Media (Image/Video) *</label>
                <input type="file" name="media" class="form-control" accept="image/*,video/mp4,video/webm" required id="mediaFile">
                <div class="form-text">Supported: JPG, PNG, GIF, MP4, WEBM (Max: 10MB)</div>
                <div id="fileSizeError" class="text-danger" style="display: none;">File size must be less than 10MB</div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Button Text</label>
                        <input type="text" name="button_text" class="form-control" value="{{ old('button_text') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Button Link</label>
                        <input type="url" name="button_link" class="form-control" value="{{ old('button_link') }}">
                    </div>
                </div>
            </div>
            
            <div class="form-check mb-3">
                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="active" {{ old('is_active', true) ? 'checked' : '' }}>
                <label class="form-check-label" for="active">Active</label>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Create Banner</button>
                <a href="{{ route('admin.hero-banners.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('mediaFile').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const errorDiv = document.getElementById('fileSizeError');
    const submitBtn = document.querySelector('button[type="submit"]');
    
    if (file) {
        console.log('File selected:', {
            name: file.name,
            size: file.size,
            type: file.type
        });
        
        const maxSize = 10 * 1024 * 1024; // 10MB in bytes
        if (file.size > maxSize) {
            errorDiv.style.display = 'block';
            submitBtn.disabled = true;
            e.target.value = ''; // Clear the file input
        } else {
            errorDiv.style.display = 'none';
            submitBtn.disabled = false;
        }
    }
});

// Add form submission logging
document.getElementById('heroBannerForm').addEventListener('submit', function(e) {
    const submitBtn = document.querySelector('button[type="submit"]');
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Uploading...';
    submitBtn.disabled = true;
    
    console.log('Form submitted');
    const formData = new FormData(this);
    console.log('Form data:', {
        title: formData.get('title'),
        description: formData.get('description'),
        order: formData.get('order'),
        media: formData.get('media') ? formData.get('media').name : 'No file'
    });
});
</script>
@endsection