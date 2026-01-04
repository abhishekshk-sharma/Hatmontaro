@extends('admin.layouts.auth')

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
            <div class="mb-3 position-relative">
                <label class="form-label">Password</label>
                <input id="password" name="password" type="password" class="form-control pe-5" />
                <i class="bi bi-eye position-absolute" id="togglePassword" style="right: 15px; top: 38px; cursor: pointer;" onclick="togglePassword()"></i>
            </div>
            <div class="mb-3 position-relative">
                <label class="form-label">Confirm Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" class="form-control pe-5" />
                <i class="bi bi-eye position-absolute" id="togglePasswordConfirm" style="right: 15px; top: 38px; cursor: pointer;" onclick="togglePasswordConfirm()"></i>
            </div>
            <button class="btn btn-primary">Register</button>
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
    
    function togglePasswordConfirm() {
        var passwordField = document.getElementById("password_confirmation");
        var toggleIcon = document.getElementById("togglePasswordConfirm");
        
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
