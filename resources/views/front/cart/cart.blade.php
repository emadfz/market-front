@extends('front.layouts.app')
@section('content')
<section class="content">
    <div class="container"> 
      <!--breadcrumb Start-->
      <div class="row">
        <div class="col-md-12">
          <ul class="breadcrumb">
            <li><a href="index.html">Home</a></li>
            <li class="active">Shopping Cart</li>
            <li class=""><a href="{{route('checkout')}}">Checkout</a></li>            
          </ul>
        </div>
      </div>
      <!--breadcrumb Start-->
      <h2>Shopping Cart</h2>
      <!-- Nav tabs Start-->
      <ul class="myactivity-tab clearfix">
        <li class="active"><a href="{{route('cart')}}" title="Review Order">Shopping Cart</a></li>
        <li><a href="{{route('checkout')}}" title="Incomplete Orders">Review Order</a></li>
        <li class=""><a href="{{route('payment')}}">Payment</a></li>       
      </ul>
      <!-- Nav tabs End-->
      <div class="shoppingcart">
        @include('front.cart.bascket')
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
    function updateqtycart(id,qty){
        $.ajax({
            url: '{{route("updateqtycart")}}',
            type: 'POST',
            dataType: 'json',
            data: {method: '_POST', product_id: id,qty :qty, submit: true},
            success: function (r) {
                $(".shoppingcart").html(r['html']);
            },
            error: function (data) {
            }
        });
    }
    function incrementcart(id,qty){
        $.ajax({
            url: '{{route("incrementcart")}}',
            type: 'POST',
            dataType: 'json',
            data: {method: '_POST', product_id: id,increment :1, submit: true},
            success: function (r) {
                $(".shoppingcart").html(r['html']);
            },
            error: function (data) {
            }
        });
    }
    function decrease(id,qty){
        $.ajax({
            url: '{{route("decrease")}}',
            type: 'POST',
            dataType: 'json',
            data: {method: '_POST', product_id: id,decrease :1, submit: true},
            success: function (r) {
                $(".shoppingcart").html(r['html']);
            },
            error: function (data) {
            }
        });
    }
function removefromcart(id){
        $.ajax({
            url: '{{route("removefromcart")}}',
            type: 'POST',
            dataType: 'json',
            data: {method: '_POST', product_id: id, submit: true},
            success: function (r) {
                $(".shoppingcart").html(r['html']);  
            },
            error: function (data) {
            }
        });
        toastr.success("Shopping cart:  Item has been removed from the Shopping cart.",'',{
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
    
function cart_to_order() {   
     var allVals = [];
     var checkedValues = $('input[name="c_b"]:checked').map(function() {
    allVals.push($(this).val());
    }).get();
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
  }    
 function remove_cart() {    
     var allVals = [];
     var checkedValues = $('input[name="c_b"]:checked').map(function() {
    allVals.push($(this).val());
    }).get();
    if(confirm("Are you sure you want to delete this item?")){
    $.ajax({
            url: '{{route("removecart")}}',
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
function addtofavorite(id,qty){
        $.ajax({
            url: '{{route("favorite")}}',
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
</script>
@endpush('scripts')