<div class="subshowbox mos-auction-setting-div {{$mosType}}" style="display: none;">
    <div class="form-group clearfix">
        <label for="min_reserved_price" class="col-md-2 control-label padd-topnone">Min. reserved price</label>
        <div class="col-md-4">
            {!! Form::text($mosType.'[min_reserved_price]', (@$updateFlag == TRUE && !empty($productData['productAuction']))?$productData['productAuction']['min_reserved_price']:null, ['class'=>"form-control $mosType.min_reserved_price", 'placeholder'=>'', 'id' => 'min_reserved_price']) !!}
        </div>
        <label for="max_product_price" class="col-md-2 control-label padd-topnone">Max. product price</label>
        <div class="col-md-4">
            {!! Form::text($mosType.'[max_product_price]', (@$updateFlag == TRUE && !empty($productData['productAuction']))?$productData['productAuction']['max_product_price']:null, ['class'=>"form-control $mosType.max_product_price", 'placeholder'=>'', 'id' => 'max_product_price']) !!}
        </div>
    </div>
    <div class="form-group clearfix">
        <label for="min_bid_increment" class="col-md-2 control-label padd-topnone">Min. bid increment(%)</label>
        <div class="col-md-4">
            {!! Form::text($mosType.'[min_bid_increment]', (@$updateFlag == TRUE && !empty($productData['productAuction']))?$productData['productAuction']['min_bid_increment']:null, ['class'=>"form-control $mosType.min_bid_increment", 'placeholder'=>'', 'id' => 'min_bid_increment']) !!}
        </div>
        <label class="col-md-2 control-label padd-topnone">Time period for auction</label>
        <div class="col-md-4 clearfix">
            <div class="col-md-6 nopadding">
                <div class="outer-field dateouter">
                    {!! Form::text($mosType.'[start_datetime]', (@$updateFlag == TRUE && !empty($productData['productAuction']))?$productData['productAuction']['start_datetime']:null, ['class'=>"form-control col-md-6 datepicker-ui $mosType.start_datetime", 'placeholder'=>'', 'id' => $mosType.'start_datetime']) !!}
                </div>
            </div>
            <div class="col-md-6 nopadding">
                <div class="outer-field dateouter">
                    {!! Form::text($mosType.'[end_datetime]', (@$updateFlag == TRUE && !empty($productData['productAuction']))?$productData['productAuction']['end_datetime']:null, ['class'=>"form-control col-md-6 datepicker-ui $mosType.end_datetime", 'placeholder'=>'', 'id' => $mosType.'end_datetime']) !!}
                </div>
            </div>
        </div>
    </div>
</div>