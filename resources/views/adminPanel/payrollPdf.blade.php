<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Payroll Slip</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #111827;
            margin: 0;
            padding: 24px;
            font-size: 12px;
        }

        .sheet {
            border: 1px solid #d1d5db;
            border-radius: 12px;
            overflow: hidden;
        }

        .header {
            background: #0f3554;
            color: #ffffff;
            padding: 18px 20px;
        }

        .content {
            padding: 20px;
        }

        .grid {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
        }

        .grid td,
        .grid th {
            border: 1px solid #d1d5db;
            padding: 8px 10px;
            vertical-align: top;
        }

        .grid th {
            width: 36%;
            text-align: left;
            background: #f8fafc;
        }

        .summary {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
        }

        .summary td {
            border: 1px solid #d1d5db;
            padding: 8px 10px;
            text-align: center;
        }

        .summary strong {
            display: block;
            font-size: 18px;
        }

        .muted {
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="sheet">
        <div class="header">
            <h2 style="margin:0 0 4px;">{{ $serverData->bank_name ?? 'Bank Manager' }}</h2>
            <div>Payroll Slip for {{ $payroll->payroll_month }}</div>
        </div>
        <div class="content">
            <table class="grid">
                <tr>
                    <th>Employee Name</th>
                    <td>{{ $payroll->employee->full_name ?? '-' }}</td>
                    <th>Employee Code</th>
                    <td>{{ $payroll->employee->employee_code ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Department</th>
                    <td>{{ $payroll->employee->department ?: '-' }}</td>
                    <th>Designation</th>
                    <td>{{ $payroll->employee->designation ?: '-' }}</td>
                </tr>
                <tr>
                    <th>Payment Status</th>
                    <td class="text-capitalize">{{ $payroll->payment_status }}</td>
                    <th>Payment Date</th>
                    <td>{{ $payroll->payment_date ?: '-' }}</td>
                </tr>
            </table>

            <table class="grid">
                <tr>
                    <th>Basic Salary</th>
                    <td>{{ number_format((float) $payroll->basic_salary, 2) }}</td>
                    <th>Allowance</th>
                    <td>{{ number_format((float) $payroll->allowance, 2) }}</td>
                </tr>
                <tr>
                    <th>Bonus</th>
                    <td>{{ number_format((float) $payroll->bonus, 2) }}</td>
                    <th>Overtime</th>
                    <td>{{ number_format((float) $payroll->overtime, 2) }}</td>
                </tr>
                <tr>
                    <th>Tax</th>
                    <td>{{ number_format((float) $payroll->tax, 2) }}</td>
                    <th>Deduction</th>
                    <td>{{ number_format((float) $payroll->deduction, 2) }}</td>
                </tr>
                <tr>
                    <th>Loan</th>
                    <td>{{ number_format((float) $payroll->loan, 2) }}</td>
                    <th>Other Deduction</th>
                    <td>{{ number_format((float) $payroll->other_deduction, 2) }}</td>
                </tr>
                <tr>
                    <th>Attendance Deduction</th>
                    <td>{{ number_format((float) $payroll->attendance_deduction, 2) }}</td>
                    <th>Net Salary</th>
                    <td>{{ number_format((float) $payroll->net_salary, 2) }}</td>
                </tr>
            </table>

            <table class="summary">
                <tr>
                    <td><strong>{{ (int) $payroll->present_days }}</strong>Present</td>
                    <td><strong>{{ (int) $payroll->late_days }}</strong>Late</td>
                    <td><strong>{{ (int) $payroll->absent_days }}</strong>Absent</td>
                    <td><strong>{{ (int) $payroll->leave_days }}</strong>Leave</td>
                </tr>
            </table>

            @if(!empty($payroll->salary_preset_source))
                <p class="muted">Salary preset source: {{ $payroll->salary_preset_source }}</p>
            @endif

            @if(!empty($payroll->note))
                <p><strong>Notes:</strong> {{ $payroll->note }}</p>
            @endif
        </div>
    </div>
</body>
</html>
