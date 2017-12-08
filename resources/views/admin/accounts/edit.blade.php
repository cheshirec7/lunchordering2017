@extends('layouts.app')
@section('title', 'Edit Account :: '.config('app.name'))
@section('content')
    <div class="col maxw-500 mx-auto mt-xl-5 mt-md-3 mt-2">
        <div class="card">
            {!! Form::model($account, ['method' => 'PUT', 'route' => ['admin.accounts.update', $account->id]]) !!}
            <div class="card-header">
                <h3><i class="fa fa-pen-square"></i>Edit Account</h3>
            </div>

            <div class="card-body">
                @include('includes.partials.messages')

                <div class="form-group">
                    {!! Form::label('account_name', 'Account Name (Last, First Names)') !!}
                    {!! Form::text('account_name', null, ['class' => 'form-control', 'maxlength' => 191, 'required' => 'required', 'autofocus' => 'autofocus']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('email', 'Email') !!}
                    {!! Form::email('email', null, ['class' => 'form-control', 'maxlength' => 191, 'required' => 'required']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('role_id', 'Role') !!}
                    {!! Form::select('role_id', $roles, $account_role, ['class' => 'form-control custom-select', 'id' => 'role_id']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('allow_new_orders', 'Allow New Orders') !!}
                    {!! Form::select('allow_new_orders', [1 => 'Yes', 0 => 'No'], $account->allow_new_orders, ['class' => 'form-control custom-select', 'id' => 'allow_new_orders']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('active', 'Account Status') !!}
                    {!! Form::select('active', [1 => 'Active', 0 => 'Disabled'], $account->active, ['class' => 'form-control custom-select', 'id' => 'active']) !!}
                </div>

            </div>
            <div class="card-footer">
                {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
                {!! link_to('admin/accounts', 'Cancel', ['class' => 'btn btn-cancel']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
