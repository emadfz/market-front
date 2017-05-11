<div class="sell-productstep1">
    <h5>Product images and videos</h5>
    <hr>

    {{ $success = Session::get('message') }}
@if($success)
    <div class="alert-box success">
        <h2>{{ $success }}</h2>
    </div>
@endif

    <!--Product Add Filter Start-->
    <div class="sell-addproduct "> 
        <!--Upload Base Image Start-->
        @include('front.sell.product.partial.image_video_upload')
        <!--Upload Base Image End-->
        <hr>
        <!--Meta Information Start-->
        @include('front.sell.product.partial.meta_tag')
        <!--Meta Information End-->
        <hr>
        <div class="form-btnblock text-right nomargin">
            <input type="hidden" value="" name="submit_type" />
            <input type="button" title="Save as draft" class="btn btn-lightgray productSubmitCls" value="Save as draft" />
            <a href="{{route('createProduct',['step_one', $productId])}}" title="Previous" class="btn btn-lightgray">Previous</a>
            <input type="button" title="Next" class="btn btn-primary productSubmitCls" value="Next" />
        </div>
    </div>
</div>
@push('scripts')
<script type="text/javascript">
    $('.productSubmitCls').click(function () {
        $("input[name=submit_type]").val($(this).val());
        $('form[id=productFormSubmitId]').submit();
    });
</script>
@endpush