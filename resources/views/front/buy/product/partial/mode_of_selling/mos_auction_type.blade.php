<label class="col-md-4 control-label padd-topnone">Select mode of auction</label>
<div class="col-md-8">
    <div class="custom-radio">
        
        <label for="{{$mosType."__by_price"}}">
            {!! Form::radio($mosType.'[auction_type]', 'By price', false, ['class'=>"form-control", 'id' => $mosType."__by_price"]) !!}<span></span>Auction by price
        </label>
        <label for="{{$mosType."__by_time"}}">
            {!! Form::radio($mosType.'[auction_type]', 'By time', false, ['class'=>"form-control", 'id' => $mosType."__by_time"]) !!}<span></span>Auction by time
        </label>
    </div>
</div>