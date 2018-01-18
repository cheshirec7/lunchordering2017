@extends('layouts.app')
@section('title', 'Edit Menu Item :: '.config('app.name'))
@push('after-styles')
    <style>
        #price {
            text-align: right;
        }

        .form-group.price, .form-group.active {
            width: 120px;
        }
    </style>
@endpush
@section('content')
    <div class="col maxw-500 mx-auto mt-xl-5 mt-md-3 mt-2">
        <div class="card">
            <div class="card-header">
                <h3><i class="fa fa-pen-square"></i>Edit Menu Item</h3>
            </div>
            {!! Form::model($menuitem, ['method' => 'PUT', 'route' => ['admin.menuitems.update', $menuitem->id]]) !!}
            <div class="card-body">
                @include('includes.partials.messages')

                <div class="form-group">
                    {!! Form::label('provider', 'Provider') !!}
                    {!! Form::select('provider', $providers, $menuitem->provider_id, ['class' => 'form-control custom-select', 'id' => 'provider_id', 'disabled' => 'disabled']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('item_name', 'Menu Item Short Name') !!}
                    {!! Form::text('item_name', null, ['class' => 'form-control', 'maxlength' => 50, 'required' => 'required', 'autofocus' => 'autofocus']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('description', 'Description') !!}
                    {!! Form::text('description', null, ['class' => 'form-control', 'maxlength' => 191, 'required' => 'required']) !!}
                </div>

                <div class="form-group price">
                    {!! Form::label('price', 'Price') !!}
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-money-bill-alt"></i></span>
                        </div>
                        <input type="text" class="form-control" name="price" id="price"
                               required="required" value="{!! $menuitem->price / 100 !!}">
                    </div>
                </div>

                <div class="form-group active">
                    {!! Form::label('active', 'Active') !!}
                    {!! Form::select('active', [1 => 'Yes', 0 => 'No'], $menuitem->active, ['class' => 'form-control custom-select','id' => 'active']) !!}
                </div>

            </div>
            <div class="card-footer">
                {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
                {!! link_to('admin/menuitems?pid='.$menuitem->provider_id, 'Cancel', ['class' => 'btn btn-cancel']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
@push('after-scripts')
    <script>
        $(document).ready(function () {
            var $price = $('#price').blur(function (a) {
                setPrice(
                        {!! config('app.menuitem_default_price') !!},
                        {!! config('app.menuitem_max_price') !!}
                );
            });

            function setPrice(def_price, max_price) {
                var theval = parseFloat(Math.round($price.val() * 100) / 100).toFixed(2);

                if (theval > max_price)
                    $price.val(max_price.toFixed(2));
                else if (theval > 0)
                    $price.val(theval);
                else
                    $price.val(def_price.toFixed(2));
            }

            $price.trigger('blur');
        });
    </script>
@endpush

