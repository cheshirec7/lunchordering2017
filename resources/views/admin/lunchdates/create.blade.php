@extends('layouts.app')
@section('title', 'New Lunch Date :: '.config('app.name'))
@push('after-styles')
    <style>
        .scheduling-header {
            background-color: #427cd8;
            background-image: linear-gradient(to bottom, #93caff 0, #003db8 100%);
            background-repeat: repeat-x;
            color: #fff;
            border: 1px solid #777;
            text-shadow: 1px 1px #222;
            font-variant: small-caps;
            text-align: center;
            padding: 3px 5px;
            font-size: 16px;
            font-family: Vollkorn, serif;
        }

        .date-header {
            padding: 5px;
            color: #900;
            background-color: #f0f3f5;
            text-align: center;
            border-bottom: 1px solid #c2cfd6;
        }

        #scrollbox {
            height: 150px;
            overflow-y: scroll;
            border: 1px solid #c2cfd6;
            padding: 5px 8px;
            color: #4285f4;
        }

        #scrollbox label {
            margin: 0;
        }

        #scrollbox .custom-control {
            display: block;
        }
    </style>
@endpush
@section('content')
    <div class="col maxw-500 mx-auto mt-xl-5 mt-md-3 mt-2">
        <div class="card">
            <div class="scheduling-header">
                Lunch Provider Scheduling
            </div>
            <div class="date-header">
                {!! $providedate->format('l, F jS, Y') !!}
            </div>
            {!! Form::open(['method' => 'POST', 'route' => ['admin.lunchdates.store']]) !!}
            <div class="card-body">
                @include('includes.partials.messages')

                <div class="form-group">
                    {!! Form::label('provider_id', 'Provider') !!}
                    {!! Form::select('provider_id', $providers, null, ['class' => 'form-control custom-select', 'id' => 'provider_id', 'required' => 'required']) !!}
                </div>

                <div id="menuitemscontainer">
                    <div class="form-group">
                        {!! Form::label('menuitems', 'Lunches Available') !!}
                        <div id="scrollbox">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('additional_text', 'Additional Message (Thanksgiving, etc.) (opt.)') !!}
                    {!! Form::text('additional_text', null, ['class' => 'form-control', 'maxlength' => 50]) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('extended_care_text', 'Extended Care Message (No Extended Care, etc.) (opt.)') !!}
                    {!! Form::text('extended_care_text', null, ['class' => 'form-control', 'maxlength' => 50]) !!}
                </div>

                {!! Form::hidden('provide_date', $providedate->toDateString()) !!}

            </div>
            <div class="card-footer">
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                {!! link_to('admin/lunchdates/'.$providedate->format('Ym'), 'Cancel', ['class' => 'btn btn-cancel']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
@push('after-scripts')
    <script>
        $(document).ready(function () {
            var $scrollbox = $('#scrollbox'),
                $menuitemscontainer = $('#menuitemscontainer'),
                $selProvider = $('#provider_id').change(function () {

                    if ($selProvider.val() < 4) {
                        $scrollbox.empty();
                        $menuitemscontainer.hide();
                    } else {
                        $.ajax({
                            type: 'GET',
                            url: '{!! route("admin.lunchdates.getMenuItemsForCreate") !!}',
                            data: {
                                pid: $selProvider.val()
                            },
                            dataType: 'json'
                        }).done(function (data) {
                            if (data.error) {
                                location.reload();
                            } else {
                                $scrollbox.html(data.html);
                                $menuitemscontainer.show();
                            }
                        }).fail(function () {
                            location.reload();
                        });
                    }
                });

            $selProvider.trigger('change');
        });
    </script>
@endpush