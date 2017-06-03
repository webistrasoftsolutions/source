<?php
function ts_remove_product_hooks_shortcode( $options = array() ){
	if( isset($options['show_image']) && !$options['show_image'] ){
		remove_action('woocommerce_before_shop_loop_item_title', 'sanzo_template_loop_product_thumbnail', 10);
	}
	if( isset($options['show_label']) && !$options['show_label'] ){
		remove_action('woocommerce_after_shop_loop_item_title', 'sanzo_template_loop_product_label', 1);
	}
	if( isset($options['show_categories']) && !$options['show_categories'] ){
		remove_action('woocommerce_after_shop_loop_item', 'sanzo_template_loop_categories', 10);
	}
	if( isset($options['show_title']) && !$options['show_title'] ){
		remove_action('woocommerce_after_shop_loop_item', 'sanzo_template_loop_product_title', 20);
	}
	if( isset($options['show_sku']) && !$options['show_sku'] ){
		remove_action('woocommerce_after_shop_loop_item', 'sanzo_template_loop_product_sku', 30);
	}
	if( isset($options['show_rating']) && !$options['show_rating'] ){
		remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_rating', 40);
	}
	if( isset($options['show_price']) && !$options['show_price'] ){
		remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_price', 50);
	}
	if( isset($options['show_short_desc']) && !$options['show_short_desc'] ){
		remove_action('woocommerce_after_shop_loop_item', 'sanzo_template_loop_short_description', 60);
	}
	if( isset($options['show_add_to_cart']) && !$options['show_add_to_cart'] ){
		remove_action('woocommerce_after_shop_loop_item', 'sanzo_template_loop_add_to_cart', 70);
		remove_action('woocommerce_after_shop_loop_item_title', 'sanzo_template_loop_add_to_cart', 10005 );
	}
	if( isset($options['meta_align']) && $options['meta_align'] == 'meta-center' && function_exists('sanzo_add_wishlist_button_to_product_list') ){
		remove_action( 'woocommerce_after_shop_loop_item', 'sanzo_add_wishlist_button_to_product_list', 71 );
		add_action( 'woocommerce_after_shop_loop_item', 'sanzo_add_wishlist_button_to_product_list', 69 );
	}
}

function ts_restore_product_hooks_shortcode(){
	add_action('woocommerce_after_shop_loop_item_title', 'sanzo_template_loop_product_label', 1);
	add_action('woocommerce_before_shop_loop_item_title', 'sanzo_template_loop_product_thumbnail', 10);
	
	add_action('woocommerce_after_shop_loop_item', 'sanzo_template_loop_categories', 10);
	add_action('woocommerce_after_shop_loop_item', 'sanzo_template_loop_product_title', 20);
	add_action('woocommerce_after_shop_loop_item', 'sanzo_template_loop_product_sku', 30);
	add_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_rating', 40);
	add_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_price', 50);
	add_action('woocommerce_after_shop_loop_item', 'sanzo_template_loop_short_description', 60);
	add_action('woocommerce_after_shop_loop_item', 'sanzo_template_loop_add_to_cart', 70); 
	add_action('woocommerce_after_shop_loop_item_title', 'sanzo_template_loop_add_to_cart', 10005 );
	
	if( function_exists('sanzo_add_wishlist_button_to_product_list') ){
		remove_action( 'woocommerce_after_shop_loop_item', 'sanzo_add_wishlist_button_to_product_list', 69 );
		add_action( 'woocommerce_after_shop_loop_item', 'sanzo_add_wishlist_button_to_product_list', 71 );
	}
}

function ts_filter_product_by_product_type( &$args = array(), $product_type = 'recent' ){
	switch( $product_type ){
		case 'sale':
			$args['post__in'] = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
		break;
		case 'featured':
			$args['tax_query'][] = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => 'featured',
				'operator' => 'IN',
			);
		break;
		case 'best_selling':
			$args['meta_key'] 	= 'total_sales';
			$args['orderby'] 	= 'meta_value_num';
			$args['order'] 		= 'desc';
		break;
		case 'top_rated':
			$args['meta_key'] 	= '_wc_average_rating';
			$args['orderby'] 	= 'meta_value_num';
			$args['order'] 		= 'desc';
		break;
		case 'mixed_order':
			$args['orderby'] 	= 'rand';
		break;
		default: /* Recent */
			$args['orderby'] 	= 'date';
			$args['order'] 		= 'desc';
		break;
	}
}

/*** Products Shortcode ***/

if( !function_exists('ts_products_shortcode') ){

	function ts_products_shortcode($atts, $content){

		extract(shortcode_atts(array(
				'title' 					=> ''
				,'title_style'				=> ''
				,'product_type'				=> 'recent'
				,'columns' 					=> 4
				,'per_page' 				=> 4
				,'product_cats'				=> ''
				,'show_list_cats'			=> 0
				,'ids'						=> ''
				,'margin'					=> 30
				,'item_border'				=> 0
				,'item_layout'				=> 'grid'
				,'meta_align'				=> ''
				,'show_shop_more' 			=> 1
				,'shop_more_text' 			=> 'Shop more'
				,'short_desc_words' 		=> 8
				,'banners' 					=> ''
				,'banner_links' 			=> ''
				,'banner_position' 			=> 'left'
				,'banner_layout' 			=> 'grid'
				,'show_image' 				=> 1
				,'show_title' 				=> 1
				,'show_sku' 				=> 0
				,'show_price' 				=> 1
				,'show_short_desc'  		=> 0
				,'show_rating' 				=> 1
				,'show_label' 				=> 1	
				,'show_categories'			=> 0	
				,'show_add_to_cart' 		=> 1
				,'is_slider'				=> 0 /* Options for slider */
				,'rows' 					=> 1
				,'show_nav'					=> 1
				,'position_nav'				=> 'nav-top'
				,'auto_play'				=> 0
				,'disable_slider_responsive'=> 0
			), $atts));
			if ( !class_exists('WooCommerce') ){
				return;
			}
			
			$options = array(
					'show_image'		=> $show_image
					,'show_label'		=> $show_label
					,'show_title'		=> $show_title
					,'show_sku'			=> $show_sku
					,'show_price'		=> $show_price
					,'show_short_desc'	=> $show_short_desc
					,'show_categories'	=> $show_categories
					,'show_rating'		=> $show_rating
					,'show_add_to_cart'	=> $show_add_to_cart
					,'meta_align'		=> $meta_align
				);
			ts_remove_product_hooks_shortcode( $options );
			add_filter('sanzo_grid_short_desc_limit_words', create_function('$limit_words', 'return '.absint($short_desc_words).';'));
			
			$args = array(
				'post_type'				=> 'product'
				,'post_status' 			=> 'publish'
				,'ignore_sticky_posts'	=> 1
				,'posts_per_page' 		=> $per_page
				,'orderby' 				=> 'date'
				,'order' 				=> 'desc'
				,'meta_query' 			=> WC()->query->get_meta_query()
				,'tax_query'           	=> WC()->query->get_tax_query()
			);
			
			ts_filter_product_by_product_type($args, $product_type);

			$product_cats = str_replace(' ', '', $product_cats);
			if( strlen($product_cats) > 0 ){
				$product_cats = explode(',', $product_cats);
			}
			if( is_array($product_cats) && count($product_cats) > 0 ){
				$field_name = is_numeric($product_cats[0])?'term_id':'slug';
				$args['tax_query'][] = array(
											'taxonomy' => 'product_cat'
											,'terms' => $product_cats
											,'field' => $field_name
											,'include_children' => false
										);
			}
			else{
				$show_list_cats = 0;
			}
			
			$ids = str_replace(' ', '', $ids);
			if( strlen($ids) > 0 ){
				$ids = explode(',', $ids);
				if( is_array($ids) && count($ids) > 0 ){
					$args['post__in'] = $ids;
					if( count($ids) == 1 ){
						$columns = 1;
					}
				}
			}
			
			ob_start();
			global $woocommerce_loop;
			if( (int)$columns <= 0 ){
				$columns = 5;
			}
			$old_woocommerce_loop_columns = $woocommerce_loop['columns'];
			$woocommerce_loop['columns'] = $columns;

			$products = new WP_Query( $args );
			
			if( $products->have_posts() ):
				if( $products->post_count == $products->found_posts ){
					$show_shop_more = 0;
				}
				
				$margin = absint($margin);
				$rows = absint($rows);
			
				$classes = array();
				$classes[] = 'ts-product-wrapper ts-shortcode ts-product';
				$classes[] = $product_type;
				$classes[] = $item_layout;
				$classes[] = $meta_align;
				$classes[] = $title_style;
				$classes[] = $title?'':'no-title';
				$classes[] = $show_list_cats?'has-list-cats':'';
				$classes[] = $item_border?'item-border':'';
				$classes[] = $margin?'item-margin':'no-margin';
				$classes[] = $show_shop_more?'has-shop-more':'';
				$classes[] = $banners?'has-banner banner-'.$banner_position:'';
				$classes[] = $is_slider?'ts-slider':'no-slider';
				if( $is_slider ){
					$classes[] = $show_nav?'has-nav':'';
					$classes[] = $rows > 1?'many-rows':'';
					$classes[] = $show_nav?$position_nav:'';
					
					if( $margin != 30 && $margin != 0 ){ /* 30: default; 0: no margin */
						static $ts_product_slider_counter = 1;
						$classes[] = 'ts-product-'.$ts_product_slider_counter;
						
						$style = '<div class="ts-shortcode-custom-style hidden">';
						$style .= '.ts-product-'.$ts_product_slider_counter.' .products .product{margin-bottom: '.$margin.'px}';
						$style .= '.ts-product-'.$ts_product_slider_counter.' .banners.grid img{margin-bottom: '.$margin.'px}';
						$style .= '</div>';
						
						$ts_product_slider_counter++;
					}
				}
				
				$data_attr = array();
				if( $is_slider ){
					$data_attr[] = 'data-nav="'.$show_nav.'"';
					$data_attr[] = 'data-margin="'.$margin.'"';
					$data_attr[] = 'data-columns="'.$columns.'"';
					$data_attr[] = 'data-autoplay="'.$auto_play.'"';
					$data_attr[] = 'data-disable_responsive="'.$disable_slider_responsive.'"';
				}
			
				$count = 0;
			?>
			<div class="<?php echo esc_attr(implode(' ', $classes)); ?>" <?php echo implode(' ', $data_attr); ?>>
				<?php 
				if( !empty($style) ){
					echo trim($style);
				}
				?>
				<?php if( strlen($title) > 0 ): ?>
					<header class="shortcode-heading-wrapper">
						<h3 class="heading-title"><?php echo esc_html($title); ?></h3>
					</header>
				<?php endif; ?>
				<?php if( $show_list_cats ): ?>
				<ul class="list-cats">
					<?php
					foreach( $product_cats as $cat ){
						$cat_obj = get_term($cat, 'product_cat');
						if( isset($cat_obj->name) ){
							echo '<li><a href="'.get_term_link($cat_obj->term_id, 'product_cat').'">'.esc_html($cat_obj->name).'</a></li>';
						}
					}
					?>
				</ul>
				<?php endif; ?>
				<div class="content-wrapper <?php echo ($is_slider)?'loading':''; ?>">
					<div class="products-wrapper">
					<?php 
					woocommerce_product_loop_start();				

					while( $products->have_posts() ): 
						$products->the_post();
						if( $is_slider && $rows > 1 && $count % $rows == 0 ){
							echo '<div class="product-group">';
						}
						wc_get_template_part( 'content', 'product' );
						if( $is_slider && $rows > 1 && ($count % $rows == $rows - 1 || $count == $products->post_count - 1) ){
							echo '</div>';
						}
						$count++;
					endwhile;	

					woocommerce_product_loop_end();
					?>
					</div>
					<?php
					if( $banners ){
						$banners = explode(',', $banners);
						if( $banner_links ){
							$banner_links = array_map('trim', explode(',', $banner_links));
						}
						else{
							$banner_links = array();
						}
						echo '<div class="banners '.$banner_layout.' '.($banner_layout == 'slider'?'loading':'').'">';
						foreach( $banners as $index => $banner ){
							$link = isset($banner_links[$index])?$banner_links[$index]:'';
							if( $link ){
								echo '<a href="'.esc_url($link).'">';
							}
							echo wp_get_attachment_image($banner, 'full');
							if( $link ){
								echo '</a>';
							}
						}
						echo '</div>';
					}
					
					if( $show_shop_more ){
						$shop_more_link = wc_get_page_permalink('shop');
						if( is_array($product_cats) && count($product_cats) == 1 ){
							$category_url = get_term_link((int) $product_cats[0], 'product_cat');
							if( is_string($category_url) ){
								$shop_more_link = $category_url;
							}
						}
						echo '<a class="shop-more-button" href="'.esc_url($shop_more_link).'">'.esc_html($shop_more_text).'</a>';
					}
					?>
				</div>
			</div>
			<?php
			endif;
			
			wp_reset_postdata();

			/* restore hooks */
			ts_restore_product_hooks_shortcode();
			remove_all_filters('sanzo_grid_short_desc_limit_words');

			$woocommerce_loop['columns'] = $old_woocommerce_loop_columns;
			return '<div class="woocommerce columns-'.$columns.'">' . ob_get_clean() . '</div>';
	}	
}
add_shortcode('ts_products', 'ts_products_shortcode');

/*** Products Widget ***/
if( !function_exists('ts_products_widget_shortcode') ){
	function ts_products_widget_shortcode($atts, $content){
	
		if( !class_exists('Sanzo_Products_Widget') ){
			return;
		}
	
		extract(shortcode_atts(array(
				'product_type'			=> 'recent'
				,'rows' 				=> 3
				,'per_page' 			=> 6
				,'product_cats'			=> ''
				,'title' 				=> ''
				,'show_image' 			=> 1
				,'show_title' 			=> 1
				,'show_price' 			=> 1
				,'show_rating' 			=> 1	
				,'show_categories'		=> 0	
				,'is_slider'			=> 0
				,'show_nav'				=> 1
				,'position_nav'			=> 'nav-top'
				,'auto_play'			=> 1
			), $atts));	
		if( trim($product_cats) != '' ){
			$product_cats = array_map('trim', explode(',', $product_cats));
		}
		
		$instance = array(
			'title'					=> $title
			,'product_type'			=> $product_type
			,'product_cats'			=> $product_cats
			,'row'					=> $rows
			,'limit'				=> $per_page
			,'show_thumbnail' 		=> $show_image
			,'show_categories' 		=> $show_categories
			,'show_product_title' 	=> $show_title
			,'show_price' 			=> $show_price
			,'show_rating' 			=> $show_rating
			,'is_slider'			=> $is_slider
			,'show_nav' 			=> $show_nav
			,'position_nav' 		=> $position_nav
			,'auto_play' 			=> $auto_play
		);
		
		ob_start();
		the_widget('Sanzo_Products_Widget', $instance);
		return ob_get_clean();
	}
}
add_shortcode('ts_products_widget', 'ts_products_widget_shortcode');

/* Product Category Slider */
if( !function_exists('ts_product_categories_slider_shortcode') ){
	function ts_product_categories_slider_shortcode($atts, $content){
		extract(shortcode_atts(array(
			'title'				=> ''
			,'title_style'		=> ''
			,'layout'			=> 'slider'
			,'style'			=> 'style-1'
			,'per_page' 		=> 5
			,'columns' 			=> 4
			,'parent' 			=> ''
			,'child_of' 		=> 0
			,'ids'	 			=> ''
			,'orderby'	 		=> 'none'
			,'order'	 		=> 'DESC'
			,'hide_empty'		=> 1
			,'show_title'		=> 1
			,'show_count'		=> 0
			,'show_nav' 		=> 0
			,'show_dots' 		=> 1
			,'auto_play' 		=> 1
			,'margin'			=> 0
		),$atts));

		if ( !class_exists('WooCommerce') ){
			return;
		}
		
		if( $style == 'style-2' ){
			add_filter('subcategory_archive_thumbnail_size', 'ts_product_cat_thumbnail_size_filter', 10);
		}
		if( $style == 'style-3' ){
			add_filter('subcategory_archive_thumbnail_size', 'ts_product_cat_thumbnail_size_filter_2', 10);
		}

		if( $show_dots ){
			$show_nav = 0;
		}
		
		if( $layout == 'grid' && !in_array($columns, array(2,3,4,5)) ){
			$columns = 4;
		}

		$args = array(
			'orderby'     => $orderby
			,'order'      => $order
			,'hide_empty' => $hide_empty
			,'include'    => array_map('trim', explode(',', $ids))
			,'pad_counts' => true
			,'parent'     => $parent
			,'child_of'   => $child_of
			,'number'     => $per_page
		);
		$product_categories = get_terms('product_cat', $args);	
		global $woocommerce_loop;
		$old_woocommerce_loop_columns = $woocommerce_loop['columns'];
		$woocommerce_loop['columns'] = $columns;	
		
		ob_start();
		
		if( count($product_categories) > 0 ):
			$classes = array();
			$classes[] = 'ts-product-category-slider-wrapper ts-shortcode';
			$classes[] = 'layout-'.$layout;
			$classes[] = $style;
			$classes[] = $title_style;
			$classes[] = $title?'':'no-title';
			if( $layout == 'slider' ){
				$classes[] = 'ts-slider';
				$classes[] = $show_nav?'show-nav':'';
				$classes[] = $show_dots?'show-dots':'';
			}
			
			$data_attr = array();
			if( $layout == 'slider' ){
				$data_attr[] = 'data-nav="'.absint($show_nav).'"';
				$data_attr[] = 'data-dots="'.absint($show_dots).'"';
				$data_attr[] = 'data-autoplay="'.absint($auto_play).'"';
				$data_attr[] = 'data-margin="'.absint($margin).'"';
				$data_attr[] = 'data-columns="'.absint($columns).'"';
			}
		?>
			<div class="<?php echo esc_attr(implode(' ', $classes)) ?>" <?php echo implode(' ', $data_attr); ?>>
				<?php if( strlen($title) > 0 ): ?>
					<header class="shortcode-heading-wrapper">
						<h3 class="heading-title"><?php echo esc_html($title); ?></h3>
					</header>
				<?php endif; ?>
				<div class="content-wrapper <?php echo ($layout == 'slider')?'loading':'' ?>">
					<?php 
					woocommerce_product_loop_start();
					foreach ( $product_categories as $category ) {
						wc_get_template( 'content-product_cat.php', array(
							'category' 		=> $category
							,'show_title' 	=> $show_title
							,'show_count' 	=> $show_count
						) );
					}
					woocommerce_product_loop_end();
					woocommerce_reset_loop();
					?>
				</div>
			</div>
		<?php
		endif;
		
		if( $style == 'style-2' ){
			remove_filter('subcategory_archive_thumbnail_size', 'ts_product_cat_thumbnail_size_filter', 10);
		}
		if( $style == 'style-3' ){
			remove_filter('subcategory_archive_thumbnail_size', 'ts_product_cat_thumbnail_size_filter_2', 10);
		}
		$woocommerce_loop['columns'] = $old_woocommerce_loop_columns;
		
		return '<div class="woocommerce columns-'.absint($columns).'">' . ob_get_clean() . '</div>';			
	}
}
add_shortcode('ts_product_categories_slider', 'ts_product_categories_slider_shortcode');

