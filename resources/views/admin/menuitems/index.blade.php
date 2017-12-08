@extends('layouts.app')
@section('title', 'Menu Items :: '.config('app.name'))
@push('after-styles')
    <style>
        #menuitems-table td:nth-child(3), #menuitems-table td:nth-child(5) {
            text-align: right;
            padding-right: 0.75rem;
        }

        #menuitems-table td:nth-child(4) {
            text-align: center;
        }

        div.dataTables_wrapper div.dataTables_info {
            padding-top: 0;
        }
    </style>
@endpush
@section('content')
    <div class="col mx-auto mt-xl-4 mt-2">
        <div class="card">
            <div class="card-header">
                <h3><i class="fa fa-utensil-spoon"></i>Menu Items</h3>
            </div>
            <div class="card-body">
                @include('includes.partials.messages')
                {!! link_to_route('admin.menuitems.create', 'New Menu Item', ['pid' => $providerid], ['class' => 'btn btn-success btn-sm float-right disabled', 'id' => 'btnCreate']) !!}
                <div class="row mb-1">
                    <label for="provider_id" class="col-form-label ml-3">Provider</label>
                    <div class="mr-3 ml-3">
                        {!! Form::select('provider_id', $providers, $providerid, ['class' => 'form-control custom-select','id' => 'provider_id']) !!}
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="menuitems-table"
                           class="table table-bordered table-striped table-sm">
                        <thead class="thead-dark">
                        <tr>
                            <th>Menu Item Short Name&nbsp;</th>
                            <th>Description&nbsp;</th>
                            <th width="1">Price&nbsp;</th>
                            <th width="1">Active&nbsp;</th>
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
    <script>
        $(document).ready(function () {
            var $selProvider = $("select[name='provider_id']").change(function (e) {
                    window.location.href = window.location.origin + window.location.pathname + '?pid=' + this.value;
                }),
                $dataTable = $('#menuitems-table').DataTable({
                    dom: 'ti',
                    processing: false,
                    serverSide: true,
                    autoWidth: false,
                    deferLoading: 0,
                    ajax: {
                        type: 'get',
                        error: function (xhr, err) {
                            if (err === 'parsererror')
                                location.reload();
                        }
                    },
                    columns: [
                        {data: 'item_name', name: 'item_name'},
                        {data: 'description', name: 'description'},
                        {data: 'price', name: 'price', searchable: false},
                        {data: 'active', name: 'active', searchable: false},
                        {data: 'numorders', name: 'numorders', searchable: false},
                        {data: 'actions', name: 'actions', searchable: false, sortable: false}
                    ],
                    order: [[0, 'asc']],
                    lengthMenu: [[-1], ['All']],
                    language: {
                        emptyTable: '&nbsp;'
                    }
                });

            function getUrl(pid) {
                return '{!! url("admin/menuitems") !!}/' + pid;
            }

            if ($selProvider.val() > 0) {
                $('#btnCreate').removeClass('disabled');
                $dataTable.settings()[0].oLanguage.sEmptyTable = 'No menu items found';
                $dataTable.ajax.url(getUrl({!! $providerid !!})).load();
                $dataTable.settings()[0].oFeatures.bServerSide = false;
            }

        });
    </script>
@endpush
