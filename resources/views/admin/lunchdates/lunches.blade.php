@foreach ($menuitems as $menuitem)
    <div class="custom-control custom-checkbox">
        <input name="menuitems[]" type="checkbox" class="custom-control-input" id="chk{!! $menuitem->id !!}"
               value="{!! $menuitem->id !!}" @if($disabled) disabled @endif @if($checked) checked @endif>
        <label class="custom-control-label" for="chk{!! $menuitem->id !!}">{{ $menuitem->item_name }}
            @if($menuitem->price/100 != config('app.menuitem_default_price'))
                ({!! money_format('$%.2n', $menuitem->price / 100) !!}) @endif</label>
    </div>
@endforeach
