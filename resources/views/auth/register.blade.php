@extends('layouts.app')
@section('title', 'Register :: '.config('app.name'))
@section('content')
    <div class="col maxw-500 mx-auto mt-xl-5 mt-md-3 mt-2">
        <div class="card">
            <div class="card-header">
                <h3><i class="fa fa-user"></i>Register</h3>
            </div>
            {!! Form::open(['route' => 'register']) !!}
            <div class="card-body">
                @include('includes.partials.messages')
                <div class="form-group">
                    {!! Form::label('name', 'Your name') !!}
                    {!! Form::text('name', null, ['class' => 'form-control', 'maxlength' => 191, 'required' => 'required', 'autofocus' => 'autofocus']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('email', 'Your email') !!}
                    {!! Form::email('email', null, ['class' => 'form-control', 'maxlength' => 191, 'required' => 'required']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('password', 'Password') !!}
                    {!! Form::password('password', ['class' => 'form-control', 'required' => 'required']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('password_confirmation', 'Confirm password') !!}
                    {!! Form::password('password_confirmation', ['class' => 'form-control', 'required' => 'required'], ['id' => 'password_confirm']) !!}
                </div>
            </div>

            <div class="card-footer">
                {!! Form::submit('Register', ['class' => 'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
