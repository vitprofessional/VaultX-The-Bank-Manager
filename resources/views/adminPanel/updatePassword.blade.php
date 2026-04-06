@extends('adminPanel.include')
@section('calculasTitle') Change Password @endsection
@section('calculasBody')

<div class="page-header">
    <div>
        <div class="page-kicker">Account Security</div>
        <h1 class="page-title">Change Password</h1>
        <p class="page-copy">Update your password to keep your account secure.</p>
    </div>
</div>

@if(session()->has('success'))
    <div class="alert alert-success">
        <i class="fa-solid fa-circle-check"></i>
        <span>{{ session()->get('success') }}</span>
    </div>
@endif

@if(session()->has('error'))
    <div class="alert alert-danger">
        <i class="fa-solid fa-circle-exclamation"></i>
        <span>{{ session()->get('error') }}</span>
    </div>
@endif

<div class="row g-4 align-items-start">
    <div class="col-12 col-lg-7">
        <div class="card">
            <div class="card-header">
                <i class="fa-solid fa-shield-halved"></i> Update Password
            </div>
            <div class="card-body">
                <form class="row g-3" action="{{ route('updatePassword') }}" method="POST">
                    @csrf
                    <input type="hidden" name="employeeId" value="{{ $employee_id }}">

                    <div class="col-12">
                        <label for="loginPass" class="form-label">Current Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" class="form-control form-control-lg" id="loginPass" name="loginPass" placeholder="Enter your current password" required>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="newPass" class="form-label">New Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-key"></i></span>
                            <input type="password" class="form-control form-control-lg" id="newPass" name="newPass" placeholder="Enter new password" required>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="confirmPass" class="form-label">Confirm New Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-key"></i></span>
                            <input type="password" class="form-control form-control-lg" id="confirmPass" name="confirmPass" placeholder="Re-enter new password" required>
                        </div>
                    </div>

                    <div class="col-12 d-flex gap-2 pt-3">
                        <button type="submit" class="btn btn-brand text-white btn-lg">
                            <i class="fa-solid fa-save"></i> Update Password
                        </button>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-lg">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-5">
        <div class="card">
            <div class="card-header">
                <i class="fa-solid fa-lightbulb"></i> Password Tips
            </div>
            <div class="card-body">
                <ul class="small">
                    <li>Use at least 8 characters</li>
                    <li>Mix uppercase and lowercase letters</li>
                    <li>Include numbers and special characters</li>
                    <li>Avoid personal information</li>
                    <li>Don't reuse old passwords</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection