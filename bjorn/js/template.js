(function($){
$(document).ready(function() {

	'use strict';
	/**
	*	Init
	*/	

	// iOS button fixes
	var iOS = false,
        p = navigator.platform;

    if (p === 'iPad' || p === 'iPhone' || p === 'iPod') {
        iOS = true;
    }
	if (iOS) {
        $('input.button, input[type="text"],input[type="button"],input[type="password"],textarea, input.input-text').css('-webkit-appearance', 'none');
        $('input').css('border-radius', '0');
    } 

    // Remove animations on touch devices
    function isTouchDevice(){
	    return true == ("ontouchstart" in window || window.DocumentTouch && document instanceof DocumentTouch);
	}

	if(isTouchDevice()===true) {
	    $("#animations-css").remove();
	}

	$("select").select2({
		allowClear: true,
		minimumResultsForSearch: 10
	});

	// Body class
	if($("body.single-post .container-page-item-title.with-bg, body.page .container-page-item-title.with-bg").length > 0) {
		$("body").addClass('blog-post-header-with-bg');

	}

	// Add images backgrounds
	$('.bjorn-post-image').each(function( index ) {
		$(this).attr('style', ($(this).attr('data-style')));
	});
	$('.bjorn-next-post').each(function( index ) {
		$(this).attr('style', ($(this).attr('data-style')));
	});
	$('.blog-post-list-layout .blog-post-thumb').each(function( index ) {
		$(this).attr('style', ($(this).attr('data-style')));
	});
	$('.blog-style-4 .blog-post .blog-post-thumb').each(function( index ) {
		$(this).attr('style', ($(this).attr('data-style')));
	});
	$('.post-content-wrapper').each(function( index ) {
		$(this).attr('style', ($(this).attr('data-style')));
	});
	$('.blog-post-related.blog-post-related-loop .blog-post-related-item .blog-post-related-image').each(function( index ) {
		$(this).attr('style', ($(this).attr('data-style')));
	});
	$('.bjorn-editorspick-post-image').each(function( index ) {
		$(this).attr('style', ($(this).attr('data-style')));
	});
	$('.bjorn-popular-post-image').each(function( index ) {
		$(this).attr('style', ($(this).attr('data-style')));
	});
	$('.sidebar .widget.widget_bjorn_text .bjorn-textwidget').each(function( index ) {
		$(this).attr('style', ($(this).attr('data-style')));
	});
	$('.container-fluid.container-page-item-title').each(function( index ) {
		$(this).attr('style', ($(this).attr('data-style')));
	});
	$('.widget-post-thumb-wrapper').each(function( index ) {
		$(this).attr('style', ($(this).attr('data-style')));
	});
	$('.widget-post-thumbsmall-wrapper').each(function( index ) {
		$(this).attr('style', ($(this).attr('data-style')));
	});
	$('.footer-html-block').each(function( index ) {
		$(this).attr('style', ($(this).attr('data-style')));
	});

	
	// Move WooCommerce sale badge
	$('.woocommerce.single span.onsale').prependTo($('.woocommerce div.product div.images.woocommerce-product-gallery'));


	// Fullscreen search
	$('.search-toggle-btn').on('click', function(e){

		$(document).keyup(function(e){
		    if(e.keyCode === 27)
		        $('.search-fullscreen-wrapper').fadeOut();
		});

		$('.search-fullscreen-wrapper').fadeIn();
		$('.search-fullscreen-wrapper .search-fullscreen-form input[type="search"]').focus();
		$('.search-fullscreen-wrapper .search-fullscreen-form input[type="search"]').val('');
	});

	$('.search-close-btn').on('click', function(e){
		$('.search-fullscreen-wrapper').fadeOut();
	});

	// Top mobile menu
	var topmenuopened = 0;
	$( document ).on( "click", ".menu-top-menu-container-toggle", function(e) {
		if(topmenuopened == 0) {
			$(this).next().slideDown();
			topmenuopened = 1;
		} else {
			topmenuopened = 0;
			$(this).next().slideUp();
		}
	});

	// Mobile menu clicks
	$('.nav li > a, .header-menu li > a').on('click', function(e){
		
		if($(window).width() < 767) {
			
			if ( $(this).next(".sub-menu").length > 0 ) {
				var sm = $(this).next(".sub-menu");
			
				if(sm.data('open') !== 1)
				{
					e.preventDefault();
					e.stopPropagation();
					sm.slideDown();

					sm.data('open', 1);

					$(this).parent().addClass('mobile-submenu-opened');

				}
				
			}
		} else {
				// Mobile menu clicks for touch devices
			if(isTouchDevice()===true) {

				if ( $(this).next(".sub-menu").length > 0 ) {
					var sm = $(this).next(".sub-menu");
				
					if(sm.data('open') !== 1)
					{
						e.preventDefault();
						e.stopPropagation();
						sm.slideDown();

						sm.data('open', 1);
					}
			
				}

			}

		}
	});

	// Float sidebar menu clicks
	$('.sidebar .widget.widget_nav_menu a').on('click', function(e){
			
			if ( $(this).next(".sub-menu").length > 0 ) {
				var sm = $(this).next(".sub-menu");
			
				if(sm.data('open') !== 1)
				{
					e.preventDefault();
					e.stopPropagation();
					sm.slideDown();

					sm.data('open', 1);

					$(this).parent().addClass('mobile-submenu-opened');

				}
				
			}
		
	});
	
	// Offcanvas menu
	function bjorn_offCanvasSidebarInit() {
		$( ".st-sidebar-menu" ).wrapInner('<div class="nano"></div>');
		$( ".st-sidebar-menu .nano" ).wrapInner('<div class="nano-content"></div>');

		$(".st-sidebar-menu .nano").nanoScroller();
		
		$("html").addClass('offcanvassidebar');

		var wp_adminbar_height = 0;

		if($("#wpadminbar").length > 0) {
			wp_adminbar_height = $("#wpadminbar").height();
		}

		$("html.offcanvassidebar .st-sidebar-content-inner").css("margin-top", wp_adminbar_height);

		var container = $('#st-container'), //-sidebar
		buttons = $("#st-sidebar-trigger-effects > a"),
		// event type (if mobile use touch events)
		eventtype = mobilecheck() ? 'touchstart' : 'click',
		resetMenu = function() {
			$(container).removeClass("st-sidebar-menu-open");
			$("html").removeClass('offcanvassidebar-open');
			setTimeout( function() {
				$('.sg_widget_custom_box_left').fadeIn();
			}, 1000 );
		},
		bodyClickFn = function(evt) {

			if( !hasParentClass( evt.target, 'st-sidebar-menu' ) ) {
				resetMenu();
				$("html").unbind( eventtype, bodyClickFn );
			}
			if( hasParentClass( evt.target, 'st-sidebar-menu-close-btn' )) {
				resetMenu();
				$("html").unbind( eventtype, bodyClickFn );
			}
		};

		buttons.each( function( i ) {

			var el = $( this );

			var effect = el.attr( 'data-effect' );

			el.bind( eventtype, function( ev ) {
				ev.stopPropagation();
				ev.preventDefault();
				container.attr('class', 'st-sidebar-container');// clear
				container.addClass(effect);
				$("html").addClass('offcanvassidebar-open');
				$('.sg_widget_custom_box_left').fadeOut();
				setTimeout( function() {

					container.addClass('st-sidebar-menu-open');
				}, 25 );
				$("html").bind( eventtype, bodyClickFn );
			});
		} );

	}

	if($("#st-sidebar-trigger-effects").length > 0) {

		$( "body" ).wrapInner('<div id="st-container" class="st-sidebar-container"></div>');
		$( ".st-sidebar-container" ).wrapInner('<div class="st-sidebar-pusher"></div>');
		$( ".st-sidebar-pusher" ).wrapInner("<div class='st-sidebar-content'></div>");
		$( ".st-sidebar-content" ).wrapInner("<div class='st-sidebar-content-inner'></div>");

		$( ".st-sidebar-menu" ).insertBefore($(".st-sidebar-pusher"));

		bjorn_offCanvasSidebarInit();
	}
	
	/**
	*	Scroll functions
	*/
	// Vertical bar
	if($(".bjorn-verticalbar").length > 0) {
		var verticalbar_top_position = $('.header-menu-bg').height()+$('header').height()+$('.bjorn-blog-posts-slider').height()+$('.container-page-item-title').height()+30;
		$(".bjorn-verticalbar").css('top', verticalbar_top_position);
		$(".bjorn-verticalbar").show();

		var verticalbaroffset = $(".bjorn-verticalbar").offset().top;
		var verticalbarfixed = 0;
		var vboffset1 = 150;
		var vboffset2 = 140;
	}

	$(window).scroll(function () {
			
			var scrollonscreen = $(window).scrollTop() + $(window).height();
			
			// Scroll to top function
			if(scrollonscreen > $(window).height() + 350){
				$('#top-link').css("bottom", "22px");
			}
			else {
				$('#top-link').css("bottom", "-60px");
			}
			
			// Vertical bar
			if($(".bjorn-verticalbar").length > 0) {
				if(verticalbarfixed == 0) {
					if($(".bjorn-verticalbar").offset().top < ($(window).scrollTop() + vboffset1)) {
						$(".bjorn-verticalbar").addClass('fixed');
						verticalbarfixed = 1;
					}
				} else {
					if(verticalbarfixed == 1) {
						if($(".bjorn-verticalbar").offset().top < verticalbaroffset - vboffset2) {
							$(".bjorn-verticalbar").removeClass('fixed');
							verticalbarfixed = 0;
						}

					}
				}
			}

	});

	// Sticky header
	function bjorn_stickyHeaderWorker() {
		// Sticky header
		if(isTouchDevice()==false) {

			var scrolltop = $(document).scrollTop();
		
			if(scrolltop > $("header").height() + $('.header-menu-bg').height()) {

				if((header_hided == 1) && (header_fixed == 1)) {
					header_fixed = 0;
				}

				if(header_fixed == 0) {

					

					header_fixed = 1;

					$(".mainmenu-belowheader.sticky-header").addClass('fixed');

					$(".mainmenu-belowheader.sticky-header").css("top", -50 + wp_adminbar_height);
					
					$(".mainmenu-belowheader.sticky-header").css("top", wp_adminbar_height);
					
					
					$("header").css("margin-top", header_original_height + wp_adminbar_height - top_menu_height);
					
				}

			} else {

				if(header_fixed == 1) {

					$(".mainmenu-belowheader.sticky-header").css("top", -90 - wp_adminbar_height);

					header_hided = 1;

					if((scrolltop < ($(window).height()/2) -100)) {

						$(".mainmenu-belowheader.sticky-header").removeClass('fixed');
						$("header").css("margin-top", 0);

						header_fixed = 0;
						header_hided = 0;

					}
				}
				
			}

		} 
	}

	if($(".mainmenu-belowheader.sticky-header").length > 0) {
		if($(window).width() > 767) {

			var header_fixed = 0;
			var header_original_height = $(".mainmenu-belowheader.sticky-header").height();
			var top_menu_height = 0;
			var wp_adminbar_height = 0;
			var header_hided = 0;

			if($("#wpadminbar").length > 0) {
				wp_adminbar_height = $("#wpadminbar").height();
			}

			if($('.header-menu-bg').length > 0) {
				top_menu_height = $('.header-menu-bg').height() - 15;
			}


			$(".mainmenu-belowheader.sticky-header").css("top", -90 - wp_adminbar_height);

			// Run first time
			bjorn_stickyHeaderWorker();	
			
			// Run on scroll
			$(window).scroll(function () {
				
				bjorn_stickyHeaderWorker();
				
			});

		}

	}

	//scroll up event
	$('#top-link, footer #footer-top-button').on('click', function(e){
		$('body,html').stop().animate({
			scrollTop:0
		},800,'easeOutCubic')
		return false;
	});


/**
*	Resize events
*/

	$(window).resize(function () {
		
	});

/**
*	Other scripts
*/


/**
* Social share for posts
*/

function bjorn_facebookShare(){
	window.open( 'https://www.facebook.com/sharer/sharer.php?u='+$(this).attr('href'), "facebookWindow", "height=380,width=660,resizable=0,toolbar=0,menubar=0,status=0,location=0,scrollbars=0" ) 
	return false;
}
function bjorn_googlePlusShare(){
	window.open( 'https://plus.google.com/share?url='+$(this).attr('href'), "googleplusWindow", "height=380,width=660,resizable=0,toolbar=0,menubar=0,status=0,location=0,scrollbars=0" ) 
	return false;
}
function bjorn_twitterShare(){
	
	var $page_title = encodeURIComponent($(this).attr('data-title'));
	
	window.open( 'http://twitter.com/intent/tweet?text='+$page_title +' '+$(this).attr('href'), "twitterWindow", "height=370,width=600,resizable=0,toolbar=0,menubar=0,status=0,location=0,scrollbars=0" ) 
	return false;
}
function bjorn_pinterestShare(){
	var $sharingImg;

	$sharingImg = $(this).attr('data-image'); 
	
	var $page_title = encodeURIComponent($(this).attr('data-title'));
	
	window.open( 'http://pinterest.com/pin/create/button/?url='+$(this).attr('href')+'&media='+$sharingImg+'&description='+$page_title, "pinterestWindow", "height=620,width=600,resizable=0,toolbar=0,menubar=0,status=0,location=0,scrollbars=0" ) 
	return false;
}
if( $('a.facebook-share').length > 0 || $('a.twitter-share').length > 0 || $('a.pinterest-share').length > 0 || $('a.googleplus-share').length > 0)  {

	$('.facebook-share').on('click', bjorn_facebookShare);
	
	$('.twitter-share').on('click', bjorn_twitterShare);

	$('.pinterest-share').on('click', bjorn_pinterestShare);

	$('.googleplus-share').on('click', bjorn_googlePlusShare);
	
}
// Common functions
function hasParentClass( e, classname ) {
	if(e === document) return false;

	if( $( e ).hasClass( classname ) ) {
		return true;
	}
	if( $( e ).parents().hasClass( classname ) ) {
		return true;
	}
}

function mobilecheck() {
	var check = false;
	(function(a){if(/(android|ipad|playbook|silk|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4)))check = true})(navigator.userAgent||navigator.vendor||window.opera);
	return check;
}
	
});
})(jQuery);