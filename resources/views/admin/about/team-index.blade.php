@extends('admin.layouts.app')

@section('title', 'Team Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Team Members</h3>
                    <div>
                        <a href="{{ route('admin.about.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to About
                        </a>
                        <a href="{{ route('admin.about.team.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Member
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($teamMembers as $member)
                                <tr>
                                    <td>
                                        @if($member->image)
                                            <img src="{{ asset('storage/' . $member->image) }}" alt="{{ $member->name }}" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="rounded-circle bg-secondary d-none align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                <i class="{{ $member->icon ?? 'bi-person' }} text-white"></i>
                                            </div>
                                        @else
                                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                <i class="{{ $member->icon ?? 'bi-person' }} text-white"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $member->name }}</td>
                                    <td>{{ $member->position }}</td>
                                    <td>
                                        <span class="badge bg-{{ $member->is_active ? 'success' : 'danger' }}">
                                            {{ $member->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.about.team.edit', $member->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.about.team.destroy', $member->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this member?')">
                                                <i class="fas fa-trash"></i>
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No team members found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
