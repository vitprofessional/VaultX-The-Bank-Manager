@extends('adminPanel.include')
@section('calculasTitle') Create Account @endsection
@section('calculasBody')

<div class="page-header">
    <div>
        <div class="page-kicker">Account Management</div>
        <h1 class="page-title">Create New Account</h1>
        <p class="page-copy">Add a new customer account to the system with complete information.</p>
    </div>
    <div>
        <a href="{{ route('acList') }}" class="btn btn-outline-secondary noprint">
            <i class="fa-solid fa-arrow-left"></i> Back to Accounts
        </a>
    </div>
</div>

<div class="row g-4 align-items-start">
    <div class="col-12 col-lg-7">
        <div class="card">
            <div class="card-header">
                <i class="fa-solid fa-user-plus"></i> Account Information
            </div>
            <div class="card-body">
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

                <form class="row g-3" method="POST" action="{{ route('saveAccount') }}">
                    @csrf
                    <input type="hidden" name="employeeId" value="{{ $employee_id }}">

                    <div class="col-12">
                        <label for="acName" class="form-label">Account Holder Name</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                            <input type="text" class="form-control form-control-lg" name="acName" id="acName" placeholder="Enter full name" required>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="acNo" class="form-label">Account Number</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-credit-card"></i></span>
                            <input type="number" class="form-control form-control-lg" id="acNo" name="acNo" placeholder="Enter account number" maxlength="13" required>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="acType" class="form-label">Account Type</label>
                        <select id="acType" class="form-select form-select-lg" name="acType" required>
                            <option value="">-- Select Account Type --</option>
                            <option value="Savings">Savings Account</option>
                            <option value="Current">Current Account</option>
                            <option value="School Banking">School Banking</option>
                            <option value="Interest Fee">Interest Fee</option>
                            <option value="Salary">Salary Account</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label for="acMobile" class="form-label">Mobile Number</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-mobile"></i></span>
                            <input type="text" class="form-control form-control-lg" name="acMobile" id="acMobile" placeholder="Enter mobile number" required>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="acFinger" class="form-label">Fingerprint ID</label>
                        <select id="acFinger" class="form-select form-select-lg" name="acFinger" required>
                            <option value="">-- Select Finger --</option>
                            <optgroup label="Left Hand">
                                <option value="L1">Left Thumb (L1)</option>
                                <option value="L2">Left Index (L2)</option>
                                <option value="L3">Left Middle (L3)</option>
                                <option value="L4">Left Ring (L4)</option>
                                <option value="L5">Left Pinky (L5)</option>
                            </optgroup>
                            <optgroup label="Right Hand">
                                <option value="R1">Right Thumb (R1)</option>
                                <option value="R2">Right Index (R2)</option>
                                <option value="R3">Right Middle (R3)</option>
                                <option value="R4">Right Ring (R4)</option>
                                <option value="R5">Right Pinky (R5)</option>
                            </optgroup>
                        </select>
                    </div>

                    <div class="col-12 d-flex gap-2 pt-2">
                        <button type="submit" class="btn btn-brand text-white btn-lg">
                            <i class="fa-solid fa-save"></i> Create Account
                        </button>
                        <a href="{{ route('acList') }}" class="btn btn-outline-secondary btn-lg">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-5">
        <div class="card">
            <div class="card-header">
                <i class="fa-solid fa-lightbulb"></i> Account Types Guide
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="fw-bold">Savings Account</h6>
                    <small class="text-muted">For individual customers with regular deposits and withdrawals</small>
                </div>
                <div class="mb-3">
                    <h6 class="fw-bold">Current Account</h6>
                    <small class="text-muted">For businesses requiring frequent transactions</small>
                </div>
                <div class="mb-3">
                    <h6 class="fw-bold">School Banking</h6>
                    <small class="text-muted">Special accounts for school students</small>
                </div>
                <div class="mb-3">
                    <h6 class="fw-bold">Interest Fee</h6>
                    <small class="text-muted">Accounts with interest-based operations</small>
                </div>
                <div class="mb-0">
                    <h6 class="fw-bold">Salary Account</h6>
                    <small class="text-muted">For employees receiving regular salary deposits</small>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection