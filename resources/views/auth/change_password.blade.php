@extends('layouts.app')
@section('title', 'Change Password :: '.config('app.name'))
@section('content')
    <div class="col maxw-500 mx-auto mt-xl-5 mt-md-3 mt-2">
        <div class="card">
            <div class="card-body">
                <div class="my-form-header">
                    <h4><i class="fa fa-user mt-2 mb-2 mr-2"></i> Change Password</h4>
                </div>
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @else
                    @include('includes.partials.messages')

                    <div>&nbsp;</div>

                    {!! Form::open(['method' => 'PATCH', 'route' => ['auth.change_password']]) !!}

                    <div class="md-form">
                        <i class="fa fa-lock prefix"></i>
                        {!! Form::password('current_password', ['class' => 'form-control', 'required' => 'required']) !!}
                        {!! Form::label('current_password', 'Current password') !!}
                    </div>

                    <div class="md-form">
                        <i class="fa fa-lock prefix"></i>
                        {!! Form::password('new_password', ['class' => 'form-control', 'required' => 'required']) !!}
                        {!! Form::label('new_password', 'New password') !!}
                    </div>
                    <div class="md-form">
                        <i class="fa fa-lock prefix"></i>
                        {!! Form::password('new_password_confirmation', ['class' => 'form-control', 'required' => 'required']) !!}
                        {!! Form::label('new_password_confirmation', 'New password confirmation') !!}
                    </div>

                    <div class="mt-5">
                        {!! Form::submit(trans('global.app_save'), ['class' => 'btn btn-primary']) !!}
                    </div>

                    {!! Form::close() !!}
                @endif
            </div>
        </div>
    </div>
@stop

