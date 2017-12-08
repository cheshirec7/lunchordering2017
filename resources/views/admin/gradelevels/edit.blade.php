@extends('layouts.app')
@section('title', 'Edit Grade Level :: '.config('app.name'))
@section('content')
    <div class="col maxw-500 mx-auto mt-xl-5 mt-md-3 mt-2">
        <div class="card">
            <div class="card-header">
                <h3><i class="fa fa-pen-square"></i>Edit Grade Level</h3>
            </div>
            {!! Form::model($gradelevel, ['method' => 'PUT', 'route' => ['admin.gradelevels.update', $gradelevel->id]]) !!}
            <div class="card-body">
                @include('includes.partials.messages')

                <div class="form-group">
                    {!! Form::label('grade', 'Grade') !!}
                    {!! Form::text('grade', null, ['class' => 'form-control', 'maxlength' => 10, 'required' => 'required', 'autofocus' => 'autofocus']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('grade_desc', 'Grade Description') !!}
                    {!! Form::text('grade_desc', null, ['class' => 'form-control', 'maxlength' => 50, 'required' => 'required']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('report_order', 'Report Order') !!}
                    {!! Form::number('report_order', null, ['class' => 'form-control', 'min' => 1, 'required' => 'required']) !!}
                </div>
            </div>
            <div class="card-footer">
                {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
                {!! link_to('admin/gradelevels', 'Cancel', ['class' => 'btn btn-cancel']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
