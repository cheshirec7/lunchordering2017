@extends('layouts.app')
@section('title', 'Edit User :: '.config('app.name'))
@section('content')
    <div class="col maxw-500 mx-auto mt-xl-5 mt-md-3 mt-2">
        <div class="card">
            <div class="card-header">
                <h3><i class="fa fa-pen-square"></i>Edit User</h3>
            </div>
            {!! Form::model($user, ['method' => 'PUT', 'route' => ['admin.users.update', $user->id]]) !!}
            <div class="card-body">
                @include('includes.partials.messages')

                <div class="form-group">
                    {!! Form::label('account_id', 'Account') !!}
                    {!! Form::select('account_id', $account, $user->account_id, ['class' => 'form-control custom-select', 'id' => 'account_id', 'disabled' => 'disabled']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('last_name', 'Last Name') !!}
                    {!! Form::text('last_name', null, ['class' => 'form-control', 'maxlength' => 191, 'required' => 'required', 'autofocus' => 'autofocus']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('first_name', 'First Name') !!}
                    {!! Form::text('first_name', null, ['class' => 'form-control', 'maxlength' => 191, 'required' => 'required']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('user_type', 'Type') !!}
                    {!! Form::select('user_type', [3 => 'Student', 4 => 'Teacher', 5 => 'Staff', 6 => 'Parent', 2 => 'Admin' ], $user->user_type, ['class' => 'form-control custom-select', 'id' => 'user_type']) !!}
                </div>

                <div class="form-group grade">
                    {!! Form::label('grade_id', 'Grade') !!}
                    {!! Form::select('grade_id', $gradelevels, $user->grade_id, ['class' => 'form-control custom-select', 'id' => 'grade_id']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('allowed_to_order', 'Allowed to Order') !!}
                    {!! Form::select('allowed_to_order', [1 => 'Yes', 0 => 'No'], $user->allowed_to_order, ['class' => 'form-control custom-select','id' => 'allowed_to_order']) !!}
                </div>

            </div>
            <div class="card-footer">
                {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
                {!! link_to('admin/users?aid='.$user->account_id, 'Cancel', ['class' => 'btn btn-cancel']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
@push('after-scripts')
    <script>
        $(document).ready(function () {
            var $usertype_sel = $('#user_type').change(function (a) {

                if ($usertype_sel.val() === "3") {
                    $('.form-group.grade').show();
                } else {
                    $('.form-group.grade').hide();
                }
            });

            $usertype_sel.trigger('change');
        });
    </script>
@endpush

