@extends('layouts.app')@section('title', 'My Account :: '.config('app.name'))@push('after-styles')    <style>        .card-body {            padding: 1rem 1rem 1.25rem;        }        .tab-content {            margin: 0;            border: none;        }        .table {            margin-bottom: 0;        }        #summary td:nth-child(2) {            text-align: right;        }        .bottom {            margin: 0 4px;        }        hr {            margin: 1px 0 2px;            border-top: 1px dashed rgba(0, 0, 0, 0.1);        }        .curbal {            margin: 3px 0 10px;            font-weight: bold;        }        .paynow {            color: #ff5500;            font-weight: bold;        }        div.dataTables_wrapper div.dataTables_info {            padding-top: 0;        }        #payments-table td:nth-child(4) {            text-align: right;        }        #payments-table tfoot td, #orders-table tfoot td {            background-color: #ffffcc;        }        #orders-table td:nth-child(3) {            text-align: right;        }        .totalcell {            text-align: right;        }        .tab-content .tab-pane {            padding: 0;        }        table.dataTable {            margin-top: 0;        }        .nav-pills .nav-link {            border-radius: 4px;        }    </style>@endpush@section('content')    <div class="col maxw-768 mx-auto mt-xl-4 mt-md-3 mt-2">        <div class="card">            <div class="card-header">                <h3><i class="fa fa-id-card"></i>My Account</h3>            </div>            <div class="card-body">                @include('includes.partials.messages')                <ul class="nav nav-pills mb-1" id="pills-tab" role="tablist">                    <li class="nav-item">                        <a class="nav-link active" id="pills-summary-tab" data-toggle="pill" href="#pills-summary"                           role="tab" aria-controls="pills-summary" aria-selected="true">Summary</a>                    </li>                    <li class="nav-item">                        <a class="nav-link" id="pills-payments-tab" data-toggle="pill" href="#pills-payments" role="tab"                           aria-controls="pills-payments" aria-selected="false">Payments</a>                    </li>                    <li class="nav-item">                        <a class="nav-link" id="pills-orders-tab" data-toggle="pill" href="#pills-orders" role="tab"                           aria-controls="pills-orders" aria-selected="false">Orders</a>                    </li>                </ul>                <div class="tab-content" id="pills-tabContent">                    <div class="tab-pane fade show active" id="pills-summary" role="tabpanel"                         aria-labelledby="pills-summary-tab">                        {!! Form::open(['method' => 'POST', 'route' => ['myaccount.pay']]) !!}                        <input type="hidden" name="total_due" value="{!! $total_due !!}"/>                        {{--<input type="hidden" name="fee" value="{!! $trx_fee !!}"/>--}}                        <table id="summary" class="table table-bordered table-striped table-sm">                            <thead class="thead-dark">                            <tr>                                <th>Totals</th>                                <th width="50">Amount</th>                            </tr>                            </thead>                            <tbody>                            @foreach ($order_aggs as $order_agg)                                <tr>                                    <td>{!! $order_agg->first_name !!} {!! $order_agg->last_name !!}                                        - {!! $order_agg->order_count !!} Lunches Ordered                                    </td>                                    <td>${!! number_format($order_agg->total_price/100,2) !!}</td>                                </tr>                            @endforeach                            <tr>                                <td>{!! $payment_agg->payment_count !!} Payments Received</td>                                <td>${!! number_format($payment_agg->credit_amt/100,2) !!}</td>                            </tr>                            {{--@if($payment_agg->fees > 0)--}}                            {{--<tr>--}}                            {{--<td>PayPal Fees Paid</td>--}}                            {{--<td>${!! number_format($payment_agg->fees/100,2) !!}</td>--}}                            {{--</tr>--}}                            {{--@endif--}}                            </tbody>                        </table>                        <div class="bottom">                            <div class="curbal">                                @if($cur_balance < 30 && $cur_balance > -30)                                    <div class="float-right">$0.00</div>                                    Current Balance                                @elseif($cur_balance < 0)                                    <div class="float-right" style="color:#ee0000;">                                        (${!! number_format(-$cur_balance/100,2) !!})                                    </div>                                    Current Account Balance                                @else                                    <div style="color: green;">                                        <div class="float-right">${!! number_format($cur_balance/100,2) !!}</div>                                        You Have an Account Credit                                    </div>                                @endif                            </div>                            @if($total_due > 100)                                <i>                                    <div class="float-right ml-3">${!! number_format($trx_fee/100,2) !!}</div>                                    When paying with PayPal add 2.2% + $0.30 transaction fee                                </i>                                <hr/>                                <div class="paynow">                                    <div class="float-right ml-3">                                        ${!! number_format($total_due/100,2) !!}</div>                                    <div class="pull">Amount Due</div>                                </div>                                <div class="mt-1">                                    <input class="float-right" type="image" name="submit"                                           src="{!! asset ('img/checkout-logo-small.png') !!}"/>                                </div>                            @endif                        </div>                        {!! Form::close() !!}                    </div>                    <div class="tab-pane fade" id="pills-payments" role="tabpanel" aria-labelledby="pills-payments-tab">                        <div class="table-responsive">                            <table id="payments-table" class="table table-bordered table-striped table-sm">                                <thead class="thead-dark">                                <tr>                                    <th>Type&nbsp;</th>                                    <th>Description&nbsp;</th>                                    <th>Received On&nbsp;</th>                                    <th>Amount&nbsp;</th>                                </tr>                                </thead>                                <tfoot>                                <tr>                                    <td colspan="3">Total</td>                                    <td class="totalcell"></td>                                </tr>                                </tfoot>                            </table>                        </div>                    </div>                    <div class="tab-pane fade" id="pills-orders" role="tabpanel" aria-labelledby="pills-orders-tab">                        <hr/>                        <div class="row mb-2 mt-2">                            <div class="mr-3 ml-3">                                {!! Form::select('user_id', $users, null, ['class' => 'form-control custom-select']) !!}                            </div>                        </div>                        <div class="table-responsive">                            <table id="orders-table" class="table table-bordered table-striped table-sm">                                <thead class="thead-dark">                                <tr>                                    <th width="60">Date&nbsp;</th>                                    <th>Lunches Ordered&nbsp;</th>                                    <th width="60">Amount&nbsp;</th>                                </tr>                                </thead>                                <tfoot>                                <tr>                                    <td colspan="2">Total</td>                                    <td class="totalcell"></td>                                </tr>                                </tfoot>                            </table>                        </div>                    </div>                </div>            </div>        </div>    </div>@endsection@push('after-scripts')    {!! Html::script("https://cdn.datatables.net/v/bs4/dt-1.10.16/datatables.min.js") !!}    <script>        $(document).ready(function () {            var $selUser = $("select[name='user_id']").change(function (e) {                    $dataTableUser.ajax.url('{!! route("myaccount.getMyAccountOrdersDatatable") !!}?uid=' + this.value).load();                }),                $dataTableUser = $('#orders-table').DataTable({                    dom: 'ti',                    processing: false,                    serverSide: false,                    autoWidth: false,                    ajax: {                        url: '{!! route("myaccount.getMyAccountOrdersDatatable") !!}?uid=' + $selUser.val(),                        type: 'post',                        error: function (xhr, err) {                            if (err === 'parsererror')                                location.reload();                        }                    },                    columns: [                        {data: 'order_date', name: 'order_date'},                        {data: 'short_desc', name: 'short_desc'},                        {data: 'total_price', name: 'total_price'},                    ],                    order: [[0, 'asc']],                    lengthMenu: [[-1], ['All']],                    language: {                        emptyTable: 'No orders found',                    },                    footerCallback: function (row, data, start, end, display) {                        var api = this.api(),                            intVal = function (i) {                                return typeof i === 'string' ?                                    i.replace(/[\$,]/g, '') * 1 :                                    typeof i === 'number' ?                                        i : 0;                            },                            total = api                                .column(2)                                .data()                                .reduce(function (a, b) {                                    return intVal(a) + intVal(b);                                }, 0);                        $(api.column(2).footer()).html(                            '$' + total.toFixed(2)                        );                    }                }),                $dataTablePayments = $('#payments-table').DataTable({                    dom: 'ti',                    processing: false,                    serverSide: false,                    autoWidth: false,                    ajax: {                        url: '{!! route("myaccount.getMyAccountPaymentsDatatable") !!}',                        type: 'get',                        error: function (xhr, err) {                            if (err === 'parsererror')                                location.reload();                        }                    },                    columns: [                        {data: 'pay_method', name: 'pay_method'},                        {data: 'credit_desc', name: 'credit_desc'},                        {data: 'credit_date', name: 'credit_date'},                        {data: 'credit_amt', name: 'credit_amt'},                    ],                    order: [[2, "asc"]],                    lengthMenu: [[-1], ['All']],                    language: {                        emptyTable: 'No payments found'                    },                    footerCallback: function (row, data, start, end, display) {                        var api = this.api(),                            intVal = function (i) {                                return typeof i === 'string' ?                                    i.replace(/[\$,]/g, '') * 1 :                                    typeof i === 'number' ?                                        i : 0;                            },                            total = api                                .column(3)                                .data()                                .reduce(function (a, b) {                                    return intVal(a) + intVal(b);                                }, 0);                        $(api.column(3).footer()).html(                            '$' + total.toFixed(2)                        );                    }                });        });    </script>@endpush