if( !function_exists('ts_product_cat_thumbnail_size_filter') ){
	function ts_product_cat_thumbnail_size_filter( $size ){
		return 'sanzo_product_cat_thumb';
	}
}

if( !function_exists('ts_product_cat_thumbnail_size_filter_2') ){
	function ts_product_cat_thumbnail_size_filter_2( $size ){
		return 'sanzo_product_cat_thumb_2';
	}
}

/* TS Product Deals Slider */
if( !function_exists('ts_product_deals_slider_shortcode') ){
	function ts_product_deals_slider_shortcode($atts, $content = null){

		extract(shortcode_atts(array(
				'title' 					=> ''
				,'title_style'				=> ''
				,'columns' 					=> 4
				,'per_page' 				=> 5
				,'product_cats'				=> ''
				,'product_type'				=> 'recent'
				,'item_border'				=> 0
				,'meta_align'				=> ''
				,'banners' 					=> ''
				,'banner_links' 			=> ''
				,'banner_position' 			=> 'left'
				,'show_counter'				=> 1
				,'show_image' 				=> 1
				,'show_title' 				=> 1
				,'show_sku' 				=> 1
				,'show_price' 				=> 1
				,'show_short_desc'  		=> 0
				,'show_rating' 				=> 1
				,'show_label' 				=> 1	
				,'show_categories'			=> 0	
				,'show_add_to_cart' 		=> 1
				,'show_nav'					=> 1
				,'position_nav'				=> 'nav-top'
				,'auto_play'				=> 1
				,'margin'					=> 30
				,'disable_slider_responsive'=> 0
			), $atts));			

			if ( !class_exists('WooCommerce') ){
				return;
			}
			
			$per_page = absint($per_page);
			
			if( $disable_slider_responsive ){
				add_filter('sanzo_loop_product_thumbnail', 'ts_product_deals_thumbnail_filter', 10);
			}
			
			if( $show_counter ){
				add_action('woocommerce_after_shop_loop_item_title', 'ts_template_loop_time_deals', 99);
			}
			
			/* Remove hook */
			$options = array(
					'show_image'		=> $show_image
					,'show_label'		=> $show_label
					,'show_title'		=> $show_title
					,'show_sku'			=> $show_sku
					,'show_price'		=> $show_price
					,'show_short_desc'	=> $show_short_desc
					,'show_categories'	=> $show_categories
					,'show_rating'		=> $show_rating
					,'show_add_to_cart'	=> $show_add_to_cart
					,'meta_align'		=> $meta_align
				);
			ts_remove_product_hooks_shortcode( $options );

			$args = array(
				'post_type'				=> array('product', 'product_variation')
				,'post_status' 			=> 'publish'
				,'ignore_sticky_posts'	=> 1
				,'posts_per_page' 		=> -1
				,'orderby' 				=> 'date'
				,'order' 				=> 'desc'
				,'meta_query' => array(
					array(
						'key'		=> '_sale_price_dates_to'
						,'value'	=> current_time( 'timestamp', true )
						,'compare'	=> '>'
						,'type'		=> 'numeric'
					)
					,array(
						'key'		=> '_sale_price_dates_from'
						,'value'	=> current_time( 'timestamp', true )
						,'compare'	=> '<'
						,'type'		=> 'numeric'
					)
				)
				,'tax_query'		=> array()
			);
			
			ts_filter_product_by_product_type($args, $product_type);
			
			$array_product_cats = array();

			$product_cats = str_replace(' ', '', $product_cats);
			if( strlen($product_cats) > 0 ){
				$array_product_cats = explode(',', $product_cats);
			}			

			ob_start();
			global $woocommerce_loop, $post, $product;
			if( (int)$columns <= 0 ){
				$columns = 5;
			}
			$old_woocommerce_loop_columns = $woocommerce_loop['columns'];
			$woocommerce_loop['columns'] = $columns;
			
			$product_ids_on_sale = array();
			
			$products = new WP_Query( $args );
			
			if( $products->have_posts() ){
				while( $products->have_posts() ){
					$products->the_post();
					if( $post->post_type == 'product' ){
						$_product = wc_get_product( $post->ID );
						if( is_object( $_product ) && $_product->is_visible() ){
							if( !empty($array_product_cats) ){
								$field_name = is_numeric($array_product_cats[0])?'ids':'slug';
								$post_terms = wp_get_post_terms($post->ID, 'product_cat', array('fields' => $field_name));
								if( is_array($post_terms) ){
									$array_intersect = array_intersect($post_terms, $array_product_cats);
									if( !empty($array_intersect) ){
										$product_ids_on_sale[] = $post->ID;
									}
								}
							}
							else{
								$product_ids_on_sale[] = $post->ID;
							}
						}
					}
					else{ /* Variation product */
						$post_parent_id = $post->post_parent;
						$parent_product = wc_get_product( $post_parent_id );
						if( is_object( $parent_product ) && $parent_product->is_visible() ){
							if( !empty($array_product_cats) ){
								$field_name = is_numeric($array_product_cats[0])?'ids':'slug';
								$post_terms = wp_get_post_terms($post_parent_id, 'product_cat', array('fields' => $field_name));
								if( is_array($post_terms) ){
									$array_intersect = array_intersect($post_terms, $array_product_cats);
									if( !empty($array_intersect) ){
										$product_ids_on_sale[] = $post_parent_id;
									}
								}
							}
							else{
								$product_ids_on_sale[] = $post_parent_id;
							}
						}
					}
					
					$product_ids_on_sale = array_unique($product_ids_on_sale);
					if( count($product_ids_on_sale) == $per_page ){
						break;
					}
				}
			}
			
			if( count($product_ids_on_sale) == 0 ){
				$product_ids_on_sale = array(0);
			}
			
			$args = array(
				'post_type'				=> 'product'
				,'post_status' 			=> 'publish'
				,'ignore_sticky_posts'	=> 1
				,'posts_per_page' 		=> $per_page
				,'orderby' 				=> 'date'
				,'order' 				=> 'desc'
				,'post__in'				=> $product_ids_on_sale
				,'meta_query' 			=> WC()->query->get_meta_query()
				,'tax_query'           	=> WC()->query->get_tax_query()
			);
			
			ts_filter_product_by_product_type($args, $product_type);
			
			$products = new WP_Query($args);
			
			$is_slider = ( isset($products->post_count) && $products->post_count > 1 )?true:false;
			
			if( $products->have_posts() ): 
				$margin = absint($margin);
				
				$classes = array();
				$classes[] = 'ts-product-deals-slider-wrapper ts-slider ts-shortcode ts-product';
				$classes[] = $meta_align;
				$classes[] = $title?'':'no-title';
				$classes[] = $item_border?'item-border':'';
				$classes[] = $margin?'item-margin':'no-margin';
				$classes[] = $banners?'has-banner banner-'.$banner_position:'';
				$classes[] = $disable_slider_responsive?'disable-responsive':'';
				if( $show_nav ){
					$classes[] = 'has-nav';
					$classes[] = $position_nav;
				}
				
				$data_attr = array();
				$data_attr[] = 'data-nav="'.esc_attr($show_nav).'"';
				$data_attr[] = 'data-autoplay="'.esc_attr($auto_play).'"';
				$data_attr[] = 'data-margin="'.esc_attr($margin).'"';
				$data_attr[] = 'data-columns="'.esc_attr($columns).'"';
				$data_attr[] = 'data-is_slider="'.esc_attr($is_slider).'"';
				$data_attr[] = 'data-disable_responsive="'.esc_attr($disable_slider_responsive).'"';
				?>
				<div class="<?php echo esc_attr( implode(' ', $classes) ); ?> <?php echo esc_attr($title_style);?>" <?php echo implode(' ', $data_attr); ?>>
					<?php if( strlen($title) > 0 ): ?>
						<header class="shortcode-heading-wrapper">
							<h3 class="heading-title"><?php echo esc_html($title); ?></h3>
						</header>
					<?php endif; ?>
					<div class="content-wrapper <?php echo ($is_slider)?'loading':''; ?>">
						<?php woocommerce_product_loop_start(); ?>				

						<?php while( $products->have_posts() ): $products->the_post(); ?>
							<?php wc_get_template_part( 'content', 'product' ); ?>							
						<?php endwhile; ?>			

						<?php woocommerce_product_loop_end(); ?>
						
						<?php
						if( $banners ){
							$banners = explode(',', $banners);
							if( $banner_links ){
								$banner_links = array_map('trim', explode(',', $banner_links));
							}
							else{
								$banner_links = array();
							}
							echo '<div class="banners">';
							foreach( $banners as $index => $banner ){
								$link = isset($banner_links[$index])?$banner_links[$index]:'';
								if( $link ){
									echo '<a href="'.esc_url($link).'">';
								}
								echo wp_get_attachment_image($banner, 'full');
								if( $link ){
									echo '</a>';
								}
							}
							echo '</div>';
						}
						?>
					</div>
				</div>
				<?php
			endif;
			
			wp_reset_postdata();			

			/* restore hooks */
			if( $disable_slider_responsive ){
				remove_filter('sanzo_loop_product_thumbnail', 'ts_product_deals_thumbnail_filter', 10);
			}
			if( $show_counter ){
				remove_action('woocommerce_after_shop_loop_item_title', 'ts_template_loop_time_deals', 99);
			}

			ts_restore_product_hooks_shortcode();

			$woocommerce_loop['columns'] = $old_woocommerce_loop_columns;
			
			return '<div class="woocommerce">' . ob_get_clean() . '</div>';
	}
}
add_shortcode('ts_product_deals_slider', 'ts_product_deals_slider_shortcode');

if( !function_exists('ts_product_deals_thumbnail_filter') ){
	function ts_product_deals_thumbnail_filter(){
		return 'shop_single';
	}
}

if( !function_exists('ts_template_loop_time_deals') ){
	function ts_template_loop_time_deals(){
		global $product;
		$date_to = '';
		$date_from = '';
		if( $product->get_type() == 'variable' ){
			$children = $product->get_children();
			if( is_array($children) && count($children) > 0 ){
				foreach( $children as $children_id ){
					$date_to = get_post_meta($children_id, '_sale_price_dates_to', true);
					$date_from = get_post_meta($children_id, '_sale_price_dates_from', true);
					if( $date_to != '' ){
						break;
					}
				}
			}
		}
		else{
			$date_to = get_post_meta($product->get_id(), '_sale_price_dates_to', true);
			$date_from = get_post_meta($product->get_id(), '_sale_price_dates_from', true);
		}
		
		$current_time = current_time('timestamp', true);
		
		if( $date_to == '' || $date_from == '' || $date_from > $current_time || $date_to < $current_time ){
			return;
		}
		
		$delta = $date_to - $current_time;
		
		$time_day = 60 * 60 * 24;
		$time_hour = 60 * 60;
		$time_minute = 60;
		
		$day = floor( $delta / $time_day );
		$delta -= $day * $time_day;
		
		$hour = floor( $delta / $time_hour );
		$delta -= $hour * $time_hour;
		
		$minute = floor( $delta / $time_minute );
		$delta -= $minute * $time_minute;
		
		if( $delta > 0 ){
			$second = $delta;
		}
		else{
			$second = 0;
		}
		
		$day = zeroise($day, 2);
		$hour = zeroise($hour, 2);
		$minute = zeroise($minute, 2);
		$second = zeroise($second, 2);

		?>
		<div class="counter-wrapper days-<?php echo strlen($day); ?>">
			<div class="days">
				<div class="number-wrapper">
					<span class="number" data-number="<?php echo esc_attr($day) ?>"><?php echo esc_html($day); ?></span>
				</div>
				<div class="ref-wrapper">
					<?php esc_html_e('Days', 'themesky'); ?>
				</div>
			</div>
			<div class="hours">
				<div class="number-wrapper">
					<span class="number" data-number="<?php echo esc_attr($hour) ?>"><?php echo esc_html($hour); ?></span>
				</div>
				<div class="ref-wrapper">
					<?php esc_html_e('Hours', 'themesky'); ?>
				</div>
			</div>
			<div class="minutes">
				<div class="number-wrapper">
					<span class="number" data-number="<?php echo esc_attr($minute) ?>"><?php echo esc_html($minute); ?></span>
				</div>
				<div class="ref-wrapper">
					<?php esc_html_e('Mins', 'themesky'); ?>
				</div>
			</div>
			<div class="seconds">
				<div class="number-wrapper">
					<span class="number" data-number="<?php echo esc_attr($second) ?>"><?php echo esc_html($second); ?></span>
				</div>
				<div class="ref-wrapper">
					<?php esc_html_e('Secs', 'themesky'); ?>
				</div>
			</div>
		</div>
		<?php
	}
}

