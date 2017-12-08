@extends('layouts.app')@section('title', 'Lunch Report :: '.config('app.name'))@push('after-styles')    <style>        #grpDates, #grpPaymentDates, #grpAccounts {            display: none;        }    </style>@endpush@section('content')    <div class="col maxw-500 mx-auto mt-xl-4 mt-md-3 mt-2">        <div class="card">            <div class="card-header">                <h3><i class="fa fa-sticky-note"></i>Administrator Reports</h3>            </div>            <div class="card-body">                <div class="form-group">                    {!! Form::label('selReports', 'Report') !!}                    {!! Form::select('selReports', $reports, 0, ['class' => 'form-control custom-select', 'id' => 'selReports']) !!}                </div>                <div class="form-group" id="grpAccounts">                    {!! Form::label('selAccounts', 'Account') !!}                    {!! Form::select('selAccounts', $accounts, 0, ['class' => 'form-control custom-select', 'id' => 'selAccounts']) !!}                </div>                <div class="form-group" id="grpDates">                    {!! Form::label('selDates', 'Date') !!}                    {!! Form::select('selDates', $dates, 0, ['class' => 'form-control custom-select', 'id' => 'selDates']) !!}                </div>                <div class="form-group" id="grpPaymentDates">                    {!! Form::label('selPaymentDates', 'Date') !!}                    {{--                    {!! Form::select('selPaymentDates', $paymentdates, 0, ['class' => 'form-control custom-select', 'id' => 'selPaymentDates']) !!}--}}                    <select class="form-control custom-select" id="selPaymentDates" name="selPaymentDates">                        <option value="0" selected="selected">- Select -</option>                        @foreach($paymentdates as $paymentdate)                            <option value="{!! $paymentdate->toDateString() !!}">{!! $paymentdate->format('l, F jS, Y') !!}</option>                        @endforeach                    </select>                </div>            </div>            <div class="card-footer">                <button id="btnRun" type="button" class="btn btn-primary" disabled>Run</button>            </div>        </div>    </div>@endsection@push('after-scripts')    <script>        $(document).ready(function () {            var $grpAccounts = $("#grpAccounts"),                $grpDates = $("#grpDates"),                $grpPaymentDates = $("#grpPaymentDates"),                $selAccounts = $("#selAccounts").change(function (e) {                    handleButton($selAccounts.val() != 0);                }),                $selDates = $("#selDates").change(function (e) {                    handleButton($selDates.val() != 0);                }),                $selPaymentDates = $("#selPaymentDates").change(function (e) {                    handleButton($selPaymentDates.val() != 0);                }),                $selReports = $("#selReports").change(function (e) {                    $grpAccounts.hide();                    $grpDates.hide();                    $grpPaymentDates.hide();                    $selDates.val(0);                    $selPaymentDates.val(0);                    $selAccounts.val(0);                    handleButton(false);                    var rpt = +($selReports.val());                    switch (rpt) {                        case 1: //Lunch Orders By Provider                            $grpDates.show();                            break;                        // case 2: $grpDates.show(); break;//Lunch Orders By Teacher                        case 2: //Lunch Orders By Grade                            $grpDates.show();                            break;                        case 3: //Account Balances                            handleButton(true);                            break;                        case 4: //Account Details                            $grpAccounts.show();                            break;                        case 5: //Lunch Labels                            $grpDates.show();                            break;                        case 6: //Payment Dates                            $grpPaymentDates.show();                            break;                    }                }),                $btnRun = $("#btnRun").click(function (e) {                    var rpt = +($selReports.val());                    switch (rpt) {                        case 1:                            window.open('reports/' + rpt + '?d=' + $selDates.val());                            break;                        // case 2: window.open('report?no='+rpt+'&d='+$selDates.val()); break;                        case 2:                            window.open('reports/' + rpt + '?d=' + $selDates.val());                            break;                        case 3:                            window.open('reports/' + rpt);                            break;                        case 4:                            window.open('reports/' + rpt + '?a=' + $selAccounts.val());                            break;                        case 5:                            window.open('reports/' + rpt + '?d=' + $selDates.val());                            break;                        case 6:                            window.open('reports/' + rpt + '?d=' + $selPaymentDates.val());                            break;                    }                });            function handleButton(enable) {                $btnRun.prop('disabled', !enable);            }        });    </script>@endpush