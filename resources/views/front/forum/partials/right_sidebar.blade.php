
<div class="rightcol-bg padd-botnone clearfix">
    <div class="equal-column">
    <h4>{{trans('forum.common.subtitle')}}</h4>

    <div class="row forums-catlist">
        <ul class="forums-cate">

            @foreach ($categories as $item)
            <?php  $color = (isset($item->color) && !empty($item->color))?$item->color:'#5ca4a9'; ?>               
            <li style="background:{{ $color }} none repeat scroll 0 0">
                <div class="infoouter">
                    <div class="info-front">
                        <div class="forums-caticon">
                            <img src="{{ env('APP_ADMIN_URL').'/images/departments/thumbnail/'.$item['Files']['path'] }}" alt="image" >
                        </div>
                        <h4>{{ ucwords($item->department_name) }}<span><b>({{ $item->topics }})</b></span></h4>
                    </div>
                    <div class="info-back">
                        <div class="infoinner">
                            <p>Search Topic</p>
                            <div class="input-search ui-widget">
                                <label for="forum_tags" class="sr-only">Search Topic: </label>
                                <input id="{{ $item->id }}" type="text" class="form-control padd-right35 forum_tags" placeholder="Search Topic">
                                <a href="#" class="search-icon"></a> 
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
    </div>
</div>