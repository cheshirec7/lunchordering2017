@extends('layouts.app')
@section('title', 'Edit Lunch Exception :: '.config('app.name'))
@push('after-styles')
    {!! Html::style("/css/bootstrap-datepicker3.min.css") !!}
@endpush
@section('content')
    <div class="col maxw-500 mx-auto mt-xl-5 mt-md-3 mt-2">
        <div class="card">
            <div class="card-header">
                <h3><i class="fa fa-pen-square"></i>Edit Lunch Exception</h3>
            </div>
            {!! Form::model($nle, ['method' => 'PUT', 'route' => ['admin.nolunchexceptions.update', $nle->id]]) !!}
            <div class="card-body">
                @include('includes.partials.messages')

                <div class="form-group">
                    {!! Form::label('exception_date', 'Exception Date') !!}
                    <div class="input-group">
                        @if ($disabled)
                            {!! Form::text('exception_date', null, ['class' => 'form-control datepicker', 'disabled' => 'disabled']) !!}
                        @else
                            {!! Form::text('exception_date', null, ['class' => 'form-control datepicker', 'required' => 'required']) !!}
                        @endif
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="far fa-calendar"></i></span>
                        </div>
                    </div>
                </div>

                <div class="form-group grade">
                    {!! Form::label('grade_id', 'Grade') !!}
                    @if ($disabled)
                        {!! Form::select('grade_id', $gradelevels, $nle->grade_id, ['class' => 'form-control custom-select', 'id' => 'grade_id', 'disabled' => 'disabled']) !!}
                    @else
                        {!! Form::select('grade_id', $gradelevels, $nle->grade_id, ['class' => 'form-control custom-select', 'id' => 'grade_id', 'required' => 'required']) !!}
                    @endif
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
                {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
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
            }).datepicker('update', '{!! $nle->exception_date->format('m/d/Y') !!}');
        });
    </script>
@endpush