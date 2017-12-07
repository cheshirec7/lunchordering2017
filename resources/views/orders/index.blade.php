@extends('layouts.app')
@section('title', 'Order Lunches :: '.config('app.name'))
@push('after-styles')
    <link rel="stylesheet" href="{!! mix('css/ordering.css') !!}">
@endpush
@section('content')
    <div class="col maxw-1200 mx-auto mt-xl-4 mt-md-2 ordering">
        @include('includes.partials.messages')
        <div class="outer">
            @if($accounts)
                <form class="form-inline">
                    <label for="account_id" class="mr-3">Order Lunches for Account</label>
                    {!! Form::select('account_id', $accounts, $accountid, ['class' => 'form-control custom-select']) !!}
                </form>
            @endif
            <div class="ordering-header">
                {!! link_to_route('orders.show', '', $prevweek->format('Ymd').'?aid='.$accountid, ['class' => 'navbtn prev', 'title' => 'Previous week']) !!}
                <div>
                    @if(!$accounts)
                        <div class="title">Order Lunches</div>
                    @endif
                    <div>{!! $daterange !!}</div>
                </div>
                {!! link_to_route('orders.show', '', $nextweek->format('Ymd').'?aid='.$accountid, ['class' => 'navbtn next', 'title' => 'Next week']) !!}
            </div>
            <table class="table table-bordered table-sm lunchestable">
                {!! $thetable !!}
                <tr>
                    <td colspan="6" class="text-left">
                        <div class="box"></div>
                        Today is {!! link_to_route('orders.show', \Carbon\Carbon::today()->format('l, F jS, Y'),
                        $curweek->format('Ymd').'?aid='.$accountid) !!}
                    </td>
                </tr>
            </table>
        </div>
    </div>
@endsection
@push('after-scripts')
    <script>
        $(document).ready(function () {
            var $selAccount = $("select[name='account_id']").change(function (e) {
                window.location.href = window.location.origin + window.location.pathname + '?aid=' + this.value;
            });
//            $('td.enabled').click(function () {
//                alert('clicked');
//                  location.href = '/admin/orders/' + $(this).data('date') + '/edit';
//            });
        });
    </script>
@endpush