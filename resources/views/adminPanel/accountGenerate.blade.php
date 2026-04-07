@extends('adminPanel.include')
@section('calculasTitle') Generate Account @endsection
<style>
    .account-print-wrap {
        width: 89mm;
        max-width: 89mm;
        margin: 0 auto;
    }

    .account-view-card {
        width: 89mm;
        height: 51mm;
        border: 0.2mm solid #2f3742;
        border-radius: 2.6mm;
        overflow: hidden;
        background: #f7f9fb;
        box-shadow: none;
    }

    .account-view-header {
        background: #e7edf1;
        border-bottom: 0.2mm solid #2f3742;
        color: #0f2233;
        font-weight: 700;
        padding: 1.7mm 2.6mm;
        font-size: 3.05mm;
        line-height: 1.2;
    }

    .account-view-body {
        padding: 2.1mm 2.35mm;
        font-size: 2.45mm;
        line-height: 1.24;
    }

    .account-bank-head {
        display: grid;
        grid-template-columns: 11mm 14mm 1fr 11mm;
        align-items: center;
        gap: 1.7mm;
        margin-bottom: 1.3mm;
    }

    .account-bank-head .logo-left,
    .account-bank-head .logo-main,
    .account-bank-head .logo-right {
        width: 100%;
        height: 5mm;
        object-fit: contain;
    }

    .account-bank-title {
        font-size: 3.5mm;
        font-weight: 800;
        margin: 0;
        color: #102033;
        line-height: 1.15;
    }

    .account-bank-meta {
        text-align: center;
        margin: 0.9mm 0 1.5mm;
    }

    .account-bank-meta p {
        margin: 0;
        font-size: 2.62mm;
        line-height: 1.27;
        color: #102033;
    }

    .account-bank-meta strong {
        font-weight: 800;
    }

    .account-view-table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        border: 0.2mm solid #2f3742;
    }

    .account-view-table th,
    .account-view-table td {
        border: 0.2mm solid #2f3742;
        padding: 0.2rem 0.5rem;
        font-size: 2.45mm;
        font-weight: bold;
        line-height: 1;
        color: #111827;
        vertical-align: middle;
    }

    .account-view-table th {
        width: 26mm;
        font-weight: 800;
        background: #f2f5f7;
        text-align: left;
        white-space: nowrap;
    }

    .account-view-table td {
        word-break: break-word;
    }

    .account-view-table .label-tight {
        width: 20mm;
        white-space: nowrap;
    }

    .account-details-title {
        padding: 0.5rem 0.75rem;
        background: #0c673d;
        color: #ffffff;
        font-weight: 800;
        letter-spacing: 0.01em;
    }

    .account-details-note {
        color: #1f2937;
        font-size: 2.3mm;
        line-height: 1.35;
        margin-top: 0.2rem;
    }

    .account-view-table th {
        color: #0f172a;
    }

    .account-view-table td {
        color: #111827;
    }

    @media print {
        @page {
            size: auto;
            margin: 0;
        }

        html,
        body {
            background: #ffffff !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            height: 100% !important;
            min-height: 100% !important;
            overflow: hidden !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .row.align-items-center.v-100 {
            min-height: auto !important;
            height: auto !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .row.align-items-center.v-100 > :not(#printArea) {
            display: none !important;
        }

        #printArea {
            position: relative !important;
            inset: auto !important;
            width: 100% !important;
            min-height: 100vh !important;
            margin: 0 !important;
            display: grid !important;
            justify-items: left !important;
            align-items: start !important;
            padding: 8mm 6mm 0 !important;
            box-sizing: border-box !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
            page-break-before: avoid !important;
            page-break-after: avoid !important;
            overflow: hidden !important;
        }

        #printArea .account-print-wrap {
            width: 89mm !important;
            max-width: 89mm !important;
            margin: 0 auto !important;
            padding: 0 !important;
        }

        #printArea .row,
        #printArea [class*='col-'],
        #printArea .account {
            margin: 0 !important;
            padding: 0 !important;
        }

        #printArea .account-view-card {
            margin: 0 auto !important;
            background: #ffffff !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
            page-break-before: avoid !important;
            page-break-after: avoid !important;
        }

        #printArea .account-view-table {
            border-collapse: collapse !important;
            border-spacing: 0 !important;
            border: 0.2mm solid #2f3742 !important;
            background: #ffffff !important;
        }

        #printArea .account-view-table th,
        #printArea .account-view-table td {
            border: 0.2mm solid #2f3742 !important;
            color: #111827 !important;
        }

        #printArea .account-view-table th {
            background: #f2f5f7 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .noprint {
            display: none !important;
        }
    }
