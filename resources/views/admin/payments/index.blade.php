@extends('layouts.app')
@section('title', 'Receive Payments :: '.config('app.name'))
@push('after-styles')
    {!! Html::style("/css/bootstrap-datepicker3.min.css") !!}
    <style>
        #payments-table td:nth-child(3), #payments-table td:nth-child(4) {
            /*, td:nth-child(5) {*/
            text-align: right;
            padding-right: 0.75rem;
        }

        div.dataTables_wrapper div.dataTables_info {
            padding-top: 0;
        }
    </style>
@endpush
@section('content')
    <div class="col maxw-768 mx-auto mt-xl-4 mt-md-3 mt-2">
        <div class="card">
            <div class="card-header">

                <h3><i class="fa fa-money-bill-alt"></i>Receive Payments</h3>
            </div>
            <div class="card-body">
                @include('includes.partials.messages')

                <ul class="nav nav-tabs" id="payTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="single-tab" data-toggle="tab" href="#single" role="tab"
                           aria-controls="single" aria-selected="true">Receive By Account</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="bulk-tab" data-toggle="tab" href="#bulk" role="tab" aria-controls="bulk"
                           aria-selected="false">Receive All Accounts</a>
                    </li>
                </ul>
                <div class="tab-content" id="payTabContent">
                    <div class="tab-pane fade show active" id="single" role="tabpanel" aria-labelledby="single-tab">
                        <form class="form-inline mb-1">
                            <label for="account_id" class="mr-2">Account</label>
                            {!! Form::select('account_id', $accounts, $accountid, ['class' => 'form-control custom-select','id' => 'account_id']) !!}
                            <div class="ml-auto"></div>
                            {!! link_to_route('admin.payments.create', 'New Payment', ['aid' => $accountid],
                           ['class' => 'btn btn-success btn-sm disabled', 'id' => 'btnCreate']) !!}
                        </form>
                        <div class="table-responsive">
                            <table id="payments-table"
                                   class="table table-bordered table-striped table-sm">
                                <thead class="thead-dark">
                                <tr>
                                    <th>Type&nbsp;</th>
                                    <th>Description&nbsp;</th>
                                    <th>Amount&nbsp;</th>
                                    {{--<th>Fee&nbsp;</th>--}}
                                    <th>Received&nbsp;</th>
                                    <th width="1">Actions&nbsp;</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="bulk" role="tabpanel" aria-labelledby="bulk-tab">
                        {!! Form::open(['method' => 'POST', 'route' => ['admin.payments.updateAll']]) !!}

                        <div class="alert alert-info" role="alert">
                            This function will create an adjustment record for all accounts that have a balance due.
                            Upon completion you can run the Payments by Date report for a listing of accounts that were
                            updated.
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    {!! Form::label('credit_date', 'Receive Date') !!}
                                    <div class="input-group">
                                        {!! Form::text('credit_date', $date, ['class' => 'form-control datepicker', 'required' => 'required']) !!}
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="far fa-calendar"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    {!! Form::label('pay_method', 'Payment Type') !!}
                                    {!! Form::select('pay_method', [4 => 'Adjustment'], 4, ['class' => 'form-control custom-select','id' => 'pay_method', 'disabled' => 'disabled']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('credit_desc', 'Description') !!}
                            {!! Form::text('credit_desc', 'On Tuition Bill', ['class' => 'form-control', 'maxlength' => 100]) !!}
                        </div>

                        {!! Form::submit('Credit All Balance-Due Accounts', ['class' => 'btn btn-primary']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('after-scripts')
    {!! Html::script("https://cdn.datatables.net/v/bs4/dt-1.10.16/datatables.min.js") !!}
    {!! Html::script("/js/bootstrap-datepicker.min.js") !!}
    <script>
        $(document).ready(function () {
            var $selAccount = $("select[name='account_id']").change(function (e) {
                    window.location.href = window.location.origin + window.location.pathname + '?aid=' + this.value;
                }),
                $dataTable = $('#payments-table').DataTable({
                    dom: 'ti',
                    processing: false,
                    serverSide: true,
                    autoWidth: false,
                    deferLoading: 0,
                    ajax: {
                        {{--url: getUrl({!! $accountid !!}),--}}
                        type: 'get',
                        error: function (xhr, err) {
                            if (err === 'parsererror')
                                location.reload();
                        }
                    },
                    columns: [
                        {data: 'pay_method', name: 'pay_method'},
                        {data: 'credit_desc', name: 'credit_desc'},
                        {data: 'credit_amt', name: 'credit_amt'},
//                        {data: 'fee', name: 'fee'},
                        {data: 'credit_date', name: 'credit_date'},
                        {data: 'actions', name: 'actions', sortable: false}
                    ],
                    order: [[3, 'asc']],
                    lengthMenu: [[-1], ['All']],
                    language: {
                        emptyTable: '&nbsp;'
                    }
                });

            function getUrl(aid) {
                return '{!! url("admin/payments") !!}/' + aid
            }

            if ($selAccount.val() > 0) {
                $('#btnCreate').removeClass('disabled');
                $dataTable.settings()[0].oLanguage.sEmptyTable = 'No payments found';
                $dataTable.ajax.url(getUrl({!! $accountid !!})).load();
                $dataTable.settings()[0].oFeatures.bServerSide = false;
            }

            $('.datepicker').datepicker({
                format: "mm/dd/yyyy",
                autoclose: true,
                daysOfWeekDisabled: "0,6",
                orientation: "bottom left",
            });
        });
    </script>
@endpush
