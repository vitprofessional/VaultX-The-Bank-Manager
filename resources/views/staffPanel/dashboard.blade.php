<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Staff Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-0">Staff Panel</h3>
                <div class="text-muted small">{{ $staff->full_name }} ({{ $staff->employee_code }})</div>
            </div>
            <a href="{{ route('staffLogout') }}" class="btn btn-outline-danger btn-sm">Logout</a>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session()->has('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session()->has('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row g-4">
            <div class="col-12 col-lg-6">
                <div class="card h-100">
                    <div class="card-header">General Information</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('staffProfileSave') }}" class="row g-3">
                            @csrf
                            <div class="col-12">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="full_name" class="form-control" value="{{ old('full_name', $staff->full_name) }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $staff->email) }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Mobile</label>
                                <input type="text" name="mobile" class="form-control" value="{{ old('mobile', $staff->mobile) }}">
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Save Profile</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card h-100">
                    <div class="card-header">Today Attendance</div>
                    <div class="card-body">
                        @if(!$staff->attendance_access)
                            <div class="alert alert-warning mb-0">Attendance permission is not granted by super admin.</div>
                        @else
                            <form method="POST" action="{{ route('staffAttendanceSave') }}" class="row g-3">
                                @csrf
                                <div class="col-12">
                                    <label class="form-label">Date</label>
                                    <input type="date" class="form-control" value="{{ $today }}" readonly>
                                    <small class="text-muted">Only current day attendance is allowed.</small>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select" required>
                                        <option value="">Select</option>
                                        <option value="present" @selected(old('status', $todayAttendance->status ?? '') === 'present')>Present</option>
                                        <option value="late" @selected(old('status', $todayAttendance->status ?? '') === 'late')>Late</option>
                                        <option value="leave" @selected(old('status', $todayAttendance->status ?? '') === 'leave')>Leave</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Check In</label>
                                    <input type="time" name="check_in_time" class="form-control" value="{{ old('check_in_time', $todayAttendance->check_in_time ?? '') }}">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Check Out</label>
                                    <input type="time" name="check_out_time" class="form-control" value="{{ old('check_out_time', $todayAttendance->check_out_time ?? '') }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Note</label>
                                    <textarea name="note" rows="2" class="form-control">{{ old('note', $todayAttendance->note ?? '') }}</textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-success">Save Today Attendance</button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">Recent Attendance</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentAttendance as $row)
                                <tr>
                                    <td>{{ $row->attendance_date }}</td>
                                    <td class="text-capitalize">{{ $row->status }}</td>
                                    <td>{{ $row->check_in_time ?: '-' }}</td>
                                    <td>{{ $row->check_out_time ?: '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
