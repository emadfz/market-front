@extends('front.sell.layout')

@section('pageContent')


{!! Form::open(['route' => ['postClassifiedProduct'], 'class' => ' ajax', 'method' =>'post', 'files'=>true, 'id' => 'productFormSubmitId'])!!}
        @include('front.sell.classified_product._form')
{!! Form::close() !!}

@endsection

@push('breadcrumb')
@include('front.reusable.breadcrumb',['breadcrumbs' => ['Product listing'=>'']])
@endpush

@push('styles')
@endpush

@push('scripts')
<script type="text/javascript">
$(document).ready(function() {
    var max_fields      = 10; //maximum input boxes allowed
    var wrapper         = $(".addjquery"); //Fields wrapper
    var add_button      = $(".link"); //Add button ID
    var x = 1; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        if(x < max_fields){ //max input box allowed
            x++; //text box increment
            $(wrapper).append('<div class="scheduled-outer"><div class="col-md-4"><div class="outer-field dateouter"><input type="text" class="form-control datepicker-ui" mindate="0" name="available_date[]"><button type="button" class="ui-datepicker-trigger">...</button></div></div><div class="col-md-4"><div class="outer-field tofrom-outer"><label>From</label><input type="text" class="form-control" name="from_time[]"></div></div><div class="col-md-4"><div class="outer-field tofrom-outer"><label>To</label><input type="text" class="form-control" name="to_time[]"></div></div><a href="#" class="remove_field">Remove</a></div>'); //add input box
        }
    $('.datepicker-ui').datepicker();
  });
    
    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); $(this).parent('div').remove(); x--;
    })

    @if($updateFlag == TRUE && !empty($productData['productOriginAddress']))
        $('input:radio[name="product_origin"]').filter('[value="No"]').attr('checked', true);
        $("div#productOriginAddressId").show();
    @else
        $('input:radio[name="product_origin"]').filter('[value="Yes"]').attr('checked', true);
    @endif

    $('.productSubmitCls').click(function(){
      $("input[name=submit_type]").val($(this).val());
        CKupdate();
      $('form[id=classifiedProductFormSubmitId]').submit();
    });

    function CKupdate(){
        for ( instance in CKEDITOR.instances )
            CKEDITOR.instances[instance].updateElement();
    }

});

$(document).on("change", "input[name=product_origin]", function () {
  this.value == 'No' ? $("#productOriginAddressId").show() : $("#productOriginAddressId").hide();
});




</script>

<script src="http://cdn.ckeditor.com/4.5.3/standard/ckeditor.js"></script> 
<script>
CKEDITOR.replace("ckeditor", {
    uiColor: "#F5F5F5",
    toolbar: "standard",
    toolbarLocation: 'bottom',
    scayt_autoStartup: false,
    enterMode: CKEDITOR.ENTER_BR,
    resize_enabled: true,
    disableNativeSpellChecker: false,
    htmlEncodeOutput: false,
    height: 140,
    removePlugins: 'elementspath',
    editingBlock: false,
    toolbarGroups:[{"name": "basicstyles", "groups": ["basicstyles", "cleanup"]},{"name": "links", "groups": ["links"]},{"name": "document", "groups": ["mode"]}],
    removeButtons: 'Source,RemoveFormat,Superscript,Anchor,Styles,Specialchar'
});
</script>

@endpush
