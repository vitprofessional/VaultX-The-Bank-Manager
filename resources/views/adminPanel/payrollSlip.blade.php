@extends('adminPanel.include')
@section('calculasTitle') Payroll Slip @endsection

<style>
    .slip-wrap {
        max-width: 900px;
        margin: 0 auto;
    }

    .slip-card {
        border: 1px solid #d7dce5;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 0.5rem 1.5rem rgba(15, 35, 63, 0.08);
        background: #ffffff;
    }

    .slip-head {
        background: linear-gradient(90deg, #0f3554, #1c658c);
        color: #ffffff;
        padding: 1.15rem 1.35rem;
    }

    .slip-body {
        padding: 1.35rem;
    }

    .slip-kv {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 0.5rem;
        padding: 0.45rem 0;
        border-bottom: 1px dashed #d8dee8;
    }

    .slip-kv strong {
        color: #0f172a;
    }

    @media print {
        .noprint,
        .app-sidebar,
        .app-topbar {
            display: none !important;
        }

        .app-main,
        .app-content,
        .slip-wrap {
            margin: 0 !important;
            padding: 0 !important;
            max-width: 100% !important;
        }

        .slip-card,
        .slip-head {
            background: #ffffff !important;
            color: #111827 !important;
            box-shadow: none !important;
            border-color: #d1d5db !important;
        }
    }
</style>

@section('calculasBody')
<div class="page-header noprint">
    <div>
        <div class="page-kicker">Payroll</div>
        <h1 class="page-title">Employee Payslip</h1>
        <p class="page-copy">Monthly payroll statement for employee compensation records.</p>
    </div>
    <div class="action-toolbar">
        <a href="{{ route('hrEmployeeIndex', ['month' => $payroll->payroll_month]) }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left"></i> Back
        </a>
        <a href="{{ route('hrPayrollPdf', ['id' => $payroll->id]) }}" class="btn btn-outline-danger">
            <i class="fa-solid fa-file-pdf"></i> Download PDF
        </a>
        <button class="btn btn-warning" onclick="window.print()">
            <i class="fa-solid fa-print"></i> Print Slip
        </button>
    </div>
</div>

<div class="slip-wrap">
    <div class="slip-card">
        <div class="slip-head">
            <h4 class="mb-1">{{ $serverData->bank_name ?? 'Bank Manager' }}</h4>
            <div>Payroll Month: <strong>{{ $payroll->payroll_month }}</strong></div>
        </div>

        <div class="slip-body">
            <div class="row g-4">
                <div class="col-12 col-md-6">
                    <h6 class="fw-bold">Employee Information</h6>
                    <div class="slip-kv"><span>Name</span><strong>{{ $payroll->employee->full_name ?? '-' }}</strong></div>
                    <div class="slip-kv"><span>Employee Code</span><strong>{{ $payroll->employee->employee_code ?? '-' }}</strong></div>
                    <div class="slip-kv"><span>Department</span><strong>{{ $payroll->employee->department ?: '-' }}</strong></div>
                    <div class="slip-kv"><span>Designation</span><strong>{{ $payroll->employee->designation ?: '-' }}</strong></div>
                    <div class="slip-kv"><span>Status</span><strong class="text-capitalize">{{ $payroll->payment_status }}</strong></div>
                </div>
                <div class="col-12 col-md-6">
                    <h6 class="fw-bold">Salary Breakdown</h6>
                    <div class="slip-kv"><span>Basic Salary</span><strong>{{ number_format((float) $payroll->basic_salary, 2) }}</strong></div>
                    <div class="slip-kv"><span>Allowance</span><strong>{{ number_format((float) $payroll->allowance, 2) }}</strong></div>
                    <div class="slip-kv"><span>Bonus</span><strong>{{ number_format((float) $payroll->bonus, 2) }}</strong></div>
                    <div class="slip-kv"><span>Overtime</span><strong>{{ number_format((float) $payroll->overtime, 2) }}</strong></div>
                    <div class="slip-kv"><span>Gross Salary</span><strong>{{ number_format((float) $payroll->gross_salary, 2) }}</strong></div>
                    <div class="slip-kv"><span>Total Deductions</span><strong>{{ number_format((float) ($payroll->tax + $payroll->deduction + $payroll->loan + $payroll->other_deduction), 2) }}</strong></div>
                    <div class="slip-kv"><span>Attendance Deduction</span><strong>{{ number_format((float) $payroll->attendance_deduction, 2) }}</strong></div>
                    <div class="slip-kv"><span>Net Salary</span><strong>{{ number_format((float) $payroll->net_salary, 2) }}</strong></div>
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-6 col-md-3"><div class="alert alert-light mb-0 text-center"><strong>{{ (int) $payroll->present_days }}</strong><br>Present</div></div>
                <div class="col-6 col-md-3"><div class="alert alert-light mb-0 text-center"><strong>{{ (int) $payroll->late_days }}</strong><br>Late</div></div>
                <div class="col-6 col-md-3"><div class="alert alert-light mb-0 text-center"><strong>{{ (int) $payroll->absent_days }}</strong><br>Absent</div></div>
                <div class="col-6 col-md-3"><div class="alert alert-light mb-0 text-center"><strong>{{ (int) $payroll->leave_days }}</strong><br>Leave</div></div>
            </div>

            @if(!empty($payroll->salary_preset_source))
                <div class="mt-3 small text-muted">
                    Salary preset source: {{ $payroll->salary_preset_source }}
                </div>
            @endif

            @if(!empty($payroll->note))
                <div class="mt-3">
                    <h6 class="fw-bold">Notes</h6>
                    <p class="mb-0 text-muted">{{ $payroll->note }}</p>
                </div>
            @endif

            <div class="mt-4 small text-muted">
                Generated on {{ date('Y-m-d H:i') }}
            </div>
        </div>
    </div>
</div>
@endsection
