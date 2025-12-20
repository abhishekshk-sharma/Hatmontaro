@extends('layouts.app')

@section('title', 'Submit Complaint')

@section('content')
<div class="container py-5" style="margin-top: 80px;">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Submit a Complaint</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.complaints.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="order_id" class="form-label">Related Order (Optional)</label>
                            <select name="order_id" id="order_id" class="form-select @error('order_id') is-invalid @enderror">
                                <option value="">Select an order (if applicable)</option>
                                @foreach($orders as $order)
                                    <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}>
                                        {{ $order->order_number }} - ₹{{ number_format($order->total_amount, 2) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('order_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject *</label>
                            <input type="text" name="subject" id="subject" 
                                   class="form-control @error('subject') is-invalid @enderror" 
                                   value="{{ old('subject') }}" required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <textarea name="description" id="description" rows="6" 
                                      class="form-control @error('description') is-invalid @enderror" 
                                      required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Please provide detailed information about your complaint.</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('user.complaints.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Submit Complaint</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection