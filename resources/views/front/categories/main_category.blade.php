@extends('front.layouts.app')
@section('content')
<div class="wrapper">
<section class="content">
    
<div class="container">
    @include('front.categories._partials.breadcrumbs')

<!--Content Part Start-->    
    <div class="fullcolumn clearfix category-page mrg-bottom">
        <!--Leftsidebar start-->
        <a href="#" id="filtershow" class="btn btn-info visible-xs-inline-block">Filter Option</a>
        
		<div class="leftcolumn clearfix">
            @include('front.categories._partials.subcategory_listing')        
            @include('front.categories._partials.attributeFilterListing')
           
            
<!--            <div class="leftcol-outer">
            	<h5>Show results for</h5>
        		<h4>Mobiles &amp; Accessories</h4>
                <ul class="subnavigation">
                  <li><a href="#" title="Mobile Accessories">Mobile Accessories<span>(1,52,60,657)</span></a></li>
                  <li><a href="#" title="Smartphones & Basic Mobiles">Smartphones &amp; Basic Mobiles<span>(3,065)</span></a></li>
                </ul>
            </div>-->
            
        </div>
        
        <!--leftsidebar close-->
        <!--Rightsidebar Start-->
        <div class="rightcolumn clearfix">
            <div class="rightcol-outer">
                @include('front.categories._partials.bannerListing')
            </div>            
            <div class="rightcol-outer">
                <div class="title-line clearfix"><h3>Best performing products</h3></div>
                    <div class="category clearfix " id="product_listing">
                            @include('front.categories._partials.departmentProductListing')
                    </div>
                </div> 
           
        </div>
        
        <button id='view_more' class="btn btn-block showmore">VIEW MORE</button>        
        <!--Rightsidebar Close-->
    </div>
<!--Content Part End-->    	
</div>  			
</section>
<!-- End container here -->
<!--Back to top --> 
<a href="#" id="back-to-top" title="Back to top"></a> 
</div>
<!-- End Wrapper -->

@if(isset($category_products) && count($category_products)>0)
    <div class="compare-bottom" style="display: block">@include('front.products.getComparedProduct')</div>
@else
    <div class="compare-bottom"></div>
@endif

@endsection
@push('scripts')
<script>
var category_slug='{{$category_slug}}';
var nextPage=2;
$('#view_more').click(function(event, state) {    
        var selfObj=$(this);
        $.ajax({
            type: "get",
            url: window.location.href+'/'+nextPage,
            async: true,
            dataType:'json',
            data:'',
            success: function (response) {                
                if(response.status=='success' && response.html!=''){
                    $('#product_listing').append(response.html);
                    nextPage=response.nextPage;
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
  </script>
@endpush