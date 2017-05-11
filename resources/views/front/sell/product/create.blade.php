@extends('front.sell.layout')

@section('pageContent')

@if($updateFlag)
    {!! Form::model($productData, ['route' => ['postProduct', $step, $productId], 'class' => 'update-product-form ajax', 'files'=>true, 'method' =>'post', 'id' => 'productFormSubmitId'])!!}
        @include('front.sell.product._form', ['step' => $step, 'model' => $productData])
    {!! Form::close() !!}
@else
    {!! Form::open(['route' => ['postProduct', $step, $productId], 'class' => 'create-product-form ajax', 'method' =>'post', 'files'=>true, 'id' => 'productFormSubmitId'])!!}
        @include('front.sell.product._form', ['step' => $step])
    {!! Form::close() !!}
@endif

@endsection

@push('breadcrumb')
@include('front.reusable.breadcrumb',['breadcrumbs' => [route('listingProduct') => 'Product listing','Add product'=>'']])
@endpush