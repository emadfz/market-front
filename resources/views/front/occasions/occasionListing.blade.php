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
                    <li>
                        <a href="{{URL('occasions')}}">Occasions</a>
                    </li>
                    <li class="active">
                        {{@$occasionDetail->name}}
                    </li>
                </ul>
            </div>
        </div>
            <!--breadcrumb End-->

            <!--Content Part Start-->    
            <div class="fullcolumn clearfix category-page">
                <div class="fullcolumn tab-content content-white" style="border:solid thin gray;height:100px;">
                    <div style="height:90%;margin-top:5px;width:20%;margin-left:20px;float:left;">                        
                        @if( isset($occasionDetail->Files[0]) )
                                  <img src="{{config('project.admin_route').'/images/occasions/small/'.@$occasionDetail->Files[0]->path}}" data-src="{{config('project.admin_route').'/images/occasions/small/'.@$occasionDetail->Files[0]->path}}" alt="{{@$occasionDetail->name}}" width="100" height="100">
                        @endif
                    </div>
                    <div style="height:90%;margin-top:5px;width:70%;margin-left:20px;float:right;">
                        <h3>{{@$occasionDetail->name}}</h3>
                    </div>
                </div>
                <!--Leftsidebar start-->
                <a href="#" id="filtershow" class="btn btn-info visible-xs-inline-block">Filter Option</a>
                
                <div class="leftcolumn clearfix">                    
                    <div class="panel">
                        <h4>
                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#pricerange">Search Product</a>
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
                    
                    
                    
                </div>
                
                <!--Rightsidebar Start-->                
                <div class="rightcolumn clearfix">
                   <div id="product_listing">
                        @if(isset($products))
                            @include('front.seller_store._partials.decendentsProductListing')
                        @endif
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
    $('#productsearch').keypress(function(e) {
        if(e.which == 13) {
            $('#go').trigger('click');
        }
    });
    $('#go').click(function(){        
        if($('#productsearch').val()!=''){
            window.location.href=window.location.pathname+'?q='+$('#productsearch').val(); 
        }
        else{
            window.location.href=window.location.pathname; 
        }
    });
</script>
@endpush