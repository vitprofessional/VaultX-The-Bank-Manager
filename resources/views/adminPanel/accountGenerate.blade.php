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
        font-size: 2mm;
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

    @media print {
        @page {
            size: 89mm 51mm landscape;
            margin: 1.8mm;
        }

        html,
        body {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body * {
            visibility: hidden;
        }

        #printArea,
        #printArea * {
            visibility: visible;
        }

        #printArea {
            position: fixed;
            inset: 0;
            margin: 0 !important;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }

        #printArea .account-print-wrap {
            width: 89mm !important;
            max-width: 89mm !important;
            margin: 0 !important;
            padding: 0 !important;
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
                        <p><strong>Branch:</strong> {{ $linked_branch }}, Cumilla</p>
                        <p><strong>Routing Number:</strong> {{ $routing_number }}, <strong>SWIFT:</strong> {{ $swift_code }}</p>
                        <p><strong>Contact:</strong> {{ $contactNo }}, <strong>Helpline:</strong> {{ $helpline }}</p>
                    </div>
                    
                <div class="p-2 bg-secondary fw-bold text-white">Account Details</div>
                    @if(isset($data))
                    <table class="account-view-table">
                        <tbody>
                            <tr>
                                <th>A/C Name</th>
                                <td colspan="3">{{ $data->acName }}</td>
                            </tr>
                            <tr>
                                <th>A/C Number</th>
                                <td colspan="3">{{ $data->acNumber }}</td>
                            </tr>
                            <tr>
                                <th>Type of Account</th>
                                <td colspan="3">{{ $data->acType }}</td>
                            </tr>
                            <tr>
                                <th>Mobile Number</th>
                                <td colspan="3">{{ $data->acMobile }}</td>
                            </tr>
                            <tr>
                                <th>A/C Finger</th>
                                <td class="label-tight">{{ $data->acFinger }}</td>
                                <th class="label-tight">Outlet Name</th>
                                <td>Virtual IT Professional</td>
                            </tr>
                        </tbody>
                    </table>
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