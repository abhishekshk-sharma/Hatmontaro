@extends('admin.layouts.app')

@section('title', 'Contact Message Details')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Contact Message #{{ $contact->id }}</h2>
        <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary">Back to Messages</a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5>Message Details</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Name:</strong> {{ $contact->name }}
                        </div>
                        <div class="col-md-6">
                            <strong>Email:</strong> {{ $contact->email }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Subject:</strong> {{ $contact->subject }}
                        </div>
                        <div class="col-md-6">
                            <strong>Status:</strong> 
                            <span class="badge bg-{{ $contact->status == 'new' ? 'warning' : ($contact->status == 'responded' ? 'success' : 'info') }}">
                                {{ ucfirst($contact->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <strong>Date:</strong> {{ $contact->created_at->format('M d, Y h:i A') }}
                    </div>
                    <div class="mb-4">
                        <strong>Message:</strong>
                        <div class="bg-light p-3 rounded mt-2">
                            {{ $contact->message }}
                        </div>
                    </div>

                    @if($contact->admin_response)
                        <div class="border-top pt-3">
                            <strong>Admin Response:</strong>
                            <div class="bg-primary bg-opacity-10 p-3 rounded mt-2">
                                {{ $contact->admin_response }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5>Admin Response</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.contacts.respond', $contact) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <textarea name="admin_response" class="form-control" rows="6" placeholder="Type your response here..." required>{{ $contact->admin_response }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            {{ $contact->admin_response ? 'Update Response' : 'Send Response' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection