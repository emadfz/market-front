<div class="form-group clearfix">
    <label for="base_price" class="col-md-2 control-label padd-topnone">Product base price</label>
    <div class="col-md-4">
        {!! Form::text($mosType.'[base_price]', (@$updateFlag == TRUE)?$productData['base_price']:null, ['class'=>"check_numeric form-control  $mosType.base_price", 'placeholder'=>'', 'id' => "$mosType.base_price"]) !!}
    </div>
    <label for="max_order_quantity" class="col-md-2 control-label padd-topnone">Max. order quantity</label>
    <div class="col-md-4">
        {!! Form::text($mosType.'[max_order_quantity]', (@$updateFlag == TRUE)?$productData['max_order_quantity']:null, ['class'=>"check_numeric form-control $mosType.max_order_quantity", 'placeholder'=>'', 'id' => 'max_order_quantity']) !!}
    </div>
</div>