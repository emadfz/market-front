@extends('front.layouts.app')
@section('content')
<div class="wrapper">
    <!-- Start container here -->
    <section class="content">
        <div class="container">

            <!--breadcrumb Start-->
            @include('front.categories._partials.breadcrumbs')
            <!--breadcrumb End-->

            <!--Content Part Start-->    
            <div class="fullcolumn clearfix category-page">
                <!--Leftsidebar start-->
                <a href="#" id="filtershow" class="btn btn-info visible-xs-inline-block">Filter Option</a>

                <div class="leftcolumn clearfix">
                    @include('front.categories._partials.subcategory_listing')
                    
                    

                    <div class="leftcol-outer">
                        <h5>Filters By<span>(What's this ?)</span></h5>
                        <div class="panel-group" id="accordion" role="tablist">
<!--                            <div class="panel">
                                <h4><a role="button" data-toggle="collapse" data-parent="#accordion" href="#brandrange">Brand</a></h4>
                                <div id="brandrange" class="panel-collapse collapse in" role="tabpanel">
                                    <div class="panel-body">
                                        <div class="btn-group-vertical custom-checkbox" data-toggle="buttons">
                                            <label class="btn active">
                                                <input type="checkbox" value="" checked="">InSpree
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>-->

                            @include('front.categories._partials.attributeFilterListing')
                            
                            
                            <div class="panel">
                                <h4><a role="button" data-toggle="collapse" data-parent="#accordion" href="#productviewrange">Recently Viewed Products</a></h4>
                                <div id="productviewrange" class="panel-collapse collapse in" role="tabpanel">
                                    <div class="panel-body">
                                        <div class="owl-carousel owl-theme productview">
                                            @foreach($products as $product)
                                                <div class="item">
                                                    <a href="#">                                                                                                      
                                                        @if(isset($product->Files[0]) && !empty($product->Files[0]))
                                                            <img src="{{env('APP_URL')}}/images/products/main/{{@$product->Files[0]->path}}" alt="Compare" width="70" height="64"/>
                                                        @else
                                                            <img src="{{env('APP_ADMIN_URL')}}/assets/admin/layouts/layout4/img/no-image-main.png" alt="Compare" width="70" height="64"/>
                                                        @endif                                                                                                                    
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--leftsidebar close-->
                <!--Rightsidebar Start-->                
                <div class="rightcolumn clearfix">                   
                        @include('front.categories._partials.bannerListing')
                   <div id="product_listing">
                        @include('front.categories._partials.decendentsProductListing')
                   <div>

                </div>
                <!--Rightsidebar Close-->

            </div>
            <!--Content Part End-->    			


        </div>  			
    </section>
    <!-- End container here -->
</div>

<!--Compare Product-->

@if(isset($category_products) && count($category_products)>0)
    <div class="compare-bottom" style="display: block">@include('front.products.getComparedProduct')</div>
@else
    <div class="compare-bottom"></div>
@endif

@endsection
@push('scripts')
<script src="{{ asset('assets/front/js/jquery.cookie.js') }}" type="text/javascript"></script>
<script>
var category_slug='{{$category_slug}}';
var nextPage=2;
var previousPage=1;
$(document).ready(
    function(){
        $('#lastpage span').show();
    }
);

var product_page_count=0;
$( "body" ).delegate( "#next,#prev,#firstpage,#lastpage", "click", function(event, state) {
        var selfObj=$(this);
        var product_page_count=Math.ceil((parseInt($('#products_count').html())/12));
        
        if(selfObj.attr('id')=='next'){
            page=nextPage;
        }        
        else if(selfObj.attr('id')=='prev'){
            page=previousPage;
        }
        else if(selfObj.attr('id')=='firstpage'){
            page=1;
        }
        else if(selfObj.attr('id')=='lastpage'){
            page=product_page_count;
        }
        
        
        if( page==0 || page>product_page_count ){
            return false;
        }
        
        jQuery.ajax({
            type: "get",
            url: window.location.href+'/'+page,
            async: true,
            dataType:'json',
            data:'',
            success: function (response) {                
                if(response.status=='success' && response.html!=''){
                    $('#product_listing').html(response.html);
                    nextPage=response.nextPage;
                    previousPage=response.previousPage;
                    $(".category-slide").owlCarousel({
                        //autoPlay: 5000, //Set AutoPlay to 5 seconds
                        pagination: false,
                        navigation: true,
                        items: 1,
                        itemsDesktop: [1199, 1],
                        itemsDesktopSmall: [979, 1],
                        itemsTablet: [768, 1],
                        itemsMobile: [479, 1],
                        responsiveRefreshRate: 100,
                    });
                }
                else if(response.status=='success' && response.html==''){                    
                    selfObj.remove();
                }
                else {
                    alert('Could not get the Data. Please contact Administrator!!');
                    return false;
                }
            }
        });
  });
 
 $('#go').click(function(){
     $('.attribute_filters').trigger('change');
 });
 $('.price_filter').change(function(){
     //$('.price_filter').attr('checked',false);     
     $('#fromprice,#toprice').val('');
     $(this).prop( "checked" );
     if($(this).is(':checked')){
        $('#fromprice').val($(this).data('from'));
        $('#toprice').val($(this).data('to'));
     }
     
 });
 
 
 $('.attribute_filters').change(function(){     
     url=window.location.pathname+'?';
     parameters=[];
     pricing=[];
     $('.attribute_filters:checked').each(function(){
         parameters.push($(this).val());
     });
     
     if( $('#fromprice').val() !='' ){
         pricing.push('from='+$('#fromprice').val());
     }
     if( $('#toprice').val() !='' ){
         pricing.push('to='+$('#toprice').val());
     }     
     
        
     if(pricing.length>0){
        url=url+pricing.join('&');
     }
     
     if(parameters.length>0){
        url=url+parameters.join('&');
     }
     window.location.href=url;
});
 
 
  </script>
@endpush