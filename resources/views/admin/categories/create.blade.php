@extends('admin.layouts.app')

@section('content')
<h3>Add Category</h3>

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.categories.store') }}">
    @csrf
    
    <div class="mb-3">
        <label class="form-label">Name</label>
        <input name="name" class="form-control" value="{{ old('name') }}" required />
    </div>
    
    <div class="mb-3">
        <label class="form-label">Slug</label>
        <input name="slug" class="form-control" value="{{ old('slug') }}" required />
        <div class="form-text">URL-friendly name (e.g., men, women, caps)</div>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
    </div>
    
    <button class="btn btn-primary">Create Category</button>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection
