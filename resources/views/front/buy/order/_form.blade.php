<div class="rightcol-bg clearfix sell-addproduct">
    <h4>Add Product</h4>
    <ul class="registration-step">
        <li {{$step == 'step_one' ? 'class=active' : ''}}><a href="{{route('createProduct',['step_one', $productId])}}" title="Product details"><span>1</span></a></li>
        <li {{$step == 'step_two' ? 'class=active' : ''}}><a href="{{ isset($productId) && $productId != '0' ? route('createProduct',['step_two', $productId]) : "javascript:;" }}" title="Images and meta tags"><span>2</span></a></li>
        <li {{$step == 'step_three' ? 'class=active' : ''}}><a href="{{ isset($productId) && $productId != '0' ? route('createProduct',['step_three', $productId]) : "javascript:;" }}" title="Attributes and shipping details"><span>3</span></a></li>
        <li {{$step == 'step_four' ? 'class=active' : ''}}><a href="{{ isset($productId) && $productId != '0' ? route('createProduct',['step_four', $productId]) : "javascript:;" }}" title="Product details"><span>4</span></a></li>
    </ul>
    @include('front.sell.product.partial.'.$step)
</div>