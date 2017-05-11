@include('front.sell.product.partial.mode_of_selling.mos_auction_type', ['mosType' => $mosType])
<input type="hidden" value="{{(@$updateFlag == TRUE)?encrypt($productData['auction_id']):0}}" name="auction_id" />
@include('front.sell.product.partial.mode_of_selling.mos_auction_by_price', ['mosType' => $mosType.'_by_price'])
@include('front.sell.product.partial.mode_of_selling.mos_auction_by_time', ['mosType' => $mosType.'_by_time'])