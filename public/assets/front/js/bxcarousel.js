var slider_Featured= $('.bxcarousel_Featured').bxSlider({
            slideWidth:195,
            minSlides :1,
            maxSlides:6,
            slideMargin:0,
            moveSlides:1,
            pager:false
});
function removeproduct_Featured(item){
        $("#" + item).parent().remove();
        slider_Featured.reloadSlider();
        return false;
}
var slider_Popular= $('.bxcarousel_Popular').bxSlider({
            slideWidth:195,
            minSlides :1,
            maxSlides:6,
            slideMargin:0,
            moveSlides:1,
            pager:false
  });
function removeproduct_Popular(item){
        $("#" + item).parent().remove();
        slider_Popular.reloadSlider();
        return false;
}
var slider_Recentlyview= $('.bxcarousel_Recentlyview').bxSlider({
            slideWidth:195,
            minSlides :1,
            maxSlides:6,
            slideMargin:0,
            moveSlides:1,
            pager:false
  });
function removeproduct_Recentlyview(item){
        $("#" + item).parent().remove();
        slider_Recentlyview.reloadSlider();
        return false;
}
var slider_Customerbuy= $('.bxcarousel_Customerbuy').bxSlider({
            slideWidth:195,
            minSlides :1,
            maxSlides:6,
            slideMargin:0,
            moveSlides:1,
            pager:false
  });
function removeproduct_Customerbuy(item){
        $("#" + item).parent().remove();
        slider_Customerbuy.reloadSlider();
        return false;
}
//BxSlider HomeSlider
  $('.bxslider').each(function(){
   var sliderInstance=$(this);
   sliderInstance.bxSlider({
             mode: 'fade',
    auto: (sliderInstance.find("li").length > 1) ? true : false
         });
  });
    

