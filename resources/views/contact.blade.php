@extends('layouts.app')
@section('title', 'Contact Us :: '.config('app.name'))
@section('content')
    <div class="col maxw-500 mx-auto mt-xl-5 mt-md-3 mt-2">
        <div class="card">
            <div class="card-header">
                <h3><i class="fa fa-envelope"></i>Contact Us</h3>
            </div>
            {!! Form::open(['route' => 'contact.send']) !!}
            <div class="card-body">

                @include('includes.partials.messages')

                <div class="form-group">
                    {!! Form::label('name', 'Your name') !!}
                    {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required', 'autofocus' => 'autofocus']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('email', 'Your email') !!}
                    {!! Form::email('email', null, ['class' => 'form-control', 'required' => 'required']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('message', 'Your message') !!}
                    {!! Form::textarea('message', null, ['class' => 'form-control', 'id' => 'message', 'rows' => 5]) !!}
                </div>

                @if (config('no-captcha.use_captcha_on_contact_form'))
                    <div class="form-group">
                        {!! Form::captcha() !!}
                        {!! Form::hidden('captcha_status', 'true') !!}
                    </div>
                @endif

                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="sendcopy" id="sendcopy">
                    <label class="custom-control-label" for="sendcopy">Send a copy to my email address</label>
                </div>
            </div>

            <div class="card-footer">
                {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection
@push('after-scripts')
    @if (config('no-captcha.use_captcha_on_contact_form'))
        {!! Captcha::script() !!}
    @endif
@endpush