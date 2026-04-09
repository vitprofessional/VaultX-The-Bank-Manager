@extends('adminPanel.include')
@section('calculasTitle') Employee ID Card @endsection

@section('calculasBody')
<div class="row gutters-20 mb-4">
    <div class="card height-auto col-10 mx-auto">
        <div class="card-body">
            <div class="heading-layout1">
                <div class="item-title">
                    <h3>Employee ID Card</h3>
                </div>
            </div>
            @if(isset($employee))
                @php
                    $fmt = $format ?? request()->get('format', 'portrait');
                    $qsL = ['format' => 'landscape'];
                    $qsP = ['format' => 'portrait'];
                @endphp
                <div class="row">
                    <div class="col-10 mx-auto">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">Employee ID Card</h4>
                                <div class="d-flex gap-2 align-items-center">
                                    <button type="button" class="btn btn-success btn-sm" onclick="printCards()"><i class="fa-solid fa-print"></i> Print</button>
                                    <a href="{{ route('hrEmployeeIndex') }}" class="btn btn-danger btn-sm"><i class="fa-solid fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                            <div class="card-body" id="printArea">
                                @if($fmt === 'portrait')
                                    @include('adminPanel.partials.employee-id-card-portrait', ['card' => $card, 'branding' => $branding])
                                @else
                                    @include('adminPanel.partials.employee-id-card', ['card' => $card, 'branding' => $branding])
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-info">Sorry! No data found</div>
                <div class="mb-4"><a href="{{ route('hrEmployeeIndex') }}" class="btn btn-primary"><i class="fa-solid fa-arrow-left"></i> Back</a></div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style id="idCardSharedStyles">
@include('adminPanel.partials.employee-id-card-styles')
</style>
@endpush

@push('scripts')
<script>
function printCards() {
    const printArea = document.getElementById('printArea');
    if (!printArea) return;

    const styleTag = document.getElementById('idCardSharedStyles');
    const styleContent = styleTag ? styleTag.innerHTML : '';
    const printWindow = window.open('', '_blank', 'width=1000,height=800');
    if (!printWindow) return;

    printWindow.document.open();
    printWindow.document.write(`
        <!doctype html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Employee ID Card Print</title>
            <style>
                ${styleContent}
                html, body { margin: 0; padding: 0; background: #fff; }
                .print-wrap { width: 100%; min-height: 100vh; display: flex; align-items: flex-start; justify-content: center; padding: 12mm 0; }
                .idm-card { box-shadow: 0 6px 14px rgba(0, 0, 0, 0.12); }
                @page { size: A4 {{ $fmt }}; margin: 5mm; }
            </style>
        </head>
        <body>
            <div class="print-wrap">${printArea.innerHTML}</div>
        </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.focus();

    setTimeout(function () {
        printWindow.print();
        printWindow.close();
    }, 250);
}
</script>
@endpush
