@extends('layouts.app')
@section('content')
    {!! Form::open(['method' => 'POST', 'route' => ['admin.roles.store']]) !!}

    {!! Form::text('name', old('name'), ['class' => 'form-control', 'required' => 'required']) !!}
    {!! Form::label('name', 'Name') !!}

    {!! Form::select('abilities[]', $abilities, old('abilities'), ['class' => 'form-control custom-select', 'multiple' => 'multiple']) !!}
    {!! Form::label('abilities', 'Abilities') !!}

    {!! Form::submit(trans('global.app_save'), ['class' => 'btn btn-primary']) !!}
    {!! Form::close() !!}
@endsection
