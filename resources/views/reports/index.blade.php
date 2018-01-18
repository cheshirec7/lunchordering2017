@extends('layouts.app')
@section('title', 'Lunch Report :: '.config('app.name'))
@section('content')
    <div class="col maxw-500 mx-auto mt-xl-4 mt-md-3 mt-2">
        <div class="card">
            <div class="card-header">
                <h3><i class="fa fa-sticky-note"></i>Lunch Report</h3>
            </div>
            <div class="card-body">
                {!! Form::open(['method' => 'get', 'target' => '_blank', 'route' => ['dolunchreport']]) !!}
                <div class="custom-control custom-radio m-1">
                    <input type="radio" id="rpttype1" name="rpttype" class="custom-control-input" value="0" checked>
                    <label class="custom-control-label" for="rpttype1">Show only dates with orders</label>
                </div>
                <div class="custom-control custom-radio m-1">
                    <input type="radio" id="rpttype2" name="rpttype" class="custom-control-input" value="1">
                    <label class="custom-control-label" for="rpttype2">Show dates with orders or events</label>
                </div>
                <div class="custom-control custom-radio m-1">
                    <input type="radio" id="rpttype3" name="rpttype" class="custom-control-input" value="2">
                    <label class="custom-control-label" for="rpttype3">Show all dates</label>
                </div>
            </div>
            <div class="card-footer">
                {!! Form::submit('Run', ['class' => 'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
