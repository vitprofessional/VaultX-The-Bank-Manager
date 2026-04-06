@extends('adminPanel.include')
@section('calculasTitle') My Profile @endsection
@section('calculasBody')

<div class="page-header">
    <div>
        <div class="page-kicker">Account Settings</div>
        <h1 class="page-title">My Profile</h1>
        <p class="page-copy">Update your personal and contact information.</p>
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
                <i class="fa-solid fa-user"></i> Profile Information
            </div>
            <div class="card-body">
                <form class="row g-3" action="{{ route('updateEmployeeProfile') }}" method="POST">
                    @csrf
                    <input type="hidden" name="employeeId" value="{{ $employee_id }}">

                    <div class="col-12">
                        <label for="employeeName" class="form-label">Full Name</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-user-circle"></i></span>
                            <input type="text" class="form-control form-control-lg" id="employeeName" name="employeeName" placeholder="Enter your full name" value="{{ $employee->name }}" required>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="employeeMail" class="form-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                            <input type="email" class="form-control form-control-lg" id="employeeMail" name="employeeMail" placeholder="Enter your email" value="{{ $employee->email }}" required>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="employeeMobile" class="form-label">Mobile Number</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-mobile"></i></span>
                            <input type="tel" class="form-control form-control-lg" id="employeeMobile" name="employeeMobile" placeholder="Enter your mobile number" value="{{ $employee->mobile }}" required>
                        </div>
                    </div>

                    <div class="col-12 d-flex gap-2 pt-3">
                        <button type="submit" class="btn btn-brand text-white btn-lg">
                            <i class="fa-solid fa-save"></i> Update Profile
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
                <i class="fa-solid fa-shield-halved"></i> Account Security
            </div>
            <div class="card-body">
                <p class="soft-muted mb-3">Manage your account security and change your password regularly.</p>
                <a href="{{ route('changeUserPass') }}" class="btn btn-outline-secondary w-100">
                    <i class="fa-solid fa-key"></i> Change Password
                </a>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="fa-solid fa-info-circle"></i> Profile Status
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="soft-muted">Employee ID</small>
                    <div class="h6 mb-0">{{ $employee_id }}</div>
                </div>
                <div class="mb-0">
                    <small class="soft-muted">Account Created</small>
                    <div class="h6 mb-0">{{ $employee->created_at->format('M d, Y') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection