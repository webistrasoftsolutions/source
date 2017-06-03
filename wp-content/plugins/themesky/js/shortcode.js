jQuery(document).ready(function($){
	"use strict";
	
	/*** Add shortcode custom style into the html tag ***/
	var shortcode_custom_style = '';
	$('.ts-shortcode-custom-style').each(function(){
		shortcode_custom_style += $(this).html();
	});
	$('.ts-shortcode-custom-style').remove();
	if( shortcode_custom_style ){
		$('head').append('<style id="ts-shortcode-custom-style" type="text/css">' + shortcode_custom_style + '</style>');
	}
	
	/*** Product Slider shortcode ***/
	$('.ts-product-wrapper.ts-shortcode.ts-slider').each(function(){
		var element = $(this);
		
		var show_nav = false;
		var auto_play = false;
		var columns = 5;
		var margin = 0;
		var disable_responsive = false;
		
		if( element.data('nav') ){
			show_nav = true;
		}
		
		if( element.data('autoplay') ){
			auto_play = true;
		}
		
		if( element.data('columns') ){
			columns = element.data('columns');
		}
		
		if( element.data('margin') ){
			margin = element.data('margin');
		}
		
		if( element.data('disable_responsive') ){
			disable_responsive = true;
		}
		
		var _slider_data = {
					loop : true
					,nav : show_nav
					,navText : [,]
					,dots : false
					,navSpeed: 1000
					,slideBy: 1
					,rtl: $('body').hasClass('rtl')
					,margin : margin
					,navRewind: false
					,autoplay: auto_play
					,autoplayTimeout: 5000
					,autoplayHoverPause: true
					,autoplaySpeed: 1000
					,mouseDrag: true
					,touchDrag: true
					,responsiveBaseElement: element.find('.products')
					,responsiveRefreshRate: 400
					,responsive:{
								0:{
									items : 1
								},
								320:{
									items : 2
								},
								579:{
									items : 3
								},
								767:{
									items : 4
								},
								880:{
									items : columns
								}
							}
					,onInitialized: function(){
						element.find('.content-wrapper').addClass('loaded').removeClass('loading');
					}
				};
		
		if( element.hasClass('list') ){
			_slider_data.responsive = { 0: { items: 1 }, 350: { items: 2 }, 900: { items: columns } };
		}
		
		if( columns == 1 ){
			_slider_data.responsive = { 0: { items: 1 } };
		}
		
		if( disable_responsive ){
			_slider_data.responsive = { 0: { items: columns } };
		}
		
		element.find('.products').owlCarousel(_slider_data);
	});
	
	$('.ts-product-wrapper.ts-shortcode .banners.slider, .ts-product-deals-slider-wrapper.ts-shortcode .banners').each(function(){
		var element = $(this);
		if( element.find('img').length > 1 ){
			var _slider_data = {
					loop : true
					,nav : false
					,dots : true
					,navSpeed: 1000
					,slideBy: 1
					,rtl: $('body').hasClass('rtl')
					,margin : 20
					,autoplay: true
					,autoplayHoverPause: true
					,autoplaySpeed: 1000
					,responsiveBaseElement: element
					,responsiveRefreshRate: 400
					,responsive:{0:{items : 1}}
					,onInitialized: function(){
						element.addClass('loaded').removeClass('loading');
					}
				};
			element.owlCarousel(_slider_data);
		}
		else{
			element.removeClass('loading');
		}
	});
	
	/*** Single Products Slider ***/
	$('.ts-single-products-slider-wrapper').each(function(){
		var element = $(this);
		var show_nav = false;
		var auto_play = false;
		if( element.data('nav') ){
			show_nav = true;
		}
		if( element.data('autoplay') ){
			auto_play = true;
		}
		
		var _slider_data = {
					loop : true
					,nav : show_nav
					,dots : false
					,navSpeed: 1000
					,slideBy: 1
					,rtl: $('body').hasClass('rtl')
					,margin : 20
					,autoplay: auto_play
					,autoplayHoverPause: true
					,autoplaySpeed: 1000
					,responsiveBaseElement: element
					,responsiveRefreshRate: 400
					,responsive:{0:{items : 1}}
					,onInitialized: function(){
						element.find('.content-wrapper').addClass('loaded').removeClass('loading');
					}
				};
		element.find('.products').owlCarousel(_slider_data);
	});
	
	/*** Product Deals Shortcode ***/
	$('.ts-product-deals-slider-wrapper').each(function(){
		var element = $(this);
		var show_nav = false;
		var auto_play = false;
		var margin = 20;
		var columns = 4;
		var is_slider = false;
		var disable_responsive = false;
		
		if( element.data('nav') ){
			show_nav = true;
		}
		if( element.data('autoplay') ){
			auto_play = true;
		}
		if( element.data('margin') != undefined ){
			margin = element.data('margin');
		}
		if( element.data('columns') ){
			columns = element.data('columns');
		}
		if( element.data('is_slider') ){
			is_slider = true;
		}
		if( element.data('disable_responsive') ){
			disable_responsive = true;
		}
		
		var _slider_data = {
				loop : true
				,nav : show_nav
				,navText : [,]
				,dots : false
				,navSpeed: 1000
				,slideBy: 1
				,rtl: $('body').hasClass('rtl')
				,margin : margin
				,navRewind: false
				,autoplay: auto_play
				,autoplayTimeout: 5000
				,autoplayHoverPause: true
				,autoplaySpeed: 1000
				,mouseDrag: true
				,touchDrag: true
				,responsiveBaseElement: element.find('.products')
				,responsiveRefreshRate: 400
				,responsive:{
							0:{
								items : 1
							},
							360:{
								items : 2
							},
							579:{
								items : 3
							},
							730:{
								items : 4
							},
							800:{
								items : columns
							}
						}
				,onInitialized: function(){
					element.find('.content-wrapper').addClass('loaded').removeClass('loading');
				}
			};
			
		if( columns == 1 ){
			_slider_data.responsive = { 0:{ items: 1 }, 320:{ items: 2 }, 579:{ items: 3 }, 767:{ items: 1 } };
		}
		
		if( disable_responsive ){
			_slider_data.responsive = { 0:{ items: columns } };
		}
		
		element.find('.products').owlCarousel(_slider_data);
	});
	
	/*** Product Category Shortcode ***/
	$(window).on('load', function(){
		$('.ts-product-category-slider-wrapper.ts-slider').each(function(){
			var element = $(this);
			var show_nav = false;
			var show_dots = false;
			var auto_play = false;
			var margin = 0;
			var columns = 4;
			if( element.data('nav') ){
				show_nav = true;
			}
			if( element.data('dots') ){
				show_dots = true;
			}
			if( element.data('autoplay') ){
				auto_play = true;
			}
			if( element.data('margin') ){
				margin = parseInt( element.data('margin') );
			}
			if( element.data('columns') ){
				columns = parseInt( element.data('columns') );
			}
			var _slider_data = { 
				loop : true
				,nav : show_nav
				,navText : [,]
				,dots : show_dots
				,navSpeed: 1000
				,slideBy: 1
				,rtl: $('body').hasClass('rtl')
				,margin : margin
				,navRewind: false
				,autoplay: auto_play
				,autoplayTimeout: 5000
				,autoplayHoverPause: false
				,autoplaySpeed: 1000
				,mouseDrag: true
				,touchDrag: true
				,responsiveBaseElement: element
				,responsiveRefreshRate: 400
				,responsive:{
							0:{
								items : 1
							},
							320:{
								items : 2
							},
							500:{
								items : 3
							},
							767:{
								items : 4
							},
							980:{
								items : columns
							}
						}
				,onInitialized: function(){
					element.find('.content-wrapper').addClass('loaded').removeClass('loading');
				}
			};
			
			element.find('.products').owlCarousel( _slider_data );
		});
	});
	
	/*** Load Products In Category Tab ***/
	var ts_product_in_category_tab_data = [];
	
	/* Change tab */
	$('.ts-product-in-category-tab-wrapper .column-tabs .tab-item').bind('click', function(){
		if( $(this).hasClass('current') || $(this).parents('.ts-product-in-category-tab-wrapper').find('.column-products').hasClass('loading') ){
			return;
		}
		var element = $(this).parents('.ts-product-in-category-tab-wrapper');
		var element_id = element.attr('id');
		var product_cat = $(this).data('product_cat');
		var shop_more_link = $(this).data('link');
		var atts = element.data('atts');
		
		var is_general_tab = $(this).hasClass('general-tab')?1:0;
		
		if( element.find('a.shop-more-button').length > 0 ){
			element.find('a.shop-more-button').attr('href', shop_more_link);
		}
		
		element.find('.column-tabs .tab-item').removeClass('current');
		$(this).addClass('current');
		
		/* Check cache */
		var tab_data_index = element_id + '-' + product_cat.toString().split(',').join('-');
		if( ts_product_in_category_tab_data[tab_data_index] != undefined ){
			/* destroy slider first */
			element.find('.column-products .products.owl-carousel').owlCarousel('destroy');
			
			element.find('.column-products .products').remove();
			element.find('.column-products').append( ts_product_in_category_tab_data[tab_data_index] );
			if( typeof ts_quickshop_handle == 'function' ){
				ts_quickshop_handle();
			}
			element.find('.lazy-loading img').each(function(){
				if( $(this).data('src') ){
					$(this).attr('src', $(this).data('src'));
				}
			});
			element.find('.lazy-loading').removeClass('lazy-loading').addClass('lazy-loaded');
			/* Shop more button handle */
			ts_product_in_category_tab_shop_more_handle( element, atts );
			
			/* Generate slider */
			ts_product_slider_in_category_tab( element, atts.show_nav, atts.auto_play, atts.columns );
			
			return;
		}
		
		element.find('.column-products').addClass('loading');
		
		$.ajax({
			type : "POST",
			timeout : 30000,
			url : ts_shortcode_params.ajax_uri,
			data : {action: 'ts_get_product_content_in_category_tab', atts: atts, product_cat: product_cat, is_general_tab: is_general_tab},
			error: function(xhr,err){
				
			},
			success: function(response) {
				if( response ){
					/* destroy slider first */
					element.find('.column-products .products.owl-carousel').owlCarousel('destroy');
					
					element.find('.column-products .products').remove();
					element.find('.column-products').append( response );
					if( typeof ts_quickshop_handle == 'function' ){
						ts_quickshop_handle();
					}
					/* save cache */
					ts_product_in_category_tab_data[tab_data_index] = response;
					
					/* Shop more button handle */
					ts_product_in_category_tab_shop_more_handle( element, atts );
					
					/* Generate slider */
					ts_product_slider_in_category_tab( element, atts.show_nav, atts.auto_play, atts.columns );
				}
				element.find('.column-products').removeClass('loading');
			}
		});
	});
	
	$('.ts-product-in-category-tab-wrapper').each(function(){
		$(this).find('.column-tabs .tab-item:first').trigger('click');
	});
	
	function ts_product_in_category_tab_shop_more_handle(element, atts){
		var hide_shop_more = element.find('.products .hide-shop-more').length;
		element.find('.products .hide-shop-more').remove();
		
		if( element.find('.products .product').length == 0 ){
			hide_shop_more = true;
		}
		
		if( atts.show_shop_more == 1 ){
			if( hide_shop_more ){
				element.find('a.shop-more-button').addClass('hidden');
				element.removeClass('has-shop-more');
			}
			else{
				element.find('a.shop-more-button').removeClass('hidden');
				element.addClass('has-shop-more');
			}
		}
	}
	
	function ts_product_slider_in_category_tab( element, show_nav, auto_play, columns, responsive, margin ){
		if( element.find('.products .product-group').length > 0 ){
			show_nav = (show_nav == 1)?true:false;
			auto_play = (auto_play == 1)?true:false;
			columns = parseInt(columns);
			var _slider_data = { 
				loop : true
				,nav : show_nav
				,navText : [,]
				,dots : false
				,navSpeed: 1000
				,slideBy: 1
				,rtl: $('body').hasClass('rtl')
				,margin : 0
				,navRewind: false
				,autoplay: auto_play
				,autoplayTimeout: 5000
				,autoplayHoverPause: false
				,autoplaySpeed: 1000
				,mouseDrag: true
				,touchDrag: true
				,responsiveBaseElement: element.find('.products')
				,responsiveRefreshRate: 400
				,responsive:{
							0:{
								items : 1
							},
							350:{
								items : 2
							},
							600:{
								items : 3
							},
							850:{
								items : columns
							}
						}
				,onInitialized: function(){
					
				}
			};
			
			if( responsive != undefined ){
				_slider_data.responsive = responsive;
			}
			
			if( margin != undefined ){
				_slider_data.margin = margin;
			}
			
			element.find('.products').owlCarousel( _slider_data );
		}
	}
	
	/* Create banner slider */
	$(window).on('load', function(){
		$('.ts-product-in-category-tab-wrapper .column-logos').each(function(){
			var element = $(this);
			var is_slider = false;
			if( typeof $.fn.carouFredSel == 'function' ){
				is_slider = true;
			}
			else{
				element.removeClass('loading');
			}
			var id = element.parents('.ts-product-in-category-tab-wrapper').attr('id');
			
			if( is_slider ){
				var items = 5;
				var _slider_data = {
							items: items
							,direction: 'up'
							,width: 'auto'
							,height: '100px'
							,infinite: true
							,prev: $('#prev-' + id)
							,next: $('#next-' + id)
							,auto: {
								play: true
								,timeoutDuration: 5000
								,duration: 800
								,delay: 3000
								,items: 1
								,pauseOnHover: true
							}
							,scroll: {
								items: 1
							}
							,swipe: {
								onTouch: true
								,onMouse: true
							}
							,onCreate: function(){
								element.addClass('loaded').removeClass('loading');
							}
						};
						
				element.find('figure').carouFredSel(_slider_data);
				
				$(window).bind('resize orientationchange', $.debounce( 250, function(){
					if( $(window).width() < 440 ){
						_slider_data.items = 1;
					}
					else if( $(window).width() < 580 ){
						_slider_data.items = 2;
					}
					else if( $(window).width() < 660 ){
						_slider_data.items = 4;
					}
					else if( $(window).width() < 768 ){
						_slider_data.items = 5;
					}
					else{
						_slider_data.items = items;
					}
					element.find('figure').trigger('configuration', _slider_data);
				} ));
			}
		});
	});

	/*** Blog Shortcode ***/
	$('.ts-blogs-wrapper.ts-shortcode').each(function(){
		var element = $(this);
		var atts = element.data('atts');
		
		/* Slider */
		if( atts.is_slider ){
			var show_nav = parseInt(atts.show_nav) == 1;
			var auto_play = parseInt(atts.auto_play) == 1;
			var margin = parseInt(atts.margin);
			var columns = parseInt(atts.columns);
			var slider_data = {
				loop : true
				,nav : show_nav
				,navText: [,]
				,navSpeed: 1000
				,slideBy: 1
				,rtl: $('body').hasClass('rtl')
				,margin : margin
				,navRewind: false
				,autoplay: auto_play
				,autoplayTimeout: 5000
				,autoplayHoverPause: true
				,autoplaySpeed: false
				,autoHeight: true
				,mouseDrag: true
				,touchDrag: true
				,responsiveBaseElement: element
				,responsiveRefreshRate: 400
				,responsive:{
							0:{
								items : 1
							},
							550:{
								items : 2
							},
							767:{
								items : 3
							},
							870:{
								items : columns
							}
						}
				,onInitialized: function(){
					element.addClass('loaded').removeClass('loading');
				}
			};
			element.find('.content-wrapper > .blogs').owlCarousel(slider_data);
		}
		
		/* Blog Gallery - Masonry - Load more */
		var is_masonry = false;
		if( atts.is_masonry && typeof $.fn.isotope == 'function' ){
			is_masonry = true;
		}
		
		$(window).bind('load', function(){
			ts_blog_shortcode_gallery_slider( element, atts );
		});
		
		if( is_masonry ){
			$(window).bind('load', function(){
				element.find('.blogs').isotope();
			});
		}
		
		/* Show more */
		element.find('a.load-more').bind('click', function(){
			var button = $(this);
			if( button.hasClass('loading') ){
				return false;
			}
			
			button.addClass('loading');
			var paged = button.attr('data-paged');
			
			$.ajax({
				type : "POST",
				timeout : 30000,
				url : ts_shortcode_params.ajax_uri,
				data : {action: 'ts_blogs_load_items', paged: paged, atts : atts},
				error: function(xhr,err){
					
				},
				success: function(response) {
					button.removeClass('loading');
					button.attr('data-paged', ++paged);
					if( response != 0 && response != '' ){
						if( is_masonry ){										
							element.find('.blogs').isotope('insert', $(response));
							setTimeout(function(){
								element.find('.blogs').isotope('layout');
							}, 500);
						}
						else { /* Append and Update first-last classes */
							element.find('.blogs').append(response);
							
							var columns = parseInt(atts.columns);
							element.find('.blogs .item').removeClass('first last');
							element.find('.blogs .item').each(function(index, ele){
								if( index % columns == 0 ){
									$(ele).addClass('first');
								}
								if( index % columns == columns - 1 ){
									$(ele).addClass('last');
								}
							});
						}
						
						ts_blog_shortcode_gallery_slider( element, atts );
					}
					else{ /* No results */
						button.parent().remove();
					}
				}
			});
			
			return false;
		});
		
	});
	
	function ts_blog_shortcode_gallery_slider( element, atts ){
		var show_nav = parseInt(atts.show_nav) == 1;
		var slider_data = {
			items: 1
			,loop: true
			,nav: false
			,dots: show_nav
			,animateIn: 'fadeIn'
			,animateOut: 'fadeOut'
			,navText: [,]
			,navSpeed: 1000
			,slideBy: 1
			,rtl: $('body').hasClass('rtl')
			,margin: 10
			,navRewind: false
			,autoplay: true
			,autoplayTimeout: 4000
			,autoplayHoverPause: true
			,autoplaySpeed: false
			,autoHeight: true
			,mouseDrag: false
			,touchDrag: false
			,responsive:{
				0:{
					items : 1
				}
			}
			,onInitialized: function(){
				element.find('.thumbnail.gallery').addClass('loaded').removeClass('loading');
			}
		};
		if( element.find('.thumbnail.gallery').length > 0 ){
			element.find('.thumbnail.gallery:not(.loaded) figure').owlCarousel(slider_data);
		}
	}
	
	/*** Image Gallery ***/
	$(window).bind('load', function(){
		$('.ts-image-gallery-wrapper.ts-slider').each(function(){
			var element = $(this);
			var show_nav = parseInt(element.data('nav')) == 1;
			var auto_play = parseInt(element.data('autoplay')) == 1;
			var margin = parseInt(element.data('margin'));
			var columns = parseInt(element.data('columns'));
			var slider_data = {
				loop : true
				,nav : show_nav
				,navText: [,]
				,navSpeed: 1000
				,slideBy: 1
				,rtl: $('body').hasClass('rtl')
				,margin : margin
				,navRewind: false
				,autoplay: auto_play
				,autoplayTimeout: 5000
				,autoplayHoverPause: true
				,autoplaySpeed: false
				,autoHeight: true
				,mouseDrag: true
				,touchDrag: true
				,responsiveBaseElement: element
				,responsiveRefreshRate: 400
				,responsive:{
							0:{
								items : 1
							},
							550:{
								items : 2
							},
							767:{
								items : 3
							},
							870:{
								items : columns
							}
						}
				,onInitialized: function(){
					element.find('.images').addClass('loaded').removeClass('loading');
				}
			};
			element.find('.images').owlCarousel(slider_data);
		});
	});
	
	/*** Logo Slider shortcode ***/
	$('.ts-logo-slider-wrapper.loading').each(function(){
		var element = $(this);
		var margin = element.data('margin');
		var show_nav = false;
		var auto_play = false;
		var break_point = element.data('break_point');
		var item = element.data('item');
		
		if( element.data('nav') ){
			show_nav = true;
		}
		
		if( element.data('auto_play') ){
			auto_play = true;
		}
		
		var _slider_data = {
				loop : true
				,nav : show_nav
				,navText: [,]
				,dots : false
				,navSpeed: 1000
				,slideBy: 1
				,rtl: $('body').hasClass('rtl')
				,margin : margin
				,navRewind: false
				,autoplay: auto_play
				,autoplayTimeout: 5000
				,autoplayHoverPause: true
				,autoplaySpeed: false
				,mouseDrag: true
				,touchDrag: true
				,responsiveBaseElement: element
				,responsiveRefreshRate: 400
				,responsive:{
							0:{
								items : 1
							},
							320:{
								items : 2
							},
							400:{
								items : 3
							},
							640:{
								items : 4
							},
							830:{
								items : 5
							}
						}
				,onInitialized: function(){
					element.addClass('loaded').removeClass('loading');
				}
			};
			
		if( break_point.length > 0 ){
			_slider_data.responsive = {};
			for( var i = 0; i < break_point.length; i++ ){
				_slider_data.responsive[break_point[i]] = {items: item[i]};
			}
		}
			
		element.find('.logos').owlCarousel(_slider_data);
	});
	
	/*** Fix min-height of Visual Composer's tab ***/	
	$(window).bind('load resize', function(){
		ts_update_tab_min_height();
		ts_check_shortcode_product_status_in_tab();
	});
	
	$('.vc_tta-tabs .vc_tta-tabs-list .vc_tta-tab').bind('click', function(){
		ts_update_tab_min_height();
	});
	
	function ts_update_tab_min_height(){
		setTimeout(function(){
			$('.vc_tta-tabs .vc_tta-panels').each(function(){
				$(this).find('.vc_tta-panel').css('min-height', 0);
				var min_height = $(this).find('.vc_tta-panel.vc_active').height();
				$(this).find('.vc_tta-panel').css('min-height', min_height);
			});
		}, 800);
	}
	
	function ts_check_shortcode_product_status_in_tab(){
		$('.vc_tta-tabs .vc_tta-panels .vc_tta-panel').each(function(){
			var panel = $(this);
			if( panel.find('.ts-product-wrapper').length > 0 ){
				panel.parents('.vc_tta-tabs').addClass('product-tab');
				if( panel.find('.ts-product-wrapper.ts-slider.has-nav.nav-top').length > 0 ){
					panel.parents('.vc_tta-tabs').find('.vc_tta-tabs-list').addClass('has-nav');
				}
				if( panel.find('.ts-product-wrapper .shop-more-button').length > 0 ){
					panel.parents('.vc_tta-tabs').find('.vc_tta-tabs-list').addClass('has-shop-more');
				}
			}
		});
	}
	
	/*** Remove Hash Url ***/
	$('.vc_tta-tabs .vc_tta-tabs-list .vc_tta-tab a, .vc_tta-accordion .vc_tta-panel-title a').bind('click', function(){
		if( history.pushState ){
			setTimeout(function(){
				history.pushState(null, null, ' ');
			}, 0);
		}
	});
	
	/*** Counter ***/
	function ts_counter( elements ){
		if( elements.length > 0 ){
			var interval = setInterval(function(){
				elements.each(function(index, element){
					var day = 0;
					var hour = 0;
					var minute = 0;
					var second = 0;
					
					var delta = 0;
					var time_day = 60 * 60 * 24;
					var time_hour = 60 * 60;
					var time_minute = 60;
					
					var wrapper = $(element);
					
					day = parseInt( wrapper.find('.days .number-wrapper .number').attr('data-number') );
					hour = parseInt( wrapper.find('.hours .number-wrapper .number').attr('data-number') );
					minute = parseInt( wrapper.find('.minutes .number-wrapper .number').attr('data-number') );
					second = parseInt( wrapper.find('.seconds .number-wrapper .number').attr('data-number') );
					
					if( day != 0 || hour != 0  || minute != 0 || second != 0 ){
						delta = (day * time_day) + (hour * time_hour) + (minute * time_minute) + second;
						delta--;
						
						day = Math.floor(delta / time_day);
						delta -= day * time_day;
						
						hour = Math.floor(delta / time_hour);
						delta -= hour * time_hour;
						
						minute = Math.floor(delta / time_minute);
						delta -= minute * time_minute;
						
						if( delta > 0 ){
							second = delta;
						}
						else{
							second = '0';
						}
						
						day = ( day < 10 )? zeroise(day, 2) : day.toString();
						hour = ( hour < 10 )? zeroise(hour, 2) : hour.toString();
						minute = ( minute < 10 )? zeroise(minute, 2) : minute.toString();
						second = ( second < 10 )? zeroise(second, 2) : second.toString();
						
						wrapper.find('.days .number-wrapper .number').attr('data-number', day);
						wrapper.find('.hours .number-wrapper .number').attr('data-number', hour);
						wrapper.find('.minutes .number-wrapper .number').attr('data-number', minute);
						wrapper.find('.seconds .number-wrapper .number').attr('data-number', second);
						
						if( ts_shortcode_params.use_persian_number == 1 ){
							day 	= day.toPersianDigits();
							hour 	= hour.toPersianDigits();
							minute 	= minute.toPersianDigits();
							second 	= second.toPersianDigits();
						}
						
						wrapper.find('.days .number-wrapper .number').text(day);
						wrapper.find('.hours .number-wrapper .number').text(hour);
						wrapper.find('.minutes .number-wrapper .number').text(minute);
						wrapper.find('.seconds .number-wrapper .number').text(second);
					}
					
				});
			}, 1000);
		}
	}
	
	if( ts_shortcode_params.use_persian_number == 1 ){
		String.prototype.toPersianDigits = function () {
			var num_dic = {
				'0': '۰'
				,'1': '۱'
				,'2': '۲'
				,'3': '۳'
				,'4': '۴'
				,'5': '۵'
				,'6': '۶'
				,'7': '۷'
				,'8': '۸'
				,'9': '۹'
			}

			return this.replace(/[0-9]/g, function (w) {
				return num_dic[w]
			});
		}
	}
	
	ts_counter( $('.product .counter-wrapper, .ts-countdown .counter-wrapper') );
	
	/*** Portfolio ***/
	$(window).bind('load', function(){
		if( typeof $.fn.isotope == 'function' ){
			$('.ts-portfolio-wrapper.ts-masonry .portfolio-inner').isotope({filter: '*'});
		}
		$('.ts-portfolio-wrapper.ts-masonry').removeClass('loading');
	});
	
	$('.ts-portfolio-wrapper .filter-bar li').bind('click', function(){
		$(this).siblings('li').removeClass('current');
		$(this).addClass('current');
		var container = $(this).parents('.ts-portfolio-wrapper').find('.portfolio-inner');
		var data_filter = $(this).data('filter');
		container.isotope({filter: data_filter});
	});
	
	/* Load more + Slider */
	$('.ts-portfolio-wrapper').each(function(){
		var element = $(this);
		var atts = element.data('atts');
		var is_slider = parseInt(atts.is_slider);
		var auto_play = parseInt(atts.auto_play)?true:false;
		var show_nav = parseInt(atts.show_nav)?true:false;
		var columns = parseInt(atts.columns);
		var margin = parseInt(atts.margin);
		
		element.find('a.load-more').bind('click', function(){
			var button = $(this);
			if( button.hasClass('loading') ){
				return false;
			}
			
			button.addClass('loading');
			var paged = button.attr('data-paged');
			
			$.ajax({
				type : "POST",
				timeout : 30000,
				url : ts_shortcode_params.ajax_uri,
				data : {action: 'ts_portfolio_load_items', paged: paged, atts : atts},
				error: function(xhr,err){
					
				},
				success: function(response) {
					button.removeClass('loading');
					button.attr('data-paged', ++paged);
					if( response != 0 && response != '' ){
						if( typeof $.fn.isotope == 'function' ){										
							element.find('.portfolio-inner').isotope('insert', $(response));
							element.find('.filter-bar li.current').trigger('click');
							setTimeout(function(){
								element.find('.portfolio-inner').isotope('layout');
							}, 500);
						}
					}
					else{ /* No results */
						button.parent().remove();
					}
				}
			});
			
			return false;
		});
		
		if( is_slider ){
			$(window).bind('load', function(){
				var slider_data = {
					loop : true
					,nav : show_nav
					,navText: [,]
					,dots : false
					,navSpeed: 1000
					,slideBy: 1
					,rtl: $('body').hasClass('rtl')
					,margin : margin
					,navRewind: false
					,autoplay: auto_play
					,autoplayTimeout: 5000
					,autoplayHoverPause: true
					,autoplaySpeed: false
					,mouseDrag: true
					,touchDrag: true
					,responsiveBaseElement: element
					,responsiveRefreshRate: 400
					,responsive: {
						0: {
							items: 1
						},
						500: {
							items: 2
						},
						1000: {
							items: 3
						},
						1300: {
							items : columns
						}
					}
					,onInitialized: function(){
						element.addClass('loaded').removeClass('loading');
					}
				};
				element.find('.portfolio-inner').owlCarousel(slider_data);
			});
		}
		
	});
	
	/* Update like */
	$(document).on('click', '.ts-portfolio-wrapper .icon-group .like, .single-portfolio .portfolio-like .ic-like', function(e){
		var _this = $(this);
		
		if( _this.hasClass('loading') ){
			return false;
		}
		_this.addClass('loading');
		
		var already_like = _this.hasClass('already-like');
		var is_single = _this.hasClass('ic-like');
		
		var post_id = _this.data('post_id');
		$.ajax({
			type : "POST",
			timeout : 30000,
			url : ts_shortcode_params.ajax_uri,
			data : {action: 'ts_portfolio_update_like', post_id: post_id},
			error: function(xhr,err){
				_this.removeClass('loading');
			},
			success: function(response) {
				if( response != '' ){
					if( already_like ){
						_this.removeClass('already-like');
						if( !is_single ){
							_this.attr('title', _this.data('like-title'));
						}
					}
					else{
						_this.addClass('already-like');
						if( !is_single ){
							_this.attr('title', _this.data('liked-title'));
						}
					}
					if( !is_single ){
						_this.text(response);
					}
					else{
						_this.siblings('.like-num').text(response);
					}
				}
				_this.removeClass('loading');
			}
		});
		
		return false;
	});
	
	/*** Reload SoundClound Iframe ***/
	$(window).bind('load', function(){
		$('.owl-item .ts-soundcloud iframe').each(function(){
			var iframe = $(this);
			var src = iframe.attr('src');
			iframe.attr('src', src);
		});
	});
	
	/*** Twitter slider ***/
	$(window).bind('load', function(){
		$('.ts-twitter-slider, .ts-testimonial-wrapper.ts-slider').each(function(){
			var element = $(this);
			var validate_slider = true;
			
			if( element.find('.item').length <= 1 ){
				validate_slider = false;
			}
			
			if( validate_slider ){
				var show_nav = false;
				var show_dots = false;
				var autoplay = false;
				if( element.data('nav') ){
					show_nav = true;
				}
				if( element.data('dots') ){
					show_dots = true;
				}
				if( element.data('autoplay') ){
					autoplay = true;
				}
				
				var slider_data = {
					items: 1
					,loop: true
					,nav: show_nav
					,dots: show_dots
					,animateIn: 'fadeIn'
					,animateOut: 'fadeOut'
					,navText: [,]
					,navSpeed: 1000
					,slideBy: 1
					,rtl: $('body').hasClass('rtl')
					,margin: 0
					,navRewind: false
					,autoplay: autoplay
					,autoplayTimeout: 5000
					,autoplayHoverPause: true
					,autoplaySpeed: false
					,mouseDrag: false
					,touchDrag: true
					,responsive:{
						0:{
							items : 1
						}
					}
					,onInitialized: function(){
						element.addClass('loaded').removeClass('loading');
					}
				};
				element.owlCarousel(slider_data);
			}
			else{
				element.removeClass('loading');
			}
		});
	});
	
	/*** Milestone ***/
	if( typeof $.fn.waypoint == 'function' && typeof $.fn.countTo == 'function' ){
		$('.ts-milestone').waypoint(function(){
			if( typeof this.disable == 'function' ){
				this.disable();
				var element = $(this.element);
				var end_num = element.data('number');
			}
			else{ /* Fix for old version of waypoint */
				var element = $(this);
				var end_num = element.data('number');
			}
			
			element.find('.number').countTo({
							from: 0
							,to: end_num
							,speed: 1500
							,refreshInterval: 30
						});
		}, {offset: '105%', triggerOnce: true});
	}
	
	/*** Button Popup ***/
	if( typeof $.fn.prettyPhoto == 'function' ){
		$('.ts-button.ts-button-popup').prettyPhoto({
			theme : 'pp_woocommerce'
			,social_tools: false
			,show_title: false
			,default_width: 600
			,default_height: 500
			,deeplinking: false
			,changepicturecallback: function(){
				$('.pp_pic_holder.pp_woocommerce').addClass('loaded');
				if( typeof $.fn.wpcf7InitForm == 'function' ){
					$('.pp_pic_holder div.wpcf7 > form .ajax-loader').remove();
					$('.pp_pic_holder div.wpcf7 > form').wpcf7InitForm();
				}
				$(window).trigger('scroll');
			}
		});
	}
	
	/*** Google Map ***/
	function ts_gmap_initialize( map_content_obj, address, zoom, map_type, title ){
		var geocoder, map;
		geocoder = new google.maps.Geocoder();
	
		geocoder.geocode( {'address': address}, function(results, status) {
			if( status == google.maps.GeocoderStatus.OK ){
				var _ret_array =  new Array(results[0].geometry.location.lat(),results[0].geometry.location.lng());
				map.setCenter(results[0].geometry.location);
				var marker = new google.maps.Marker({
					map: map
					,title: title
					,position: results[0].geometry.location
				});
			}
		});
		
		var mapCanvas = map_content_obj.get(0);
		var mapOptions = {
			center: new google.maps.LatLng(44.5403, -78.5463)
			,zoom: zoom
			,mapTypeId: google.maps.MapTypeId[map_type]
			,scrollwheel : false
			,zoomControl : true
			,panControl : true
			,scaleControl : true
			,streetViewControl : false
			,overviewMapControl : true
			,disableDoubleClickZoom : false
		}
		map = new google.maps.Map(mapCanvas, mapOptions)
	}
	
	$(window).bind('load resize', function(){
		$('.google-map-container').each(function(){
			var element = $(this);
			var map_content = $(this).find('> div');
			var address = element.data('address');
			var zoom = element.data('zoom');
			var map_type = element.data('map_type');
			var title = element.data('title');
			ts_gmap_initialize( map_content, address, zoom, map_type, title );
		});
	});
	
});

function zeroise( str, max ){
	str = str.toString();
	return str.length < max ? zeroise('0' + str, max) : str;
}