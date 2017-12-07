{{--@inject('request', 'Illuminate\Http\Request')--}}
@extends('layouts.app')
@section('content')
    <div class="panel-body">
        <table class="table table-bordered table-striped {{ count($roles) > 0 ? 'datatable' : '' }} dt-select">
            <thead>
            <tr>
                <th style="text-align:center;"><input type="checkbox" id="select-all"/></th>
                <th>@lang('global.roles.fields.name')</th>
                <th>@lang('global.roles.fields.abilities')</th>
                <th>&nbsp;</th>
            </tr>
            </thead>

            <tbody>
            @if (count($roles) > 0)
                @foreach ($roles as $role)
                    <tr data-entry-id="{{ $role->id }}">
                        <td></td>
                        <td>{{ $role->name }}</td>
                        <td>
                            @foreach ($role->abilities()->pluck('name') as $ability)
                                <span class="label label-info label-many">{{ $ability }}</span>
                            @endforeach
                        </td>
                        <td>
                            <a href="{{ route('admin.roles.edit',[$role->id]) }}"
                               class="btn btn-sm btn-info">@lang('global.app_edit')</a>
                            {!! Form::open(array(
                                'style' => 'display: inline-block;',
                                'method' => 'DELETE',
                                'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
                                'route' => ['admin.roles.destroy', $role->id])) !!}
                            {!! Form::submit(trans('global.app_delete'), array('class' => 'btn btn-sm btn-danger')) !!}
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6">@lang('global.app_no_entries_in_table')</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
@endsection
@section('javascript')
    <script>
        window.route_mass_crud_entries_destroy = '{{ route('admin.roles.mass_destroy') }}';
    </script>
@endsection