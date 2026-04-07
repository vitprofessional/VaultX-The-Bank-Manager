<div class="slip-card payroll-slip-card">
    <div class="slip-head payroll-slip-head">
        <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
            <div>
                <h4 class="mb-1">{{ $serverData->bank_name ?? 'Bank Manager' }}</h4>
                <div class="small opacity-75">Monthly payroll statement</div>
            </div>
            <div class="text-end">
                <div class="small opacity-75">Payroll Month</div>
                <div class="fw-bold">{{ $payroll->payroll_month }}</div>
            </div>
        </div>
    </div>

    <div class="slip-body payroll-slip-body">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <div class="slip-section-title">Employee Information</div>
                <div class="slip-kv"><span>Name</span><strong>{{ $payroll->employee->full_name ?? '-' }}</strong></div>
                <div class="slip-kv"><span>Employee Code</span><strong>{{ $payroll->employee->employee_code ?? '-' }}</strong></div>
                <div class="slip-kv"><span>Department</span><strong>{{ $payroll->employee->department ?: '-' }}</strong></div>
                <div class="slip-kv"><span>Designation</span><strong>{{ $payroll->employee->designation ?: '-' }}</strong></div>
                <div class="slip-kv"><span>Payment Status</span><strong class="text-capitalize">{{ $payroll->payment_status }}</strong></div>
                <div class="slip-kv"><span>Payment Date</span><strong>{{ $payroll->payment_date ?: '-' }}</strong></div>
            </div>
            <div class="col-12 col-md-6">
                <div class="slip-section-title">Salary Breakdown</div>
                <div class="slip-kv"><span>Basic Salary</span><strong>{{ number_format((float) $payroll->basic_salary, 2) }}</strong></div>
                <div class="slip-kv"><span>Allowance</span><strong>{{ number_format((float) $payroll->allowance, 2) }}</strong></div>
                <div class="slip-kv"><span>Bonus</span><strong>{{ number_format((float) $payroll->bonus, 2) }}</strong></div>
                <div class="slip-kv"><span>Overtime</span><strong>{{ number_format((float) $payroll->overtime, 2) }}</strong></div>
                <div class="slip-kv"><span>Tax</span><strong>{{ number_format((float) $payroll->tax, 2) }}</strong></div>
                <div class="slip-kv"><span>Total Deductions</span><strong>{{ number_format((float) ($payroll->tax + $payroll->deduction + $payroll->loan + $payroll->other_deduction), 2) }}</strong></div>
                <div class="slip-kv"><span>Attendance Deduction</span><strong>{{ number_format((float) $payroll->attendance_deduction, 2) }}</strong></div>
                <div class="slip-kv"><span>Net Salary</span><strong>{{ number_format((float) $payroll->net_salary, 2) }}</strong></div>
            </div>
        </div>

        <div class="slip-summary-grid mt-3">
            <div class="slip-summary-box"><strong>{{ (int) $payroll->present_days }}</strong><span>Present</span></div>
            <div class="slip-summary-box"><strong>{{ (int) $payroll->late_days }}</strong><span>Late</span></div>
            <div class="slip-summary-box"><strong>{{ (int) $payroll->absent_days }}</strong><span>Absent</span></div>
            <div class="slip-summary-box"><strong>{{ (int) $payroll->leave_days }}</strong><span>Leave</span></div>
        </div>

        @if(!empty($payroll->salary_preset_source))
            <div class="slip-note mt-3">Salary preset source: {{ $payroll->salary_preset_source }}</div>
        @endif

        @if(!empty($payroll->note))
            <div class="mt-3">
                <div class="slip-section-title">Notes</div>
                <div class="slip-note">{{ $payroll->note }}</div>
            </div>
        @endif

        <div class="mt-3 small text-muted">Generated on {{ date('Y-m-d H:i') }}</div>
    </div>
</div>
