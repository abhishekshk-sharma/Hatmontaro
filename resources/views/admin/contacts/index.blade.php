@extends('admin.layouts.app')

@section('title', 'Contact Messages')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Contact Messages</h2>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search by name, email, subject..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="all">All Status</option>
                            <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New</option>
                            <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Read</option>
                            <option value="responded" {{ request('status') == 'responded' ? 'selected' : '' }}>Responded</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary">Clear</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($messages->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($messages as $message)
                            <tr class="{{ $message->status == 'new' ? 'table-warning' : '' }}">
                                <td>#{{ $message->id }}</td>
                                <td>{{ $message->name }}</td>
                                <td>{{ $message->email }}</td>
                                <td>{{ Str::limit($message->subject, 30) }}</td>
                                <td>
                                    <span class="badge bg-{{ $message->status == 'new' ? 'warning' : ($message->status == 'responded' ? 'success' : 'info') }}">
                                        {{ ucfirst($message->status) }}
                                    </span>
                                </td>
                                <td>{{ $message->created_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.contacts.show', $message) }}" class="btn btn-sm btn-primary">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $messages->links() }}
            @else
                <div class="text-center py-5">
                    <h4>No contact messages found</h4>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection