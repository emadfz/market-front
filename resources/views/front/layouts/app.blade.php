<!DOCTYPE html>
<html lang="en">
<!--    <html itemscope itemtype="http://schema.org/Product">-->
<head>
    @if(isset($title))
    <title >{{$title}}</title>

    @else
    <title >Welcome to inSpree Marketplace</title>
    @endif
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><meta name="gtv-autozoom" content="off" />-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="title" content="Testing Content" />
    <meta name="description" content="Page description" />
    <meta name="keywords" content="Page keywords" />
    <!--<link rel="image_src" href="" /><link rel="canonical" href="" />-->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{asset('assets/front/ico/favicon.ico')}}" type="image/x-icon">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.debug.js"></script>

    @stack('metaTagsSocialShare')
    @yield('3stylesheet')
    <!-- Bootstrap Css -->
    @include('front.layouts.partials.styles')
    @stack('styles')
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="bootstrap/js/vendor/html5shiv.min.js"></script>
<script src="bootstrap/js/vendor/respond.min.js"></script>
<![endif]-->

</head>
<style type="text/css">
    @media print
    {    
        .no-print, .no-print *
        {
            display: none !important;
        }
    }
</style>

<body>
    <div class="wrapper">
        <!-- BEGIN HEADER -->
        @include('front.layouts.partials.header')
        <!-- END HEADER -->
        <!-- BEGIN CONTAINER -->
        @yield('content')

        <!-- BEGIN Modal SignIn -->
        @include('front.auth.modals.sign_in')
        <!-- END Modal SignIn -->

        <!-- BEGIN Modal ForgotPassword -->
        @include('front.auth.modals.forgot_password')
        <!-- END Modal ForgotPassword -->

        <!-- BEGIN Modal AdvancedSearch -->
        @include('front.home.modals.advanced_search')
        <!-- END Modal AdvancedSearch -->

        <!-- END CONTAINER -->
        @include('front.layouts.partials.footer')
        @include('front.home.partials.occs')
    </div>
    <!-- BEGIN JS -->
    @include('front.layouts.partials.scripts')
    @stack('scripts')
    <!-- END JS -->
    <!-- Go to www.addthis.com/dashboard to customize your tools -->
    <!-- Go to www.addthis.com/dashboard to customize your tools -->
    <!-- <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-57cfba077c4ae438"></script>-->
    <script type="text/javascript">
        var popupSize = {width: 780,height: 550};
        $(document).on('click', '.social-share li > a', function (e) {
            var verticalPos = Math.floor(($(window).width() - popupSize.width) / 2),
            horisontalPos = Math.floor(($(window).height() - popupSize.height) / 2);
            var popup = window.open($(this).prop('href'), 'social','width=' + popupSize.width + ',height=' + popupSize.height +',left=' + verticalPos + ',top=' + horisontalPos +',location=0,menubar=0,toolbar=0,status=0,scrollbars=1,resizable=1');
            if (popup) {popup.focus();e.preventDefault();}
        });
    </script>
</body>
</html>
