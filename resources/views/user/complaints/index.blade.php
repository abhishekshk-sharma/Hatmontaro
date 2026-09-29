@extends('layouts.app')

@section('title', 'My Complaints')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>My Complaints</h2>
                <div>
                    <a href="{{ route('user.profile') }}" class="btn btn-outline-secondary me-2">Back to Profile</a>
                    <a href="{{ route('user.complaints.create') }}" class="btn btn-primary">Submit New Complaint</a>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    @if($complaints->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Subject</th>
                                        <th>Order</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($complaints as $complaint)
                                    <tr>
                                        <td>#{{ $complaint->id }}</td>
                                        <td>{{ $complaint->subject }}</td>
                                        <td>
                                            @if($complaint->order)
                                                {{ $complaint->order->order_number }}
                                            @else
                                                <span class="text-muted">General</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $complaint->status == 'resolved' ? 'success' : ($complaint->status == 'in_progress' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                                            </span>
                                        </td>
                                        <td>{{ $complaint->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <a href="{{ route('user.complaints.show', $complaint) }}" class="btn btn-sm btn-outline-primary">View</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $complaints->links() }}
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-chat-square-text display-1 text-muted"></i>
                            <h4 class="mt-3">No complaints submitted</h4>
                            <p class="text-muted">If you have any issues, feel free to submit a complaint.</p>
                            <a href="{{ route('user.complaints.create') }}" class="btn btn-primary">Submit Complaint</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection