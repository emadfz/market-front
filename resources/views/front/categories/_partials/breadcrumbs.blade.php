<div class="row">
    <div class="col-md-12">
        <ul class="breadcrumb">
            <li><a href="{{URL('')}}">Home</a></li>            
            @foreach($breadcrumbs as $breadcrumb)
            <li class="active">
                <a href="{{URL('/c/'.$breadcrumb['category_slug'])}}" title="{{$breadcrumb['text']}}">{{$breadcrumb['text']}}</a>
            </li>
            @endforeach
            <!--         <li class="active">mobile</li>-->
        </ul>
    </div>
</div>