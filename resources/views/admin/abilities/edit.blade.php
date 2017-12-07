@extends('layouts.app')

@section('content')
    {!! Form::model($ability, ['method' => 'PUT', 'route' => ['admin.abilities.update', $ability->id]]) !!}

    {!! Form::text('name', old('title'), ['class' => 'form-control', 'required' => 'required']) !!}
    {!! Form::label('name', 'Name') !!}

    {!! Form::submit(trans('global.app_update'), ['class' => 'btn btn-primary']) !!}
    {!! Form::close() !!}
@endsection
