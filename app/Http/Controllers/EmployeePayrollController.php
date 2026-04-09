<?php

namespace App\Http\Controllers;

use App\Models\BankEmployee;
use App\Models\Payroll;
use App\Models\ServerConfig;
use App\Models\StaffAttendance;
use App\Models\StaffEmployee;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Session;

class EmployeePayrollController extends Controller
{
    public function index(Request $request)
    {
        $selectedMonth = $request->get('month', date('Y-m'));
        [$monthStart, $monthEnd] = $this->monthRange($selectedMonth);
        
        $currentAdminId = $this->currentAdminId();
        $currentAdmin = BankEmployee::find($currentAdminId);
        
        // Super admin can see all records; others see only their created records
        if($currentAdmin && $currentAdmin->profileType == 1) {
            // Super admin - get all records
            $employees = StaffEmployee::orderBy('full_name')->get();
            $payrolls = Payroll::with('employee')
                ->where('payroll_month', $selectedMonth)
                ->orderBy('id', 'desc')
                ->get();
            $attendanceRecords = StaffAttendance::with('employee')
                ->whereBetween('attendance_date', [$monthStart, $monthEnd])
                ->orderBy('attendance_date', 'desc')
                ->orderBy('id', 'desc')
                ->get();
        } else {
            // Other roles - get only their created records
            $employees = StaffEmployee::where('created_by', $currentAdminId)
                ->orderBy('full_name')->get();
            $payrolls = Payroll::with('employee')
                ->where('generated_by', $currentAdminId)
                ->where('payroll_month', $selectedMonth)
                ->orderBy('id', 'desc')
                ->get();
            $attendanceRecords = StaffAttendance::with('employee')
                ->where('marked_by', $currentAdminId)
                ->whereBetween('attendance_date', [$monthStart, $monthEnd])
                ->orderBy('attendance_date', 'desc')
                ->orderBy('id', 'desc')
                ->get();
        }

        $attendanceMarkerIds = $attendanceRecords->pluck('marked_by')->filter()->unique()->values();
        $attendanceMarkedByMap = BankEmployee::whereIn('id', $attendanceMarkerIds)->pluck('name', 'id');

        return view('adminPanel.employeeSystem', [
            'employees' => $employees,
            'payrolls' => $payrolls,
            'attendanceRecords' => $attendanceRecords,
            'attendanceMarkedByMap' => $attendanceMarkedByMap,
            'selectedMonth' => $selectedMonth,
            'salaryPresets' => config('hr.salary_presets', []),
            'generatedEmployeeCode' => $this->generateUniqueEmployeeCode(),
        ]);
    }

    public function edit($id, Request $request)
    {
        $profile = StaffEmployee::findOrFail($id);
        $selectedMonth = $request->get('month', date('Y-m'));
        [$monthStart, $monthEnd] = $this->monthRange($selectedMonth);
        
        $currentAdminId = $this->currentAdminId();
        $currentAdmin = BankEmployee::find($currentAdminId);
        
        // Super admin can see all records; others see only their created records
        if($currentAdmin && $currentAdmin->profileType == 1) {
            // Super admin - get all records
            $employees = StaffEmployee::orderBy('full_name')->get();
            $payrolls = Payroll::with('employee')
                ->where('payroll_month', $selectedMonth)
                ->orderBy('id', 'desc')
                ->get();
            $attendanceRecords = StaffAttendance::with('employee')
                ->whereBetween('attendance_date', [$monthStart, $monthEnd])
                ->orderBy('attendance_date', 'desc')
                ->orderBy('id', 'desc')
                ->get();
        } else {
            // Other roles - get only their created records
            $employees = StaffEmployee::where('created_by', $currentAdminId)
                ->orderBy('full_name')->get();
            $payrolls = Payroll::with('employee')
                ->where('generated_by', $currentAdminId)
                ->where('payroll_month', $selectedMonth)
                ->orderBy('id', 'desc')
                ->get();
            $attendanceRecords = StaffAttendance::with('employee')
                ->where('marked_by', $currentAdminId)
                ->whereBetween('attendance_date', [$monthStart, $monthEnd])
                ->orderBy('attendance_date', 'desc')
                ->orderBy('id', 'desc')
                ->get();
        }

        $attendanceMarkerIds = $attendanceRecords->pluck('marked_by')->filter()->unique()->values();
        $attendanceMarkedByMap = BankEmployee::whereIn('id', $attendanceMarkerIds)->pluck('name', 'id');

        return view('adminPanel.employeeSystem', [
            'profile' => $profile,
            'employees' => $employees,
            'payrolls' => $payrolls,
            'attendanceRecords' => $attendanceRecords,
            'attendanceMarkedByMap' => $attendanceMarkedByMap,
            'selectedMonth' => $selectedMonth,
            'salaryPresets' => config('hr.salary_presets', []),
        ]);
    }

