<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Calculas Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/32dcd4a478.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('public/css/calculas.css') }}">
  </head>
  <body class="calculas-auth">
    <div class="auth-wrap">
      <section class="auth-brand-panel d-flex align-items-end">
        <div class="auth-brand-content">
          <span class="auth-badge"><i class="fa-solid fa-shield-halved"></i> Secure banking workspace</span>
          <h1 class="auth-title">Calculas Admin</h1>
          <p class="auth-text text-white-50">A focused back-office panel for cash tracking, account management, reporting, and branch configuration.</p>

          <div class="row g-3 mt-4">
            <div class="col-12 col-md-4">
              <div class="app-sidebar-card">
                <div class="fw-bold text-white">Fast login</div>
                <div class="soft-muted">Access roles and records in one place.</div>
              </div>
            </div>
            <div class="col-12 col-md-4">
              <div class="app-sidebar-card">
                <div class="fw-bold text-white">Clear audit flow</div>
                <div class="soft-muted">Track account, cash, and employee activity.</div>
              </div>
            </div>
            <div class="col-12 col-md-4">
              <div class="app-sidebar-card">
                <div class="fw-bold text-white">Branch settings</div>
                <div class="soft-muted">Keep business data and branding consistent.</div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="auth-card-panel">
        <div class="auth-card">
          <div class="mb-4 text-center">
            <div class="mx-auto mb-3 app-brand-mark">
              <i class="fa-solid fa-building-columns"></i>
            </div>
            <div class="page-kicker">Banking operations</div>
            <h2 class="mb-2">Welcome back</h2>
            <p class="auth-text mb-0">Sign in to continue to the Calculas dashboard.</p>
          </div>

          @if(session()->has('success'))
            <div class="alert alert-success border-0 rounded-4">{{ session()->get('success') }}</div>
          @endif

          @if(session()->has('error'))
            <div class="alert alert-danger border-0 rounded-4">{{ session()->get('error') }}</div>
          @endif

          @if($allemployee->count() > 0)
            <form action="{{ route('loginCalculas') }}" method="POST" class="d-grid gap-3">
              @csrf
              <div>
                <label class="form-label fw-semibold" for="loginId">Login ID</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fa-solid fa-id-card"></i></span>
                  <input type="text" class="form-control form-control-lg" id="loginId" name="loginId" placeholder="Enter login ID" required>
                </div>
              </div>

              <div>
                <label class="form-label fw-semibold" for="loginPass">Password</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fa-solid fa-key"></i></span>
                  <input type="password" class="form-control form-control-lg" id="loginPass" name="loginPass" placeholder="Enter password" required>
                </div>
              </div>

              <button class="btn btn-brand btn-lg text-white" type="submit">Sign In</button>
            </form>
          @else
            <form action="{{ route('registerCalculas') }}" method="POST" class="d-grid gap-3">
              @csrf
              <div>
                <label class="form-label fw-semibold" for="employeeName">Employee Name</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                  <input type="text" class="form-control form-control-lg" id="employeeName" name="employeeName" placeholder="Enter employee name" required>
                </div>
              </div>

              <div>
                <label class="form-label fw-semibold" for="employeeMail">Employee Email</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                  <input type="email" class="form-control form-control-lg" id="employeeMail" name="employeeMail" placeholder="Enter employee email" required>
                </div>
              </div>

              <div>
                <label class="form-label fw-semibold" for="loginIdRegister">Login ID</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fa-solid fa-id-card"></i></span>
                  <input type="text" class="form-control form-control-lg" id="loginIdRegister" name="loginId" placeholder="Create login ID" required>
                </div>
              </div>

              <div>
                <label class="form-label fw-semibold" for="loginPassRegister">Password</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                  <input type="password" class="form-control form-control-lg" id="loginPassRegister" name="loginPass" placeholder="Create password" required>
                </div>
              </div>

              <button class="btn btn-brand btn-lg text-white" type="submit">Create Admin Profile</button>
            </form>
          @endif
        </div>
      </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
