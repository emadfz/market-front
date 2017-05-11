<section class="panel-product">
    <div class="container">
        <div class="row">
        <?php 
            $currency = session()->get('currency');
            $currency_rate = session()->get('currency_rate');
            
        ?>
            <div class="col-md-12">
                <div class="title-line clearfix"><h3>Featured Items<span><a href="#">View All</a></span></h3></div>
                <div class="carousel-wrap">
                    <!-- featured item carousel-->
                    <div class="homepage-carousel">
                         <ul class="bxcarousel_Featured">
                        <?php $i = 1; ?>
                        @foreach($products['Featured_Products'] as $product)
                        <li> <div class="item" id="item{{$i}}">
                            <div class="ribbon"><span>new</span></div>
                            <a href="javascript:void(0);" onclick="removeproduct_Featured('item{{$i}}');" class="product-close" title="Delete"></a>
                            <figure class="img">
                                <a href="{{ route("productSlugUrl",[$product->product_slug]) }}">
                                   
                                   <img src="http://php54.indianic.com/marketplace-front/public/images/products/main/{{@$product->Files[0]->path}}" width="174" height="174" alt=""></a>
                            </figure>
                            <div class="item-detail">
                                <?php
                                $length = 18;
                                $string = $product->name;
                                if (strlen($product->name) > $length) {
                                    $append = "...";
                                    $string = wordwrap($product->name, $length);
                                    $string = explode("\n", $string, 2);
                                    $string = $string[0] . $append;
                                }
                                ?>
                                <a href="{{ route("productSlugUrl",[$product->product_slug]) }}" class="title">{{@$string}}</a>
                                <div class="price"><del><em><?php print(convert_currency(70.00 , $currency_rate));?></em></del><span> <?php print(convert_currency($product->base_price , $currency_rate));?>
                                    </span></div>
                            </div>
                            <div class="item-footer">
                                <span>Free Shipping</span>
                                <span class="link">
                                    <?php 
                                    if(Auth::id()) {?>
                                        <a href="javascript: void(0);" onclick="addtofavorite('<?php echo @$product->ProductSku[0]->id;?>','1')" title="Favorite" @if(in_array($product->id,$favorite_items)) class="favorite active" @else class="favorite" @endif>Favorite</a> 
                                    <?php } else { ?>
                                        <a href="javascript: void(0);" onclick="$('.signinModal').click();" title="Favorite" class="favorite">Favorite</a> 
                                    <?php } ?>
                                        <a href="javascript: void(0);" onclick="addtocart('<?php echo @$product->ProductSku[0]->id;?>','1');" title="Cart" class="cart">Cart</a>
                                </span>
                            </div>
                            </div></li>
                        <?php $i++; ?>
                        @endforeach   
                        </ul>
                    </div>
                    <!-- End featured item carousel-->
                </div>
                <div class="title-line clearfix"><h3>Most Popular Products <span><a href="#">View All</a></span></h3></div>
                <div class="carousel-wrap">
                    <!-- featured item carousel-->
                    <div class="homepage-carousel">
                       <ul class="bxcarousel_Popular">
                        @foreach($products['PopularProducts'] as $product)
                        <li><div class="item" id="item{{$i}}">
                            <div class="ribbon"><span>new</span></div>
                            <a href="javascript:void(0);" onclick="removeproduct_Popular('item{{$i}}');" class="product-close" title="Delete"></a>
                            <figure class="img">
                                <a href="{{ route("productSlugUrl",[$product->product_slug]) }}"><img src="http://php54.indianic.com/marketplace-front/public/images/products/main/{{@$product->Files[0]->path}}" width="174" height="174" alt=""></a>
                            </figure>
                            <div class="item-detail">
                                <?php
                                $length = 18;
                                $string = $product->name;
                                if (strlen($product->name) > $length) {
                                    $append = "...";
                                    $string = wordwrap($product->name, $length);
                                    $string = explode("\n", $string, 2);
                                    $string = $string[0] . $append;
                                }
                                ?>
                                <a href="{{ route("productSlugUrl",[$product->product_slug]) }}" class="title">{{@$string}}</a>
                                <div class="price"><del><em><?php print(convert_currency(70.00 , $currency_rate));?></em></del><span> <?php print(convert_currency($product->base_price , $currency_rate));?></span></div>
                            </div>
                            <div class="item-footer">
                                <span>Free Shipping</span>
                                <span class="link">
                                    <?php 
                                    if(Auth::id()) {?>
                                        <a href="javascript: void(0);" onclick="addtofavorite('<?php echo @$product->ProductSku[0]->id;?>','1')" title="Favorite" @if(in_array($product->id,$favorite_items)) class="favorite active" @else class="favorite" @endif>Favorite</a> 
                                    <?php } else { ?>
                                        <a href="javascript: void(0);" onclick="$('.signinModal').click();" title="Favorite" class="favorite">Favorite</a> 
                                    <?php } ?>
                                        <a href="javascript: void(0);" onclick="addtocart('<?php echo @$product->ProductSku[0]->id;?>','1');" title="Cart" class="cart">Cart</a>
                                </span>
                            </div>
                            </div></li>
                        <?php $i++; ?>
                        @endforeach 
                         </ul>
                    </div>
                    <!-- End featured item carousel-->
                </div>
            </div>
        </div>
    </div>
</section>
@push('scripts')
<script src="{{ asset('assets/front/js/bxcarousel.js') }}"></script>
<script>
function addtofavorite(id,qty){
    //alert($(this).attr('class'));
        $.ajax({
            url: window.location.href+'favorite',
            type: 'POST',
            dataType: 'json',
            data: {method: '_POST', favorite_product_id: id, submit: true},
            success: function (r) {
                $("#favorite").removeClass("nav-favourite");
                $("#favorite").removeClass("favorite");
                $("#favorite").removeClass("active");
                $("#favorite").addClass("nav-favourite active");
            },
            error: function (data) {
            }
        });
        toastr.success("Favorite:  Item has been marked as favorite.",'',{
  "closeButton": true,
  "debug": false,
  "positionClass": "toast-top-right",
  "onclick": null,
  "showDuration": "1000",
  "hideDuration": "1000",
  "timeOut": "1000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
});
}    
function remove_cart() {         
     var allVals = [];
     $('#c_b :checked').each(function() {
       allVals.push($(this).val());
     });
     $('#t').val(allVals);
  }    
$(document).ready(function(){ 
$('a.favorite').click(function(){
    if($(this).attr('class') == 'favorite'){
        $(this).addClass( "active" );
    }else{
        $(this).removeClass( "active" );
    }
    
})
});
</script>
@endpush('scripts')
