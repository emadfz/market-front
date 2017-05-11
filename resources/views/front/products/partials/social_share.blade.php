<?php $shareUrl = request()->fullUrl(); ?>
<div class="social-connect clearfix">
    <div>
        <h3>Share</h3>
        <ul class="social-share">
            <li class="facebook"><a href="https://www.facebook.com/sharer/sharer.php?u={{urlencode($shareUrl)}}" title="Facebook" target="_blank"></a></li>
            <li class="twitter"><a href="https://twitter.com/intent/tweet?url={{ urlencode($shareUrl) }}" target="_blank" title="Twitter"></a></li><!-- &text=SitePoint&hashtags=web,development -->
            <li class="google"><a href="https://plus.google.com/share?url={{ urlencode($shareUrl) }}" target="_blank" title="Google+"></a></li>
            <li class="pintrest"><a href="https://pinterest.com/pin/create/button/?{{http_build_query(['url' => $shareUrl,'media' => env('APP_ADMIN_URL').'/images/products/main/'.@$product->Files[0]->path, 'description' => $product->description])}}" target="_blank" title="Pinterest"></a></li>
            <li class="linkedin"><a href="http://www.linkedin.com/shareArticle?mini=true&url={{ urlencode($shareUrl) }}" title="Linkedin"></a></li>
            <!--<li class="instagram"><a href="#" title="Instagram"></a></li><li class="youtube"><a href="#" title="You Tube"></a></li>-->
        </ul>
        <?php
        /*<a href="https://api.addthis.com/oexchange/0.8/forward/facebook/offer?url=http%3A%2F%2Fkoshk.dev&pubid=USERNAME&ct=1&title=AddThis%20-%20Get%20likes%2C%20get%20shares%2C%20get%20followers&pco=tbxnj-1.0" target="_blank"><img src="https://cache.addthiscdn.com/icons/v2/thumbs/32x32/facebook.png" border="0" alt="Facebook"/></a>
        <a href="https://api.addthis.com/oexchange/0.8/forward/twitter/offer?url=http%3A%2F%2Fkoshk.dev&pubid=USERNAME&ct=1&title=AddThis%20-%20Get%20likes%2C%20get%20shares%2C%20get%20followers&pco=tbxnj-1.0" target="_blank"><img src="https://cache.addthiscdn.com/icons/v2/thumbs/32x32/twitter.png" border="0" alt="Twitter"/></a>
        <a href="https://api.addthis.com/oexchange/0.8/forward/google_plusone_share/offer?url=http%3A%2F%2Fkoshk.dev&pubid=USERNAME&ct=1&title=AddThis%20-%20Get%20likes%2C%20get%20shares%2C%20get%20followers&pco=tbxnj-1.0" target="_blank"><img src="https://cache.addthiscdn.com/icons/v2/thumbs/32x32/google_plusone_share.png" border="0" alt="Google+"/></a>
        <a href="https://www.addthis.com/bookmark.php?source=tbx32nj-1.0&v=300&url=http%3A%2F%2Fkoshk.dev&pubid=USERNAME&ct=1&title=AddThis%20-%20Get%20likes%2C%20get%20shares%2C%20get%20followers&pco=tbxnj-1.0" target="_blank"><img src="https://cache.addthiscdn.com/icons/v2/thumbs/32x32/addthis.png" border="0" alt="Addthis"/></a>
        <div class="addthis_inline_share_toolbox"></div>*/
        ?>
    </div>
</div>
@include('front.reusable.social_share_meta_tags', ['model' => $product])