/* Single Products Slider */
if( !function_exists('ts_single_products_slider_shortcode') ){
	function ts_single_products_slider_shortcode( $atts, $content ){
		extract(shortcode_atts(array(
				'title' 				=> ''
				,'title_style'			=> ''
				,'product_type'			=> 'recent'
				,'per_page' 			=> 5
				,'product_cats'			=> ''
				,'ids'					=> ''
				,'show_shop_more' 		=> 1
				,'shop_more_text' 		=> 'Shop more'
				,'thumbnail_position'	=> 'left'
				,'show_image' 			=> 1
				,'show_title' 			=> 1
				,'show_sku' 			=> 1
				,'show_price' 			=> 1
				,'show_short_desc'  	=> 1
				,'show_rating' 			=> 0
				,'show_label' 			=> 1
				,'show_categories'		=> 1
				,'show_add_to_cart' 	=> 1
				,'show_nav'				=> 1
				,'auto_play'			=> 0
			), $atts));
		if ( !class_exists('WooCommerce') ){
			return;
		}
		
		if( $show_short_desc ){
			add_action('woocommerce_after_shop_loop_item', 'ts_template_single_products_slider_short_description', 45);
			$show_short_desc = 0;
		}
		
		add_filter('sanzo_loop_product_thumbnail', create_function('', 'return "shop_single";'), 999);
		
		$options = array(
				'show_image'		=> $show_image
				,'show_label'		=> $show_label
				,'show_title'		=> $show_title
				,'show_sku'			=> $show_sku
				,'show_price'		=> $show_price
				,'show_short_desc'	=> $show_short_desc
				,'show_categories'	=> $show_categories
				,'show_rating'		=> $show_rating
				,'show_add_to_cart'	=> $show_add_to_cart
			);
		ts_remove_product_hooks_shortcode( $options );
		
		$args = array(
			'post_type'				=> 'product'
			,'post_status' 			=> 'publish'
			,'ignore_sticky_posts'	=> 1
			,'posts_per_page' 		=> $per_page
			,'orderby' 				=> 'date'
			,'order' 				=> 'desc'
			,'meta_query' 			=> WC()->query->get_meta_query()
			,'tax_query'           	=> WC()->query->get_tax_query()
		);
		
		ts_filter_product_by_product_type($args, $product_type);
		
		$product_cats = str_replace(' ', '', $product_cats);
		if( strlen($product_cats) > 0 ){
			$product_cats = explode(',', $product_cats);
		}
		if( is_array($product_cats) && count($product_cats) > 0 ){
			$field_name = is_numeric($product_cats[0])?'term_id':'slug';
			$args['tax_query'][] = array(
										'taxonomy' => 'product_cat'
										,'terms' => $product_cats
										,'field' => $field_name
										,'include_children' => false
									);
		}
		
		$ids = str_replace(' ', '', $ids);
		if( strlen($ids) > 0 ){
			$ids = explode(',', $ids);
			if( is_array($ids) && count($ids) > 0 ){
				$args['post__in'] = $ids;
			}
		}
		
		ob_start();

		$products = new WP_Query( $args );
		
		if( $products->have_posts() ){
			if( $products->post_count == $products->found_posts ){
				$show_shop_more = 0;
			}
			$classes = array();
			$classes[] = 'ts-single-products-slider-wrapper ts-shortcode ts-product ts-slider';
			$classes[] = $product_type;
			$classes[] = $title?'':'no-title';
			$classes[] = $show_shop_more?'has-shop-more':'';
			$classes[] = $show_nav?'nav-top':'';
			$classes[] = 'thumbnail-'.$thumbnail_position;
			
			$data_attr = array();
			$data_attr[] = 'data-nav="'.$show_nav.'"';
			$data_attr[] = 'data-autoplay="'.$auto_play.'"';
		?>
		<div class="<?php echo esc_attr(implode(' ', $classes)); ?> <?php echo esc_attr($title_style);?>" <?php echo implode(' ', $data_attr); ?>>
			<?php if( strlen($title) > 0 ): ?>
				<header class="shortcode-heading-wrapper">
					<h3 class="heading-title"><?php echo esc_html($title); ?></h3>
				</header>
			<?php endif; ?>
			<div class="content-wrapper loading">
				<div class="products-wrapper">
				<?php 
					woocommerce_product_loop_start();				

					while( $products->have_posts() ): 
						$products->the_post();
						wc_get_template_part( 'content', 'product' );
					endwhile;	

					woocommerce_product_loop_end();
				?>
				</div>
				<?php 
				if( $show_shop_more ){
					$shop_more_link = wc_get_page_permalink('shop');
					if( is_array($product_cats) && count($product_cats) == 1 ){
						$category_url = get_term_link((int) $product_cats[0], 'product_cat');
						if( is_string($category_url) ){
							$shop_more_link = $category_url;
						}
					}
					echo '<a class="shop-more-button" href="'.esc_url($shop_more_link).'">'.esc_html($shop_more_text).'</a>';
				}
				?>
			</div>
		</div>
		<?php
		}
		
		wp_reset_postdata();
		
		/* restore hooks */
		ts_restore_product_hooks_shortcode();
		
		remove_action('woocommerce_after_shop_loop_item', 'ts_template_single_products_slider_short_description', 45);
		
		remove_all_filters('sanzo_loop_product_thumbnail', 999);
		
		return '<div class="woocommerce">' . ob_get_clean() . '</div>';
	}
}
add_shortcode('ts_single_products_slider', 'ts_single_products_slider_shortcode');

if( !function_exists('ts_template_single_products_slider_short_description') ){
	function ts_template_single_products_slider_short_description(){
	?>
		<div class="short-description">
			<?php sanzo_the_excerpt_max_words(-1, '', false, '', true); ?>
		</div>
	<?php
	}
}

