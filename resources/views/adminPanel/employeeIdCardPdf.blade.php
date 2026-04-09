<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Employee ID Card</title>
    <style>
        @include('adminPanel.partials.employee-id-card-styles')

        html,
        body {
            margin: 0;
            padding: 0;
            background: #fff;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        @page {
            size: A4 portrait;
            margin: 12mm;
        }

        /* DOMPDF-safe layout fallback: avoid flex dependency */
        .idm-card-sheet {
            width: 380px;
            margin: 0 auto;
            text-align: center;
            font-size: 0;
            white-space: nowrap;
        }

        .idm-card {
            display: inline-block;
            vertical-align: top;
            margin: 0 6px;
            text-align: left;
            box-shadow: none;
        }

        .idm-front {
            display: block;
            position: relative;
        }

        .idm-side-ribbon {
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
        }

        .idm-content {
            padding-left: 38px;
        }
    </style>
</head>
<body>
    @if(($format ?? 'landscape') === 'portrait')
        @include('adminPanel.partials.employee-id-card-portrait', ['card' => $card, 'branding' => $branding])
    @else
        @include('adminPanel.partials.employee-id-card', ['card' => $card, 'branding' => $branding])
    @endif
</body>
</html>
