@extends('layouts.app')
@section('title', 'Edit Provider :: '.config('app.name'))
@section('content')
    <div class="col maxw-500 mx-auto mt-xl-5 mt-md-3 mt-2">
        <div class="card">
            <div class="card-header">
                <h3><i class="fa fa-pen-square"></i>Edit Provider</h3>
            </div>
            {!! Form::model($provider, ['method' => 'PUT', 'route' => ['admin.providers.update', $provider->id]]) !!}
            <div class="card-body">
                @include('includes.partials.messages')

                <div class="form-group">
                    {!! Form::label('provider_name', 'Provider Name') !!}
                    {!! Form::text('provider_name', null, ['class' => 'form-control', 'maxlength' => 50, 'required' => 'required', 'autofocus' => 'autofocus']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('provider_image', 'Image') !!}
                    {!! Form::select('provider_image', $imagefiles, $provider->provider_image, ['class' => 'form-control custom-select', 'id' => 'provider_image']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('provider_url', 'URL') !!}
                    {!! Form::text('provider_url', null, ['class' => 'form-control', 'maxlength' => 191, 'required' => 'required']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('provider_includes', 'Included With Lunches Message') !!}
                    {!! Form::textarea('provider_includes', null, ['class' => 'form-control', 'maxlength' => 191,  'id' => 'provider_includes', 'rows' => 5]) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('allow_orders', 'Allow New Orders') !!}
                    {!! Form::select('allow_orders', [1 => 'Yes', 0 => 'No'], $provider->allow_orders, ['class' => 'form-control custom-select','id' => 'allow_orders']) !!}
                </div>

            </div>
            <div class="card-footer">
                {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
                {!! link_to('admin/providers', 'Cancel', ['class' => 'btn btn-cancel']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
