@extends('layouts.app')
@section('title', 'Users :: '.config('app.name'))
@push('after-styles')
    <style>
        #users-table td:nth-child(6) {
            text-align: right;
            padding-right: 0.75rem;
        }
    </style>
@endpush
@section('content')
    <div class="col maxw-1024 mx-auto mt-xl-4 mt-2">
        <div class="card">
            <div class="card-header">
                <h3><i class="fa fa-users"></i>Users</h3>
            </div>
            <div class="card-body">
                @include('includes.partials.messages')

                {!! link_to_route('admin.users.create', 'New User', ['aid' => $accountid],
                    ['class' => 'btn btn-success btn-sm pull-right disabled', 'id' => 'btnCreate']) !!}

                <div class="form-group row">
                    <label for="account_id" class="col-form-label ml-3">Account</label>
                    <div class="mr-3 ml-3">
                        {!! Form::select('account_id', $accounts, $accountid, ['class' => 'form-control custom-select','id' => 'account_id']) !!}
                    </div>
                </div>
                <hr/>
                <div class="table-responsive">
                    <table id="users-table"
                           class="table table-bordered table-striped table-sm">
                        <thead class="thead-dark">
                        <tr>
                            <th>Last Name&nbsp;</th>
                            <th>First Name&nbsp;</th>
                            <th>Type&nbsp;</th>
                            <th>Grade&nbsp;</th>
                            <th width="80">Can Order&nbsp;</th>
                            <th width="1">#&nbsp;Orders&nbsp;</th>
                            <th width="1">Actions&nbsp;</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('after-scripts')
    {!! Html::script("https://cdn.datatables.net/v/bs4/dt-1.10.16/datatables.min.js") !!}
    {{--<script src="/js/datatables.min.js"></script>--}}
    {!! Html::script("js/dtExtend.js") !!}
    <script>
        $(document).ready(function () {
            var $selAccount = $("select[name='account_id']").change(function (e) {
                    window.location.href = window.location.origin + window.location.pathname + '?aid=' + this.value;
                }),
                $dataTable = $('#users-table').DataTable({
                    dom: 'lfrtip',
                    processing: false,
                    serverSide: true,
                    autoWidth: false,
                    ajax: {
                        url: getUrl({!! $accountid !!}),
                        type: 'post',
                        error: function (xhr, err) {
                            if (err === 'parsererror')
                                location.reload();
                        }
                    },
                    columns: [
                        {data: 'last_name', name: 'last_name'},
                        {data: 'first_name', name: 'first_name'},
                        {data: 'user_type', name: 'user_type', searchable: false},
                        {data: 'grade_desc', name: 'gl.grade_desc', searchable: true},
                        {data: 'allowed_to_order', name: 'allowed_to_order', searchable: false},
                        {data: 'numorders', name: 'numorders', searchable: false},
                        {data: 'actions', name: 'actions', searchable: false, sortable: false}
                    ],
                    order: [[0, 'asc']],
                    search: {caseInsensitive: !0},
                    language: {
                        emptyTable: 'No users found',
                        search: 'Search'
                    },
                    searchDelay: 500
                });

            function getUrl(aid) {
                return '{!! route("admin.users.getDatatable") !!}?aid=' + aid;
            }

            if ($selAccount.val() > 0) {
                $('#btnCreate').removeClass('disabled');
            }

        });
    </script>
@endpush