</style>
@section('calculasBody')
<div class="row align-items-center v-100">
    @if(isset($data))
    <div class="row text-center">
        <div class="col-12 account-print-wrap">
            <div class="action-toolbar justify-content-center">
                <button class="btn btn-warning btn-sm noprint" onclick="window.print()"><i class="fas fa-print"></i> Print</button>
                <a class="btn btn-success btn-sm noprint" href="{{ route('accountCreation') }}"><i class="fas fa-plus"></i> Add New</a>
                <a href="{{ route('acList') }}" class="btn btn-primary btn-sm noprint"><i class="fas fa-users"></i> Account List</a>
                <a href="{{ route('acEdit',['id'=>$data->id]) }}" class="btn btn-sm btn-success noprint" title="Edit Data"><i class="fa-solid fa-file-pen"></i> Edit Data</a>
            </div>
        </div>
    </div>
    <div class="row" id="printArea">
        <div class="col-4 mx-auto account-print-wrap my-2 account">
            <div class="account-view-card">
                @php
                if(!empty($serverData) && $serverData->count()>0):
                    $bankName           = $serverData->bank_name;
                    $linked_branch      = $serverData->linked_branch;
                    $branch_district    = $serverData->branch_district;
                    $routing_number     = $serverData->routing_number;
                    $swift_code         = $serverData->swift_code;
                    $helpline           = $serverData->helpline;
                    if(!empty($serverData->bank_logo)):
                        $bank_logo          = asset('/public/upload/logos/').'/'.$serverData->bank_logo;
                    else:
                        $bank_logo          = asset('/public/img/')."/bankLogo.png";
                    endif;
                    if(!empty($serverData->logo_2)):
                        $secondLogo         = asset('/public/upload/logos/').'/'.$serverData->logo_2;
                    else:
                        $secondLogo         = asset('/public/img/')."/abLogo.jpg";
                    endif;
                    if(!empty($serverData->logo_3)):
                        $logo_3             = asset('/public/upload/logos/').'/'.$serverData->logo_3;
                    else:
                        $logo_3             = asset('/public/img/')."/rocket.jpg";
                    endif;
                    
                    $contactNo          = $serverData->contact_number;
                else:
                    $bankName           = "Dutch Banla Bank PLC";
                    $linked_branch      = "Jhawtala SME/Krishi";
                    $branch_district    = "Cumilla";
                    $routing_number     = "090191161";
                    $swift_code         = "DBBLBDDH";
                    $helpline           = "16216";
                    $contactNo          = "01836994770";
                endif;
                @endphp
                <div class="account-view-body">
                    <div class="account-bank-head">
                        <img src="{{ $secondLogo }}" alt="{{ $bankName }}" class="logo-left">
                        <img src="{{ $bank_logo }}" alt="{{ $bankName }}" class="logo-main">
                        <h6 class="account-bank-title">{{ $bankName }}</h6>
                        <img src="{{ $logo_3 }}" alt="{{ $bankName }}" class="logo-right">
                    </div>

                    <div class="account-bank-meta">
                        <p><strong>Branch:</strong> {{ $linked_branch }}, <strong>District:</strong> {{ $branch_district }}</p>
                        <p><strong>Routing Number:</strong> {{ $routing_number }}, <strong>SWIFT:</strong> {{ $swift_code }}</p>
                    </div>
                    
                <div class="account-details-title">Account Details</div>
                    @if(isset($data))
                    @php
                        $thumbLabels = [
                            'L1' => 'Left Thumb (L1)',
                            'L2' => 'Left Index (L2)',
                            'L3' => 'Left Middle (L3)',
                            'L4' => 'Left Ring (L4)',
                            'L5' => 'Left Pinky (L5)',
                            'R1' => 'Right Thumb (R1)',
                            'R2' => 'Right Index (R2)',
                            'R3' => 'Right Middle (R3)',
                            'R4' => 'Right Ring (R4)',
                            'R5' => 'Right Pinky (R5)',
                        ];
                        $thumbLabel = $thumbLabels[$data->acFinger] ?? $data->acFinger;
                    @endphp
                    <table class="account-view-table">
                        <tbody>
                            <tr>
                                <th>A/C Name</th>
                                <td colspan="3" style="font-size: 12px;">{{ $data->acName }}</td>
                            </tr>
                            <tr>
                                <th>A/C Number</th>
                                <td colspan="3" style="font-size: 12px;">{{ $data->acNumber }}</td>
                            </tr>
                            <tr>
                                <th width="20%">Account Type</th>
                                <td width="25%">{{ $data->acType }}</td>
                                <th width="20%">Mobile</th>
                                <td width="35%">{{ $data->acMobile }}</td>
                            </tr>
                            <tr>
                                <th>Thumb</th>
                                <td class="label-tight">{{ $thumbLabel }}</td>
                                <th class="label-tight">Outlet</th>
                                <td>Virtual IT Professional</td>
                            </tr>
                        </tbody>
                    </table>
                    <p class="account-details-note"><strong>Branch:</strong> {{ $contactNo }} <strong>(9.30AM-5.30PM)</strong>, <strong>Helpline:</strong> {{ $helpline }} <strong>(24/7)</strong> </p>
                    @else
                    <div class="alert alert-info">
                        <i class="fa-solid fa-info-circle"></i>
                        <span>Sorry! No account data found</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="col-12 col-md-10 mx-auto my-2">
        <div class="card">
            <div class="card-body">
                <div class="alert alert-info">Sorry! No data found with your query</div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection