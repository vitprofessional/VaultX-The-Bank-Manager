@extends('adminPanel.include')
@section('calculasTitle') Update Account @endsection
@section('calculasBody')

<div class="page-header">
    <div>
        <div class="page-kicker">Account Management</div>
        <h1 class="page-title">Update Account</h1>
        <p class="page-copy">Edit account details with consistent data and profile information.</p>
    </div>
</div>

<div class="row align-items-center v-100">
    <div class="col-10 col-md-6 mx-auto my-4">
        <div class="row mb-2">
            <div class="col-12 mx-auto text-center mt-4">
                <div class="action-toolbar justify-content-center">
                    <a class="btn btn-success btn-sm noprint" href="{{ route('accountCreation') }}"><i class="fas fa-plus"></i> Add New</a>
                    <a href="{{ route('acList') }}" class="btn btn-primary btn-sm noprint"><i class="fas fa-users"></i> Account List</a>
                    <a href="{{ route('acView',['id'=>$data->id]) }}" class="btn btn-sm btn-success noprint" title="View Data"><i class="fa-solid fa-eye"></i> View Data</a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">Account Update</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        @if(session()->has('success'))
                            <div class="alert alert-success w-100">
                                {{ session()->get('success') }}
                            </div>
                        @endif
                        @if(session()->has('error'))
                            <div class="alert alert-danger w-100">
                                {{ session()->get('error') }}
                            </div>
                        @endif
                    </div>
                </div>
                @if(isset($data))
                <div class="row">
                    @if($data->employee_id == $employee_id)
                    <form class="row g-3" method="POST" action="{{ route('acUpdate') }}">
                        @csrf
                        <input type="hidden" name="acId" value="{{ $data->id }}">
                        <div class="col-12">
                            <label for="acName" class="form-label">Account Name</label>
                            <input type="text" class="form-control" value="{{ $data->acName }}" name="acName" id="acName" placeholder="Enter the name of account holder" />
                        </div>
                        <div class="col-12">
                            <label for="acNo" class="form-label">A/C Number</label>
                            <input type="number" maxlength="13" class="form-control" id="acNo" value="{{ $data->acNumber }}" name="acNo" placeholder="Enter the account number" />
                        </div>
                        <div class="col-12">
                            <label for="acType" class="form-label">A/C Type</label>
                            <select id="acType" class="form-select" name="acType" require>
                                <option value="{{ $data->acType }}">{{ $data->acType }}</option>
                                <option value="Savings">Savings</option>
                                <option value="Current">Current</option>
                                <option value="School Banking">School Banking</option>
                                <option value="Interest Fee">Interest Fee</option>
                                <option value="Salary">Salary</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="acMobile" class="form-label">Mobile Number</label>
                            <input type="text" class="form-control" value="{{ $data->acMobile }}" name="acMobile" id="acMobile" placeholder="Enter the mobile number of account holder" />
                        </div>
                        <div class="col-12">
                            <label for="acFinger" class="form-label">A/C Finger</label>
                            <select id="acFinger" class="form-select" name="acFinger" require>
                                <option value="{{ $data->acFinger }}">{{ $data->acFinger }}</option>
                                <option value="L1">L1</option>
                                <option value="L2">L2</option>
                                <option value="L3">L3</option>
                                <option value="L4">L4</option>
                                <option value="L5">L5</option>
                                <option value="R1">R1</option>
                                <option value="R2">R2</option>
                                <option value="R3">R3</option>
                                <option value="R4">R4</option>
                                <option value="R5">R5</option>
                            </select>
                        </div>
                        <div class="col-12 form-actions">
                            <button type="submit" class="btn btn-brand text-white">Update Account</button>
                            <a href="{{ route('acList') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                    @else
                        <div class="alert alert-info">Sorry! You are not the author to edit this account details.</div>
                    @endif
                </div>
                @else
                <div class="alert alert-info">
                    Sorry! No data found
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection