<!-- resources/views/profile/show.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            @include('profile.partials.sidebar')
        </div>
        
        <!-- Content -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h4>My Style Profile</h4>
                </div>
                <div class="card-body">
                    <!-- Style preferences form -->
                    <form id="stylePreferencesForm">
                        <!-- Style types -->
                        <div class="mb-4">
                            <h6>Preferred Styles</h6>
                            @foreach(['minimalist', 'bohemian', 'classic', 'streetwear', 'formal', 'casual'] as $style)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" 
                                           name="styles[]" value="{{ $style }}"
                                           id="style-{{ $style }}">
                                    <label class="form-check-label" for="style-{{ $style }}">
                                        {{ ucfirst($style) }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Colors -->
                        <div class="mb-4">
                            <h6>Preferred Colors</h6>
                            <div class="color-picker">
                                @foreach(['black', 'white', 'blue', 'red', 'green', 'pink', 'yellow', 'purple'] as $color)
                                    <div class="color-option" style="background-color: {{ $color }}"></div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Sizes -->
                        <div class="mb-4">
                            <h6>Size Preferences</h6>
                            <select name="size" class="form-select">
                                <option value="">Select Size</option>
                                <option value="XS">XS</option>
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="L">L</option>
                                <option value="XL">XL</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Save Preferences</button>
                    </form>
                </div>
            </div>
            
            <!-- AI Recommendations History -->
            <div class="card mt-4">
                <div class="card-header">
                    <h4>My AI Recommendations</h4>
                </div>
                <div class="card-body">
                    @foreach(auth()->user()->aiRecommendations as $recommendation)
                        <div class="border-bottom pb-3 mb-3">
                            <p><strong>You asked:</strong> "{{ $recommendation->user_query }}"</p>
                            <small class="text-muted">{{ $recommendation->created_at->diffForHumans() }}</small>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection