@extends('layouts.app')
@section('title', 'Providers :: '.config('app.name'))
@push('after-styles')
    <style>
        div.dataTables_wrapper div.dataTables_info {
            padding-top: 0;
        }

        #providers-table td:nth-child(5) {
            text-align: right;
            padding-right: 0.75rem;
        }

        #providers-table td:nth-child(7) {
            text-align: center;
        }
    </style>
@endpush
@section('content')
    <div class="col mx-auto mt-xl-4 mt-2">
        <div class="card">
            <div class="card-header">
                {!! link_to_route('admin.providers.create', 'New Provider', [], ['class' => 'btn btn-success btn-sm float-right', 'title' => 'New Provider', 'id' => 'btnCreate']) !!}
                <h3><i class="fa fa-table"></i>Providers</h3>
            </div>
            <div class="card-body">
                @include('includes.partials.messages')
                <div class="table-responsive">
                    <table id="providers-table"
                           class="table table-bordered table-striped table-sm">
                        <thead class="thead-dark">
                        <tr>
                            <th>Provider&nbsp;</th>
                            <th>Image&nbsp;</th>
                            <th>URL&nbsp;</th>
                            <th>Included With Lunches Message&nbsp;</th>
                            <th width="1">#&nbsp;Orders&nbsp;</th>
                            <th width="1">Can Order&nbsp;</th>
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
    <script>
        $(document).ready(function () {
            $('#providers-table').DataTable({
                dom: 'ti',
                processing: false,
                serverSide: false,
                autoWidth: false,
                ajax: {
                    url: '{!! url("admin/providers") !!}/0',
                    type: 'get',
                    error: function (xhr, err) {
                        if (err === 'parsererror')
                            location.reload();
                    }
                },
                columns: [
                    {data: 'provider_name', name: 'provider_name'},
                    {data: 'provider_image', name: 'provider_image', sortable: false},
                    {data: 'provider_url', name: 'provider_url', sortable: false},
                    {data: 'provider_includes', name: 'provider_includes', sortable: false},
                    {data: 'numorders', name: 'numorders'},
                    {data: 'allow_orders', name: 'allow_orders'},
                    {data: 'actions', name: 'actions', sortable: false}
                ],
                order: [[0, "asc"]],
                lengthMenu: [[-1], ['All']],
                language: {
                    emptyTable: 'No providers found'
                }
            });
        });
    </script>
@endpush
