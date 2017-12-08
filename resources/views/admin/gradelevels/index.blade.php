@extends('layouts.app')
@section('title', 'Grade Levels :: '.config('app.name'))
@push('after-styles')
    <style>
        div.dataTables_wrapper div.dataTables_info {
            padding-top: 0;
        }

        #gradelevels td:nth-child(3), td:nth-child(4) {
            text-align: right;
            padding-right: 0.75rem;
        }

        #gradelevels-table td:nth-child(5) {
            text-align: center;
        }
    </style>
@endpush
@section('content')
    <div class="col maxw-768 mx-auto mt-xl-4 mt-md-3 mt-2">
        <div class="card">
            <div class="card-header">
                {!! link_to_route('admin.gradelevels.create', 'New Grade Level', [], ['class' => 'btn btn-success btn-sm float-right', 'title' => 'New Grade Level', 'id' => 'btnCreate']) !!}
                <h3><i class="fa fa-table"></i>Grade Levels</h3>
            </div>
            <div class="card-body">
                @include('includes.partials.messages')
                <div class="table-responsive">
                    <table id="gradelevels-table"
                           class="table table-bordered table-striped table-sm">
                        <thead class="thead-dark">
                        <tr>
                            <th>Grade&nbsp;</th>
                            <th>Description&nbsp;</th>
                            <th>Report Order&nbsp;</th>
                            <th>#&nbsp;Users&nbsp;</th>
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
            $('#gradelevels-table').DataTable({
                dom: 'ti',
                processing: false,
                serverSide: false,
                autoWidth: false,
                ajax: {
                    url: '{!! url("admin/gradelevels") !!}/0',
                    type: 'get',
                    error: function (xhr, err) {
                        if (err === 'parsererror')
                            location.reload();
                    }
                },
                columns: [
                    {data: 'grade', name: 'grade'},
                    {data: 'grade_desc', name: 'grade_desc'},
                    {data: 'report_order', name: 'report_order'},
                    {data: 'numusers', name: 'numusers', searchable: false},
                    {data: 'actions', name: 'actions', sortable: false}
                ],
                order: [[2, "asc"]],
                lengthMenu: [[-1], ['All']],
                language: {
                    emptyTable: 'No grade levels found'
                }
            });
        });
    </script>
@endpush
