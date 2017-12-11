@extends('layouts.app')
@section('title', 'Order Lunches :: '.config('app.name'))
@push('after-styles')
    <link rel="stylesheet" href="{!! mix('css/ordermonth.css') !!}">
@endpush
@section('content')
    <div class="col maxw-1200 mx-auto mt-xl-4 mt-md-2 ordermonth">
        @include('includes.partials.messages')
        <div class="outer">
            @if($accounts)
                <form class="form-inline">
                    <label for="account_id" class="mr-3">Order Lunches for Account</label>
                    {!! Form::select('account_id', $accounts, $accountid, ['class' => 'form-control custom-select']) !!}
                    @if($avatar)
                        <img class="avatar" src="{!! $avatar !!}" height="35" alt="User Image"/>
                    @endif
                </form>
            @endif
            <div class="header">
                {!! link_to_route('orders.show', '', $prevmonth->format('Ymd').'?aid='.$accountid.'&vb=m', ['class' => 'navbtn prev', 'title' => 'Previous month']) !!}
                <div>
                    <div @if($showviewby) style="margin-left:14px;" @endif>
                        {!! $curmonth->format('F Y') !!}
                        @if($showviewby)
                            <div class="dropdown">
                                <a class="btn dropdown-toggle" href="#" role="button" id="viewBy" data-toggle="dropdown"
                                   aria-haspopup="true" aria-expanded="false">
                                    {{--<i class="far fa-calendar"></i>--}}
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="viewBy">
                                    {!! link_to_route('orders.show', 'View by Week', $curmonth->format('Ymd').'?aid='.$accountid.'&vb=w', ['class' => 'dropdown-item']) !!}
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="title">{{ $user->first_last }}</div>
                </div>
                {!! link_to_route('orders.show', '', $nextmonth->format('Ymd').'?aid='.$accountid.'&vb=m', ['class' => 'navbtn next', 'title' => 'Next month']) !!}
            </div>
            <table class="table table-bordered table-sm lunchestable">
                <tr class="lunchdates">
                    <th>Mon</th>
                    <th>Tue</th>
                    <th>Wed</th>
                    <th>Thu</th>
                    <th>Fri</th>
                </tr>
                {!! $thetable !!}
                <tr>
                    <td colspan="5" class="text-left">
                        <div class="todaybox"></div>
                        Today is {!! link_to_route('orders.show', \Carbon\Carbon::today()->format('l, F jS, Y'),
                            $curmonth->format('Ymd').'?aid='.$accountid) !!}
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
                window.location.href = window.location.origin + window.location.pathname + '?vb=m&aid=' + this.value;
            });
        });
    </script>
@endpush