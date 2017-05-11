@extends('front.layouts.app')
@section('content')
<!DOCTYPE html>
<html lang="en">


    <!-- Start Wrapper -->
    <div class="wrapper"> 
        <!-- Start container here -->
        <section class="content">
            <div class="container"> 
                <!--breadcrumb Start-->
                <div class="row">
                    <div class="col-md-12">
                        <ul class="breadcrumb">
                            <li><a href="{{ URL('') }}">Home</a></li>                            
                            <li class="active">Compare</li>
                        </ul>
                    </div>
                </div>
                <!--breadcrumb Start-->
                <h2>Compare</h2>
                <div class="widecolumn compare-page comparesearch clearfix">
                    <div class="compare-table-wrap">
                        @foreach($categories as $category)
                        
                        <table class="compare-table">
                            <tbody>
                                <tr class="headtitle">
                                    <th colspan="5">{{$category->text}}
                                        <span class="pull-right">                                            
                                            {{ !$url=$category->category_slug.'/' }}
                                            @foreach($category->product as $product)
                                                {{ !$url.=$product->product_slug.'/' }}
                                            @endforeach
                                            <a href="{{ URL('compare/'.$url) }}" class="btn btn-primary btn-sm" title="Compare">Compare</a>
                                            <a href="{{ URL('compare/remove/'.$category->category_slug) }}"  class="cancel-link " title="Clear all">Clear all</a>
                                        </span>
                                    </th>
                                </tr>	
                                <tr class="product-info">
                                    <th></th>
                                    @foreach($category->product as $product)
                                        <td class="first-td">
                                            <div class="productbox">
                                                <a href="{{ URL('compare/remove/'.$category->category_slug.'/'.encrypt($product->id)) }}" class="product-close" title="Delete">Delete</a>
                                                <figure class="img">
                                                    <a href="{{URL('product/'.$product->product_slug)}}">
                                                        @if(isset($product->Files) && $product->Files->count()>0)
                                                            <?php                               
                                                            $file=@$product->Files[0];                                    
                                                            $condition = isset($file) && !empty($file); ?>
                                                            
                                                            @if($condition)
                                                                <img src="{{env('APP_URL')}}/images/products/main/{{@$file->path}}" alt="Compare" width="218" height="174">
                                                            @else
                                                                <img src="{{env('APP_ADMIN_URL')}}/assets/admin/layouts/layout4/img/no-image-main.png" alt="Compare" width="218" height="174">
                                                            @endif    
                                                        @else
                                                                <img src="{{env('APP_ADMIN_URL')}}/assets/admin/layouts/layout4/img/no-image-main.png" alt="Compare" width="218" height="174">
                                                        @endif
                                                    </a>                                                    
                                                </figure>
                                                <div class="item-detail"> 
                                                    <a href="#" class="title">
                                                        {{$product->name}}
                                                    </a>                                                    
                                                    <div class="price">
                                                        <del>                                                                                                                        
                                                            <em><?php print(convert_currency( $product->base_price , session()->get('currency_rate')));?></em>
                                                        </del>
                                                        <span>
                                                            $ @foreach($product->productSkus as $productSku)
                                                                @if($productSku->is_default=='Yes')
                                                            <?php print(convert_currency($productSku->final_price, session()->get('currency_rate')));?> 
                                                                 
                                                                @endif
                                                            @endforeach
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="item-footer"> <span>Free Shipping</span> <span class="link"> <a href="#" title="Favorite" class="favorite">Favorite</a> <a href="#" title="Cart" class="cart">Cart</a> </span> </div>
                                            </div>
                                        </td>
                                    @endforeach                
                                </tr>
                            </tbody>
                        </table>
                        @endforeach


                    </div>
                </div>
            </div>
        </section>
        <!-- End container here --> 
        @endsection
@push('scripts')
<script>
    $('.product-close,.cancel-link ').click(function(e){
        if(!confirm('Are you sure you want to remove?')){
            e.preventDefault();
        }            
    });
</script>
@endpush