/* Product in category tabs */
if( !function_exists('ts_products_in_category_tabs_shortcode') ){
	function ts_products_in_category_tabs_shortcode($atts, $content){

		extract(shortcode_atts(array(
			'title' 						=> ''
			,'color'						=> '#f5a72c'
			,'icon_fontawesome' 			=> 'fa fa-info-circle'
			,'icon_image' 					=> ''
			,'logos'						=> ''
			,'links'						=> ''
			,'product_type'					=> 'recent'
			,'columns' 						=> 4
			,'per_page' 					=> 8
			,'product_cats'					=> ''
			,'parent_cat' 					=> ''
			,'include_children' 			=> 0
			,'show_general_tab' 			=> 0
			,'general_tab_heading' 			=> ''
			,'product_type_general_tab' 	=> 'recent'
			,'show_image' 					=> 1
			,'show_title' 					=> 1
			,'show_sku' 					=> 0
			,'show_price' 					=> 1
			,'show_short_desc'  			=> 0
			,'show_rating' 					=> 0
			,'show_label' 					=> 1	
			,'show_categories'				=> 0	
			,'show_add_to_cart' 			=> 1
			,'meta_align' 					=> ''
			,'show_shop_more' 				=> 1
			,'shop_more_text' 				=> 'Shop more'
			,'is_slider' 					=> 0
			,'rows' 						=> 1
			,'show_nav' 					=> 1
			,'auto_play' 					=> 1
		), $atts));
		if ( !class_exists('WooCommerce') ){
			return;
		}
		
		if( empty($product_cats) && empty($parent_cat) ){
			return;
		}
		
		if( empty($product_cats) ){
			$sub_cats = get_terms('product_cat', array('parent' => $parent_cat, 'fields' => 'ids', 'orderby' => 'none'));
			if( is_array($sub_cats) && !empty($sub_cats) ){
				$product_cats = implode(',', $sub_cats);
			}
			else{
				return;
			}
			$shop_more_link = get_term_link((int)$parent_cat, 'product_cat');
			if( is_wp_error($shop_more_link) ){
				$shop_more_link = '#';
			}
		}
		else{
			$shop_more_link = get_permalink( wc_get_page_id('shop') );
		}
		
		$atts = compact('product_type', 'columns', 'rows', 'per_page', 'include_children', 'show_image', 'show_title', 'show_sku'
						, 'show_price', 'show_short_desc', 'show_rating', 'show_label', 'show_categories', 'show_add_to_cart'
						, 'meta_align', 'show_shop_more', 'product_type_general_tab', 'is_slider', 'show_nav', 'auto_play');
		
		$logos_arr = array();
		$logos = str_replace(' ', '', $logos);
		if( $logos != '' ){
			$logos_arr = explode(',', $logos);
		}
		
		$links_arr = array();
		$links = str_replace(' ', '', $links);
		if( $links != '' ){
			$links_arr = explode(',', $links);
		}
		
		$classes = array();
		$classes[] = 'ts-product-in-category-tab-wrapper ts-shortcode ts-product';
		$classes[] = $product_type;
		$classes[] = $meta_align;
		if( count($logos_arr) == 0 ){
			$classes[] = 'no-logo';
		}
		else{
			$classes[] = 'has-logo';
			wp_enqueue_script( 'jquery-caroufredsel' );
		}
		
		if( $show_shop_more ){
			$classes[] = 'has-shop-more';
		}
		else{
			$classes[] = 'no-shop-more';
		}
		
		if( $is_slider ){
			$classes[] = 'has-slider';
		}
		
		$rand_id = 'ts-product-in-category-tab-'.rand();
		$selector = '#'.$rand_id;
		
		$inline_style = '<div class="ts-shortcode-custom-style hidden">';
		$inline_style .= $selector.' .heading-tab{background-color:'.$color.'}';
		$inline_style .= $selector.' .column-tabs{border-color:'.$color.'}';
		$inline_style .= $selector.' .owl-nav div:hover{color:'.$color.'}';
		// Hover
		$inline_style .= $selector.' .column-tabs .tabs li:hover,'.$selector.' .column-tabs .tabs li.current{color:'.$color.'}';
		$inline_style .= '</div>';
		ob_start();
		
		?>
		<div class="<?php echo esc_attr(implode(' ', $classes)); ?>" id="<?php echo esc_attr($rand_id) ?>" data-atts="<?php echo htmlentities(json_encode($atts)); ?>">
			<?php echo trim($inline_style); ?>
			<div class="column-tabs">
				<header class="heading-tab">
					<?php if( strlen($title) > 0 ): ?>
						<h3 class="heading-title">
							<?php 
							if( !empty($icon_image) ){
								echo wp_get_attachment_image($icon_image);
							}else{ 
							?>
								<i class="<?php echo esc_attr($icon_fontawesome) ?>"></i>
							<?php 
							}
							?>
							<span><?php echo esc_html($title); ?></span>
						</h3>
					<?php endif; ?>
				</header>
				
				<ul class="tabs">
				<?php 
				if( $show_general_tab ){
				?>
					<li class="tab-item general-tab" data-product_cat="<?php echo esc_attr($product_cats) ?>" data-link="<?php echo esc_url($shop_more_link) ?>"><?php echo esc_html($general_tab_heading) ?></li>
				<?php
				}
				
				$product_cats = array_map('trim', explode(',', $product_cats));
				foreach( $product_cats as $k => $product_cat ):
					$term = get_term_by( is_numeric($product_cat)?'term_id':'slug', $product_cat, 'product_cat');
					if( !isset($term->name) ){
						continue;
					}
				?>
					<li class="tab-item" data-product_cat="<?php echo esc_attr($product_cat) ?>" data-link="<?php echo esc_url(get_term_link($term, 'product_cat')) ?>"><?php echo esc_html($term->name) ?></li>
				<?php
				endforeach;
				?>
				</ul>
				
				<?php if( $show_shop_more ): ?>
					<a class="shop-more-button" href="<?php echo esc_url($shop_more_link) ?>"><?php echo esc_html($shop_more_text) ?></a>
				<?php endif; ?>
			</div>
			<div class="columns-wrapper">
				<?php if( count($logos_arr) > 0 ): ?>
				<div class="column-logos loading">
					<figure>
					<?php 
					foreach( $logos_arr as $index => $logo ){
						if( isset($links_arr[$index]) ){
							$link = $links_arr[$index];
						}
						else{
							$link = '#';
						}
						
						echo '<a href="'.( $link != '#'? esc_url($link): 'javascript: void(0)' ).'">';
						echo wp_get_attachment_image($logo, 'ts_logo_thumb');
						echo '</a>';
					}
					?>
					</figure>
					<div class="owl-controls">
						<div class="owl-nav">
							<div class="owl-prev" id="prev-<?php echo esc_attr($rand_id) ?>"></div>
							<div class="owl-next" id="next-<?php echo esc_attr($rand_id) ?>"></div>
						</div>
					</div>
				</div>
				<?php endif; ?>
			
				<div class="column-products woocommerce columns-<?php echo esc_attr($columns) ?>">
				
				</div>
			</div>
		</div>
		<?php
		
		return ob_get_clean();
	}	
}
add_shortcode('ts_products_in_category_tabs', 'ts_products_in_category_tabs_shortcode');

