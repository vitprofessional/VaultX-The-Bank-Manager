<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('calculasTitle')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/32dcd4a478.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('public/css/calculas.css') }}">
  </head>
  <body class="calculas-app">
    <div class="app-shell">
        <aside class="app-sidebar d-none d-lg-flex flex-column">
            <div class="app-brand">
                <div class="app-brand-mark"><i class="fa-solid fa-building-columns"></i></div>
                <div>
                    <div class="app-brand-title">Calculas Admin</div>
                    <div class="app-brand-subtitle">Bank operations workspace</div>
                </div>
            </div>

            <div class="app-sidebar-card text-center">
                @if($serverData != null)
                    <h3>{{ $serverData->bank_name }}</h3>
                    <h4>{{ $serverData->business_name }}, {{ $serverData->district }}</h4>
                    <h5>{{ $serverData->contact_number }}</h5>
                @else
                    <h3>Dutch Bangla Bank PLC</h3>
                    <h4>Burichong Bazar, Cumilla</h4>
                    <h5>01678909091</h5>
                @endif
            </div>

            <nav class="app-nav">
                <a class="app-nav-item" href="{{ route('home') }}"><i class="fa-solid fa-gauge"></i><span>Dashboard</span></a>
                <a class="app-nav-item" href="{{ route('accountCreation') }}"><i class="fa-solid fa-address-card"></i><span>Create Account</span></a>
                <a class="app-nav-item" href="{{ route('acList') }}"><i class="fa-solid fa-users"></i><span>Account List</span></a>
                <a class="app-nav-item" href="{{ route('debitCredit') }}"><i class="fa-solid fa-calculator"></i><span>Debit / Credit</span></a>
                <a class="app-nav-item" href="{{ route('generateReport') }}"><i class="fa-solid fa-chart-column"></i><span>Generate Report</span></a>
                <a class="app-nav-item" href="{{ route('projectBrochure') }}"><i class="fa-solid fa-book-open"></i><span>Brochure</span></a>
                @if(Session::has('superAdmin') || Session::has('generalAdmin') || Session::has('manager'))
                    <a class="app-nav-item" href="{{ route('bankEmployee') }}"><i class="fa-solid fa-user-shield"></i><span>Admin Management</span></a>
                    <a class="app-nav-item" href="{{ route('hrEmployeeIndex') }}"><i class="fa-solid fa-users-gear"></i><span>Employee & Payroll</span></a>
                    <a class="app-nav-item" href="{{ route('serverConfig') }}"><i class="fa-solid fa-gear"></i><span>Settings</span></a>
                @endif
            </nav>
        </aside>

        <main class="app-main">
            <header class="app-topbar">
                <div class="app-topbar-left">
                    <div class="app-topbar-meta">
                        <div class="app-topbar-title">@yield('calculasTitle')</div>
                        <div class="app-topbar-subtitle">Secure banking operations panel</div>
                    </div>
                    <div class="app-topbar-search">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                            <input type="text" class="form-control" placeholder="Search records, accounts, employees..." aria-label="Search">
                        </div>
                    </div>
                </div>

                <div class="app-topbar-right">
                    <span class="app-status-pill"><i class="fa-solid fa-shield-heart"></i> Secure Session</span>
                    <div class="dropdown app-user-menu">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-circle-user me-1"></i> {{ $employee->name ?? 'User' }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end rounded-4 shadow border-0 p-2 app-user-dropdown">
                            <li><a class="dropdown-item rounded-3" href="{{ route('changeUserPass') }}"><i class="fa-solid fa-key me-2"></i> Change Password</a></li>
                            <li><a class="dropdown-item rounded-3" href="{{ route('userProfile') }}"><i class="fa-solid fa-user me-2"></i> Profile</a></li>
                            @if(Session::has('superAdmin') || Session::has('generalAdmin') || Session::has('manager'))
                                <li><a class="dropdown-item rounded-3" href="{{ route('serverConfig') }}"><i class="fa-solid fa-server me-2"></i> Server Configuration</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item rounded-3 text-danger" href="{{ route('logoutCalculas') }}"><i class="fa-solid fa-right-from-bracket me-2"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>
            </header>

            <div class="app-content">
                @yield('calculasBody')
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTables for all tables with datatable class
            $('.datatable').DataTable({
                pageLength: 10,
                lengthMenu: [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                },
                dom: '<"row mb-3"<"col-md-6"l><"col-md-6"f>>rtip'
            });
        });
    </script>
  </body>
</html>
