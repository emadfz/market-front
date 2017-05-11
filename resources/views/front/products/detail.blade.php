@extends('front.layouts.app')
@section('content')
<section class="content">
    <div class="container">
        <!--breadcrumb Start-->
        <div class="row">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li><a href="{{URL('')}}">Home</a></li>            
                    <li class="active">
                        <a href="{{URL('/c/'.$category['category_slug'])}}" title="{{$category['category_name']}}">{{$category['category_name']}}</a>
                    </li>
                    <li class="active">
                        {{$product->name}}
                    </li>
                </ul>
            </div>
        </div>
        <!--breadcrumb End-->
        <!--Content Part Start--> 
        @if($product->category_id == 208)
            @include('front.products.automobile')  
        @elseif($product->category_id == 153)
            @include('front.products.realestate') 
        @elseif($product->category_id == 234)
            @include('front.products.jewellery')     
        @else
            @include('front.products.generalproduct') 
        @endif   
        <?php 
        /*@include('front.products.realestate')
        @include('front.products.generalproduct')  */ ?>
        <!--Content Part End-->    			
    </div>  			
</section>
@endsection
@section('3stylesheet')
<link rel="stylesheet" href="{{ asset('assets/front/css/jquery.fancybox.css') }}">
<link rel="stylesheet" href="{{ asset('assets/front/css/circle.css') }}">
<link rel="stylesheet" href="{{ asset('assets/front/css/jquery.jqzoom.css') }}">
@stop
@push('scripts')

<script src="{{ asset('assets/front/js/jquery.cookie.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/front/js/bxcarousel.js') }}"></script>
<script src="{{ asset('assets/front/js/jquery-ui.multidatespicker.js') }}"></script>

<script>
    function change_product(key){
        attribute_values=$("#"+key).val();
        sliptted_val=attribute_values.split('-');;
        $("#final_price").html(sliptted_val[1]);
        $("#add_cart_btn").html('<a href="#" class="btn btn-primary" title="Add to cart" onclick="addtocartSku('+sliptted_val[0]+',1);">Add to cart</a>');
    }
    $(document).ready(function(){
	$('.viewreview-dropdown .dropdown-menu').click(function(event){
     event.stopPropagation();
});
var today = new Date();
$('#multidate-set').multiDatesPicker({
	minDate: 0,//set current date above
	//maxDate: 30, //This use for next days limit set
	//maxPicks: 2, //max select date
	//addDisabledDates: [today.setDate(1), today.setDate(3)] //For disable date set
});
});

</script> 
@endpush
