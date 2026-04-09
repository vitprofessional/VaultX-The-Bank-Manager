@extends('adminPanel.include')
@section('calculasTitle') Account List @endsection
@section('calculasBody')

<div class="page-header">
    <div>
        <div class="page-kicker">Account Management</div>
        <h1 class="page-title">Account List</h1>
        <p class="page-copy">View, manage, and track all customer accounts in your branch.</p>
    </div>
    <div>
        <a class="btn btn-brand text-white noprint" href="{{ route('accountCreation') }}">
            <i class="fas fa-plus"></i> Add New Account
        </a>
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

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="accountListTable" class="table table-striped datatable">
                <thead>
                    <tr>
                        <th scope="col" class="fw-700">#</th>
                        <th scope="col" class="fw-700">Account Name</th>
                        <th scope="col" class="fw-700">Account Number</th>
                        <th scope="col" class="fw-700">Mobile</th>
                        <th scope="col" class="fw-700">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $data = \App\Models\AccountList::orderBy('id','DESC')->get();
                        $x = 1;
                    @endphp
                    @if(!empty($data) && $data->count()>0)
                        @foreach($data as $d)
                            <tr>
                                <th scope="row">{{ $x }}</th>
                                <td>{{ $d->acName }}</td>
                                <td><code>{{ $d->acNumber }}</code></td>
                                <td>{{ $d->acMobile }}</td>
                                <td>
                                    <a href="{{ route('acView',['id'=>$d->id]) }}" class="btn btn-sm btn-info text-white" title="View Details"><i class="fa-solid fa-eye"></i> View</a>
                                    @if($d->employee_id == $employee_id || Session::has('superAdmin'))
                                        <a href="{{ route('acEdit',['id'=>$d->id]) }}" class="btn btn-sm btn-success text-white" title="Edit Account"><i class="fa-solid fa-file-pen"></i> Edit</a>
                                        <a href="{{ route('acDelete',['id'=>$d->id]) }}" onclick="return confirm('Are you sure you want to delete this account?')" class="btn btn-sm btn-danger text-white" title="Delete Account"><i class="fa-solid fa-trash"></i> Delete</a>
                                    @endif
                                </td>
                            </tr>
                            @php $x++; @endphp
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <div class="soft-muted">
                                    <i class="fa-solid fa-inbox"></i> No accounts found
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection