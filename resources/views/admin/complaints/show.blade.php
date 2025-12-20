@extends('admin.layouts.app')

@section('title', 'Complaint Details')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Complaint #{{ $complaint->id }}</h2>
        <a href="{{ route('admin.complaints.index') }}" class="btn btn-outline-secondary">Back to Complaints</a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- User Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>User Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Username:</strong> {{ $complaint->user->username }}</p>
                            <p><strong>Email:</strong> {{ $complaint->user->email }}</p>
                            <p><strong>Phone:</strong> {{ $complaint->user->phone_no ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>UPI ID:</strong> {{ $complaint->user->upi_id ?? 'N/A' }}</p>
                            <p><strong>Member Since:</strong> {{ $complaint->user->joining_date->format('M d, Y') }}</p>
                            <p><strong>Total Orders:</strong> {{ $complaint->user->orders()->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Complaint Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Complaint Details</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Subject:</strong> {{ $complaint->subject }}
                        </div>
                        <div class="col-md-6">
                            <strong>Date:</strong> {{ $complaint->created_at->format('M d, Y h:i A') }}
                        </div>
                    </div>
                    
                    @if($complaint->order)
                        <div class="mb-3">
                            <strong>Related Order:</strong> 
                            <a href="{{ route('admin.orders.show', $complaint->order) }}">{{ $complaint->order->order_number }}</a>
                            (₹{{ number_format($complaint->order->total_amount, 2) }})
                        </div>
                    @endif

                    <div class="mb-4">
                        <strong>Description:</strong>
                        <div class="bg-light p-3 rounded mt-2">
                            {{ $complaint->description }}
                        </div>
                    </div>

                    @if($complaint->admin_response)
                        <div class="border-top pt-3">
                            <strong>Admin Response:</strong>
                            <div class="bg-primary bg-opacity-10 p-3 rounded mt-2">
                                {{ $complaint->admin_response }}
                            </div>
                            <small class="text-muted">Updated: {{ $complaint->updated_at->format('M d, Y h:i A') }}</small>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order Details (if applicable) -->
            @if($complaint->order)
                <div class="card">
                    <div class="card-header">
                        <h5>Order Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($complaint->order->items as $item)
                                    <tr>
                                        <td>{{ $item->product->name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>₹{{ number_format($item->price, 2) }}</td>
                                        <td>₹{{ number_format($item->quantity * $item->price, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <!-- Status Update -->
            <div class="card">
                <div class="card-header">
                    <h5>Update Complaint</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.complaints.update', $complaint) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="pending" {{ $complaint->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ $complaint->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ $complaint->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ $complaint->status == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Admin Response</label>
                            <textarea name="admin_response" class="form-control" rows="6" placeholder="Type your response here...">{{ $complaint->admin_response }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Update Complaint</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection