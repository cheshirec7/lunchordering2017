@extends('layouts.app')
@section('title', 'Login :: '.config('app.name'))
@push('after-styles')
    <style>
        .outer_or {
            position: relative;
        }

        .inner_or {
            position: absolute;
            top: -10px;
            left: calc(50% - 18px);
            background-color: #fff;
            padding: 0 10px;
            font-style: italic;
            font-size: 12px;
        }

        img.fb {
            width: 40px;
        }
    </style>
@endpush
@section('content')
    <div class="col maxw-500 mx-auto mt-xl-5 mt-md-3 mt-2">
        <div class="card">
            <div class="card-header">
                <h3><i class="fa fa-user"></i>Login</h3>
            </div>

            {!! Form::open(['route' => 'login']) !!}
            <div class="card-body">

                @include('includes.partials.messages')

                <div class="d-flex mx-auto">
                    <a class="mx-auto" href="{!! route('gotoFacebook') !!}">
                        <img class="fb" src="{!! asset('img/facebook-login.png') !!}"/>
                    </a>
                </div>

                <div class="outer_or">
                    <hr style="margin-top: 20px;">
                    <div class="inner_or">or</div>
                </div>

                <div class="form-group">
                    {!! Form::label('email', 'Email') !!}
                    {!! Form::email('email', null, ['class' => 'form-control', 'maxlength' => 191, 'required' => 'required', 'autofocus' => 'autofocus']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('password', 'Password') !!}
                    {!! Form::password('password', ['class' => 'form-control', 'required' => 'required']) !!}
                </div>

                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="remember" id="remember">
                    <label class="custom-control-label" for="remember">Remember me&nbsp;&nbsp;<i>(do not use on shared
                            devices)</i></label>
                </div>
            </div>
            <div class="card-footer">
                {!! link_to_route('password.request', 'Forgot Your Password?', [], ['class' => 'btn btn-light float-right']) !!}
                {!! Form::submit('Login', ['class' => 'btn btn-primary']) !!}
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection
