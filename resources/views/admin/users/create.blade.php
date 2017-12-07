@extends('layouts.app')
@section('title', 'New User :: '.config('app.name'))
@section('content')
    <div class="col maxw-500 mx-auto mt-xl-5 mt-md-3 mt-2">
        <div class="card">
            <div class="card-header">
                <h3><i class="fa fa-plus"></i>New User</h3>
            </div>
            {!! Form::open(['method' => 'POST', 'route' => ['admin.users.store']]) !!}
            <div class="card-body">
                @include('includes.partials.messages')

                <div class="form-group">
                    {!! Form::label('account', 'Account') !!}
                    {!! Form::select('account', $account, $accountid, ['class' => 'form-control custom-select', 'id' => 'account_id', 'disabled' => 'disabled']) !!}
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
                    {!! Form::select('user_type', [3 => 'Student', 4 => 'Teacher',5 => 'Staff', 6 => 'Parent', 2 => 'Admin' ], 3, ['class' => 'form-control custom-select', 'id' => 'user_type']) !!}
                </div>

                <div class="form-group grade">
                    {!! Form::label('grade_id', 'Grade') !!}
                    {!! Form::select('grade_id', $gradelevels, null, ['class' => 'form-control custom-select', 'id' => 'grade_id']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('allowed_to_order', 'Allowed to Order') !!}
                    {!! Form::select('allowed_to_order', [1 => 'Yes', 0 => 'No'], 1, ['class' => 'form-control custom-select', 'id' => 'allowed_to_order']) !!}
                </div>

                {!! Form::hidden('account_id', $accountid)!!}

            </div>
            <div class="card-footer">
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                {!! link_to('admin/users?aid='.$accountid, 'Cancel', ['class' => 'btn btn-cancel']) !!}
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
        });
    </script>
@endpush