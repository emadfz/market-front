<!--breadcrumb Start-->
<div class="row">
    <div class="col-md-12">
        <ul class="breadcrumb">
            <li><a href="{{route('sellerDashboard')}}">Home</a></li>
            <?php foreach ($breadcrumbs as $k => $value) { ?>
                <li><?php echo $value != '' ? '<a href="'.$k.'" class="active">'.$value.'</a>' : $k; ?></li>
            <?php } ?>
        </ul>
    </div>
</div>
<!--breadcrumb Start-->