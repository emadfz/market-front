<div class="container">
    <div class="compare-left">
        <span class="body-semibold">Your compare product list</span>
    </div>
    <div class="compare-right">
        <ul class="compare-quick">
            {{$url=''}}
            {{$slug=''}}
            @foreach($category_products as $product)
            <li>
                <span class="thumbbox">
                    <a href="{{URL('product/'.$product->product_slug)}}">
                        @if(isset($product->Files) && $product->Files->count()>0)
                                    {{!$condition = isset($product->Files[0]) && !empty($product->Files[0])}}
                                    @if($condition)    
                                        <img src="{{env('APP_URL')}}/images/products/small/{{@$product->Files[0]->path}}" alt="Compare">
                                    @else
                                        <img src="{{env('APP_ADMIN_URL')}}/assets/admin/layouts/layout4/img/no-image-main.png" alt="Compare">
                                    @endif                                                                        
                        @else                                                                                                        
                                        <img src="{{env('APP_ADMIN_URL')}}/assets/admin/layouts/layout4/img/no-image-main.png" alt="Compare">
                        @endif
                    </a>
                </span>
                <span class="title"><a href="{{URL('product/'.$product->product_slug)}}">{{$product->name}}</a></span>
                <a href="javascript:void(0)" data-productid="{{encrypt($product->id)}}" class="product-close" title="Delete">Delete</a>
            </li>
            {{ !$slug.='/'.$product->product_slug  }}
            @endforeach
        </ul>
        <div class="comparebtn-outer">            
            @if($category_products->count()<2)
                <a href="javascript:void(0)"  class="btn btn-primary btn-sm" disabled  title="Compare">Compare</a>
            @else
                <a href="{{ URL('/compare/'.$category_slug.$slug) }}"  class="btn btn-primary btn-sm"   title="Compare">Compare</a>
            @endif
            <a href="javascript:void(0)" id="clearcompare" class="cancel-link" title="Clear all">Clear all</a>
        </div>
    </div>
</div>
@if($category_products->count()<1)
<script>        
    $(".compare-bottom").fadeOut(200);
</script>
@endif