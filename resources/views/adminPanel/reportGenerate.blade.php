@extends('adminPanel.include')
@section('calculasTitle') Generate Report {{ date('d-M-Y') }} @endsection
<style>
    .report-panel {
        border: 1px solid #d3dae0;
        border-radius: 0.5rem;
        overflow: hidden;
        background: #fff;
        box-shadow: none;
    }

    .report-panel .card-header {
        background: #f2f4f7;
        color: #111827;
        border-bottom: 1px solid #d3dae0;
        font-weight: 700;
    }

    .report-plain-table {
        margin-bottom: 0;
        border: 1px solid #d3dae0;
    }

    .report-plain-table thead th {
        background: #f7f8fa;
        color: #111827;
        border-bottom: 1px solid #d3dae0;
        text-transform: none;
        letter-spacing: 0;
    }

    .report-plain-table tbody td,
    .report-plain-table tbody th {
        border-bottom: 1px solid #dfe5ea;
        color: #111827;
    }

    .report-total-row td {
        font-size: 1.05rem;
        font-weight: 800;
        background: #f2f4f7;
        color: #111827;
        border-top: 1px solid #cfd6dd;
    }

    .report-summary-table {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid #d3dae0;
    }

    .report-summary-table th,
    .report-summary-table td {
        border: 1px solid #d3dae0;
        padding: 0.45rem 0.75rem;
        color: #111827;
        font-size: 0.98rem;
        line-height: 1.2;
    }

    .report-summary-table th {
        width: 65%;
        background: #f7f8fa;
        font-weight: 700;
        text-align: left;
    }

    @media print {
        html,
        body,
        body.calculas-app,
        .app-shell,
        .app-main,
        .app-content {
            background: #ffffff !important;
            min-height: auto !important;
            height: auto !important;
        }

        .app-content {
            padding: 0 !important;
        }

        .row {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

        #printArea {
            margin: 0 !important;
            padding: 0 !important;
            page-break-inside: auto;
        }

        #printArea > [class*='col-'] {
            break-inside: avoid;
            page-break-inside: avoid;
        }

        .report-panel {
            border: 1px solid #cfd6dd;
            box-shadow: none !important;
            break-inside: avoid;
        }

        .report-panel .card-header {
            background: #f2f4f7 !important;
            color: #111827 !important;
            border-bottom: 1px solid #cfd6dd !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .report-plain-table thead th,
        .report-plain-table tbody td,
        .report-plain-table tbody th,
        .report-total-row td,
        .report-summary-table th,
        .report-summary-table td {
            color: #111827 !important;
            border-color: #cfd6dd !important;
        }

        .report-plain-table thead th,
        .report-total-row td,
        .report-summary-table th {
            background: #f2f4f7 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>
@section('calculasBody')

<div class="page-header noprint">
    <div>
        <div class="page-kicker">Reporting</div>
        <h1 class="page-title">Generate Report</h1>
        <p class="page-copy">Filter debit and credit data by date and print a clean end-of-day summary.</p>
    </div>
</div>
<div class="row align-items-center v-100">
    <div class="col-10 col-md-7 mx-auto my-2 noprint">
        <div class="card">
            <div class="card-header">Generate Report</div>
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
            
            <form class="row g-3 card-body" method="POST" action="{{ route('getData') }}">
                @csrf
                <input type="hidden" name="employeeId" value="{{ $employee_id }}">
                <div class="col-12">
                    <label for="reportDate" class="form-label">Date</label>
                    <input type="date" class="form-control" name="reportDate" id="reportDate" placeholder="Enter the date of your query" required />
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Get Data</button>
                </div>
            </form>
        </div>
    </div>
    @if(isset($data) && isset($debit) && isset($credit))
        @php
            $y = 1;
            $liquid         = $data->ob;
            $handCash       = $data->cb;
            $totalCalculas  = $liquid+$handCash+$creditTotal;
            $finalCalculas  = $totalCalculas-$debitTotal;
            $lastBalance    = $data->lastBalance;
            $lastHandCash   = $finalCalculas-$lastBalance;
        @endphp
    <div class="row">
        <div class="col-12">
            <div class="action-toolbar justify-content-center">
                <button class="btn btn-warning btn-sm noprint" onclick="window.print()"><i class="fas fa-print"></i> Print</button>
            </div>
        </div>
    </div>
    <div class="row" id="printArea">
        <div class="col-12 col-md-6 mx-auto my-2">
            <div class="card report-panel">
                <div class="card-header fw-bold">Debit Details</div>
                <div class="card-body">
                    <table id="debitTable" class="table table-striped report-plain-table">
                        <thead>
                            <tr>
                                <th scope="col" class="fw-700">#</th>
                                <th scope="col" class="fw-700">Purpose</th>
                                <th scope="col" class="fw-700">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $x = 1;
                            @endphp
                            @if(isset($debit))
                                @foreach($debit as $d)
                                    <tr>
                                        <th scope="row">{{ $x }}</th>
                                        <td>{{ $d->details }}</td>
                                        <td>{{ number_format($d->amount, 0) }}</td>
                                    </tr>
                                    @php
                                    $x++;
                                    @endphp
                                @endforeach
                                <tr class="report-total-row">
                                    <td colspan="3">Total Debit: {{ number_format($debitTotal, 0) }}</td>
                                </tr>
                            @else
                            <tr>
                                <td colspan="3" class="text-center">No debit entries found</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 mx-auto my-2">
            <div class="card report-panel">
                <div class="card-header fw-bold">Credit Details</div>
                <div class="card-body">
                    <table id="creditTable" class="table table-striped report-plain-table">
                        <thead>
                            <tr>
                                <th scope="col" class="fw-700">#</th>
                                <th scope="col" class="fw-700">Purpose</th>
                                <th scope="col" class="fw-700">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $y = 1;
                            @endphp
                            @if(isset($credit))
                                @foreach($credit as $c)
                                    <tr>
                                        <th scope="row">{{ $y }}</th>
                                        <td>{{ $c->details }}</td>
                                        <td>{{ number_format($c->amount, 0) }}</td>
                                    </tr>
                                    @php
                                    $y++;
                                    @endphp
                                @endforeach
                                <tr class="report-total-row">
                                    <td colspan="3">Total Credit: {{ number_format($creditTotal, 0) }}</td>
                                </tr>
                            @else
                            <tr>
                                <td colspan="3" class="text-center">No credit entries found</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-7 mx-auto my-2">
            <div class="card report-panel">
                <div class="card-header fw-bold"><i class="fa-solid fa-calculator"></i> Final Calculas:- {{ Carbon\Carbon::parse($reportDate)->format('d-M-Y') }}</div>
                <div class="card-body">
                    @if(isset($data))
                        <table class="report-summary-table">
                            <tbody>
                                <tr>
                                    <th>Opening Balance</th>
                                    <td>{{ number_format($liquid, 0) }}</td>
                                </tr>
                                <tr>
                                    <th>Opening Cash</th>
                                    <td>{{ number_format($handCash, 0) }}</td>
                                </tr>
                                <tr>
                                    <th>Credit</th>
                                    <td>{{ number_format($creditTotal, 0) }}</td>
                                </tr>
                                <tr>
                                    <th>Total</th>
                                    <td>{{ number_format($totalCalculas, 0) }}</td>
                                </tr>
                                <tr>
                                    <th>Debit</th>
                                    <td>{{ number_format($debitTotal, 0) }}</td>
                                </tr>
                                <tr>
                                    <th>Final Calculas</th>
                                    <td>{{ number_format($finalCalculas, 0) }}</td>
                                </tr>
                                <tr>
                                    <th>EOD Balance</th>
                                    <td>{{ number_format($lastBalance, 0) }}</td>
                                </tr>
                                <tr>
                                    <th>EOD Cash</th>
                                    <td>{{ number_format($lastHandCash, 0) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    @else
                    <div class="alert alert-info">
                        <i class="fa-solid fa-info-circle"></i>
                        <span>Sorry! No calculas data found</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="col-12 col-md-10 mx-auto my-2">
        <div class="card">
            <div class="card-body">
                <div class="alert alert-info">Sorry! No data found with your query</div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection