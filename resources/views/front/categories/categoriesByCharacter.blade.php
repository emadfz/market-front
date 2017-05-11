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
         <li><a href="index.html">Home</a></li>
         <li class="active">Category</li>
      </ul>
  	</div>
 </div>
<!--breadcrumb Start-->

    <div class="widecolumn category-listing clearfix">
		<h2>Category</h2>
        <ul class="category-nav clearfix">
             @foreach (range('A', 'Z') as $char) 
                <li><a href="{{ url('categories').'/'.strtolower($char)}}" title="{{$char}}">{{$char}}</a></li>
             @endforeach
        </ul>
        <div class="bg-color clearfix">
            <div class="category-list">
                   <h5>Categories -  <a href="#">{{$ch}}</a></h5>                
                <ul class="clearfix">
                @foreach($categories as $category)                
                    @if($category['status']=='Active')
                        <li><a href="{{URL('/c/'.$category['category_slug'])}}" title="{{$category['text']}}">{{$category['text']}}</a></li>                        
                    @endif    
                @endforeach
                </ul>
            </div>
			
                    
        </div>
        

    </div>
</div>  			
</section>
<!-- End container here -->



<!--Back to top --> 
<a href="#" id="back-to-top" title="Back to top"></a> 
</div>
<!-- End Wrapper -->
@endsection