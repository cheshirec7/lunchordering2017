@extends('layouts.app')
@section('title', 'Schedule Lunches :: '.config('app.name'))
@push('after-styles')
    <style>
        .scheduling .header {
            height: 43px;
            margin: 0 5px;
        }

        .scheduling .header h3 {
            float: left;
            margin: 4px 0 0;
            padding: 0;
        }

        .scheduling .header svg {
            margin-right: 10px;
        }

        .scheduling #monthyear {
            color: #5790eb;
            float: right;
            line-height: 30px;
            margin: 6px 10px 0;
        }

        .scheduling .navbtn {
            background: url("/img/nav-small.png") no-repeat scroll 0 0 transparent;
            width: 36px;
            height: 36px;
            cursor: pointer;
            float: right;
            opacity: 0.9;
        }

        .scheduling .navbtn.prev {
            background-position: 0 0;
        }

        .scheduling .navbtn.prev:hover {
            /*background-position: 0 -73px;*/
            opacity: 1;
        }

        .scheduling .navbtn.next {
            background-position: 0 -36px;
        }

        .scheduling .navbtn.next:hover {
            /*background-position: 0 -109px;*/
            opacity: 1;
        }

        .scheduling .table {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.16), 0 0 0 1px rgba(0, 0, 0, 0.04);
            background-color: #f8f8f8;
        }

        .scheduling .table .thead-dark th {
            background-color: #427cd8;
            background-image: linear-gradient(to bottom, #93caff 0, #003db8 100%);
            background-repeat: repeat-x;
            color: #fff;
            border: 1px solid #777;
            text-shadow: 1px 1px #222;
            font-variant: small-caps;
            text-align: center;
        }

        .scheduling .table td {
            width: 1%;
            position: relative;
            text-align: center;
            font-size: 13px;
            line-height: 15px;
            padding: 15px 10px 10px;
            height: 120px;
            opacity: 0.5;
            background-clip: padding-box;
        }

        .scheduling .table td div {
            margin-bottom: 5px;
        }

        .scheduling .table td img {
            max-height: 75px;
            max-width: 100px;
        }

        .scheduling .table td img + div {
            margin-top: 10px;
        }

        .scheduling .table tr:last-child td {
            height: 15px;
        }

        .scheduling .table td:nth-child(1),
        .scheduling .table td:nth-child(7) {
            width: 0.01%;
            padding: 0;
        }

        .scheduling td span {
            position: absolute;
            right: 6px;
            top: 4px;
            color: #4285f4;
            font-size: 14px;
        }

        .scheduling .table td.enabled {
            background-color: #fff;
            opacity: 1;
        }

        .scheduling td.enabled:hover {
            cursor: pointer;
            background-color: #eff3f9;
        }

        .scheduling .enabled .oc {
            color: #080;
        }

        .scheduling .enabled .nle {
            color: red;
        }

    </style>
@endpush
@section('content')
    <div class="col maxw-1200 mx-auto mt-xl-4 mt-2 pr-4 pl-4 scheduling">
        @include('includes.partials.messages')
        <div class="table-responsive">
            <div class="header">
                {!! link_to_route('admin.lunchdates.show', '', $nextmonth->format('Ym'), ['class' => 'navbtn next']) !!}
                <h3 id="monthyear">{!! $startmonth->format('F Y') !!}</h3>
                {!! link_to_route('admin.lunchdates.show', '', $prevmonth->format('Ym'), ['class' => 'navbtn prev']) !!}
                <h3><i class="far fa-calendar"></i>Schedule Lunches</h3>
            </div>
            <table id="scheduling-table" class="table table-bordered table-sm">
                <thead class="thead-dark">
                <tr>
                    <th>Sun</th>
                    <th>Monday</th>
                    <th>Tuesday</th>
                    <th>Wednesday</th>
                    <th>Thursday</th>
                    <th>Friday</th>
                    <th>Sat</th>
                </tr>
                </thead>
                <tbody>
                {!! $thetable !!}
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
@push('after-scripts')
    <script>
        $(document).ready(function () {
            $('td.enabled').click(function () {
                location.href = '/admin/lunchdates/' + $(this).data('date') + '/edit';
            });
        });
    </script>
@endpush
