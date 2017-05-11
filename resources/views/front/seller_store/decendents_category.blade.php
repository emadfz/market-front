@extends('front.layouts.app')
@section('content')
<div class="wrapper">
    <!-- Start container here -->
    <section class="content">
        <div class="container">

            <!--breadcrumb Start-->
            <div class="row">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li>
                        <a href="{{URL('')}}">Home</a>
                    </li>                                
                </ul>
            </div>
        </div>
            <!--breadcrumb End-->

            <!--Content Part Start-->    
            <div class="fullcolumn clearfix category-page">
                <div class="fullcolumn tab-content content-white" style="border:solid thin gray;height:100px;">
                    <div style="height:90%;margin-top:5px;width:20%;margin-left:20px;float:left;">
                        <img src="{{getUserProfileImage($sellerDetail->user)}}" />
                    </div>
                    <div style="height:90%;margin-top:5px;width:70%;margin-left:20px;float:right;">
                        {{$sellerDetail->business_name}}
                    </div>
                </div>
                <!--Leftsidebar start-->
                <a href="#" id="filtershow" class="btn btn-info visible-xs-inline-block">Filter Option</a>
                
                <div class="leftcolumn clearfix">                    
                    <div class="panel">
                        <h4>
                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#pricerange">Search In Shop</a>
                        </h4>
                        <div class="panel-collapse collapse in" role="tabpanel">
                            <div class="panel-body">
                                <div class="form-group clearfix" data-toggle="buttons">
                                    <input type="text" class="form-control width150" id="productsearch" value="{{@$data['q']}}" placeholder="Search Product">
                                </div>
                                <div class="form-group clearfix">
                                    <button type="button" id="go" class="btn btn-primary">go</button>
                                </div>
                            </div>
                        </div>
                    </div>                                       
                    <div class="panel">
                        <h4>
                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#pricerange">Shop By Category</a>
                        </h4>
                        <div class="panel-collapse collapse in" role="tabpanel">
                            <div class="panel-body">
                                <div class="btn-group-vertical custom-checkbox" data-toggle="buttons">
                                        <label class="btn">                                            
                                            <input class="category_filter_all" @if(count($data['categories'])<1) checked @endif type="checkbox"  value="all">All Category
                                            <span></span>                        
                                        </label>
                                        
                                        @foreach($product_categories as $product)
                                            <label class="btn">
                                                <input class="category_filter" type="checkbox" @if(in_array($product->productCategories->category_slug,@$data['categories']) ) checked @endif value="{{$product->productCategories->category_slug}}">
                                                    {{$product->productCategories->text}}
                                                <span></span>
                                            </label>                                        
                                        @endforeach
                                    
                                    
                                        <label class="btn">
                                            <input class="category_filter" type="checkbox" value="test-data" @if(in_array('test-data',@$data['categories']) ) checked @endif>
                                                Test data
                                            <span></span>
                                        </label>                                        
                                    
                                </div>
                            </div>
                        </div>
                    </div>                    
                    
                    
                </div>
                
                <!--Rightsidebar Start-->                
                <div class="rightcolumn clearfix">       
                   <div id="product_listing">
                        @include('front.seller_store._partials.decendentsProductListing')
                   </div>                   
                <!--Rightsidebar Close-->
                </div>
            <!--Content Part End-->    	
        </div>  			
    </section>
    <!-- End container here -->
</div>

<!--Compare Product-->

@endsection
@push('scripts')

<script>
var product_page_count=0;
var nextPage=2;
var previousPage=1;
$( "body" ).delegate( "#next,#prev,#firstpage,#lastpage", "click", function(event, state) {
    
        var selfObj=$(this);
        var product_page_count=Math.ceil((parseInt($('#products_count').html())/{{config('project.category_product_limit')}}));
        
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
            //return false;
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

 
 
$('#productsearch').keypress(function(e) {
    if(e.which == 13) {
        $('.category_filter').trigger('change');
    }
});
 
 $('#go').click(function(){
     $('.category_filter').trigger('change');
 });
 
 $('.category_filter_all').change(function(e){        
    if($('.category_filter:checked').length==0) {
        $(this).prop('checked','true');
        e.preventDefault();
        return false;
    }
    
    $('.category_filter:checked').prop('checked', false);
     $('.category_filter').trigger('change');
 });
 
 $('.category_filter').change(function(){
     url=window.location.pathname;
     
     parameters=[];
     //if($(this).val()!='all'){
        $('.category_filter:checked').each(function(){
            parameters.push($(this).val());
        });
     //}
     if($('#productsearch').val()!=''){
        url=url+'?q='+$('#productsearch').val(); 
     }
     
     if(parameters.length>0){
        if($('#productsearch').val()==''){
            url=url+'?';
        }
        url=url+'&categories='+parameters.join('_');
     }
     
     window.location.href=url;
});
  </script>
@endpush