    public function saveEmployee(Request $request)
    {
        $employeeId = $request->employee_profile_id;

        $validated = $request->validate([
            'employee_profile_id' => ['nullable', 'integer'],
            'full_name' => ['required', 'string', 'max:255'],
            'employee_code' => [
                'required',
                'string',
                'max:60',
                Rule::unique('staff_employees', 'employee_code')->ignore($employeeId),
            ],
            'email' => ['nullable', 'email', 'max:255'],
            'mobile' => ['nullable', 'string', 'max:30'],
            'designation' => ['nullable', 'string', 'max:100'],
            'department' => ['nullable', 'string', 'max:100'],
            'joined_at' => ['nullable', 'date'],
            'basic_salary' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:active,inactive'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'can_login' => ['nullable', 'in:1'],
            'attendance_access' => ['nullable', 'in:1'],
            'login_password' => ['nullable', 'string', 'min:6', 'max:64'],
        ]);

        if (!empty($validated['employee_profile_id'])) {
            $employee = StaffEmployee::findOrFail($validated['employee_profile_id']);
        } else {
            $employee = new StaffEmployee();
            $employee->created_by = $this->currentAdminId();
            $employee->employee_code = $validated['employee_code'];
        }

        $employee->full_name = $validated['full_name'];
        $employee->email = $validated['email'] ?? null;
        $employee->mobile = $validated['mobile'] ?? null;
        $employee->designation = $validated['designation'] ?? null;
        $employee->department = $validated['department'] ?? null;
        $employee->joined_at = $validated['joined_at'] ?? null;
        $employee->basic_salary = $validated['basic_salary'];
        $employee->status = $validated['status'];

        if ($this->isSuperAdmin() && $this->staffAccessColumnsExist()) {
            $canLogin = $request->boolean('can_login');
            $employee->can_login = $canLogin;
            $employee->attendance_access = $request->boolean('attendance_access');

            if ($canLogin && empty($employee->login_password) && empty($validated['login_password'])) {
                return back()->with('error', 'Login password is required when login access is enabled.')->withInput();
            }

            if (!empty($validated['login_password'])) {
                $employee->login_password = Hash::make($validated['login_password']);
            }
        }

        if ($request->hasFile('avatar') && !$this->avatarColumnExists()) {
            return back()->with('error', 'Avatar column is missing. Please run database migration first.');
        }

        if ($request->hasFile('avatar') && $this->avatarColumnExists()) {
            $employee->avatar_path = $this->storeEmployeeAvatar(
                $request->file('avatar'),
                $employee->avatar_path ?? null
            );
        }

        $employee->save();

        return back()->with('success', 'Employee profile saved successfully.');
    }

    public function deleteEmployee($id)
    {
        $employee = StaffEmployee::findOrFail($id);

        if (!empty($employee->avatar_path)) {
            $avatarFullPath = public_path($employee->avatar_path);
            if (File::exists($avatarFullPath)) {
                File::delete($avatarFullPath);
            }
        }

        $employee->delete();

        return back()->with('success', 'Employee profile deleted successfully.');
    }

    public function resetEmployeeDeviceBinding($id)
    {
        if (!$this->isSuperAdmin()) {
            return back()->with('error', 'Only super admin can reset staff device binding.');
        }

        if (!Schema::hasColumn('staff_employees', 'device_fingerprint') || !Schema::hasColumn('staff_employees', 'device_bound_at')) {
            return back()->with('error', 'Device binding columns are missing. Please run migration first.');
        }

        $employee = StaffEmployee::findOrFail($id);
        $employee->device_fingerprint = null;
        $employee->device_bound_at = null;
        $employee->save();

        return back()->with('success', 'Device binding has been reset for ' . $employee->full_name . '.');
    }

