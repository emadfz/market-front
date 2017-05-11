@extends('front.layouts.app')
@section('content')
<!-- Start Wrapper -->
<div class="wrapper">
    <!-- Start container here -->
    <section class="content">
        <div class="container"> 
            <!--breadcrumb Start-->
            <div class="row">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li><a href="index.html">Home</a></li>                        
                        <li  class="active">
                            <a href="{{URL('/compare')}}">
                                Compare
                            </a>
                        </li>
                        <li>{{$category_slug}}</li>
                    </ul>
                </div>
            </div>
            <!--breadcrumb Start-->
            <h2>Compare product for {{$category_slug}}</h2>            
            <div class="widecolumn compare-page clearfix">
                <div class="compare-table-wrap">

                    <table class="compare-table">
                        <tbody>
                            <tr class="product-info">
                                <th>
                                    <label for="difference">
                                        <a href="javascript:void(0)" id="difference" style="color:blue" >Highlights Differences</a>
                                    </label>

                                </th>                                                                
                                @foreach($products as $product)
                                <td class="first-td">                    
                                    <div class="productbox">
                                        <a href="{{ URL('compare/removeProduct/'.$category_slug.'/'.encrypt($product->id)) }}" class="product-close" title="Delete">Delete</a>
                                        <figure class="img">
                                            <a href="#">
                                                @if(isset($product->Files) && $product->Files->count()>0)
                                                <?php
                                                $file = @$product->Files[0];
                                                $condition = isset($file) && !empty($file);
                                                ?>
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
                                            <a href="#" class="title">{{$product->name}}</a>
                                            <div class="price">
                                                <del>                                                                                                                        
                                                    <em><?php print(convert_currency( $product->base_price , session()->get('currency_rate')));?></em>
                                                </del>
                                                <span>
                                                    @foreach($product->productSkus as $productSku)
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
                                
                                @for($i=4-$products->count();$i>=1;$i--)
                                    <td class="first-td"></td>
                                @endfor
                                
                                
                            </tr>
                            @foreach($variantAttributes as $indexAttributes=>$variantAttribute)
                                <tr class="headtitle">
                                    <th colspan="5">{{$indexAttributes}}</th>
                                </tr>
                            
                                @foreach($variantAttribute as $indexAttributeValues=>$attributeValues)
                                    <tr class="attribute_value">
                                        <th>{{$indexAttributeValues}}</th>                                        
                                        @foreach($products as $product)
                                            <td>{{ @$attributeValues[$product->id] }} </td>
                                        @endforeach
                                        @for($i=4-$products->count();$i>=1;$i--)
                                                <td>&nbsp;</td>
                                        @endfor
                                    </tr>
                                @endforeach
                            
                            @endforeach
                            @foreach($nonVariantAttributes as $indexAttributes=>$nonVariantAttribute)
                            <tr class="headtitle">
                                <th colspan="5">{{$indexAttributes}}</th>
                            </tr>
                            @foreach($nonVariantAttribute as $indexAttributeValues=>$attributeValues)
                            <tr class="attribute_value">
                                <th>{{$indexAttributeValues}}</th>
                                @foreach($products as $product)
                                <td>{{ @$attributeValues[$product->id] }} </td>
                                @endforeach
                                @for($i=4-$products->count();$i>=1;$i--)
                                            <td>&nbsp;</td>
                                @endfor
                            </tr>
                            @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <!-- End container here --> 
</div>
@endsection
@push('scripts')
<script>
    
    $('#difference').click(function () {
        
        if ($(this).html()=="Highlights Differences") {
            $(this).html('Clear Highlights');    
            $(".attribute_value").each(function (index) {
                temp = [];
                selfobj = $(this);
                compcount = 0;
                $(this).find('td').each(function (index) {
                    if ($.inArray($(this).html(), temp) > -1) {
                        compcount++;
                    }




                    temp.push($(this).html());

                });
                if (compcount != {{count($products)-1}}) {
                    selfobj.css('background', '#CDE4F9');
                }

            });

        } else {
            $(this).html('Highlights Differences');    
            $(".attribute_value").each(function (index) {
                $(this).css('background', 'none');

            });

        }

    });

</script>

@endpush