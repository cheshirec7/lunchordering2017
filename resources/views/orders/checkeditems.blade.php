<tr>
    <td>
        <div class="custom-control custom-checkbox">
            <input class="custom-control-input" type="checkbox"
                   id="cci{!! $orderdetail->menuitem_id !!}"
                   name="menuitems[]" checked
                   value="{!! $orderdetail->menuitem_id !!}">
            <label class="custom-control-label" for="cci{!! $orderdetail->menuitem_id !!}">
                {{ $orderdetail->description }}
                @if($orderdetail->price/100 != config('app.menuitem_default_price'))
                    ({!! money_format('$%.2n', $orderdetail->price / 100) !!})
                @endif
            </label>
        </div>
    </td>
    <td>
        <input type="number" min="1" max="2" name="qtys[]"
               id="qty{!! $orderdetail->menuitem_id !!}"
               data-price="{!! $orderdetail->price !!}"
               value="{!! $orderdetail->qty !!}">
    </td>
</tr>