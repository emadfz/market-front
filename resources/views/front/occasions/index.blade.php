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
         <li class="active">
             Occasions
         </li>
      </ul>
  	</div>
 </div>
<!--breadcrumb Start-->

    <div class="widecolumn category-listing clearfix">
		<h2>Occasions</h2>
        <ul class="category-nav clearfix">
<!--             @foreach (range('A', 'Z') as $char) 
                <li><a href="{{ url('categories').'/'.strtolower($char)}}" title="A">{{$char}}</a></li>
             @endforeach-->
        </ul>
        <div class="bg-color clearfix">
            <div class="category-list">
                <ul class="modal-greetingcard">                    
                @foreach($occasions as $occasion)
                    <li>
                        <div class="custom-radio">
                            <a href="{{URL('/occasion/'.$occasion->name)}}">
                                @if( isset($occasion->Files[0]) )
                                  <img src="{{config('project.admin_route').'/images/occasions/small/'.$occasion->Files[0]->path}}" data-src="{{config('project.admin_route').'/images/occasions/small/'.$occasion->Files[0]->path}}" alt="{{$occasion->name}}" width="223" height="162">
                                 @endif
                                  <div class="overly"></div>

                                  <p>{{$occasion->name}}</p>
                            </a>
                        </div>
                    </li>
                  
            
                
                @endforeach               
                </ul>
            </div>  
        </div>
        

    </div>
</div>  			
</section>
<!-- End container here -->



<a href="#" id="back-to-top" title="Back to top"></a> 
</div>
<!-- End Wrapper -->

<!-- jquery.min and bootstrap.min is available here. You can add other minified file here...--> 
<script src="bootstrap/js/lib.min.js"></script> 
<!-- Custom javascript code goes in general.js file... --> 
<script src="bootstrap/js/general.js"></script>

@endsection