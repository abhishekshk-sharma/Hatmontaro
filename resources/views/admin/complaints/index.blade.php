@extends('admin.layouts.app')

@section('title', 'User Complaints')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>User Complaints</h2>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search by subject, user name, email..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="all">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('admin.complaints.index') }}" class="btn btn-outline-secondary">Clear</a>
                    </div>
                </div>
            </form>
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
                                <th>User</th>
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
                                <td>
                                    {{ $complaint->user->username }}<br>
                                    <small class="text-muted">{{ $complaint->user->email }}</small>
                                </td>
                                <td>{{ Str::limit($complaint->subject, 30) }}</td>
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
                                    <a href="{{ route('admin.complaints.show', $complaint) }}" class="btn btn-sm btn-primary">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $complaints->links() }}
            @else
                <div class="text-center py-5">
                    <h4>No complaints found</h4>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection