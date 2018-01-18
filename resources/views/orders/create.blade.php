@extends('layouts.app')
@section('title', 'Order Lunch :: '.config('app.name'))
@push('after-styles')
    <link rel="stylesheet" href="{!! mix('css/orderlunches.css') !!}">
@endpush
@section('content')
    <div class="col mx-auto mt-xl-4 mt-2 orderlunches">
        <div class="card">
            <img class="provider" src="/img/providers/{!! $lunchdate->provider_image !!}">
            <div class="order-header">
                Order Lunch
            </div>
            <div class="date-user-header">
                @if(!empty($avatar))
                    <img class="avatar" src="{!! $avatar !!}" alt="User Image"/>
                @endif
                {!! $orderdate->format('l, F jS, Y') !!}<br/>{!! $user->first_last !!}
            </div>

            {!! Form::open(['method' => 'POST', 'route' => ['orders.store']]) !!}
            {!! Form::hidden('uid', $user->id) !!}
            {!! Form::hidden('aid', $accountid) !!}
            {!! Form::hidden('date', $orderdate->format('Ymd')) !!}

            <div class="card-body">
                @include('includes.partials.messages')

                <div class="row">
                    <div class="col-md-6">
                        <table class="lunchestable">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Qty</th>
                            </tr>
                            </thead>
                            <tbody>
                            {!! $checkeditems !!}
                            {!! $uncheckeditems1 !!}
                            </tbody>
                        </table>

                    </div>
                    <div class="col-md-6">
                        <table class="lunchestable col2">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Qty</th>
                            </tr>
                            </thead>
                            <tbody>
                            {!! $uncheckeditems2 !!}
                            </tbody>
                        </table>
                    </div>
                </div>

                <hr/>
                <div class="totals">
                    <div id="ordertotal" class="float-right">$0.00</div>
                    <div id="ordertotaltext">Order Total</div>
                </div>
                <div class="lunchincludes">- {{ $lunchdate->provider_includes }} -</div>
            </div>
            <div class="card-footer">
                {!! link_to('orders/'.$orderdate->startOfWeek()->format('Ymd').'?aid='.$accountid.'&vb='.session('viewby'), 'Cancel', ['class' => 'btn a-button btn-cancel float-right']) !!}
                {!! Form::submit('Save', ['class' => 'btn btn-primary ']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
@push('after-scripts')
    <script>
        $(document).ready(function () {
            var $checks = $('.custom-control-input', '.lunchestable').change(function () {
                    var $qty = $("#qty" + this.value);
                    $qty.prop('disabled', !this.checked);
                    this.checked ? $qty.val(1) : $qty.val('');
                    recalcTotal();
                }),
                $qtys = $('input[type="number"]', '.lunchestable').change(function (e) {
                    var $this = $(this);
                    if ($this.val() < 1 || $this.val() > 2) {
                        $this.val(1);
                    }
                    recalcTotal();
                });

            function recalcTotal() {
                var total = 0;
                $("input[type='number']").each(function (index) {
                    var $this = $(this);
                    if (!$this.is(':disabled')) {
                        total += $this.val() * $this.data('price');
                    }
                }).promise().done(function () {
                    total /= 100;
                    $('#ordertotal').text('$' + total.toFixed(2));
                });
            }

            recalcTotal();
        });
    </script>
@endpush