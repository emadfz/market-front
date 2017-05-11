if (!("ontouchstart" in document.documentElement)) {
    document.documentElement.className += " no-touch";
} else {
    document.documentElement.className += " touch";
}
(function($) {
    /**********************Defining methods STARTS****************/
    /*Utilizing the Modernizr object created to implement placeholder functionality*/
    var hasPlaceholderSupport = function() {
        var input = document.createElement('input');
        return ('placeholder' in input);
    };

    /**************************************************
    @Purpose: To make it work placeholder in IE 8/9 
    @Return: N/A
    @CreatedBy: Company name (for which organization you create the project NOT INDIANIC)
    @Parameter: N/A
    ***************************************************/
    var showPlaceholder = function() {
        if (!Modernizr.input.placeholder) {
            $('[placeholder]').focus(function() {
                var input = $(this);
                if (input.val() == input.attr('placeholder')) {
                    input.val('');
                    input.removeClass('placeholder');
                }
            }).blur(function() {
                var input = $(this);
                if (input.val() === '' || input.val() === input.attr('placeholder')) {
                    input.addClass('placeholder');
                    input.val(input.attr('placeholder'));
                }
            }).blur();
            $('[placeholder]').parents('form').submit(function() {
                $(this).find('[placeholder]').each(function() {
                    var input = $(this);
                    if (input.val() == input.attr('placeholder')) {
                        input.val('');
                    }
                });
            });
        }
    };
    /**************************************************
    @Purpose: For sidebar menu 
    @Return: Array/ value / function / Object
    @CreatedBy: Company name (for which organization you create the project NOT INDIANIC)
    @Parameter: {
           element : The element that will have the class removed
           className : The name of the class that will be removed from the element
    } 
    ***************************************************/
    var removeClassAnimation = function(element, className) {
        //return { "name": "ck"};
        //return ["1","jeff",333,];
        //return function() {};
        //return 4;
    };

    /**********************Defining methods END****************/
    /*Initializing START*/
    $(document).ready(function() {
        $('a[href="#"]').click(function(e) {
            e.preventDefault();
        });
	    //Bootstrap Tooltip Function
        $('[data-toggle="tooltip"]').tooltip();
        //Bootstrap Select Dropdown Start
        $('.selectpicker').selectpicker();
        //Bootstrap Select Dropdown End		
		$('.modal').modal({
  			keyboard: false,
			backdrop:'static',
			show:false
		});
        //Sticy Navbar start
        var offset = $('.stickynav').offset();
        $(window).scroll(function() {
            $('.stickynav').addClass('affix');
            $(".content").css('margin-top', '50px')
            if ($(document).scrollTop() < 50) {
                $(".content").css('margin-top', '0px')
                $('.stickynav').removeClass('affix');
            }
        });
        //Sticy Navbar Close
		//collapse Up/Down arrow
		$('.collapse').on('shown.bs.collapse', function(){
			$(this).parent().find(".glyphicon-chevron-up").removeClass("glyphicon-chevron-up").addClass("glyphicon-chevron-down");
			}).on('hidden.bs.collapse', function(){
			$(this).parent().find(".glyphicon-chevron-down").removeClass("glyphicon-chevron-down").addClass("glyphicon-chevron-up");
		});
		//
		//Sign In Modal
		$(".registrashow").on("click",function(){
			$("#registrapopup").addClass("effectslide");
			if($("#siginInAdjust").hasClass("modal-md")){
				$("#siginInAdjust").removeClass("modal-md").addClass("modal-lg");
                                $(this).parent().hide();
			}
			else if($("#siginInAdjust").hasClass("modal-lg")){
				$("#registrapopup").removeClass("effectslide");
				$("#siginInAdjust").removeClass("modal-lg").addClass("modal-md");
			}
		});

		$("#signInId,#showregistration-cancel").on("click",function(){
			if($("div#siginInAdjust").hasClass("modal-lg")){
                                $('.registrashow').parent().show();
				$("#registrapopup").removeClass("effectslide");
				$("#siginInAdjust").removeClass("modal-lg").addClass("modal-md");
			}
		});
		//Back to top Function Start
        if ($('#back-to-top').length) {
            var scrollTrigger = 100, // px
                backToTop = function() {
                    var scrollTop = $(window).scrollTop();
                    if (scrollTop > scrollTrigger) {
                        $('#back-to-top').addClass('show');
                    } else {
                        $('#back-to-top').removeClass('show');
                    }
                };
            backToTop();
            $(window).on('scroll', function() {
                backToTop();
            });
            $('#back-to-top').on('click', function(e) {
                e.preventDefault();
                $('html,body').animate({
                    scrollTop: 0
                }, 300);
            });
        }
        //Back to top Function Close
        // Custom File Upload
		var media = $('#uploadvideo');
		if (media.length) {
			var mediaDefaultValue = $('.file span.value').text();
			var mediaCharLimit = 20;
			$('.file .btn-primary').click(function() {
				media.click();
			});
			media.on('change', function() {
				var value = this.value.replace("C:\\fakepath\\", "");
				var newValue;
				var valueExt;
				var charLimit;
				if (value) {
					newValue = value;
					valueExt = value.split('.').reverse()[0];
					if (newValue.length > mediaCharLimit) {
						charLimit = mediaCharLimit - valueExt.length;
						// truncate chars.
						newValue = $.trim(value).substring(0, charLimit) + 'â€¦';
						// if file name has extension, add it to newValue.
						if (valueExt.length) {
							newValue += valueExt;
						}
					}
				}
				else {
					newValue = mediaDefaultValue;
				}
				$(this).parent().find('span.value').text(newValue);
			});
		}
		// Custom File Upload close
		//Extend Datatable Default Setting
    	$.extend( true, $.fn.dataTable.defaults, {
		"scrollX": true,	
        "language": {
				"lengthMenu": "Show entries _MENU_",
				"zeroRecords": "No records found",
				"info": "Showing page _PAGE_ of _PAGES_",
				"infoEmpty": "No records found",
				"infoFiltered": "(filtered from _MAX_ total records)"
        },
        order: [[1, 'desc']],
        });
		//Extend Datatable Default Setting End
		
		
        //Common Function below
        SetDatePicker(); // Datepicker Function
        filtermobile(); // Filter Show lessthan 768
		productnav(); //Product Sidebar Navgation sell,Buy
		compareProduct();
//        
//BxSlider Mingle-Getconnected,Category,Subcategory
$('.bxsliderSlide').bxSlider({
    mode: 'horizontal',
});
//
$('.bxslider-nopager').bxSlider({
    mode: 'horizontal',
    pager:false
});
// BxSlider Carousel Category Page/Subcategory
$('.bxcarousel-image').bxSlider({
    slideWidth:186,
    minSlides: 1,
    maxSlides:5,
    slideMargin:10,
    moveSlides:1,
    pager:false

});
// BxSlider Carousel Category Page Product View Leftsidebar ThunbSlider Start
$(".bxsliderThumb").bxSlider({
    slideWidth:70,
    minSlides: 1,
    maxSlides:2,
    slideMargin:10,
    moveSlides:1,
    pager:false
});
// BxSlider Carousel Category Page Product View Leftsidebar ThunbSlider End
//Product Detail Gallery Start
$('.bxslider-gallery').bxSlider({
    pagerCustom: '#bx-pager',
     video: true,
     adaptiveHeight:true

});
//Product Detail Gallery End
    });
    //JQueryUi datepicker start
    function SetDatePicker() {
        var e = $(".datepicker-ui");
        e.each(function() {
            var e = $(this),
                t = e.attr("mindate"),
                i = e.attr("maxdate"),
                a = null,
                n = null;
            if (void 0 != t && "" != t && (a = "0" != t ? new Date(t) : t), void 0 != i && "" != i && (n = "0" != i ? new Date(i) : i), e.hasClass("daterange")) {
                var o = e.attr("startdateid"),
                    s = e.attr("enddateid");
                "" != o && void 0 != o && "" != $("#" + o).val() && (a = new Date($("#" + o).val())), "" != s && void 0 != s && "" != $("#" + s).val()
            }
            e.datepicker({
                showButtonPanel: false,
                firstDay: 0,
                minDate: a,
                maxDate: n,
                showOn: "both",
                changeYear: true,
                yearRange: "-80:+50",
                dateFormat: "dd-MM-yy",
                monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                changeMonth: !0,
            })
        })
    }
    //JQueryUi datepicker End
    //Filter Show lessthan 768 Start
    function filtermobile() {
        $("#filtershow").on("click", function() {
            $(".leftcolumn").fadeIn(200);
            if ($(this).css("display", "block")) {
                $(".close-filter").on("click", function() {
                    $(".leftcolumn").fadeOut(400)
                })
            }
        });
		$(".leftcol-outer").find("h4").on("click", function() {
                if ($(this).next(".subnavigation").css('display') == "none") {
                    $(this).next(".subnavigation").slideDown();
					//$(".subnavigation").slideUp();
                } else
                    $(this).next(".subnavigation").slideUp();
        });
    }
    //Filter Show lessthan 768 End	
	//Product Sidebar Navgation sell,Buy lessthan 768 Start
    function productnav() {
        $("#productnav").on("click", function() {
            $(".leftcol-bg").fadeIn(200);
            if ($(this).css("display", "block")) {
                $(".close-filter").on("click", function() {
                    $(".leftcol-bg").fadeOut(400)
                })
            }
        });
	}
    //Product Sidebar Navgation sell,Buy lessthan 768 End
    //Comapre Product on click compare Bottom show
	function compareProduct(){
	  $("body").delegate(".compare-icon,.product-close,#clearcompare","click",function(){
                
                getcompare_path='';
                if($(this).hasClass('compare-icon')){                    
                    getcompare_path=assetsPath+'getComparedProduct/'+category_slug+'/'+$(this).data('productid');                    
                }
                else if($(this).hasClass('product-close')){                    
                    getcompare_path=assetsPath+'getComparedProduct/'+category_slug+'/'+$(this).data('productid')+'/close';  
                }
                else if($(this).hasClass('cancel-link')){
                    getcompare_path=assetsPath+'getComparedProduct/'+category_slug+'/0/clearall';
                }
                
                
                
                $('.compare-bottom').load(getcompare_path);
                
		$(this).addClass("active");
	  	$(".compare-bottom").fadeIn(200);
		if ($(this).css("display", "block")) {
                    $("body").delegate("#clearcompare","click", function() {
                        $(".compare-bottom").fadeOut(400);
                        $(this).removeClass("active") 
                    });
                }
	  });
	}
	//Comapre Product on click compare Bottom show
	$(window).load(function() {
		//Custom Scrollbar Function Start
      $(".custom-scrollbar").mCustomScrollbar({
                autoHideScrollbar: !0,
                theme: "light-thin",
                advanced: {
                    autoScrollOnFocus: false,
                },
            }), $(".custom-scrollbar-hori").mCustomScrollbar({
                horizontalScroll: !0
      }) 
	});
    $(window).resize(function() {
       if ($(window).width() < 768) {
            filtermobile(); //Filter Show leftsidebar
			productnav();// Product Sidebar Navgation sell,Buy
        } else {
            $(".leftcolumn").show();
			$(".leftcol-bg").show();
        }
	});
})(jQuery);
//Initializing END*/

