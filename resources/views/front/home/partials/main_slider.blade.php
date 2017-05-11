<?php 
$k = 0;
$b=0;
$count= round(count($advertisements['Main_Box']) / 5);
for($i=0;$i < $count;$i++) {
    ?>
<div class="homeslide-outer">

<!-- Start Slider one --> 
<div class="row ">
    <div class="col-lg-12">
        <div class="slider-main homeslide">
            <div class="slides-outer">
                <ul class="bxslider">
                    <?php for($j=$k;$j< $k+5;$j++) { 
                            if(isset($advertisements['Main_Box'][$j]) && isset($advertisements['Main_Box'][$j]->Files)){
                        ?>
                        <li><a href="{{@$advertisements['Main_Box'][$j]->advr_url}}"><img src="http://php54.indianic.com/marketplace/public/images/advertisements/main/{{@$advertisements['Main_Box'][$j]->Files[0]->path}}" alt="Chania" width="820" height="450"><span class="btn btn-primary" title="Shop Now">shop now</span></a></li>
                    <?php }}
                    $k = $k +5;
                    ?>
                </ul>
            </div>
            <div class="small-banner">
                <?php for($m=$b;$m< $b+3;$m++) { 
                    if(isset($advertisements['Banner'][$m]) && isset($advertisements['Banner'][$m]->Files[0]) && ($advertisements['Banner'][$m]->Files[0]->path != '')){
                        ?>
                <a href="{{@$advertisements['Banner'][$m]->advr_url}}"><img src="http://php54.indianic.com/marketplace/public/images/advertisements/main/{{@$advertisements['Banner'][$m]->Files[0]->path}}" width="340" height="143" alt=""></a>
                <?php }}
                    $b = $b +3;
                    ?>
            </div>
        </div>
    </div>
</div>
<!-- End Slider one -->
<!-- Start Slider Two -->
<div class="row">
    <div class="col-lg-12">
        <div class="slider-main slider-rth homeslide">
            <div class="slides-outer">
                <ul class="bxslider">
                      <?php 
                         for($j=$k;$j< $k+5;$j++) { 
                            if(isset($advertisements['Main_Box'][$j]) && isset($advertisements['Main_Box'][$j]->Files[0])){
                        ?>
                        <li><a href="{{@$advertisements['Main_Box'][$j]->advr_url}}"><img src="http://php54.indianic.com/marketplace/public/images/advertisements/main/{{@$advertisements['Main_Box'][$j]->Files[0]->path}}" alt="Chania" width="820" height="450"><span class="btn btn-primary" title="Shop Now">shop now</span></a></li>
                      <?php }}
                    $k = $k +5;
                    ?>
                </ul>
            </div>
            <div class="small-banner">
            <?php for($m=$b;$m< $b+3;$m++) { 
                if(isset($advertisements['Banner'][$m]) && isset($advertisements['Banner'][$m]->Files[0]) && ($advertisements['Banner'][$m]->Files[0]->path != '')){
            ?>
                <a href="{{@$advertisements['Banner'][$m]->advr_url}}"><img src="http://php54.indianic.com/marketplace/public/images/advertisements/main/{{@$advertisements['Banner'][$m]->Files[0]->path}}" width="340" height="143" alt=""></a>
            <?php }}
                $b = $b +3;
            ?>    
            </div>
        </div>
    </div>
</div>
<!-- Start Slider Two -->
</div>
<?php } ?>