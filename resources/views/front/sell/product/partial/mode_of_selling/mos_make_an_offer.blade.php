<div class="form-group clearfix">
    <label for="min_reserved_price" class="col-md-2 control-label padd-topnone">Min. reserved price</label>
    <div class="col-md-4">
        {!! Form::text($mosType.'[min_reserved_price]', (@$updateFlag == TRUE)?$productData['min_reserved_price']:null, ['class'=>"check_numeric form-control $mosType.min_reserved_price", 'placeholder'=>'', 'id' => 'min_reserved_price']) !!}
    </div>
    <label for="max_product_price" class="col-md-2 control-label padd-topnone">Max. product price</label>
    <div class="col-md-4">
        {!! Form::text($mosType.'[max_product_price]', (@$updateFlag == TRUE)?$productData['max_product_price']:null, ['class'=>"check_numeric form-control $mosType.max_product_price", 'placeholder'=>'', 'id' => 'max_product_price']) !!}
    </div>
</div>