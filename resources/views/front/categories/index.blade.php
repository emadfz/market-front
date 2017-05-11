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
                <li>
                    <a href="{{ url('categories').'/'.strtolower($char)}}" title="{{$char}}">{{$char}}</a>
                </li>
             @endforeach
        </ul>
        <div class="bg-color clearfix">
            <div class="category-list">
                @foreach($categories as $category)
                    @if($category['status']=='Active')
                        <h5><a href="{{URL('/c/'.$category['category_slug'])}}">{{$category['text']}}</a></h5>
                        <ul class="clearfix">
                            @foreach($category['children'] as $subcategory)
                                @if($subcategory['status']=='Active')
                                    <li>
                                        <a href="{{URL('/c/'.$subcategory['category_slug'])}}" title="{{$subcategory['text']}}">
                                            {{$subcategory['text']}}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @endif
                @endforeach
               
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

<!-- jquery.min and bootstrap.min is available here. You can add other minified file here...--> 
<script src="bootstrap/js/lib.min.js"></script> 
<!-- Custom javascript code goes in general.js file... --> 
<script src="bootstrap/js/general.js"></script>

@endsection