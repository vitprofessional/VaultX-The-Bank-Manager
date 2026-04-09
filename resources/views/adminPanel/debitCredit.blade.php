@extends('adminPanel.include')
@section('calculasTitle') Debit/Credit @endsection
@section('calculasBody')

@if(Session::has('cashier') || Session::has('superAdmin'))
    <div class="page-header">
        <div>
            <div class="page-kicker">Cash Transactions</div>
            <h1 class="page-title">Debit/Credit Entry</h1>
            <p class="page-copy">Record daily debit and credit transactions for cash management.</p>
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
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <i class="fa-solid fa-plus-circle"></i> New Transaction
                </div>
                <div class="card-body">
                    @php
                        $cashierIds = [];
                        $cashiers = collect();
                        $selectedCashierId = null;
                        if(Session::has('superAdmin')) {
                            $cashiers = \App\Models\BankEmployee::where('profileType', 4)->orderBy('name')->get(['id', 'name']);
                            $cashierIds = $cashiers->pluck('id')->toArray();
                            $selectedCashierId = (int) request()->get('cashierId', !empty($cashierIds) ? $cashierIds[0] : 0);
                            if (!in_array($selectedCashierId, $cashierIds, true)) {
                                $selectedCashierId = !empty($cashierIds) ? $cashierIds[0] : null;
                            }
                        }

                        if(isset($data)):
                            $amount = $data->amount;
                            $details = $data->details;
                            $txnType = $data->txnType;
                            $dcId = $data->id;
                        else:
                            $amount = '';
                            $details = '';
                            $txnType = '';
                            $dcId = '';
                        endif;

                        $entryEmployeeId = isset($data)
                            ? $data->employee_id
                            : (!empty($selectedCashierId) ? $selectedCashierId : $employee_id);
                    @endphp
                    @if(Session::has('superAdmin'))
                        <form method="GET" action="{{ route('debitCredit') }}" class="row g-2 align-items-end mb-4">
                            <div class="col-12 col-md-8">
                                <label class="form-label">Cashier</label>
                                <select name="cashierId" class="form-select" onchange="this.form.submit()">
                                    @foreach($cashiers as $cashier)
                                        <option value="{{ $cashier->id }}" @selected((int) $selectedCashierId === (int) $cashier->id)>{{ $cashier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <button type="submit" class="btn btn-outline-primary w-100">Load Cashier Data</button>
                            </div>
                        </form>
                    @endif
                    <form class="row g-3" method="POST" action="{{ route('saveDebitCredit') }}">
                        @csrf
                        <input type="hidden" value="{{ $dcId }}" name="dcId">
                        <input type="hidden" name="employeeId" value="{{ $entryEmployeeId }}">
                        
                        <div class="col-12">
                            <label for="amount" class="form-label">Transaction Amount</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-money-bill"></i></span>
                                <input type="number" class="form-control form-control-lg" value="{{ $amount }}" name="amount" id="amount" placeholder="Enter amount" required>
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="note" class="form-label">Description / Note</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-note-sticky"></i></span>
                                <textarea class="form-control" id="note" name="note" rows="3" placeholder="Enter description or note">{{ $details }}</textarea>
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="type" class="form-label">Transaction Type</label>
                            <select id="type" class="form-select form-select-lg" name="txnType" required>
                                @if(!empty($txnType))
                                    <option value="{{ $txnType }}" selected>{{ $txnType }}</option>
                                @else
                                    <option value="">-- Select Type --</option>
                                @endif
                                <option value="Debit">Debit</option>
                                <option value="Credit">Credit</option>
                            </select>
                        </div>

                        <div class="col-12 d-flex gap-2">
                            <button type="submit" class="btn btn-brand text-white"><i class="fa-solid fa-save"></i> Save Transaction</button>
                            @if(!empty($dcId))
                                <a href="{{ route('debitCredit', ['cashierId' => $selectedCashierId]) }}" class="btn btn-outline-secondary">Cancel</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <i class="fa-solid fa-list"></i> Today's Transactions
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="transactionTable" class="table table-striped datatable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $today = date('Y-m-d');
                                    if(Session::has('superAdmin')) {
                                        $data = \App\Models\DebitCredit::whereDate('created_at', $today)
                                            ->where('employee_id', $selectedCashierId)
                                            ->orderBy('id', 'DESC')
                                            ->get();
                                    } else {
                                        $data = \App\Models\DebitCredit::whereDate('created_at', $today)
                                            ->where(['employee_id' => $employee_id])
                                            ->orderBy('id', 'DESC')
                                            ->get();
                                    }
                                    $x = 1;
                                @endphp
                                @if(isset($data) && count($data)>0)
                                    @foreach($data as $d)
                                        <tr>
                                            <th scope="row">{{ $x }}</th>
                                            <td>{{ $d->details }}</td>
                                            <td><strong>{{ $d->amount }}</strong></td>
                                            <td>
                                                @if($d->txnType == 'Debit')
                                                    <span class="badge text-bg-danger">{{ $d->txnType }}</span>
                                                @else
                                                    <span class="badge text-bg-success">{{ $d->txnType }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('editDCdata',['id'=>$d->id]) }}" class="btn btn-sm btn-success text-white" title="Edit"><i class="fa-solid fa-file-pen"></i></a>
                                                <a href="{{ route('delDCdata',['id'=>$d->id]) }}" onclick="return confirm('Are you sure you want to delete this transaction?')" class="btn btn-sm btn-danger text-white" title="Delete"><i class="fa-solid fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        @php $x++; @endphp
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-center">-</td>
                                        <td class="text-center">
                                            <div class="soft-muted mb-0">
                                                <i class="fa-solid fa-inbox"></i> No transactions recorded
                                            </div>
                                        </td>
                                        <td class="text-center">-</td>
                                        <td class="text-center">-</td>
                                        <td class="text-center">-</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="alert alert-info">
        <i class="fa-solid fa-info-circle"></i>
        <span><strong>Access Restricted:</strong> Only cashiers can access Debit/Credit functions. Other roles can manage accounts, reports, and configuration.</span>
    </div>
@endif

@endsection