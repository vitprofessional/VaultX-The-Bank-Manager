<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Staff Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-5">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="mb-2">Staff Login</h3>
                        <p class="text-muted small mb-4">Desktop access only. First login will bind your computer.</p>

                        @if(session()->has('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        @if(session()->has('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form method="POST" action="{{ route('staffLoginSubmit') }}" id="staffLoginForm">
                            @csrf
                            <input type="hidden" name="device_fingerprint" id="deviceFingerprint">

                            <div class="mb-3">
                                <label class="form-label">Employee Code</label>
                                <input type="text" name="employee_code" class="form-control" value="{{ old('employee_code') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const source = [
                navigator.userAgent || '',
                navigator.platform || '',
                navigator.language || '',
                String(navigator.hardwareConcurrency || ''),
                String(screen.width || ''),
                String(screen.height || ''),
                String(screen.colorDepth || ''),
                Intl.DateTimeFormat().resolvedOptions().timeZone || ''
            ].join('|');

            function hashString(str) {
                let hash = 0;
                for (let i = 0; i < str.length; i++) {
                    hash = ((hash << 5) - hash) + str.charCodeAt(i);
                    hash |= 0;
                }
                return 'pc_' + Math.abs(hash).toString(16);
            }

            const input = document.getElementById('deviceFingerprint');
            if (input) {
                input.value = hashString(source);
            }
        })();
    </script>
</body>
</html>
