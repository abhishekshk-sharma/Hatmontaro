@extends('layouts.app')

@section('title', 'Complaint Details')

@section('content')
<div class="container py-5" style="margin-top: 80px;">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Complaint #{{ $complaint->id }}</h2>
                <a href="{{ route('user.complaints.index') }}" class="btn btn-outline-secondary">Back to Complaints</a>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $complaint->subject }}</h5>
                        <span class="badge bg-{{ $complaint->status == 'resolved' ? 'success' : ($complaint->status == 'in_progress' ? 'warning' : 'secondary') }} fs-6">
                            {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Submitted:</strong> {{ $complaint->created_at->format('M d, Y h:i A') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Related Order:</strong> 
                            @if($complaint->order)
                                <a href="{{ route('user.orders.view', $complaint->order->id) }}">{{ $complaint->order->order_number }}</a>
                            @else
                                <span class="text-muted">General Complaint</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6>Description:</h6>
                        <div class="bg-light p-3 rounded">
                            {{ $complaint->description }}
                        </div>
                    </div>

                    @if($complaint->admin_response)
                        <div class="border-top pt-3">
                            <h6>Admin Response:</h6>
                            <div class="bg-primary bg-opacity-10 p-3 rounded text-light">
                                {{ $complaint->admin_response }}
                            </div>
                            <small class="text-muted">Updated: {{ $complaint->updated_at->format('M d, Y h:i A') }}</small>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Your complaint is being reviewed. We will respond as soon as possible.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection