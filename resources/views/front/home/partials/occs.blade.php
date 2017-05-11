{{-- image --}}
@section('occ_img')
<a href="#" class="head-advertise"><img src="{{asset('assets/front/img/header-advertise.jpg')}}" alt="" height="50" width="254"></a>
@endsection
{{-- scripts  --}}
@section('occ_script')
	 
<script type="text/javascript">
window.onload=function(){
    if ($('.count').length > 1) {
                        setInterval(function(){
				    $('.count').hide();
				     var randomImage=Math.ceil($('.count').length*Math.random());
					$('.count:eq('+randomImage+')').show();
				    
			},3000);
    }
}
</script>
@endsection
 {{-- end scripts --}}