    public function savePayroll(Request $request)
    {
        $validated = $request->validate([
            'staff_employee_id' => ['required', 'exists:staff_employees,id'],
            'payroll_month' => ['required', 'regex:/^\\d{4}-\\d{2}$/'],
            'salary_preset_key' => ['nullable', 'string', 'max:120'],
            'allowance' => ['nullable', 'numeric', 'min:0'],
            'bonus' => ['nullable', 'numeric', 'min:0'],
            'overtime' => ['nullable', 'numeric', 'min:0'],
            'tax' => ['nullable', 'numeric', 'min:0'],
            'deduction' => ['nullable', 'numeric', 'min:0'],
            'loan' => ['nullable', 'numeric', 'min:0'],
            'other_deduction' => ['nullable', 'numeric', 'min:0'],
            'payment_status' => ['required', 'in:pending,paid,hold'],
            'payment_date' => ['nullable', 'date'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $employee = StaffEmployee::findOrFail($validated['staff_employee_id']);
        $preset = $this->resolvePresetForEmployee($employee, $validated['salary_preset_key'] ?? null);
        $attendanceSummary = $this->attendanceSummaryForEmployeeMonth($employee->id, $validated['payroll_month']);

        $basicSalary = (float) $employee->basic_salary;
        $allowance = (float) ($validated['allowance'] ?? $preset['allowance']);
        $bonus = (float) ($validated['bonus'] ?? $preset['bonus']);
        $overtime = (float) ($validated['overtime'] ?? $preset['overtime']);

        $tax = (float) ($validated['tax'] ?? $preset['tax']);
        $deduction = (float) ($validated['deduction'] ?? $preset['deduction']);
        $loan = (float) ($validated['loan'] ?? $preset['loan']);
        $otherDeduction = (float) ($validated['other_deduction'] ?? $preset['other_deduction']);

        $attendanceSettings = config('hr.salary_presets.attendance', []);
        $workingDays = (int) ($attendanceSettings['working_days'] ?? 26);
        $lateFactor = (float) ($attendanceSettings['late_penalty_factor'] ?? 0.25);
        $leaveFactor = (float) ($attendanceSettings['leave_penalty_factor'] ?? 0.5);

        $dailyRate = $workingDays > 0 ? $basicSalary / $workingDays : 0;
        $attendanceDeduction = (($attendanceSummary['absent_days'] ?? 0) * $dailyRate)
            + (($attendanceSummary['late_days'] ?? 0) * $dailyRate * $lateFactor)
            + (($attendanceSummary['leave_days'] ?? 0) * $dailyRate * $leaveFactor);

        $grossSalary = $basicSalary + $allowance + $bonus + $overtime;
        $netSalary = $grossSalary - ($tax + $deduction + $loan + $otherDeduction + $attendanceDeduction);
        if ($netSalary < 0) {
            $netSalary = 0;
        }

        Payroll::updateOrCreate(
            [
                'staff_employee_id' => $employee->id,
                'payroll_month' => $validated['payroll_month'],
            ],
            [
                'basic_salary' => $basicSalary,
                'present_days' => (int) ($attendanceSummary['present_days'] ?? 0),
                'late_days' => (int) ($attendanceSummary['late_days'] ?? 0),
                'absent_days' => (int) ($attendanceSummary['absent_days'] ?? 0),
                'leave_days' => (int) ($attendanceSummary['leave_days'] ?? 0),
                'allowance' => $allowance,
                'bonus' => $bonus,
                'overtime' => $overtime,
                'tax' => $tax,
                'deduction' => $deduction,
                'loan' => $loan,
                'other_deduction' => $otherDeduction,
                'attendance_deduction' => $attendanceDeduction,
                'gross_salary' => $grossSalary,
                'net_salary' => $netSalary,
                'payment_status' => $validated['payment_status'],
                'payment_date' => $validated['payment_date'] ?? null,
                'note' => $validated['note'] ?? null,
                'generated_by' => $this->currentAdminId(),
                'salary_preset_source' => $preset['source'],
                'salary_preset_key' => $preset['key'],
            ]
        );

        return back()->with('success', 'Payroll saved successfully.');
    }

    public function saveAttendance(Request $request)
    {
        $validated = $request->validate([
            'attendance_id' => ['nullable', 'integer', 'exists:staff_attendances,id'],
            'staff_employee_id' => ['required', 'exists:staff_employees,id'],
            'attendance_date' => ['required', 'date'],
            'status' => ['required', 'in:present,absent,leave,late'],
            'check_in_time' => ['nullable', 'date_format:H:i'],
            'check_out_time' => ['nullable', 'date_format:H:i'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $attendanceId = $validated['attendance_id'] ?? null;

        if (!empty($attendanceId)) {
            $attendance = StaffAttendance::findOrFail($attendanceId);

            if (!$this->canManageAttendance($attendance)) {
                return back()->with('error', 'You are not allowed to update this attendance record.');
            }

            $attendance->staff_employee_id = $validated['staff_employee_id'];
            $attendance->attendance_date = $validated['attendance_date'];
            $attendance->status = $validated['status'];
            $attendance->check_in_time = $validated['check_in_time'] ?? null;
            $attendance->check_out_time = $validated['check_out_time'] ?? null;
            $attendance->note = $validated['note'] ?? null;
            $attendance->marked_by = $this->currentAdminId();
            $attendance->save();

            return redirect()->route('hrEmployeeIndex', [
                'month' => substr($validated['attendance_date'], 0, 7),
            ])->with('success', 'Attendance updated successfully.');
        }

        StaffAttendance::updateOrCreate(
            [
                'staff_employee_id' => $validated['staff_employee_id'],
                'attendance_date' => $validated['attendance_date'],
            ],
            [
                'status' => $validated['status'],
                'check_in_time' => $validated['check_in_time'] ?? null,
                'check_out_time' => $validated['check_out_time'] ?? null,
                'note' => $validated['note'] ?? null,
                'marked_by' => $this->currentAdminId(),
            ]
        );

        return back()->with('success', 'Attendance saved successfully.');
    }

    public function editAttendance($id, Request $request)
    {
        $attendanceProfile = StaffAttendance::with('employee')->findOrFail($id);

        if (!$this->canManageAttendance($attendanceProfile)) {
            return back()->with('error', 'You are not allowed to edit this attendance record.');
        }

        $selectedMonth = $request->get('month', substr((string) $attendanceProfile->attendance_date, 0, 7));
        [$monthStart, $monthEnd] = $this->monthRange($selectedMonth);

        $currentAdminId = $this->currentAdminId();

        if ($this->isSuperAdmin()) {
            $employees = StaffEmployee::orderBy('full_name')->get();
            $payrolls = Payroll::with('employee')
                ->where('payroll_month', $selectedMonth)
                ->orderBy('id', 'desc')
                ->get();
            $attendanceRecords = StaffAttendance::with('employee')
                ->whereBetween('attendance_date', [$monthStart, $monthEnd])
                ->orderBy('attendance_date', 'desc')
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $employees = StaffEmployee::where('created_by', $currentAdminId)
                ->orderBy('full_name')->get();
            $payrolls = Payroll::with('employee')
                ->where('generated_by', $currentAdminId)
                ->where('payroll_month', $selectedMonth)
                ->orderBy('id', 'desc')
                ->get();
            $attendanceRecords = StaffAttendance::with('employee')
                ->where('marked_by', $currentAdminId)
                ->whereBetween('attendance_date', [$monthStart, $monthEnd])
                ->orderBy('attendance_date', 'desc')
                ->orderBy('id', 'desc')
                ->get();
        }

        $attendanceMarkerIds = $attendanceRecords->pluck('marked_by')->filter()->unique()->values();
        $attendanceMarkedByMap = BankEmployee::whereIn('id', $attendanceMarkerIds)->pluck('name', 'id');

        return view('adminPanel.employeeSystem', [
            'attendanceProfile' => $attendanceProfile,
            'employees' => $employees,
            'payrolls' => $payrolls,
            'attendanceRecords' => $attendanceRecords,
            'attendanceMarkedByMap' => $attendanceMarkedByMap,
            'selectedMonth' => $selectedMonth,
            'salaryPresets' => config('hr.salary_presets', []),
            'generatedEmployeeCode' => $this->generateUniqueEmployeeCode(),
        ]);
    }

    public function deleteAttendance($id)
    {
        $attendance = StaffAttendance::findOrFail($id);

        if (!$this->canManageAttendance($attendance)) {
            return back()->with('error', 'You are not allowed to delete this attendance record.');
        }

        $attendance->delete();

        return back()->with('success', 'Attendance deleted successfully.');
    }

    public function deletePayroll($id)
    {
        $payroll = Payroll::findOrFail($id);
        $payroll->delete();

        return back()->with('success', 'Payroll deleted successfully.');
    }

    public function payrollSlip($id)
    {
        $payroll = Payroll::with('employee')->findOrFail($id);

        return view('adminPanel.payrollSlip', [
            'payroll' => $payroll,
        ]);
    }

    public function downloadPayrollPdf($id)
    {
        $payroll = Payroll::with('employee')->findOrFail($id);
        $pdf = Pdf::loadView('adminPanel.payrollPdf', [
            'payroll' => $payroll,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('payroll-slip-' . $payroll->id . '.pdf');
    }

    public function employeeIdCard($id, Request $request)
    {
        $employee = StaffEmployee::findOrFail($id);
        $format = $this->resolveCardFormat($request->get('format', 'portrait'));

        [$card, $branding] = $this->buildEmployeeCardViewData($employee);

        return view('adminPanel.employeeIdCardPreview', [
            'employee' => $employee,
            'card' => $card,
            'branding' => $branding,
            'format' => $format,
        ]);
    }

    public function downloadEmployeeIdCardPdf($id, Request $request)
    {
        $employee = StaffEmployee::findOrFail($id);
        $format = $this->resolveCardFormat($request->get('format', 'portrait'));

        [$card, $branding] = $this->buildEmployeeCardViewData($employee);

        $pdf = Pdf::loadView('adminPanel.employeeIdCardPdf', [
            'employee' => $employee,
            'card' => $card,
            'branding' => $branding,
            'format' => $format,
        ])->setPaper('a4', $format);

        return $pdf->download('employee-id-card-' . $employee->employee_code . '.pdf');
    }

    private function currentAdminId(): ?int
    {
        if (Session::has('superAdmin')) {
            return BankEmployee::find(Session::get('superAdmin'))?->id;
        }

        if (Session::has('generalAdmin')) {
            return BankEmployee::find(Session::get('generalAdmin'))?->id;
        }

        if (Session::has('manager')) {
            return BankEmployee::find(Session::get('manager'))?->id;
        }

        if (Session::has('cashier')) {
            return BankEmployee::find(Session::get('cashier'))?->id;
        }

        return null;
    }

    private function monthRange(string $selectedMonth): array
    {
        $start = \Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth()->toDateString();
        $end = \Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->endOfMonth()->toDateString();

        return [$start, $end];
    }

    private function generateUniqueEmployeeCode(): string
    {
        do {
            $code = 'EMP-' . date('Y') .random_int(1000, 9999);
        } while (StaffEmployee::where('employee_code', $code)->exists());

        return $code;
    }

    private function resolvePresetForEmployee(StaffEmployee $employee, ?string $requestedPresetKey): array
    {
        $presetConfig = config('hr.salary_presets', []);
        $departmentPresets = $presetConfig['departments'] ?? [];
        $designationPresets = $presetConfig['designations'] ?? [];

        $departmentKey = $employee->department ? 'department:' . $employee->department : null;
        $designationKey = $employee->designation ? 'designation:' . $employee->designation : null;

        $preset = array_fill_keys(['allowance', 'bonus', 'overtime', 'tax', 'deduction', 'loan', 'other_deduction'], 0);
        $sourceParts = [];

        if (!empty($requestedPresetKey) && $requestedPresetKey !== 'auto') {
            [$type, $name] = array_pad(explode(':', $requestedPresetKey, 2), 2, null);
            $lookup = $type === 'designation' ? ($designationPresets[$name] ?? null) : ($departmentPresets[$name] ?? null);
            if (is_array($lookup)) {
                $preset = array_merge($preset, $lookup);
                $sourceParts[] = $requestedPresetKey;
                return [
                    'key' => $requestedPresetKey,
                    'source' => implode('|', $sourceParts),
                    'allowance' => (float) $preset['allowance'],
                    'bonus' => (float) $preset['bonus'],
                    'overtime' => (float) $preset['overtime'],
                    'tax' => (float) $preset['tax'],
                    'deduction' => (float) $preset['deduction'],
                    'loan' => (float) $preset['loan'],
                    'other_deduction' => (float) $preset['other_deduction'],
                ];
            }
        }

        if (!empty($employee->department) && isset($departmentPresets[$employee->department])) {
            $preset = array_merge($preset, $departmentPresets[$employee->department]);
            $sourceParts[] = $departmentKey;
        }

        if (!empty($employee->designation) && isset($designationPresets[$employee->designation])) {
            $preset = array_merge($preset, $designationPresets[$employee->designation]);
            $sourceParts[] = $designationKey;
        }

        return [
            'key' => $requestedPresetKey ?: 'auto',
            'source' => implode('|', $sourceParts) ?: 'default',
            'allowance' => (float) $preset['allowance'],
            'bonus' => (float) $preset['bonus'],
            'overtime' => (float) $preset['overtime'],
            'tax' => (float) $preset['tax'],
            'deduction' => (float) $preset['deduction'],
            'loan' => (float) $preset['loan'],
            'other_deduction' => (float) $preset['other_deduction'],
        ];
    }

    private function attendanceSummaryForEmployeeMonth(int $employeeId, string $selectedMonth): array
    {
        [$monthStart, $monthEnd] = $this->monthRange($selectedMonth);

        $records = StaffAttendance::where('staff_employee_id', $employeeId)
            ->whereBetween('attendance_date', [$monthStart, $monthEnd])
            ->get();

        return [
            'present_days' => $records->where('status', 'present')->count(),
            'late_days' => $records->where('status', 'late')->count(),
            'absent_days' => $records->where('status', 'absent')->count(),
            'leave_days' => $records->where('status', 'leave')->count(),
        ];
    }

    private function buildEmployeeCardViewData(StaffEmployee $employee): array
    {
        $server = $this->activeServerConfig();
        $joinedAt = $employee->joined_at
            ? \Carbon\Carbon::parse($employee->joined_at)->format('d M Y')
            : 'N/A';

        $validity = \Carbon\Carbon::now()->endOfYear()->format('d M Y');
        $issueDate = \Carbon\Carbon::now()->format('d M Y');

        $bankName       = $server->bank_name ?? 'Bank Manager';
        $companyName    = $server->business_name ?? "Virtual IT Professional";
        $companyLocation= $server->location ?? "Burichong Bazar, Burichong, Cumilla";
        $outletBranch   = "Burichong Bazar Outlet, Burichong, Cumilla";
        $logoName = $server->bank_logo ?? null;

        $initials = collect(explode(' ', trim((string) $employee->full_name)))
            ->filter()
            ->map(function ($part) {
                return strtoupper(substr($part, 0, 1));
            })
            ->take(2)
            ->implode('');

        if ($initials === '') {
            $initials = 'EM';
        }

        $avatarSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="300" height="360">'
            . '<rect width="100%" height="100%" fill="#e2e8f0"/>'
            . '<text x="50%" y="52%" dominant-baseline="middle" text-anchor="middle" font-family="Arial" font-size="88" fill="#0f172a" font-weight="700">'
            . $initials
            . '</text></svg>';

        $photoUrl = 'data:image/svg+xml;base64,' . base64_encode($avatarSvg);
        if (!empty($employee->avatar_path)) {
            $avatarFullPath = public_path($employee->avatar_path);
            if (File::exists($avatarFullPath)) {
                $mimeType = File::mimeType($avatarFullPath) ?: 'image/jpeg';
                $photoUrl = 'data:' . $mimeType . ';base64,' . base64_encode(File::get($avatarFullPath));
            }
        }

        $card = [
            'name' => $employee->full_name,
            'studentId' => $employee->employee_code,
            'class' => $employee->department ?: 'General',
            'section' => $employee->designation ?: 'Employee',
            'roll' => ucfirst($employee->status ?: 'active'),
            'guardianName' => $employee->email ?: 'N/A',
            'guardianPhone' => $employee->mobile ?: 'N/A',
            'guardianRelation' => 'Contact',
            'validity' => $validity,
            'issueDate' => $issueDate,
            'joinedAt' => $joinedAt,
            'photoUrl' => $photoUrl,
            'qrCodeSvg' => $this->generateEmployeeQrCode($employee),
        ];

        $branding = [
            'name' => $bankName,
            'company' => $companyName,
            'outlet' => $outletBranch,
            'tagline' => !empty($server->linked_branch)
                ? 'Branch: ' . $server->linked_branch
                : 'Employee Identity Card',
            'phone' => $server->contact_number ?? $server->helpline ?? 'N/A',
            'email' => $server->email ?? 'N/A',
            'website' => request()->getHost(),
            'location' => $server->location ?: 'N/A',
            'address' => trim(($server->location ?? '') . ', ' . ($server->district ?? '')),
            'logoUrl' => $logoName ? asset('upload/logos/' . $logoName) : null,
        ];

        return [$card, $branding];
    }

    private function generateEmployeeQrCode(StaffEmployee $employee): string
    {
        $payload = implode("\n", [
            'Employee ID Card',
            'Name: ' . $employee->full_name,
            'Employee Code: ' . ($employee->employee_code ?: 'N/A'),
            'Department: ' . ($employee->department ?: 'N/A'),
            'Designation: ' . ($employee->designation ?: 'N/A'),
            'Mobile: ' . ($employee->mobile ?: 'N/A'),
            'Email: ' . ($employee->email ?: 'N/A'),
            'Status: ' . ucfirst($employee->status ?: 'active'),
            'Joined At: ' . ($employee->joined_at ? \Carbon\Carbon::parse($employee->joined_at)->format('d M Y') : 'N/A'),
        ]);

        try {
            $renderer = new ImageRenderer(
                new RendererStyle(180, 0),
                new SvgImageBackEnd()
            );

            $writer = new Writer($renderer);

            return 'data:image/svg+xml;base64,' . base64_encode($writer->writeString($payload));
        } catch (\Throwable $throwable) {
            return '';
        }
    }

    private function activeServerConfig(): ServerConfig
    {
        $adminId = $this->currentAdminId();
        $server = null;

        if (!empty($adminId)) {
            $admin = BankEmployee::find($adminId);
            $creator = $admin?->creator;

            $server = ServerConfig::where('employee_id', $adminId)
                ->orWhere('employee_id', $creator)
                ->first();
        }

        return $server ?: (ServerConfig::first() ?: new ServerConfig());
    }

    private function resolveCardFormat(string $format): string
    {
        return in_array($format, ['landscape', 'portrait'], true) ? $format : 'portrait';
    }

    private function isSuperAdmin(): bool
    {
        return Session::has('superAdmin');
    }

    private function canManageAttendance(StaffAttendance $attendance): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return (int) $attendance->marked_by === (int) $this->currentAdminId();
    }

    private function storeEmployeeAvatar(UploadedFile $avatarFile, ?string $oldAvatarPath = null): string
    {
        $destinationPath = public_path('upload/employees');
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        $extension = strtolower($avatarFile->getClientOriginalExtension() ?: 'jpg');
        $fileName = 'employee_' . date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
        $avatarFile->move($destinationPath, $fileName);

        if (!empty($oldAvatarPath)) {
            $oldPath = public_path($oldAvatarPath);
            if (File::exists($oldPath)) {
                File::delete($oldPath);
            }
        }

        return 'upload/employees/' . $fileName;
    }

    private function avatarColumnExists(): bool
    {
        return Schema::hasColumn('staff_employees', 'avatar_path');
    }

    private function staffAccessColumnsExist(): bool
    {
        return Schema::hasColumn('staff_employees', 'can_login')
            && Schema::hasColumn('staff_employees', 'attendance_access')
            && Schema::hasColumn('staff_employees', 'login_password');
    }
}
