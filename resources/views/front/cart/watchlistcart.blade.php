@extends('front.layouts.app')

@section('content')
<section class="content">
    <div class="container"> 
      <!--breadcrumb Start-->
      <div class="row">
        <div class="col-md-12">
          <ul class="breadcrumb">
            <li><a href="index.html">Home</a></li>
            <li class="active">Watch list</li>
          </ul>
        </div>
      </div>
      <!--breadcrumb Start-->
      <h2>Watch list</h2>
      <!-- Nav tabs Start-->
      <ul class="myactivity-tab clearfix">
        <li><a href="{{route('favorite')}}" title="Review Order">Favorite</a></li>
        <li class="active"><a href="{{route('watchlist')}}" title="Incomplete Orders">Watchlist</a></li>
      </ul>
      <!-- Nav tabs End-->
      <div class="shoppingcart">
        @include('front.cart.watchlist')
      </div>
    </div>
  </section>
@endsection

@push('scripts')
<script>
 
    function removeproduct(item){
        $("#" + item).parent().remove();
        return false;
    }
function cart_to_order() {    
     var allVals = [];
     var checkedValues = $('input[name="c_b"]:checked').map(function() {
    allVals.push($(this).val());
    }).get();
    //if(confirm("review completed?")){
    $.ajax({
            url: '{{route("cart_to_order")}}',
            type: 'POST',
            dataType: 'json',
            data: {method: '_POST', product_ids: allVals, submit: true},
            success: function (r) {
                window.location.href = "{{URL::to('checkout')}}"
                $(".shoppingcart").html(r['html']);                
            },
            error: function (data) {
            }
        });
           //location.href="{{URL::to('cart')}}";
    //}       
  } 
function removefromwatchlist(id){
        $.ajax({
            url: '{{route("removefromwatchlist")}}',
            type: 'POST',
            dataType: 'json',
            data: {method: '_POST', product_id: id, submit: true},
            success: function (r) {
                $(".shoppingcart").html(r['html']);  
            },
            error: function (data) {
            }
        });
        toastr.success("watchlist:  Item has been removed from the watchlist.",'',{
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
     var checkedValues = $('input[name="c_b"]:checked').map(function() {
    allVals.push($(this).val());
    }).get();
    if(confirm("Are you sure you want to delete this item?")){
    $.ajax({
            url: '{{route("removefavorite")}}',
            type: 'POST',
            dataType: 'json',
            data: {method: '_POST', product_ids: allVals, submit: true},
            success: function (r) {
                $(".shoppingcart").html(r['html']);                
            },
            error: function (data) {
            }
        });
           //location.href="{{URL::to('cart')}}";
    }       
  }    
  $("#checkAll").change(function () {
    $("input:checkbox").prop('checked', $(this).prop("checked"));
    //enable_proceed();
});
$(":checkbox").on("click", function(){
 
    var id = $(this).attr('id');
    if(id == 'checkAll')
    {
        enable_proceed1();
    }else{
        enable_proceed();
    }
} );
$(document).on("click", ':checkbox', function(event) { 
    var id = $(this).attr('id');
    if(id == 'checkAll')
    {
        enable_proceed1();
    }else{
        enable_proceed();
    }
});
function enable_proceed(){
var allVals = [];
     var checkedValues = $('input[name="c_b"]:checked').map(function() {
    allVals.push($(this).val());
    }).get();
    if(allVals.length > 0){
        $(".basket_class").removeClass("disabled");
    }else{
        $(".basket_class").removeClass("disabled");
        $(".basket_class").addClass("disabled");
    }
}
function enable_proceed1(){
var allVals = [];
     var checkedValues = $('input[name="c_b"]:checked').map(function() {
    allVals.push($(this).val());
    }).get();
        $(".basket_class").removeClass("disabled");
        $(".basket_class").addClass("disabled");
}
function addtocart(id,qty){
        $.ajax({
            url: '{{route("cart")}}',
            type: 'POST',
            dataType: 'json',
            data: {method: '_POST', product_id: id, submit: true},
            success: function (r) {
                $(".cartcontent").html(r['count']);
            },
            error: function (data) {
            }
        });
        toastr.success("Shopping Cart:  Item has been added to shopping cart.",'',{
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
function addtowatchlist(id,qty){
        $.ajax({
            url: '{{route("watchlist")}}',
            type: 'POST',
            dataType: 'json',
            data: {method: '_POST', product_id: id, submit: true},
            success: function (r) {
                //$(".cartcontent").html(r['count']);
            },
            error: function (data) {
            }
        });
        toastr.success("Watch List:  Item has been added to watch list.",'',{
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
</script>

@endpush('scripts')