@extends('front.layouts.app')

@section('content')
<section class="content">
    <div class="container"> 
        @stack('breadcrumb')
        @include('front.reusable.inner_menu')        
       
            
                <!--Leftside Start -->
                    
                <!--Leftside End -->
                <!--Rightside Start -->
                @yield('pageContent')
                <!--Rightside End -->
            
        </div>
    </div>
</section>
@stack('modalPopup')
@endsection