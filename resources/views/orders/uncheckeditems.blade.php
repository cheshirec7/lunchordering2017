<tr>
    <td>
        <div class="custom-control custom-checkbox">
            <input class="custom-control-input" type="checkbox"
                   id="cci{!! $menuitem->id !!}"
                   name="menuitems[]"
                   value="{!! $menuitem->id !!}">
            <label class="custom-control-label" for="cci{!! $menuitem->id !!}">
                {{ $menuitem->description }}
                @if($menuitem->price/100 != config('app.menuitem_default_price'))
                    ({!! money_format('$%.2n', $menuitem->price / 100) !!})
                @endif
            </label>
        </div>
    </td>
    <td>
        <input type="number" min="1" max="2" name="qtys[]" id="qty{!! $menuitem->id !!}"
               data-price="{!! $menuitem->price !!}" disabled>
    </td>
</tr>