@extends('admin.layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <h3>Admin Login</h3>
        
        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        
        <form method="POST" action="{{ route('admin.login') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input name="email" value="{{ old('email') }}" class="form-control" />
            </div>
            <div class="mb-3 position-relative">
                <label class="form-label">Password</label>
                <input id="password" name="password" type="password" class="form-control pe-5" />
                <i class="bi bi-eye position-absolute" id="togglePassword" style="right: 15px; top: 38px; cursor: pointer;" onclick="togglePassword()"></i>
            </div>
            <button class="btn btn-primary">Login</button>
            
            <div class="mt-3 text-center">
                <a href="{{ route('admin.password.request') }}" class="text-decoration-none">Forgot Your Password?</a>
            </div>
        </form>
    </div>
</div>
<script>
    function togglePassword() {
        var passwordField = document.getElementById("password");
        var toggleIcon = document.getElementById("togglePassword");
        
        if (passwordField.getAttribute("type") === "password") {
            passwordField.setAttribute("type", "text");
            toggleIcon.classList.remove("bi-eye");
            toggleIcon.classList.add("bi-eye-slash");
        } else {
            passwordField.setAttribute("type", "password");
            toggleIcon.classList.remove("bi-eye-slash");
            toggleIcon.classList.add("bi-eye");
        }
    }
</script>
@endsection
