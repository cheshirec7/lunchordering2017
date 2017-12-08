@extends('layouts.app')
@section('title', 'No Lunch Exceptions :: '.config('app.name'))
@push('after-styles')
    <style>
        div.dataTables_wrapper div.dataTables_info {
            padding-top: 0;
        }

        .nle label {
            margin-right: 0.75rem;
        }

        #nle-table td:nth-child(5) {
            text-align: center;
        }
    </style>
@endpush
@section('content')
    <div class="col maxw-1024 mx-auto mt-xl-4 mt-2 nle">
        <div class="card">
            <div class="card-header">
                <h3><i class="fa fa-ban"></i>No Lunch Exceptions</h3>
            </div>
            <div class="card-body">
                @include('includes.partials.messages')
                {!! link_to_route('admin.nolunchexceptions.create', 'New Exception', [],
                                    ['class' => 'btn btn-success btn-sm float-right', 'title' => 'New Exception', 'id' => 'btnCreate']) !!}
                <form class="form-inline">
                    <div class="form-group mb-1">
                        {!! Form::label('dates_to_show', 'Dates to show') !!}
                        {!! Form::select('dates_to_show', [0 => 'Today and later', 1 => 'All'], 0, ['class' => 'form-control custom-select', 'id' => 'dates_to_show']) !!}
                    </div>
                </form>

                <div class="table-responsive">
                    <table id="nle-table"
                           class="table table-bordered table-striped table-sm">
                        <thead class="thead-dark">
                        <tr>
                            <th>Exception Date&nbsp;</th>
                            <th>Grade&nbsp;</th>
                            <th>Reason&nbsp;</th>
                            <th>Description&nbsp;</th>
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
            var $selDatesToShow = $("select[name='dates_to_show']").change(function (e) {
                    $dataTable.ajax.url('{!! url("admin/nolunchexceptions") !!}/' + this.value).load();
                }),
                $dataTable = $('#nle-table').DataTable({
                    dom: 'ti',
                    processing: false,
                    serverSide: false,
                    autoWidth: false,
                    ajax: {
                        url: '{!! url("admin/nolunchexceptions") !!}/0',
                        type: 'get',
                        error: function (xhr, err) {
                            if (err === 'parsererror')
                                location.reload();
                        }
                    },
                    columns: [
                        {data: 'exception_date', name: 'exception_date'},
                        {data: 'grade_desc', name: 'grade_desc'},
                        {data: 'reason', name: 'reason'},
                        {data: 'description', name: 'description'},
                        {data: 'actions', name: 'actions', sortable: false}
                    ],
                    order: [[0, "desc"]],
                    lengthMenu: [[-1], ['All']],
                    language: {
                        emptyTable: 'No exceptions found'
                    }
                });
        });
    </script>
@endpush
