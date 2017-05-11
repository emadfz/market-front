@extends('front.sell.layout')

@section('pageContent')

{!! Form::model($productData, ['route' => ['postProduct',['step1', encrypt($productData['id'])]], 'class' => 'form-horizontal update-product-form ajax', 'method' =>'post'])!!}
@include('front.sell.product._form', ['step' => $step, 'model' => $productData])
{!! Form::close() !!}

@endsection

@push('scripts')
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