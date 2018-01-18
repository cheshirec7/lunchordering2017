@extends('layouts.app')
@section('title', 'Create Lunch Exception :: '.config('app.name'))
@push('after-styles')
    {!! Html::style("/css/bootstrap-datepicker3.min.css") !!}
@endpush
@section('content')
    <div class="col maxw-500 mx-auto mt-xl-5 mt-md-3 mt-2">
        <div class="card">
            <div class="card-header">
                <h3><i class="fa fa-plus"></i>Create Lunch Exception</h3>
            </div>
            {!! Form::open(['method' => 'POST', 'route' => ['admin.nolunchexceptions.store']]) !!}
            <div class="card-body">
                @include('includes.partials.messages')

                <div class="form-group">
                    {!! Form::label('exception_date', 'Exception Date') !!}
                    <div class="input-group">
                        {!! Form::text('exception_date', null, ['class' => 'form-control datepicker', 'required' => 'required']) !!}
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="far fa-calendar"></i></span>
                        </div>
                    </div>
                </div>

                <div class="form-group grade">
                    {!! Form::label('grade_id', 'Grade') !!}
                    {!! Form::select('grade_id', $gradelevels, null, ['class' => 'form-control custom-select', 'required' => 'required', 'id' => 'grade_id']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('reason', 'Reason') !!}
                    {!! Form::text('reason', null, ['class' => 'form-control', 'maxlength' => 30, 'required' => 'required']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('description', 'Description') !!}
                    {!! Form::text('description', null, ['class' => 'form-control', 'maxlength' => 50, 'required' => 'required']) !!}
                </div>

            </div>
            <div class="card-footer">
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                {!! link_to('admin/nolunchexceptions', 'Cancel', ['class' => 'btn btn-cancel']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
@push('after-scripts')
    {!! Html::script("/js/bootstrap-datepicker.min.js") !!}
    <script>
        $(document).ready(function ($) {
            $('.datepicker').datepicker({
                format: "mm/dd/yyyy",
                autoclose: true,
                daysOfWeekDisabled: "0,6",
                orientation: "bottom left",
                startDate: "{!! $tomorrow !!}",
                endDate: "{!! config('app.last_day_of_school') !!}"
            }).datepicker('update', '{!! $date !!}');
        });
    </script>
@endpush