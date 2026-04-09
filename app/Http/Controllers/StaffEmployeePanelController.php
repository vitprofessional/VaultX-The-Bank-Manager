<?php

namespace App\Http\Controllers;

use App\Models\StaffAttendance;
use App\Models\StaffEmployee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffEmployeePanelController extends Controller
{
    public function loginView()
    {
        return view('staffPanel.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'employee_code' => ['required', 'string', 'max:60'],
            'password' => ['required', 'string', 'max:64'],
            'device_fingerprint' => ['required', 'string', 'max:191'],
        ]);

        if (!$this->isDesktopRequest($request->userAgent() ?? '')) {
            return back()->with('error', 'Staff login is allowed only from desktop or laptop devices.')->withInput();
        }

        $staff = StaffEmployee::where('employee_code', $validated['employee_code'])->first();

        if (!$staff || !$staff->can_login || $staff->status !== 'active' || empty($staff->login_password)) {
            return back()->with('error', 'Login access is not available for this employee.');
        }

        if (!Hash::check($validated['password'], (string) $staff->login_password)) {
            return back()->with('error', 'Invalid employee code or password.');
        }

        $fingerprint = $validated['device_fingerprint'];

        if (empty($staff->device_fingerprint)) {
            $alreadyBound = StaffEmployee::where('device_fingerprint', $fingerprint)
                ->where('id', '!=', $staff->id)
                ->exists();

            if ($alreadyBound) {
                return back()->with('error', 'This computer is already bound to another user. One PC can be used by only one user.');
            }

            $staff->device_fingerprint = $fingerprint;
            $staff->device_bound_at = now();
            $staff->save();
        } elseif ($staff->device_fingerprint !== $fingerprint) {
            return back()->with('error', 'Login denied. This account is bound to another computer.');
        }

        session()->put('staffEmployee', $staff->id);

        return redirect()->route('staffDashboard');
    }

    public function logout()
    {
        session()->forget('staffEmployee');

        return redirect()->route('staffLogin')->with('success', 'Logout successful.');
    }

    public function dashboard()
    {
        $staff = $this->currentStaff();
        if (!$staff) {
            return redirect()->route('staffLogin')->with('error', 'Session expired. Please login again.');
        }

        $today = date('Y-m-d');
        $todayAttendance = StaffAttendance::where('staff_employee_id', $staff->id)
            ->whereDate('attendance_date', $today)
            ->first();

        $recentAttendance = StaffAttendance::where('staff_employee_id', $staff->id)
            ->orderBy('attendance_date', 'desc')
            ->limit(10)
            ->get();

        return view('staffPanel.dashboard', [
            'staff' => $staff,
            'today' => $today,
            'todayAttendance' => $todayAttendance,
            'recentAttendance' => $recentAttendance,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $staff = $this->currentStaff();
        if (!$staff) {
            return redirect()->route('staffLogin')->with('error', 'Session expired. Please login again.');
        }

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'mobile' => ['nullable', 'string', 'max:30'],
        ]);

        $staff->full_name = $validated['full_name'];
        $staff->email = $validated['email'] ?? null;
        $staff->mobile = $validated['mobile'] ?? null;
        $staff->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    public function saveTodayAttendance(Request $request)
    {
        $staff = $this->currentStaff();
        if (!$staff) {
            return redirect()->route('staffLogin')->with('error', 'Session expired. Please login again.');
        }

        if (!$staff->attendance_access) {
            return back()->with('error', 'Attendance access is not granted for your account.');
        }

        $validated = $request->validate([
            'status' => ['required', 'in:present,late,leave'],
            'check_in_time' => ['nullable', 'date_format:H:i'],
            'check_out_time' => ['nullable', 'date_format:H:i'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $today = date('Y-m-d');

        StaffAttendance::updateOrCreate(
            [
                'staff_employee_id' => $staff->id,
                'attendance_date' => $today,
            ],
            [
                'status' => $validated['status'],
                'check_in_time' => $validated['check_in_time'] ?? null,
                'check_out_time' => $validated['check_out_time'] ?? null,
                'note' => $validated['note'] ?? null,
                'marked_by' => null,
            ]
        );

        return back()->with('success', 'Today\'s attendance saved successfully.');
    }

    private function currentStaff(): ?StaffEmployee
    {
        $staffId = session('staffEmployee');
        if (empty($staffId)) {
            return null;
        }

        return StaffEmployee::find($staffId);
    }

    private function isDesktopRequest(string $userAgent): bool
    {
        return !preg_match('/android|iphone|ipad|ipod|iemobile|opera mini|mobile/i', $userAgent);
    }
}
