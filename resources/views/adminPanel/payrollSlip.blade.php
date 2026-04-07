@extends('adminPanel.include')
@section('calculasTitle') Payroll Slip @endsection

<style>
    @page {
        size: A4 portrait;
        margin: 0;
    }

    html,
    body {
        background: #ffffff;
    }

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

    .slip-section-title {
        font-weight: 700;
        font-size: 0.92rem;
        color: #0f3554;
        margin-bottom: 0.45rem;
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

    .slip-summary-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 0.75rem;
    }

    .slip-summary-box {
        border: 1px solid #d7dce5;
        border-radius: 0.75rem;
        padding: 0.65rem 0.5rem;
        text-align: center;
        background: #ffffff;
    }

    .slip-summary-box strong {
        display: block;
        font-size: 1.1rem;
        line-height: 1.1;
        color: #0f172a;
    }

    .slip-summary-box span {
        display: block;
        font-size: 0.8rem;
        color: #475569;
    }

    .slip-note {
        border: 1px solid #d7dce5;
        border-radius: 0.75rem;
        padding: 0.75rem 0.85rem;
        background: #ffffff;
        color: #334155;
    }

    @media print {
        @page {
            size: A4 portrait;
            margin: 0;
        }

        html,
        body {
            background: #ffffff !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            height: 100% !important;
            overflow: hidden !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

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
            width: 100% !important;
            min-height: 0 !important;
        }

        .app-shell,
        .app-main,
        .app-content {
            display: block !important;
        }

        .app-shell,
        .app-main {
            min-height: 0 !important;
            height: auto !important;
        }

        .app-content > :not(.slip-wrap) {
            display: none !important;
        }

        .slip-card,
        .slip-head,
        .slip-body,
        .slip-summary-box,
        .slip-note {
            background: #ffffff !important;
            color: #111827 !important;
            box-shadow: none !important;
            border-color: #d1d5db !important;
        }

        .slip-card {
            page-break-inside: avoid !important;
            break-inside: avoid !important;
            border-radius: 12px !important;
            border-width: 1px !important;
            box-shadow: none !important;
            page-break-after: avoid !important;
        }

        .slip-head {
            padding: 0.85rem 1rem !important;
        }

        .slip-body {
            padding: 0.9rem 1rem !important;
        }

        .slip-summary-grid {
            gap: 0.4rem !important;
        }

        .slip-summary-box {
            padding: 0.45rem 0.35rem !important;
        }

        .slip-summary-box strong {
            font-size: 1rem !important;
        }

        .slip-section-title {
            margin-bottom: 0.3rem !important;
        }

        .slip-kv {
            padding: 0.28rem 0 !important;
            gap: 0.35rem !important;
        }

        .slip-note {
            padding: 0.55rem 0.7rem !important;
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
    @include('adminPanel._payrollSlipContent')
</div>
@endsection
