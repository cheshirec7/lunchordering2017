@extends('layouts.app')
@section('content')
    {!! Form::open(['method' => 'POST', 'route' => ['admin.abilities.store']]) !!}

    {!! Form::text('name', old('name'), ['class' => 'form-control', 'required' => 'required']) !!}
    {!! Form::label('name', 'Name') !!}

    {!! Form::submit(trans('global.app_save'), ['class' => 'btn btn-primary']) !!}
    {!! Form::close() !!}
@endsection

