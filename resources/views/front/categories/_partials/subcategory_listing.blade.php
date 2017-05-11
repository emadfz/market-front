<style>
    .subnavigation{display:none;}      
</style>
<a href="#" class="close-filter">Close</a>
@if($categories['children']->count()>0)
        @foreach($categories['children'] as $key=>$category)        
            <div class="leftcol-outer">                
                <h4>
                    <a href="{{URL('/c/'.$category->category_slug)}}" title="{{$category->text}}">{{$category->text}}</a>
                    @if($category->children->count()>0)
                        <img src='{{asset('/assets/front/img/bgi/bottom_arrow.png')}}'/>
                    @endif
                </h4>
                
                @if($category->children->count()>0)
                    <ul class="subnavigation" @if($key==0) {{'style=display:block'}}@endif>
                        @foreach($category->children as $subcategory)
                            <li>
                                <a href="{{URL('/c/'.$subcategory->category_slug)}}" title="{{$subcategory->text}}">
                                    {{$subcategory->text}}
                                </a>
                            </li>
                        @endforeach                  
                    </ul>
                @endif
            </div>            
        @endforeach                  
@endif