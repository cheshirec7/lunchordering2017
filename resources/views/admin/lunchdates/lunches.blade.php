@foreach ($menuitems as $menuitem)
<label class="custom-control custom-checkbox">
    <input class="custom-control-input" name="menuitems[]" type="checkbox" value="{!! $menuitem->id !!}" @if($disabled) disabled @endif @if($checked) checked @endif>
    <span class="custom-control-indicator"></span>
    <span class="custom-control-description">{{ $menuitem->item_name }}
        @if($menuitem->price/100 != config('app.menuitem_default_price')) ({!! money_format('$%.2n', $menuitem->price / 100) !!}) @endif
    </span>
</label>
@endforeach
