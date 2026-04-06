@extends('adminPanel.include')
@section('calculasTitle') Dashboard @endsection
@section('calculasBody')
@php
    $today  =   date('Y-m-d');
@endphp

<div class="page-header">
    <div>
        <div class="page-kicker">Cash Management</div>
        <h1 class="page-title">Dashboard</h1>
        <p class="page-copy">Record and review the daily cash position for the current branch.</p>
    </div>
</div>

@if(Session::has('cashier'))
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

    @php
        $dateToday = date('Y-m-d');
        $data = \App\Models\BankCapital::whereDate('created_at', $dateToday)->where('employee_id', $employee_id)->first();

        if(isset($data)):
            $liquid = $data->ob;
            $handCash = $data->cb;
            $lastBalance = $data->lastBalance;
            $totalBalance = $liquid + $handCash;
        else:
            $liquid = '';
            $handCash = '';
            $lastBalance = '';
            $totalBalance = '';
        endif;
    @endphp

    <div class="row g-4 align-items-start">
        <div class="col-12 col-xxl-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <span><i class="fa-solid fa-calculator"></i> Cash Calculations</span>
                    <span class="badge bg-light text-dark rounded-pill">{{ $today }}</span>
                </div>
                <div class="card-body">
                    @if(isset($capitalId))
                        <div class="mb-4 p-3 rounded-4" style="background: var(--accent-soft); border: 1px solid rgba(11,122,117,.12);">
                            <div class="fw-semibold text-uppercase small text-success"><i class="fa-solid fa-edit"></i> Editing existing record</div>
                            <div class="soft-muted">Update the daily values and save the current balance snapshot.</div>
                        </div>
                        <form class="row g-3" method="POST" action="{{ route('updateCalculas') }}">
                            @csrf
                            <input type="hidden" name="capitalId" value="{{ $capitalId }}">
                            <div class="col-12 col-md-6">
                                <label for="liquid" class="form-label">Liquid Balance</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-money-bill"></i></span>
                                    <input type="number" class="form-control form-control-lg" value="{{ $liquid }}" name="liquid" id="liquid" placeholder="Enter liquid balance" required>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="handCash" class="form-label">Hand Cash</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-coins"></i></span>
                                    <input type="number" class="form-control form-control-lg" value="{{ $handCash }}" id="handCash" name="handCash" placeholder="Enter hand cash" required>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="lastBalance" class="form-label">Last Balance</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-scale-balanced"></i></span>
                                    <input type="number" class="form-control form-control-lg" value="{{ $lastBalance }}" id="lastBalance" name="lastBalance" placeholder="Enter last balance" required>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="totalBalance" class="form-label">Total Balance</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-plus"></i></span>
                                    <input type="number" class="form-control form-control-lg" value="{{ $totalBalance }}" id="totalBalance" readonly style="background-color: #f0f0f0;">
                                </div>
                            </div>
                            <div class="col-12 d-flex gap-2 pt-2">
                                <button type="submit" class="btn btn-brand text-white btn-lg"><i class="fa-solid fa-save"></i> Save Changes</button>
                                <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-lg">Cancel</a>
                            </div>
                        </form>
                    @else
                        @if(isset($data))
                            <div class="row g-3 mb-4">
                                <div class="col-12 col-md-6 col-xl-3">
                                    <div class="stat-card">
                                        <div class="stat-label">Liquid Balance</div>
                                        <div class="stat-value">{{ number_format($liquid, 0) }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-xl-3">
                                    <div class="stat-card">
                                        <div class="stat-label">Hand Cash</div>
                                        <div class="stat-value">{{ number_format($handCash, 0) }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-xl-3">
                                    <div class="stat-card">
                                        <div class="stat-label">Last Balance</div>
                                        <div class="stat-value">{{ number_format($lastBalance, 0) }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-xl-3">
                                    <div class="stat-card" style="background: var(--accent-soft); border-color: rgba(11,122,117,.12);">
                                        <div class="stat-label text-success">Total Balance</div>
                                        <div class="stat-value text-success">{{ number_format($totalBalance, 0) }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('editCalculas',['id'=>$data->id]) }}" class="btn btn-brand text-white btn-lg">
                                    <i class="fa-solid fa-edit"></i> Edit Record
                                </a>
                            </div>
                        @else
                            <div class="alert alert-info mb-4">
                                <i class="fa-solid fa-info-circle"></i>
                                <span>No record found for today. Create a new cash calculation entry to get started.</span>
                            </div>
                            <form class="row g-3" method="POST" action="{{ route('saveCalculas') }}">
                                @csrf
                                <input type="hidden" name="employeeId" value="{{ $employee_id }}">
                                <div class="col-12 col-md-6">
                                    <label for="liquid" class="form-label">Liquid Balance</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-money-bill"></i></span>
                                        <input type="number" class="form-control form-control-lg" name="liquid" id="liquid" placeholder="Enter liquid balance" required>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="handCash" class="form-label">Hand Cash</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-coins"></i></span>
                                        <input type="number" class="form-control form-control-lg" id="handCash" name="handCash" placeholder="Enter hand cash" required>
                                    </div>
                                </div>
                                <div class="col-12 d-flex gap-2 pt-2">
                                    <button type="submit" class="btn btn-brand text-white btn-lg">
                                        <i class="fa-solid fa-plus"></i> Create Record
                                    </button>
                                </div>
                            </form>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
@else
    <div class="alert alert-info">
        <i class="fa-solid fa-info-circle"></i>
        <span><strong>Access Notice:</strong> Only cashiers can access cash management functions. Other roles can manage accounts, reports, and system configuration.</span>
    </div>
@endif

@endsection