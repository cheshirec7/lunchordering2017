@extends('layouts.app')
@section('content')
    {!! Form::model($role, ['method' => 'PUT', 'route' => ['admin.roles.update', $role->id]]) !!}

    {!! Form::label('name', 'Name*', ['class' => 'control-label']) !!}
    {!! Form::text('name', old('name'), ['class' => 'form-control', 'required' => 'required']) !!}

    {!! Form::select('abilities[]', $abilities, old('abilities') ? old('abilities') :
        $role->getAbilities()->pluck('name', 'name'), ['class' => 'form-control custom-select', 'multiple' => 'multiple']) !!}
    {!! Form::label('abilities', 'Abilities') !!}

    {!! Form::submit(trans('global.app_update'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
@endsection

