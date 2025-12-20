@extends('admin.layouts.app')

@section('title', 'Edit Section')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit {{ ucfirst($section->section) }} Section</h3>
                </div>
                <form action="{{ route('admin.about.section.update', $section->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label>Title *</label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $section->title) }}" required>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="form-group">
                            <label>Content *</label>
                            <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="5" required>{{ old('content', $section->content) }}</textarea>
                            @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="form-group">
                            <label>Image</label>
                            @if($section->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $section->image) }}" alt="Current Image" style="max-width: 200px; height: auto;">
                                </div>
                            @endif
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                            @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        
                        @if($section->section === 'hero')
                        <div class="form-group">
                            <label>Stats (JSON format)</label>
                            <textarea name="extra_data" class="form-control @error('extra_data') is-invalid @enderror" rows="3" placeholder='{"stats":[{"label":"Happy Customers","value":"10K+"}]}'>{{ old('extra_data', json_encode($section->extra_data)) }}</textarea>
                            @error('extra_data')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        @endif
                        
                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" class="form-check-input" value="1" {{ old('is_active', $section->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label">Active</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('admin.about.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection