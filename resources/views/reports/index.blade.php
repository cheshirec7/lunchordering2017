@extends('layouts.app')
@section('title', 'Lunch Report :: '.config('app.name'))
@section('content')
    <div class="col maxw-500 mx-auto mt-xl-4 mt-md-3 mt-2">
        <div class="card">
            <div class="card-header">
                <h3><i class="fa fa-file-text"></i>Lunch Report</h3>
            </div>
            <div class="card-body">
                {!! Form::open(['method' => 'get', 'target' => '_blank', 'route' => ['dolunchreport']]) !!}
                <div class="custom-controls-stacked">
                    <label class="custom-control custom-radio">
                        <input name="rpttype" type="radio" class="custom-control-input" checked value="0">
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">Show only dates with orders</span>
                    </label>
                    <label class="custom-control custom-radio">
                        <input name="rpttype" type="radio" class="custom-control-input" value="1">
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">Show dates with orders or events</span>
                    </label>
                    <label class="custom-control custom-radio">
                        <input name="rpttype" type="radio" class="custom-control-input" value="2">
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">Show all dates</span>
                    </label>
                </div>
            </div>
            <div class="card-footer">
                {!! Form::submit('Run', ['class' => 'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