//general AJAX model popup 
//-> Added By RD
$(".ajaxModal").click(function(ev) {    
    if($('#pincode').val()==''){
        $('#pincode').css('border','solid red thin');        
        return false;
    }
    $('#pincode').css('border','');        
    //$.blockUI({ message: '<h3><img src="'+assetsPath+'assets/front/img/loading-spinner-grey.gif" /> Please Wait...</h3>' });
    ev.preventDefault();
    var target = $(this).attr("href");
    var modalId = $(this).data("target");
    
    //$(modalId+" .modal-body").html('<h3><img src="'+assetsPath+'assets/front/img/loading-spinner-grey.gif" /> Please Wait...</h3>');
    $("#shippingCalculator .modal-body").html('<h3><img src="'+assetsPath+'assets/front/img/loading-spinner-grey.gif" /> Please Wait...</h3>');
    // load the url and show modal on success
    $(modalId+" .modal-body").load(target, function() { 
         $(modalId).modal("show"); 
         //$.unblockUI();
    });
});

//new added code 26 november 2016

$('.table-responsive').on('show.bs.dropdown', function () {
     $('.table-responsive').css( "overflow", "inherit" );
});

$('.table-responsive').on('hide.bs.dropdown', function () {
     $('.table-responsive').css( "overflow", "auto" );
});