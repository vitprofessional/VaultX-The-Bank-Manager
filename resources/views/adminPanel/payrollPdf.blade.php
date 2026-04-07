<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Payroll Slip</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 0;
        }

        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            background: #ffffff;
            font-family: DejaVu Sans, sans-serif;
            color: #111827;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            box-sizing: border-box;
            background: #ffffff;
        }

        .card {
            width: 100%;
            border: 1px solid #d7dce5;
            border-radius: 14px;
            overflow: hidden;
            background: #ffffff;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .head {
            background-color: #155b7f;
            background-image: linear-gradient(90deg, #0f3554 0%, #1c658c 100%);
            color: #ffffff;
            padding: 18px 22px;
        }

        .head-table {
            width: 100%;
            border-collapse: collapse;
        }

        .head-left,
        .head-right {
            vertical-align: top;
        }

        .bank-name {
            margin: 0 0 4px 0;
            font-size: 17px;
            font-weight: 700;
            line-height: 1.1;
            color: #ffffff !important;
        }

        .subtitle {
            font-size: 11px;
            font-weight: 400;
            color: #dbeafe !important;
        }

        .month-label {
            text-align: right;
            font-size: 11px;
            font-weight: 600;
            color: #dbeafe !important;
            margin-bottom: 2px;
        }

        .month-value {
            text-align: right;
            font-size: 15px;
            font-weight: 700;
            color: #ffffff !important;
        }

        .body {
            padding: 18px 20px 14px;
        }

        .two-col {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .two-col td {
            width: 50%;
            vertical-align: top;
            padding: 0;
        }

        .section-title {
            margin: 0 0 6px 0;
            font-size: 13px;
            font-weight: 700;
            color: #0f3554;
        }

        .kv-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .kv-table td {
            padding: 8px 0;
            border-bottom: 1px dashed #e5e7eb;
            font-size: 12px;
        }

        .kv-label {
            width: 46%;
            color: #334155;
        }

        .kv-value {
            width: 54%;
            color: #0f172a;
            text-align: right;
            font-weight: 700;
        }

        .summary-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 10px 0;
            table-layout: fixed;
            margin-top: 14px;
        }

        .summary-table td {
            border: 1px solid #d7dce5;
            border-radius: 12px;
            padding: 10px 6px;
            text-align: center;
            background: #ffffff;
        }

        .summary-count {
            display: block;
            font-size: 18px;
            line-height: 1;
            font-weight: 700;
            color: #111827;
            margin-bottom: 3px;
        }

        .summary-label {
            display: block;
            font-size: 11px;
            color: #475569;
        }

        .note-box {
            margin-top: 12px;
            border: 1px solid #d7dce5;
            border-radius: 12px;
            padding: 10px 12px;
            font-size: 12px;
            color: #334155;
            background: #ffffff;
        }

        .footer {
            margin-top: 10px;
            font-size: 11px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="card">
            <div class="head">
                <table class="head-table">
                    <tr>
                        <td class="head-left">
                            <div class="bank-name">{{ $serverData->bank_name ?? 'Bank Manager' }}</div>
                            <div class="subtitle">Monthly payroll statement</div>
                        </td>
                        <td class="head-right">
                            <div class="month-label">Payroll Month</div>
                            <div class="month-value">{{ $payroll->payroll_month }}</div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="body">
                <table class="two-col">
                    <tr>
                        <td style="padding-right:10px;">
                            <div class="section-title">Employee Information</div>
                            <table class="kv-table">
                                <tr><td class="kv-label">Name</td><td class="kv-value">{{ $payroll->employee->full_name ?? '-' }}</td></tr>
                                <tr><td class="kv-label">Employee Code</td><td class="kv-value">{{ $payroll->employee->employee_code ?? '-' }}</td></tr>
                                <tr><td class="kv-label">Department</td><td class="kv-value">{{ $payroll->employee->department ?: '-' }}</td></tr>
                                <tr><td class="kv-label">Designation</td><td class="kv-value">{{ $payroll->employee->designation ?: '-' }}</td></tr>
                                <tr><td class="kv-label">Payment Status</td><td class="kv-value">{{ $payroll->payment_status }}</td></tr>
                                <tr><td class="kv-label">Payment Date</td><td class="kv-value">{{ $payroll->payment_date ?: '-' }}</td></tr>
                            </table>
                        </td>
                        <td style="padding-left:10px;">
                            <div class="section-title">Salary Breakdown</div>
                            <table class="kv-table">
                                <tr><td class="kv-label">Basic Salary</td><td class="kv-value">{{ number_format((float) $payroll->basic_salary, 2) }}</td></tr>
                                <tr><td class="kv-label">Allowance</td><td class="kv-value">{{ number_format((float) $payroll->allowance, 2) }}</td></tr>
                                <tr><td class="kv-label">Bonus</td><td class="kv-value">{{ number_format((float) $payroll->bonus, 2) }}</td></tr>
                                <tr><td class="kv-label">Overtime</td><td class="kv-value">{{ number_format((float) $payroll->overtime, 2) }}</td></tr>
                                <tr><td class="kv-label">Tax</td><td class="kv-value">{{ number_format((float) $payroll->tax, 2) }}</td></tr>
                                <tr><td class="kv-label">Total Deductions</td><td class="kv-value">{{ number_format((float) ($payroll->tax + $payroll->deduction + $payroll->loan + $payroll->other_deduction), 2) }}</td></tr>
                                <tr><td class="kv-label">Attendance Deduction</td><td class="kv-value">{{ number_format((float) $payroll->attendance_deduction, 2) }}</td></tr>
                                <tr><td class="kv-label">Net Salary</td><td class="kv-value">{{ number_format((float) $payroll->net_salary, 2) }}</td></tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <table class="summary-table">
                    <tr>
                        <td><span class="summary-count">{{ (int) $payroll->present_days }}</span><span class="summary-label">Present</span></td>
                        <td><span class="summary-count">{{ (int) $payroll->late_days }}</span><span class="summary-label">Late</span></td>
                        <td><span class="summary-count">{{ (int) $payroll->absent_days }}</span><span class="summary-label">Absent</span></td>
                        <td><span class="summary-count">{{ (int) $payroll->leave_days }}</span><span class="summary-label">Leave</span></td>
                    </tr>
                </table>

                @if(!empty($payroll->salary_preset_source))
                    <div class="note-box">Salary preset source: {{ $payroll->salary_preset_source }}</div>
                @endif

                @if(!empty($payroll->note))
                    <div class="note-box">{{ $payroll->note }}</div>
                @endif

                <div class="footer">Generated on {{ date('Y-m-d H:i') }}</div>
            </div>
        </div>
    </div>
</body>
</html>
