@extends('admin.layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <h3>Admin Login</h3>
        <form method="POST" action="{{ route('admin.login') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input name="email" value="{{ old('email') }}" class="form-control" />
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input name="password" type="password" class="form-control" />
            </div>
            <button class="btn btn-primary">Login</button>
        </form>
    </div>
</div>
@endsection
