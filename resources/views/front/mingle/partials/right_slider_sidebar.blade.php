@if(count($advertisements) > 0)
<div class="rightcol-outer">
        <!--Main Category Slider Start -->
        <ul class="bxsliderSlide">
            <?php
            for ($j = 0; $j < count($advertisements); $j++) {
                if (isset($advertisements[$j]) && isset($advertisements[$j]->Files)) {
                    ?>
                    <li><a href="{{@$advertisements[$j]->advr_url}}"><img src="{{env('APP_ADMIN_URL')}}/images/advertisements/main/{{@$advertisements[$j]->Files[0]->path}}" width="930" height="450"></a></li>
                <?php
                }
            }
            ?>
        </ul>
        <!--Main Category Slider Close -->
    </div>
@endif