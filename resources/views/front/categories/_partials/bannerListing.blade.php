 <div class="rightcol-outer">
     <div class="slides-border category-mainslide">
                        <!--Main Category Slider Start -->
                        <ul class="bxsliderSlide">                            
                            @if($categories->AdvertisementCatMap->count()>0)
                                <?php $advertiseCount=0; ?>
                                @foreach($categories->AdvertisementCatMap as $advertisementMap)
                                    <?php 
                                    if(++$advertiseCount >5){
                                        break;
                                    } 
                                    
                                    ?>
                                
                                    @if( isset($advertisementMap->AdvertisementCategory->Files[0]) && $advertisementMap->AdvertisementCategory->Files[0]->count()>0  )                                    
                                        <li>
                                            <a href="{{@$advertisementMap->AdvertisementCategory->advr_url }}">
                                                <img src="{{env('APP_ADMIN_URL')}}/images/advertisements/main/{{@$advertisementMap->AdvertisementCategory->Files[0]->path}}" alt="" width="970" height="420">
                                            </a>
                                        </li>
                                    @else
                                        <li>
                                            <a href="{{@$advertisementMap->AdvertisementCategory->advr_url }}">
                                                <img src="{{env('APP_ADMIN_URL')}}/assets/admin/layouts/layout4/img/no-image-main.png" alt="Compare" width="970" height="420" title="">
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            @else                                                 
                                    @if( isset($categories->Files[0]) && $categories->Files[0]->count()>0  )
                                        <li>                                            
                                            <a href="{{@$categories->category_slug}}">
                                                <img src="{{env('APP_ADMIN_URL')}}/images/category/main/{{@$categories->Files[0]->path}}" alt="{{@$categories->text}}" width="970" height="420">
                                            </a>
                                        </li>                                                                                                
                                    @else
                                        <li>
                                            <a href="{{@$categories->category_slug}}">
                                                <img src="{{env('APP_ADMIN_URL')}}/assets/admin/layouts/layout4/img/no-image-main.png" alt="Compare" width="970" height="420">
                                            </a>    
                                        </li>                                                                                                                                    
                                    @endif
                                
                            @endif
                        </ul>
                          </div>
                        <!--Main Category Slider Close -->
                        <!-- Subcategory item carousel-->
                        @if($categories->children->count()>0)
                        <div class="carousel-wrap">
                           <ul id="subcategory" class="bxcarousel-image">                                                                                            
                                @foreach($categories->children as $category_child)
                                    @if( isset($category_child->Files[0]) && $category_child->Files[0]->count()>0 )
                                        
                                            <li>
                                                <!--<a href="{{@$category_child->category_slug}}">-->
                                                <img src="{{env('APP_ADMIN_URL')}}/images/category/small/{{@$category_child->Files[0]->path}}" alt="{{@$category_child->text}}" width="194" height="128">
                                                <span>{{@$category_child->text}}</span>
                                                <!--</a>-->
                                            </li>                                                                                                
                                        
                                    @else
                                       
                                            <li>
<!--                                                 <a href="{{@$category_child->category_slug}}">-->
                                                <img src="{{env('APP_ADMIN_URL')}}/assets/admin/layouts/layout4/img/no-image-main.png" alt="Compare" width="194" height="128">                                            
                                                <span>{{@$category_child->text}}</span>
                                                   <!--</a>-->
                                            </li>
                                     
                                    @endif
                                @endforeach                                                                   
                            </ul>
                        </div>
                        @endif
                        <!-- End Subcategory item carousel-->
   
</div>