@extends('layouts.app')
@section('title', 'Edit Payment :: '.config('app.name'))
@push('after-styles')
    {!! Html::style("/css/bootstrap-datepicker3.min.css") !!}
    <style>
        #credit_amt {
            text-align: right;
        }

        .form-group.credit-amt {
            width: 120px;
        }
    </style>
@endpush
@section('content')
    <div class="col maxw-500 mx-auto mt-xl-5 mt-md-3 mt-2">
        <div class="card">
            <div class="card-header">
                <h3><i class="fa fa-pen-square"></i>Edit Payment</h3>
            </div>
            {!! Form::model($payment, ['method' => 'PUT', 'route' => ['admin.payments.update', $payment->id]]) !!}
            <div class="card-body">
                @include('includes.partials.messages')

                <div class="form-group">
                    {!! Form::label('account', 'Account') !!}
                    {!! Form::select('account', $account, $payment->account_id, ['class' => 'form-control custom-select', 'disabled'=>'disabled']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('credit_date', 'Date Received') !!}
                    <div class="input-group">
                        {!! Form::text('credit_date', $payment->credit_date->format('m/d/Y'), ['class' => 'form-control datepicker', 'required' => 'required']) !!}
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="far fa-calendar"></i></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('pay_method', 'Payment Method') !!}
                    {!! Form::select('pay_method', [4 => 'Adjustment', 1 => 'Cash', 2 => 'Check', 3 => 'Paypal'], $payment->pay_method, ['class' => 'form-control custom-select','id' => 'pay_method']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('credit_desc', 'Description (Adjustment reason, check #, etc.)') !!}
                    {!! Form::text('credit_desc', null, ['class' => 'form-control', 'maxlength' => 100]) !!}
                </div>

                <div class="form-group credit-amt">
                    {!! Form::label('credit_amt', 'Amount') !!}
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-money-bill-alt"></i></span>
                        </div>
                        <input type="text" class="form-control" name="credit_amt" id="credit_amt" required="required"
                               value="{!! $payment->credit_amt / 100 !!}">
                    </div>
                </div>

            </div>
            <div class="card-footer">
                {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
                {!! link_to('admin/payments?aid='.$payment->account_id, 'Cancel', ['class' => 'btn btn-cancel']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
@push('after-scripts')
    {!! Html::script("/js/bootstrap-datepicker.min.js") !!}
    <script>
        $(document).ready(function ($) {

            var $amount = $('#credit_amt').blur(function (a) {
                var theval = parseFloat(Math.round($amount.val() * 100) / 100).toFixed(2);

                if (theval > 0)
                    $amount.val(theval);
                else
                    $amount.val('0.00');
            });

            $('.datepicker').datepicker({
                format: "mm/dd/yyyy",
                autoclose: true,
                daysOfWeekDisabled: "0,6",
                orientation: "bottom left",
            });

            $amount.trigger('blur');
        });
    </script>
@endpush

