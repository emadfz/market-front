<div class="col-md-5 product-leftcol">
    <div class="productdata-gallery">

        <div class="ribbon day"><span>1 Day Left</span></div>

        <a href="#" title="Favorite" class="like">Favorite</a>

        <div class="zoom"><span></span>Click to Zoom</div>

        <div class="bxslider-gallery">
<!--            <div class="item"><a rel="position:'body'" class='cloud-zoom' href="{{asset('assets/front/img/z1.png')}}"><img src="{{asset('assets/front/img/z1.png')}}" alt=""  width="450" height="452"></a></div>
            <div class="item"><a rel="position:'body'" class='cloud-zoom' href="{{asset('assets/front/img/product-detail-1.jpg')}}"><img src="{{asset('assets/front/img/product-detail-1.jpg')}}" alt=""></a></div>-->

            @foreach ($product->Files as $file)
            <div class="item">
                <a rel="position:'body'" class='cloud-zoom' href="{{ env("APP_URL").'/images/products/main/'.$file->path }}">
                    <img src="{{ env("APP_URL").'/images/products/small/'.$file->path }}" alt="" width="450" height="452" />
                </a>
            </div>
            @endforeach
            <div class="item"><iframe src="http://player.vimeo.com/video/17914974" width="450" height="452" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>
           
        </div>

        <div id="bx-pager">
            <?php $i=0; ?>
            @foreach ($product->Files as $file)
            <a data-slide-index="{{$i}}" href="#"><img src="{{ env("APP_URL").'/images/products/thumbnail/'.$file->path }}" alt="" width="55" height="67"></a>
            <?php $i++; ?>
            @endforeach
            <a href="#">
                <img src="{{asset('assets/front/img/small-8.jpg')}}" alt="" width="55" height="67"><span class="video-icon"></span>
            </a>
        </div>

    </div>
</div>