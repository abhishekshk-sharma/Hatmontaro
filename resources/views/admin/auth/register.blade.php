@extends('admin.layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <h3>Admin Register</h3>
        <form method="POST" action="{{ route('admin.register') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input name="name" value="{{ old('name') }}" class="form-control" />
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input name="email" value="{{ old('email') }}" class="form-control" />
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input name="password" type="password" class="form-control" />
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input name="password_confirmation" type="password" class="form-control" />
            </div>
            <button class="btn btn-primary">Register</button>
        </form>
    </div>
</div>
@endsection
