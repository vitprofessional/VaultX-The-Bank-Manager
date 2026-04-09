@extends('adminPanel.include')
@section('calculasTitle') Employee System @endsection

@section('calculasBody')
<div class="page-header">
    <div>
        <div class="page-kicker">HR Operations</div>
        <h1 class="page-title">Employee System and Payroll</h1>
        <p class="page-copy">Manage employee records and process monthly payroll from one workspace.</p>
    </div>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        <strong>Validation failed:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif

@php
    $salaryPresets = $salaryPresets ?? [];
    $departmentPresets = $salaryPresets['departments'] ?? [];
    $designationPresets = $salaryPresets['designations'] ?? [];
    $attendanceSettings = $salaryPresets['attendance'] ?? [];
    $departmentOptions = array_unique(array_merge(array_keys($departmentPresets), ['Accounts', 'Operations', 'Cash', 'HR', 'IT', 'Branch Support']));
    $designationOptions = array_unique(array_merge(array_keys($designationPresets), ['Manager', 'Officer', 'Assistant', 'Cashier', 'Executive']));
@endphp

<div class="row g-4 align-items-start">
    <div class="col-12 col-xl-5">
        <div class="card">
            <div class="card-header">
                <i class="fa-solid fa-id-card me-2"></i>@if(isset($profile)) Update @else New @endif Employee Profile
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('hrEmployeeSave') }}" class="row g-3" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="employee_profile_id" value="{{ $profile->id ?? '' }}">

                    @if(isset($profile) && !empty($profile->avatar_path) && file_exists(public_path($profile->avatar_path)))
                        <div class="col-12">
                            <label class="form-label">Current Avatar</label>
                            <div>
                                <img src="{{ asset('public/'.$profile->avatar_path) }}" alt="Employee Avatar" style="width:72px;height:72px;object-fit:cover;border-radius:50%;border:2px solid #e5e7eb;">
                            </div>
                        </div>
                    @endif

                    <div class="col-12">
                        <label class="form-label">Avatar (JPG, PNG, WEBP | Max 2MB)</label>
                        <input type="file" name="avatar" class="form-control" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                        <small class="text-muted">Upload employee profile photo for ID card and profile view.</small>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="full_name" class="form-control" placeholder="Enter full name" value="{{ old('full_name', $profile->full_name ?? '') }}" required>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">Employee Code</label>
                        <input type="text" name="employee_code" class="form-control" placeholder="Auto-generated unique employee code" value="{{ old('employee_code', $profile->employee_code ?? $generatedEmployeeCode ?? '') }}" readonly>
                        <small class="text-muted">This code is generated in the form and saved as-is. It cannot be edited here.</small>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="">Select status</option>
                            <option value="active" @selected(old('status', $profile->status ?? 'active') == 'active')>Active</option>
                            <option value="inactive" @selected(old('status', $profile->status ?? '') == 'inactive')>Inactive</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Enter email address" value="{{ old('email', $profile->email ?? '') }}">
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">Mobile</label>
                        <input type="text" name="mobile" class="form-control" placeholder="Enter mobile number" value="{{ old('mobile', $profile->mobile ?? '') }}">
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">Designation</label>
                        <select name="designation" class="form-select">
                            <option value="">Select designation</option>
                            @foreach($designationOptions as $designationOption)
                                <option value="{{ $designationOption }}" @selected(old('designation', $profile->designation ?? '') == $designationOption)>{{ $designationOption }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">Department</label>
                        <select name="department" class="form-select">
                            <option value="">Select department</option>
                            @foreach($departmentOptions as $departmentOption)
                                <option value="{{ $departmentOption }}" @selected(old('department', $profile->department ?? '') == $departmentOption)>{{ $departmentOption }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">Join Date</label>
                        <input type="date" name="joined_at" class="form-control" placeholder="Select join date" value="{{ old('joined_at', $profile->joined_at ?? '') }}">
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">Basic Salary</label>
                        <input type="number" name="basic_salary" class="form-control" step="0.01" min="0" placeholder="Enter basic salary" value="{{ old('basic_salary', $profile->basic_salary ?? 0) }}" required>
                    </div>

                    @if(Session::has('superAdmin'))
                        <div class="col-12">
                            <div class="border rounded-3 p-3 bg-light-subtle">
                                <div class="fw-semibold mb-2"><i class="fa-solid fa-shield-halved me-1"></i> Staff Access Control</div>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" role="switch" id="canLogin" name="can_login" value="1" @checked(old('can_login', $profile->can_login ?? false))>
                                    <label class="form-check-label" for="canLogin">Enable employee login access</label>
                                </div>
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" role="switch" id="attendanceAccess" name="attendance_access" value="1" @checked(old('attendance_access', $profile->attendance_access ?? false))>
                                    <label class="form-check-label" for="attendanceAccess">Allow employee self attendance</label>
                                </div>

                                <label class="form-label">Login Password @if(isset($profile) && !empty($profile->login_password)) (optional to change) @endif</label>
                                <input type="password" name="login_password" class="form-control" placeholder="Set staff login password">
                                <small class="text-muted">Required when login access is enabled for first-time setup.</small>
                            </div>
                        </div>
                    @endif

                    <div class="col-12">
                        <label class="form-label">Salary Preset</label>
                        <select name="salary_preset_key" class="form-select" id="salaryPresetKey">
                            <option value="auto">Auto based on department / designation</option>
                            <optgroup label="Department Presets">
                                @foreach($departmentPresets as $name => $preset)
                                    <option value="department:{{ $name }}">{{ $name }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Designation Presets">
                                @foreach($designationPresets as $name => $preset)
                                    <option value="designation:{{ $name }}">{{ $name }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                        <small class="text-muted">Use a preset to auto-fill payroll allowance, bonus, tax, and deductions.</small>
                    </div>

                    <div class="col-12 d-flex gap-2">
                        <button type="submit" class="btn btn-brand text-white">
                            <i class="fa-solid fa-floppy-disk"></i> Save Employee
                        </button>
                        @if(isset($profile))
                            <a href="{{ route('hrEmployeeIndex') }}" class="btn btn-outline-secondary">Cancel Edit</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="fa-solid fa-money-check-dollar me-2"></i>Create Payroll
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('hrPayrollSave') }}" class="row g-3">
                    @csrf

                    <div class="col-12">
                        <label class="form-label">Employee</label>
                        <select name="staff_employee_id" class="form-select" required>
                            <option value="">Select employee</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" @selected(old('staff_employee_id') == $emp->id)>
                                    {{ $emp->full_name }} ({{ $emp->employee_code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">Payroll Month</label>
                        <input type="month" name="payroll_month" class="form-control" placeholder="Select payroll month" value="{{ old('payroll_month', $selectedMonth) }}" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Payroll Preset</label>
                        <select name="salary_preset_key" class="form-select" id="payrollPresetKey">
                            <option value="auto" selected>Auto based on employee department / designation</option>
                            <optgroup label="Department Presets">
                                @foreach($departmentPresets as $name => $preset)
                                    <option value="department:{{ $name }}">{{ $name }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Designation Presets">
                                @foreach($designationPresets as $name => $preset)
                                    <option value="designation:{{ $name }}">{{ $name }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">Payment Status</label>
                        <select name="payment_status" class="form-select" required>
                            <option value="">Select payment status</option>
                            <option value="pending" @selected(old('payment_status', 'pending') == 'pending')>Pending</option>
                            <option value="paid" @selected(old('payment_status') == 'paid')>Paid</option>
                            <option value="hold" @selected(old('payment_status') == 'hold')>Hold</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">Allowance</label>
                        <input type="number" name="allowance" class="form-control" step="0.01" min="0" placeholder="Enter allowance" value="{{ old('allowance', 0) }}">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Bonus</label>
                        <input type="number" name="bonus" class="form-control" step="0.01" min="0" placeholder="Enter bonus" value="{{ old('bonus', 0) }}">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Overtime</label>
                        <input type="number" name="overtime" class="form-control" step="0.01" min="0" placeholder="Enter overtime amount" value="{{ old('overtime', 0) }}">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Tax</label>
                        <input type="number" name="tax" class="form-control" step="0.01" min="0" placeholder="Enter tax amount" value="{{ old('tax', 0) }}">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label">Deduction</label>
                        <input type="number" name="deduction" class="form-control" step="0.01" min="0" placeholder="Enter deduction" value="{{ old('deduction', 0) }}">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label">Loan</label>
                        <input type="number" name="loan" class="form-control" step="0.01" min="0" placeholder="Enter loan amount" value="{{ old('loan', 0) }}">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label">Other Deduction</label>
                        <input type="number" name="other_deduction" class="form-control" step="0.01" min="0" placeholder="Enter other deduction" value="{{ old('other_deduction', 0) }}">
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">Payment Date</label>
                        <input type="date" name="payment_date" class="form-control" placeholder="Select payment date" value="{{ old('payment_date') }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Notes</label>
                        <textarea name="note" rows="2" class="form-control" placeholder="Optional notes">{{ old('note') }}</textarea>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-receipt"></i> Process Payroll
                        </button>
                    </div>
                </form>
                <div class="alert alert-info mt-3 mb-0 small">
                    Attendance deductions are calculated automatically from employee records for the selected month.
                    Working days: {{ $attendanceSettings['working_days'] ?? 26 }},
                    late factor: {{ $attendanceSettings['late_penalty_factor'] ?? 0.25 }},
                    leave factor: {{ $attendanceSettings['leave_penalty_factor'] ?? 0.5 }}.
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="fa-solid fa-calendar-check me-2"></i>@if(isset($attendanceProfile)) Update @else Attendance @endif Entry
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('hrAttendanceSave') }}" class="row g-3">
                    @csrf
                    <input type="hidden" name="attendance_id" value="{{ $attendanceProfile->id ?? '' }}">
                    <div class="col-12 col-md-6">
                        <label class="form-label">Employee</label>
                        <select name="staff_employee_id" class="form-select" required>
                            <option value="">Select employee</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" @selected(old('staff_employee_id', $attendanceProfile->staff_employee_id ?? '') == $emp->id)>{{ $emp->full_name }} ({{ $emp->employee_code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Date</label>
                        <input type="date" name="attendance_date" class="form-control" placeholder="Select attendance date" value="{{ old('attendance_date', $attendanceProfile->attendance_date ?? '') }}" required>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="">Select attendance status</option>
                            <option value="present" @selected(old('status', $attendanceProfile->status ?? '') == 'present')>Present</option>
                            <option value="late" @selected(old('status', $attendanceProfile->status ?? '') == 'late')>Late</option>
                            <option value="absent" @selected(old('status', $attendanceProfile->status ?? '') == 'absent')>Absent</option>
                            <option value="leave" @selected(old('status', $attendanceProfile->status ?? '') == 'leave')>Leave</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label">Check In</label>
                        <input type="time" name="check_in_time" class="form-control" placeholder="Select check in time" value="{{ old('check_in_time', $attendanceProfile->check_in_time ?? '') }}">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label">Check Out</label>
                        <input type="time" name="check_out_time" class="form-control" placeholder="Select check out time" value="{{ old('check_out_time', $attendanceProfile->check_out_time ?? '') }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Note</label>
                        <textarea name="note" class="form-control" rows="2" placeholder="Optional attendance note">{{ old('note', $attendanceProfile->note ?? '') }}</textarea>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fa-solid @if(isset($attendanceProfile)) fa-pen-to-square @else fa-calendar-plus @endif"></i> @if(isset($attendanceProfile)) Update Attendance @else Save Attendance @endif
                        </button>
                        @if(isset($attendanceProfile))
                            <a href="{{ route('hrEmployeeIndex', ['month' => $selectedMonth]) }}" class="btn btn-outline-secondary ms-2">Cancel Edit</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-7">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fa-solid fa-people-group me-2"></i>Employee Directory
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Avatar</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Department</th>
                                <th>Basic Salary</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employees as $index => $emp)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if(!empty($emp->avatar_path) && file_exists(public_path($emp->avatar_path)))
                                            <img src="{{ asset('public/'.$emp->avatar_path) }}" alt="{{ $emp->full_name }}" style="width:36px;height:36px;object-fit:cover;border-radius:50%;border:1px solid #e5e7eb;">
                                        @else
                                            <span class="badge rounded-pill text-bg-light" style="width:36px;height:36px;line-height:30px;font-weight:700;">
                                                {{ strtoupper(substr(trim((string) $emp->full_name), 0, 1)) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ $emp->full_name }}</td>
                                    <td><code>{{ $emp->employee_code }}</code></td>
                                    <td>{{ $emp->department ?: '-' }}</td>
                                    <td>{{ number_format((float) $emp->basic_salary, 2) }}</td>
                                    <td>
                                        @if($emp->status === 'active')
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('hrEmployeeIdCardPreview', ['id' => $emp->id]) }}" class="btn btn-sm btn-info text-white" title="Preview ID Card">
                                            <i class="fa-solid fa-id-card"></i>
                                        </a>
                                        <a href="{{ route('hrEmployeeEdit', ['id' => $emp->id, 'month' => $selectedMonth]) }}" class="btn btn-sm btn-success text-white" title="Edit Employee">
                                            <i class="fa-solid fa-file-pen"></i>
                                        </a>
                                        @if(Session::has('superAdmin'))
                                            <form method="POST" action="{{ route('hrEmployeeDeviceReset', ['id' => $emp->id]) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-warning text-dark" onclick="return confirm('Reset bound device for this employee? They will need to login again from a new PC to bind.');" title="Reset Device Binding">
                                                    <i class="fa-solid fa-laptop-medical"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('hrEmployeeDelete', ['id' => $emp->id]) }}" onclick="return confirm('Delete this employee profile? All payroll records of this employee will be removed.');" class="btn btn-sm btn-danger" title="Delete Employee">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-center">No employees found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span><i class="fa-solid fa-table-list me-2"></i>Payroll Register</span>
                <form method="GET" action="{{ route('hrEmployeeIndex') }}" class="d-flex align-items-center gap-2">
                    <label for="monthFilter" class="mb-0 small text-muted">Month</label>
                    <input id="monthFilter" type="month" name="month" class="form-control form-control-sm" value="{{ $selectedMonth }}">
                    <button type="submit" class="btn btn-sm btn-outline-primary">Filter</button>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Employee</th>
                                <th>Month</th>
                                <th>Gross</th>
                                <th>Net</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payrolls as $index => $payroll)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $payroll->employee->full_name ?? '-' }}</td>
                                    <td>{{ $payroll->payroll_month }}</td>
                                    <td>{{ number_format((float) $payroll->gross_salary, 2) }}</td>
                                    <td>{{ number_format((float) $payroll->net_salary, 2) }}</td>
                                    <td>
                                        @if($payroll->payment_status === 'paid')
                                            <span class="badge bg-success">Paid</span>
                                        @elseif($payroll->payment_status === 'hold')
                                            <span class="badge bg-warning text-dark">Hold</span>
                                        @else
                                            <span class="badge bg-secondary">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('hrPayrollSlip', ['id' => $payroll->id]) }}" class="btn btn-sm btn-primary" title="View Payslip">
                                            <i class="fa-solid fa-file-invoice"></i>
                                        </a>
                                        <a href="{{ route('hrPayrollDelete', ['id' => $payroll->id]) }}" onclick="return confirm('Delete this payroll entry?');" class="btn btn-sm btn-danger" title="Delete Payroll">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-center py-3">No payroll entries found for {{ $selectedMonth }}.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span><i class="fa-solid fa-calendar-days me-2"></i>Attendance Register</span>
                <span class="small text-muted">Month: {{ $selectedMonth }}</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Employee</th>
                                <th>Status</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Edited By</th>
                                <th>Last Updated</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendanceRecords as $index => $attendance)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $attendance->attendance_date }}</td>
                                    <td>{{ $attendance->employee->full_name ?? '-' }}</td>
                                    <td class="text-capitalize">{{ $attendance->status }}</td>
                                    <td>{{ $attendance->check_in_time ?: '-' }}</td>
                                    <td>{{ $attendance->check_out_time ?: '-' }}</td>
                                    <td>{{ $attendanceMarkedByMap[$attendance->marked_by] ?? 'N/A' }}</td>
                                    <td>{{ optional($attendance->updated_at)->format('d M Y H:i') ?: '-' }}</td>
                                    <td>
                                        <a href="{{ route('hrAttendanceEdit', ['id' => $attendance->id, 'month' => $selectedMonth]) }}" class="btn btn-sm btn-success text-white" title="Edit Attendance">
                                            <i class="fa-solid fa-file-pen"></i>
                                        </a>
                                        <a href="{{ route('hrAttendanceDelete', ['id' => $attendance->id]) }}" onclick="return confirm('Delete this attendance record?');" class="btn btn-sm btn-danger" title="Delete Attendance">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-center py-3">No attendance records found for {{ $selectedMonth }}.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        const presetMap = @json([
            'departments' => $departmentPresets,
            'designations' => $designationPresets,
        ]);

        const componentFields = ['allowance', 'bonus', 'overtime', 'tax', 'deduction', 'loan', 'other_deduction'];
        const payrollPreset = document.getElementById('payrollPresetKey');

        function applyPreset(presetKey) {
            let preset = null;

            if (!presetKey || presetKey === 'auto') {
                return;
            }

            const parts = presetKey.split(':');
            if (parts.length < 2) {
                return;
            }

            const type = parts[0];
            const name = parts.slice(1).join(':');
            if (presetMap[type] && presetMap[type][name]) {
                preset = presetMap[type][name];
            }

            if (!preset) {
                return;
            }

            componentFields.forEach((field) => {
                const input = document.querySelector(`[name="${field}"]`);
                if (input && preset[field] !== undefined) {
                    input.value = preset[field];
                }
            });
        }

        if (payrollPreset) {
            payrollPreset.addEventListener('change', function () {
                applyPreset(this.value);
            });
        }
    })();
</script>
@endsection
