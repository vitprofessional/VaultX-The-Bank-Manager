@extends('adminPanel.include')
@section('calculasTitle') Configuration @endsection
@section('calculasBody')
@php
    if(!empty($config) && $config->count()>0):
        $businessId             = $config->id;
        $businessName           = $config->business_name;
        $businesslocation       = $config->location;
        $businessLicense        = $config->license_no;
        $businessMobile         = $config->contact_number;
        $businessMail           = $config->email;
        $businessThana          = $config->thana;
        $businessDistrict       = $config->district;
        $businessBank           = $config->bank_name;
        $linkedBranch           = $config->linked_branch;
        $branchRoutingNumber    = $config->routing_number;
        $branchDistrict         = $config->branch_district;
        $bankSwift              = $config->swift_code;
        $bankHelpline           = $config->helpline;
        $bankLogo               = $config->bank_logo;
        $secondLogo             = $config->logo_2;
        $thirdLogo              = $config->logo_3;
    else:
        $businessId             = '';
        $businessName           = '';
        $businesslocation       = '';
        $businessLicense        = '';
        $businessMobile         = '';
        $businessMail           = '';
        $businessThana          = '';
        $businessDistrict       = '';
        $businessBank           = '';
        $linkedBranch           = '';
        $branchRoutingNumber    = '';
        $branchDistrict         = '';
        $bankSwift              = '';
        $bankHelpline           = '';
        $bankLogo               = '';
        $secondLogo             = '';
        $thirdLogo              = '';
    endif;
@endphp
<div class="row align-items-center v-100">
    <div class="col-12 mb-3">
        @if(session()->has('success'))
            <div class="alert alert-success w-100">
                {{ session()->get('success') }}
            </div>
        @endif
        @if(session()->has('error'))
            <div class="alert alert-danger w-100">
                {{ session()->get('error') }}
            </div>
        @endif
    </div>
    <div class="col-10 my-4 mx-auto">
        <div class="row">
            <div class="col-4">
                @if(!empty($bankLogo))
                    <label class="mb-2 fw-bold" for="bankLogo">Bank Logo</label>
                    <div class="input-group mb-3">
                        <img class="w-50" src="{{ asset('/public/upload/logos/') }}/{{ $bankLogo }}" alt="{{ $businessBank }}">
                    </div>
                    <a href="{{ route('delBankLogo',['id'=>$businessId]) }}" class="btn btn-danger btn-sm">Delete</a>
                @else
                <form class="form" action="{{ route('saveBankLogo') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="businessId" value="{{ $businessId }}">
                    <label class="mb-2 fw-bold" for="bankLogo">Bank Logo</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="bankLogo"><i class="fa-brands fa-slack"></i></span>
                        <input type="file" class="form-control" aria-label="bankLogo" name="bankLogo" aria-describedby="bankLogo" required>
                    </div>
                    <div class="input-group mb-3">
                        <button class="btn btn-primary btn-sm" type="submit">
                            Update Logo
                        </button>
                    </div>
                </form>
                @endif
            </div>
            <div class="col-4">
                @if(!empty($secondLogo))
                    <label class="mb-2 fw-bold" for="secondLogo">Additional Logo</label>
                    <div class="input-group mb-3">
                        <img class="w-50" src="{{ asset('/public/upload/logos/') }}/{{ $secondLogo }}" alt="{{ $businessBank }}">
                    </div>
                    <a href="{{ route('delSecondLogo',['id'=>$businessId]) }}" class="btn btn-danger btn-sm">Delete</a>
                @else
                <form class="form" action="{{ route('saveSecondLogo') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="businessId" value="{{ $businessId }}">
                    <label class="mb-2 fw-bold" for="secondLogo">Additional Logo</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="secondLogo"><i class="fa-brands fa-joomla"></i></span>
                        <input type="file" class="form-control" aria-label="secondLogo" name="secondLogo" aria-describedby="secondLogo" required>
                    </div>
                    <div class="input-group mb-3">
                        <button class="btn btn-success btn-sm" type="submit">
                            Update Logo
                        </button>
                    </div>
                </form>
                @endif
            </div>
            <div class="col-4">
                @if(!empty($thirdLogo))
                    <label class="mb-2 fw-bold" for="thirdLogo">Third Logo</label>
                    <div class="input-group mb-3">
                        <img class="w-50" src="{{ asset('/public/upload/logos/') }}/{{ $thirdLogo }}" alt="{{ $businessBank }}">
                    </div>
                    <a href="{{ route('delThirdLogo',['id'=>$businessId]) }}" class="btn btn-danger btn-sm">Delete</a>
                @else
                <form class="form" action="{{ route('saveThirdLogo') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="businessId" value="{{ $businessId }}">
                    <label class="mb-2 fw-bold" for="thirdLogo">Third Logo</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="thirdLogo"><i class="fa-brands fa-drupal"></i></span>
                        <input type="file" class="form-control" aria-label="thirdLogo" name="thirdLogo" aria-describedby="thirdLogo" required>
                    </div>
                    <div class="input-group mb-3">
                        <button class="btn btn-info btn-sm" type="submit">
                            Update Logo
                        </button>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
    <div class="col-10 mx-auto my-4">
        <div class="card">
            <div class="card-header">Business Setup</div>
            <div class="card-body">
                <div class="row">
                    <form class="form row" action="{{ route('saveServerConfig') }}" method="POST">
                        @csrf
                        <input type="hidden" name="businessId" value="{{ $businessId }}">
                        <div class="col-6">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="businessName"><i class="fa-light fa-warehouse-full"></i></span>
                                <input type="text" class="form-control" placeholder="Enter business name" aria-label="businessName" name="businessName" value="{{ $businessName }}" aria-describedby="businessName">
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="businessLocation"><i class="fa-duotone fa-light fa-location-crosshairs"></i></span>
                                <input type="text" class="form-control" placeholder="Enter business location" aria-label="businessLocation" name="businessLocation" value="{{ $businesslocation }}" aria-describedby="businessLocation">
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="licenseNo"><i class="fa-light fa-building-memo"></i></span>
                                <input type="text" class="form-control" placeholder="Enter business license number" aria-label="licenseNo" name="licenseNo" value="{{ $businessLicense }}" aria-describedby="licenseNo">
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="contactNumber"><i class="fa-light fa-phone-office"></i></span>
                                <input type="text" class="form-control" placeholder="Office mobile or contact number" aria-label="contactNumber" name="contactNumber" value="{{ $businessMobile }}" aria-describedby="contactNumber">
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="emailAddress"><i class="fa-light fa-envelopes"></i></span>
                                <input type="text" class="form-control" placeholder="Office email address" aria-label="emailAddress" name="emailAddress" value="{{ $businessMail }}" aria-describedby="emailAddress">
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="businessThana"><i class="fa-light fa-building-shield"></i></span>
                                <input type="text" class="form-control" placeholder="Enter business thana name" aria-label="businessThana" name="businessThana" value="{{ $businessThana }}" aria-describedby="businessThana">
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="businessDistrict"><i class="fa-duotone fa-light fa-building-magnifying-glass"></i></span>
                                <input type="text" class="form-control" placeholder="Enter business district name" aria-label="businessDistrict" name="businessDistrict" value="{{ $businessDistrict }}" aria-describedby="businessDistrict">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="bankName"><i class="fa-light fa-building-columns"></i></span>
                                <input type="text" class="form-control" placeholder="Enter bank name" aria-label="bankName" name="bankName" value="{{ $businessBank }}" aria-describedby="bankName">
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="linkedBranch"><i class="fa-light fa-vault"></i></span>
                                <input type="text" class="form-control" placeholder="Enter bank linked branch" aria-label="linkedBranch" name="linkedBranch" value="{{ $linkedBranch }}" aria-describedby="linkedBranch">
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="branchDistrict"><i class="fa-light fa-building-circle-check"></i></span>
                                <input type="text" class="form-control" placeholder="Enter linked branch district" aria-label="branchDistrict" name="branchDistrict" value="{{ $branchDistrict }}" aria-describedby="branchDistrict">
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="routingNumber"><i class="fa-light fa-passport"></i></span>
                                <input type="text" class="form-control" placeholder="Enter branch routing number" aria-label="routingNumber" name="routingNumber" value="{{ $branchRoutingNumber }}" aria-describedby="routingNumber">
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="bankSwift"><i class="fa-brands fa-swift"></i></span>
                                <input type="text" class="form-control" placeholder="Enter linked bank swift code" aria-label="bankSwift" name="bankSwift" value="{{ $bankSwift }}" aria-describedby="bankSwift">
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="bankHelpline"><i class="fa-light fa-phone-rotary"></i></span>
                                <input type="text" class="form-control" placeholder="Enter linked bank helpline number" aria-label="bankHelpline" name="bankHelpline" value="{{ $bankHelpline }}" aria-describedby="bankHelpline" required>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <button class="btn btn-success" type="submit">
                                <i class="fa-light fa-right-from-bracket fa-beat"></i> Save Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection