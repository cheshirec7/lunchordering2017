@extends('layouts.app')
@section('title', 'Accounts :: '.config('app.name'))
@push('after-styles')
    <style>
        td:nth-child(4),
        td:nth-child(5),
        td:nth-child(6),
        td:nth-child(7) {
            text-align: right;
        }

        #gradelevels-table td:nth-child(8) {
            text-align: center;
        }
    </style>
@endpush
@section('content')
    <div class="col mx-auto mt-xl-4 mt-2">
        <div class="card">
            <div class="card-header">
                {!! link_to_route('admin.accounts.create', 'New Account', [], ['class' => 'btn btn-success btn-sm pull-right', 'title' => 'New Account']) !!}
                <h3><i class="fa fa-id-card-o"></i>Accounts</h3>
            </div>
            <div class="card-body">
                @include('includes.partials.messages')

                <div class="table-responsive">
                    <table id="accounts-table"
                           class="table table-bordered table-striped table-sm">
                        <thead class="thead-dark">
                        <tr>
                            <th>Name&nbsp;</th>
                            <th>Email&nbsp;</th>
                            <th>Role&nbsp;</th>
                            <th># Users&nbsp;</th>
                            <th># Orders&nbsp;</th>
                            <th>Credits&nbsp;</th>
                            <th>Debits&nbsp;</th>
                            <th>Can Order&nbsp;</th>
                            <th width="1">Status&nbsp;</th>
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
            $('#accounts-table').DataTable({
                dom: 'lfrtip',
                processing: false,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: '{!! route("admin.accounts.getDatatable") !!}',
                    type: 'post',
                    error: function (xhr, err) {
                        if (err === 'parsererror')
                            location.reload();
                    }
                },
                columns: [
                    {data: 'account_name', name: 'account_name'},
                    {data: 'email', name: 'email'},
                    {data: 'roles', name: 'roles', searchable: false},
                    {data: 'attached_users', name: 'attached_users', searchable: false, sortable: false},
                    {data: 'total_orders', name: 'total_orders', searchable: false},
                    {data: 'confirmed_credits', name: 'confirmed_credits', searchable: false},
                    {data: 'total_debits', name: 'total_debits', searchable: false},
                    {data: 'allow_new_orders', name: 'allow_new_orders', searchable: false},
                    {data: 'active', name: 'active', searchable: false},
                    {data: 'actions', name: 'actions', searchable: false, sortable: false}
                ],
                order: [[0, 'asc']],
                search: {caseInsensitive: !0},
                language: {
                    emptyTable: 'No accounts found',
                    search: 'Search'
                }
            });
        });
    </script>
@endpush
