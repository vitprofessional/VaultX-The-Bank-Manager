@extends('adminPanel.include')
@section('calculasTitle') Update Account @endsection
@section('calculasBody')

<div class="page-header">
    <div>
        <div class="page-kicker">Account Management</div>
        <h1 class="page-title">Update Account</h1>
        <p class="page-copy">Edit customer account information with the same structure used in account creation.</p>
    </div>
    <div class="action-toolbar noprint">
        <a href="{{ route('acList') }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left"></i> Back to Accounts
        </a>
        @if(isset($data))
            <a href="{{ route('acView',['id'=>$data->id]) }}" class="btn btn-outline-primary" title="View Data">
                <i class="fa-solid fa-eye"></i> View Data
            </a>
        @endif
    </div>
</div>

<div class="row g-4 align-items-start">
    <div class="col-12 col-lg-7">
        <div class="card">
            <div class="card-header">
                <i class="fa-solid fa-user-pen"></i> Account Information
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

                @if(isset($data))
                    @if($data->employee_id == $employee_id)
                    <form class="row g-3" method="POST" action="{{ route('acUpdate') }}">
                        @csrf
                        <input type="hidden" name="acId" value="{{ $data->id }}">
                        <input type="hidden" name="employeeId" value="{{ $employee_id }}">

                        <div class="col-12">
                            <label for="acName" class="form-label">Account Holder Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                <input type="text" class="form-control form-control-lg" value="{{ $data->acName }}" name="acName" id="acName" placeholder="Enter full name" required>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="acNo" class="form-label">Account Number</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-credit-card"></i></span>
                                <input type="number" class="form-control form-control-lg" id="acNo" value="{{ $data->acNumber }}" name="acNo" placeholder="Enter account number" maxlength="13" required>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="acType" class="form-label">Account Type</label>
                            <select id="acType" class="form-select form-select-lg" name="acType" required>
                                <option value="Savings" @selected($data->acType == 'Savings')>Savings Account</option>
                                <option value="Current" @selected($data->acType == 'Current')>Current Account</option>
                                <option value="School Banking" @selected($data->acType == 'School Banking')>School Banking</option>
                                <option value="Interest Fee" @selected($data->acType == 'Interest Fee')>Interest Fee</option>
                                <option value="Salary" @selected($data->acType == 'Salary')>Salary Account</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="acMobile" class="form-label">Mobile Number</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-mobile"></i></span>
                                <input type="text" class="form-control form-control-lg" value="{{ $data->acMobile }}" name="acMobile" id="acMobile" placeholder="Enter mobile number" required maxlength="20">
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="acFinger" class="form-label">Fingerprint ID</label>
                            <select id="acFinger" class="form-select form-select-lg" name="acFinger" required>
                                <optgroup label="Left Hand">
                                    <option value="L1" @selected($data->acFinger == 'L1')>Left Thumb (L1)</option>
                                    <option value="L2" @selected($data->acFinger == 'L2')>Left Index (L2)</option>
                                    <option value="L3" @selected($data->acFinger == 'L3')>Left Middle (L3)</option>
                                    <option value="L4" @selected($data->acFinger == 'L4')>Left Ring (L4)</option>
                                    <option value="L5" @selected($data->acFinger == 'L5')>Left Pinky (L5)</option>
                                </optgroup>
                                <optgroup label="Right Hand">
                                    <option value="R1" @selected($data->acFinger == 'R1')>Right Thumb (R1)</option>
                                    <option value="R2" @selected($data->acFinger == 'R2')>Right Index (R2)</option>
                                    <option value="R3" @selected($data->acFinger == 'R3')>Right Middle (R3)</option>
                                    <option value="R4" @selected($data->acFinger == 'R4')>Right Ring (R4)</option>
                                    <option value="R5" @selected($data->acFinger == 'R5')>Right Pinky (R5)</option>
                                </optgroup>
                            </select>
                        </div>

                        <div class="col-12 d-flex gap-2 pt-2">
                            <button type="submit" class="btn btn-brand text-white btn-lg">
                                <i class="fa-solid fa-floppy-disk"></i> Update Account
                            </button>
                            <a href="{{ route('acList') }}" class="btn btn-outline-secondary btn-lg">Cancel</a>
                        </div>
                    </form>
                    @else
                        <div class="alert alert-info">
                            <i class="fa-solid fa-info-circle"></i>
                            <span>Sorry! You are not the author to edit this account details.</span>
                        </div>
                    @endif
                @else
                <div class="alert alert-info">
                    <i class="fa-solid fa-info-circle"></i>
                    <span>Sorry! No data found</span>
                </div>
                @endif
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