add_action('wp_ajax_ts_get_product_content_in_category_tab', 'ts_get_product_content_in_category_tab');
add_action('wp_ajax_nopriv_ts_get_product_content_in_category_tab', 'ts_get_product_content_in_category_tab');
if( !function_exists('ts_get_product_content_in_category_tab') ){
	function ts_get_product_content_in_category_tab( $atts ){
		if( empty($_POST['atts']) || empty($_POST['product_cat']) ){
			die('0');
		}
		$atts = $_POST['atts'];
		$product_cat = $_POST['product_cat'];
		$is_general_tab = (isset($_POST['is_general_tab']) && $_POST['is_general_tab'])?true:false;
		
		if( $is_general_tab ){
			$atts['product_type'] = $atts['product_type_general_tab'];
		}
		
		ob_start();
		extract($atts);
		
		$options = array(
				'show_image'		=> $show_image
				,'show_label'		=> $show_label
				,'show_title'		=> $show_title
				,'show_sku'			=> $show_sku
				,'show_price'		=> $show_price
				,'show_short_desc'	=> $show_short_desc
				,'show_categories'	=> $show_categories
				,'show_rating'		=> $show_rating
				,'show_add_to_cart'	=> $show_add_to_cart
				,'meta_align'		=> $meta_align
			);
		ts_remove_product_hooks_shortcode( $options );
		
		$args = array(
			'post_type'				=> 'product'
			,'post_status' 			=> 'publish'
			,'ignore_sticky_posts'	=> 1
			,'posts_per_page' 		=> $per_page
			,'orderby' 				=> 'date'
			,'order' 				=> 'desc'
			,'meta_query' 			=> WC()->query->get_meta_query()
			,'tax_query'           	=> WC()->query->get_tax_query()
		);
		
		ts_filter_product_by_product_type($args, $product_type);
		
		$args['tax_query'][] = array(
									'taxonomy' => 'product_cat'
									,'terms' => explode(',', $product_cat)
									,'field' => 'term_id'
									,'include_children' => $include_children
								);
		
		
		global $woocommerce_loop;
		if( (int)$columns <= 0 ){
			$columns = 3;
		}
		$old_woocommerce_loop_columns = $woocommerce_loop['columns'];
		$woocommerce_loop['columns'] = $columns;

		$products = new WP_Query( $args );
		
		$count = 0;
		
		woocommerce_product_loop_start();
		if( $products->have_posts() ){	

			if( isset($products->found_posts, $products->post_count) && $products->found_posts == $products->post_count ){
				echo '<div class="hidden hide-shop-more"></div>';
			}

			while( $products->have_posts() ){ 
				$products->the_post();
				
				if( $is_slider && $count % $rows == 0 ){
					echo '<div class="product-group">';
				}
				
				wc_get_template_part( 'content', 'product' );
				
				if( $is_slider && ($count % $rows == $rows - 1 || $count == $products->post_count - 1) ){
					echo '</div>';
				}
				$count++;
			}

		}
		woocommerce_product_loop_end();
		
		wp_reset_postdata();

		/* restore hooks */
		ts_restore_product_hooks_shortcode();

		$woocommerce_loop['columns'] = $old_woocommerce_loop_columns;
		
		die(ob_get_clean());
	}
}

if( !function_exists('ts_list_of_product_categories_shortcode') ){
	function ts_list_of_product_categories_shortcode( $atts ){
		extract(shortcode_atts(array(
			'title' 				=> ''
			,'bg_image'				=> ''
			,'bg_color'				=> '#f5f5f5'
			,'item_border'			=> 0
			,'product_cat'			=> ''
			,'include_parent'		=> 1
			,'limit_sub_cat'		=> 5
			,'include_cats'			=> ''
		), $atts));
		
		if( empty($product_cat) && empty($include_cats) ){
			return;
		}
		
		ob_start();
		
		$bg_image_url = wp_get_attachment_url( $bg_image );
		
		$list_categories = array();
		
		if( !empty($product_cat) ){
			$list_categories = get_terms('product_cat', array('child_of' => $product_cat, 'number' => $limit_sub_cat));
		}
		else if( !empty($include_cats) ){
			$include_parent = false;
			$include_cats = array_map('trim', explode(',', $include_cats));
			$list_categories = get_terms('product_cat', array('include' => $include_cats, 'orderby' => 'none'));
		}
		?>
		<div class="ts-list-of-product-categories-wrapper ts-shortcode <?php echo ($item_border)?'item-border':''; ?>" style="background-image: url(<?php echo esc_url($bg_image_url); ?>);background-color:<?php echo $bg_color; ?>">
			<?php if( $title ): ?>
			<h3 class="heading-title"><?php echo esc_html($title) ?></h3>
			<?php endif; ?>
			<ul class="list-categories">
				<?php 
				if( $include_parent ){
					$parent_obj = get_term($product_cat, 'product_cat');
					if( !is_wp_error($parent_obj) && $parent_obj != null ){
				?>
					<li><a href="<?php echo get_term_link($parent_obj, 'product_cat'); ?>"><?php echo esc_html($parent_obj->name) ?></a></li>
				<?php 
					}
				}
				if( !empty($list_categories) && is_array($list_categories) ){
					foreach( $list_categories as $cat ){
					?>
					<li><a href="<?php echo get_term_link($cat, 'product_cat'); ?>"><?php echo esc_html($cat->name) ?></a></li>
					<?php
					}
				}
				?>
			</ul>
		</div>
		<?php
		return ob_get_clean();
	}	
}
add_shortcode('ts_list_of_product_categories', 'ts_list_of_product_categories_shortcode');
?>