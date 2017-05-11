<div class="row">
    <div class="form-horizontal col-md-12">
        <div class="form-group mrg-bottom">
            <label class="control-label col-md-2 padd-topnone">Select mode of selling<span class="required">*</span></label>
            <div class="col-md-10">
                <div class="custom-radio">
                    <label for="mosBuyNow">
                        {!! Form::radio('mode_of_selling', 'Buy it now', false, ['class'=>'form-control', 'id'=>'mosBuyNow']) !!}<span></span>Buy it now
                    </label>
                    <label for="mosMakeOffer">
                        {!! Form::radio('mode_of_selling', 'Make an offer', false, ['class'=>'form-control', 'id'=>'mosMakeOffer']) !!}<span></span>Make an offer
                    </label>
                    <label for="mosAuction">
                        {!! Form::radio('mode_of_selling', 'Auction', false, ['class'=>'form-control', 'id'=>'mosAuction']) !!}<span></span>Auction
                    </label>
                    <label for="mosBuyNowMakeOffer">
                        {!! Form::radio('mode_of_selling', 'Buy it now and Make an offer', false, ['class'=>'form-control', 'id'=>'mosBuyNowMakeOffer']) !!}<span></span>Buy it now and Make an offer
                    </label>
                    <label for="mosBuyNowAuction">
                        {!! Form::radio('mode_of_selling', 'Buy it now and Auction', false, ['class'=>'form-control', 'id'=>'mosBuyNowAuction']) !!}<span></span>Buy it now and Auction
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

<!--On Radio Click open below field Start-->
<!--Buy it Now-->
<div class="row showbox-outer mos-setting-div" data-mos_id="mosBuyNowDiv" style="display: none;">
    <div class="col-md-10 col-md-offset-2">
        <div class="showbox">
            @include('front.sell.product.partial.mode_of_selling.mos_buy_it_now', ['mosType' => 'bin'])
        </div>
    </div>
</div>

<!--Make an Offer-->
<div class="row showbox-outer mos-setting-div" data-mos_id="mosMakeOfferDiv" style="display: none;">
    <div class="col-md-10 col-md-offset-2">
        <div class="showbox">
            @include('front.sell.product.partial.mode_of_selling.mos_make_an_offer', ['mosType' => 'maf'])
        </div>
    </div>
</div>

<!--Auction-->
<div class="row showbox-outer mos-setting-div" data-mos_id="mosAuctionDiv" style="display: none;">
    <div class="col-md-10 col-md-offset-2">
        <div class="showbox">
            @include('front.sell.product.partial.mode_of_selling.mos_auction', ['mosType' => 'auction'])
        </div>
    </div>
</div>

<!--Buy it Now & Make an Offer-->
<div class="row showbox-outer mos-setting-div" data-mos_id="mosBuyNowMakeOfferDiv"  style="display: none;">
    <div class="col-md-10 col-md-offset-2">
        <div class="showbox">
            @include('front.sell.product.partial.mode_of_selling.mos_buy_it_now', ['mosType' => 'bin_maf'])
            @include('front.sell.product.partial.mode_of_selling.mos_make_an_offer', ['mosType' => 'bin_maf'])
        </div>
    </div>
</div>

<!--Buy it Now & Auction-->
<div class="row showbox-outer mos-setting-div" data-mos_id="mosBuyNowAuctionDiv" style="display: none;">
    <div class="col-md-10 col-md-offset-2">
        <div class="showbox">
            @include('front.sell.product.partial.mode_of_selling.mos_buy_it_now', ['mosType' => 'bin_auction'])
            @include('front.sell.product.partial.mode_of_selling.mos_auction', ['mosType' => 'bin_auction'])
        </div>
    </div>
</div>
<!--On Radio Click open below field Start-->
@push('scripts')
<script type="text/javascript">

    $(document).on("change", "input[name=mode_of_selling]", function () {
        var mosID = this.id;
        $("div.mos-setting-div").hide();
        var tmpdiv = "";
        $("div.mos-setting-div").each(function (index) {
            tmpdiv = mosID + "Div";
            if ($(this).data('mos_id') == tmpdiv) {
                $(this).show();
            }
        });
    });

@if($updateFlag == TRUE)
    @if($productData['mode_of_selling'] == 'Buy it now')
        $("#mosBuyNow").attr("checked", "checked").trigger("change");
    @elseif($productData['mode_of_selling'] == 'Make an offer')
        $("#mosMakeOffer").attr("checked", "checked").trigger("change");
    @elseif($productData['mode_of_selling'] == 'Auction')
        $("#mosAuction").attr("checked", "checked").trigger("change");
    @elseif($productData['mode_of_selling'] == 'Buy it now and Make an offer')
        $("#mosBuyNowMakeOffer").attr("checked", "checked").trigger("change");
    @elseif($productData['mode_of_selling'] == 'Buy it now and Auction')
        $("#mosBuyNowAuction").attr("checked", "checked").trigger("change");
    @endif
@else
    $("#mosBuyNow").attr("checked", "checked").trigger("change");
@endif    

    $(document).on("change", "#auction__by_time", function () {
        $("div.mos-auction-setting-div").hide();
        $(".auction_by_price").hide();
        $(".auction_by_time").show();
    });
    
    $(document).on("change", "#auction__by_price", function () {
        $("div.mos-auction-setting-div").hide();
        $(".auction_by_price").show();
        $(".auction_by_time").hide();
    });

    $(document).on("change", "#bin_auction__by_price", function () {
        $("div.mos-auction-setting-div").hide();
        $(".bin_auction_by_time").hide();
        $(".bin_auction_by_price").show();
    });

    $(document).on("change", "#bin_auction__by_time", function () {
        $("div.mos-auction-setting-div").hide();
        $(".bin_auction_by_price").hide();
        $(".bin_auction_by_time").show();
    });
    
    @if($updateFlag == TRUE && !empty($productData['productAuction']))
        @if($productData['mode_of_selling'] == 'Auction' && $productData['productAuction']['mode'] == 'By price')
            $("#auction__by_price").attr("checked", "checked").trigger("change");
        @elseif($productData['mode_of_selling'] == 'Auction' && $productData['productAuction']['mode'] == 'By time')
            $("#auction__by_time").attr("checked", "checked").trigger("change");
        @elseif($productData['mode_of_selling'] == 'Buy it now and Auction' && $productData['productAuction']['mode'] == 'By price')
            $("#bin_auction__by_price").attr("checked", "checked").trigger("change");
        @elseif($productData['mode_of_selling'] == 'Buy it now and Auction' && $productData['productAuction']['mode'] == 'By time')
            $("#bin_auction__by_time").attr("checked", "checked").trigger("change");    
        @endif
    @endif
    //$("#auction__by_price").trigger("click");
    //$("#bin_auction__by_price").trigger("click");

</script>
@endpush