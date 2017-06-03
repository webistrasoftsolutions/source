<?php 
/************************************
*** Custom Post Type Shortcodes
*************************************/
/*** Shortcode Team memmber ***/
function ts_team_member_shortcode($atts){
	extract(shortcode_atts(array(
						'style'  			=> '1'
						,'id'				=> ''
						,'target'			=> '_blank'
						,'excerpt_words'	=> 30						
					), $atts ));
					
	if( strlen(trim($id)) == 0 || !is_numeric($id) ){
		return;
	}
	if( !is_numeric($excerpt_words) ){
		$excerpt_words = 30;
	}
	
	ob_start();
	global $post, $ts_team_members;
	$thumb_size_name = isset($ts_team_members->thumb_size_name)?$ts_team_members->thumb_size_name:'ts_team_thumb';
	
	$args = array(
				'post_type'				=> 'ts_team'
				,'post_status'			=> 'publish'
				,'ignore_sticky_posts'	=> true
				,'p'					=> $id
			);
	
	$team = new WP_Query($args);
	if( $team->have_posts() ){
		while( $team->have_posts() ){
			$team->the_post();
			$profile_link = get_post_meta($post->ID, 'ts_profile_link', true);
			if( $profile_link == '' ){
				$profile_link = '#';
			}
			$name = get_the_title($post->ID);
			if( function_exists('sanzo_the_excerpt_max_words') ){
				$content = sanzo_the_excerpt_max_words($excerpt_words, $post, true, '', false);
			}
			else{
				$content = substr(wp_strip_all_tags($post->post_content), 0, 300);
			}
			$role = get_post_meta($post->ID, 'ts_role', true);
			
			$facebook_link = get_post_meta($post->ID, 'ts_facebook_link', true);
			$twitter_link = get_post_meta($post->ID, 'ts_twitter_link', true);
			$google_plus_link = get_post_meta($post->ID, 'ts_google_plus_link', true);
			$linkedin_link = get_post_meta($post->ID, 'ts_linkedin_link', true);
			$rss_link = get_post_meta($post->ID, 'ts_rss_link', true);
			$dribbble_link = get_post_meta($post->ID, 'ts_dribbble_link', true);
			$pinterest_link = get_post_meta($post->ID, 'ts_pinterest_link', true);
			$instagram_link = get_post_meta($post->ID, 'ts_instagram_link', true);
			$custom_link = get_post_meta($post->ID, 'ts_custom_link', true);
			$custom_link_icon_class = get_post_meta($post->ID, 'ts_custom_link_icon_class', true);
			
			$social_content = '';
			
			if( $facebook_link ){
				$social_content .= '<a class="facebook" href="'.esc_url($facebook_link).'" target="'.$target.'"><i class="fa fa-facebook"></i></a>';
			}
			if( $twitter_link ){
				$social_content .= '<a class="twitter" href="'.esc_url($twitter_link).'" target="'.$target.'"><i class="fa fa-twitter"></i></a>';
			}
			if( $google_plus_link ){
				$social_content .= '<a class="google" href="'.esc_url($google_plus_link).'" target="'.$target.'"><i class="fa fa-google-plus"></i></a>';
			}
			if( $linkedin_link ){
				$social_content .= '<a class="linked" href="'.esc_url($linkedin_link).'" target="'.$target.'"><i class="fa fa-linkedin"></i></a>';
			}
			if( $rss_link ){
				$social_content .= '<a class="rss" href="'.esc_url($rss_link).'" target="'.$target.'"><i class="fa fa-rss"></i></a>';
			}
			if( $dribbble_link ){
				$social_content .= '<a class="dribbble" href="'.esc_url($dribbble_link).'" target="'.$target.'"><i class="fa fa-dribbble"></i></a>';
			}
			if( $pinterest_link ){
				$social_content .= '<a class="pinterest" href="'.esc_url($pinterest_link).'" target="'.$target.'"><i class="fa fa-pinterest-p"></i></a>';
			}
			if( $instagram_link ){
				$social_content .= '<a class="instagram" href="'.esc_url($instagram_link).'" target="'.$target.'"><i class="fa fa-instagram"></i></a>';
			}
			if( $custom_link ){
				$social_content .= '<a class="custom" href="'.esc_url($custom_link).'" target="'.$target.'"><i class="fa '.esc_attr($custom_link_icon_class).'"></i></a>';
			}
			
			?>
			<div class="ts-team-member style-<?php echo esc_attr($style); ?>">
				<div class="content-info">
					<?php if( has_post_thumbnail() ): ?>
						<div class="image-thumbnail">
							<figure>
							<?php the_post_thumbnail($thumb_size_name); ?>
							</figure>
						</div>
					<?php endif; ?>
					
					<header>
						<div class="member-name"><span><?php esc_html_e('Name: ', 'themesky'); ?></span><h3><a class="name" href="<?php echo esc_url($profile_link); ?>" target="<?php echo esc_attr($target) ?>"><?php echo esc_html($name); ?></a></h3></div>
						<div class="member-role"><span><?php esc_html_e('Position: ', 'themesky'); ?></span><span class="role"><?php echo esc_html($role); ?></span></div>
						<div class="member-excerpt"><?php echo esc_html($content); ?></div>
						<div class="member-social"><span><?php esc_html_e('Follows: ', 'themesky'); ?></span><span class="social"><?php echo $social_content; ?></span></div>
					</header>
				</div>
				
			</div>
			<?php
		}
	}
	
	wp_reset_postdata();
	
	return ob_get_clean();
}
add_shortcode('ts_team_member', 'ts_team_member_shortcode');

/*** Shortcode Feature ***/
function ts_feature_shortcode( $atts ){
	extract(shortcode_atts(array(
						'style'						=> 'feature-horizontal'
						,'style_thumbnail'			=> 'thumbnail-default'
						,'bg_transparent'			=> 0
						,'bg_color'					=> '#f5a72c'
						,'border_content'			=> 0
						,'border_color'				=> '#f5a72c'
						,'border_width'				=> '1'
						,'icon_bg_transparent'		=> 1
						,'icon_bg_color'			=> '#f5a72c'
						,'text_title_color'			=> '#ffffff'
						,'text_title_color_hover'	=> '#ffffff'
						,'text_excerpt_color'		=> '#ffffff'
						,'use_icon_font'			=> 0
						,'icon_fontawesome' 		=> 'fa fa-info-circle'
						,'img_id'					=> ''
						,'img_url'					=> ''
						,'title' 					=> ''
						,'excerpt' 					=> ''
						,'link' 					=> ''		
						,'target' 					=> '_blank'
						,'extra_class'				=> ''
					), $atts ));
	
	ob_start();
	
	$classes = array();
	$classes[] = 'ts-feature-wrapper';
	$classes[] = $extra_class;
	$classes[] = $style;
	$classes[] = $style_thumbnail;
	if(( strlen($img_id) > 0 && !$use_icon_font)|| (strlen($img_url) > 0 && !$use_icon_font)){
		$classes[] = 'has-image';
	}
	if( $use_icon_font ){
		$classes[] = 'has-icon';
	}
	if( $link == '' ){
		$classes[] = 'no-link';
	}
	if( $bg_transparent ){
		$classes[] = 'bg-transparent';
	}
	if( $icon_bg_transparent ){
		$classes[] = 'icon-bg-transparent';
	}
	if( $border_content ){
		$classes[] = 'has-border';
	}
	
	static $ts_feature_counter = 1;
	$unique_class = 'ts-feature-'.$ts_feature_counter;
	$selector = ' .' . $unique_class;
	$ts_feature_counter++;
	$style = '<div class="ts-shortcode-custom-style hidden">';
	if( !$bg_transparent){
		$style .= $selector.' .ts-feature-content .feature-content{
					background-color: '. $bg_color . ';
				}';
	}
	if( $border_content){
		$style .= $selector.' .ts-feature-content .feature-content{
					border-color: '. $border_color . ';
					border-width: '. $border_width .'px;
				}';
	}
	if( !$icon_bg_transparent && !$border_content ){
		$style .= $selector.' .feature-thumbnail{
					background-color: '. $icon_bg_color . ';
				}';
	}
	else if( $bg_transparent && $border_content && !$icon_bg_transparent ){
		$style .= $selector.' .icon-wrapper,'.
				  $selector.' .image-wrapper{
					background-color: '. $icon_bg_color . ';
				}';
	}
	$style .= $selector.' .feature-header h3 a{
				color: '. $text_title_color . ';
				}'.
			  $selector.' .feature-header h3 a:hover{
				color: '. $text_title_color_hover . ';
				}'.
			  $selector.' .feature-excerpt{
				color: '. $text_excerpt_color . ';
			}';
	$style .= '</div>';
	?>
	<div class="<?php echo esc_attr($unique_class) ?> <?php echo esc_attr(implode(' ', $classes)) ?>">
	
		<?php echo trim($style); ?>
		
		<div class="ts-feature-content">
			<div class="feature-content">
			
				<?php if( $use_icon_font ): ?>
				<div class="icon-wrapper">
					<a target="<?php echo esc_attr($target); ?>" class="feature-icon" href="<?php echo ($link != '')?esc_url($link):'javascript: void(0)' ?>">
						<i class="<?php echo esc_attr($icon_fontawesome); ?>"></i>
					</a>
				</div>
				<?php endif; ?>
				
				<?php if(( strlen($img_id) > 0 && !$use_icon_font)|| (strlen($img_url) > 0 && !$use_icon_font)) : ?>
				<div class="image-wrapper">
					<a target="<?php echo esc_attr($target); ?>" class="feature-thumbnail" href="<?php echo ($link != '')?esc_url($link):'javascript: void(0)' ?>" >
						<?php 
						if( $img_url != '' ){
						?>
							<img title="<?php echo esc_attr($title) ?>" alt="<?php echo esc_attr($title) ?>" src="<?php echo esc_url($img_url) ?>" />
						<?php
						}
						else{
							if( apply_filters('ts_page_intro_feature_filter', false) ){
								$image_loading = get_template_directory_uri() . '/images/prod_loading.gif';
								$image_src = wp_get_attachment_image_src($img_id, 'full');
								if( is_array($image_src) ){
								?>
								<img src="<?php echo esc_url($image_loading) ?>" data-src="<?php echo esc_url($image_src[0]) ?>" alt="<?php echo esc_attr($title) ?>" width="<?php echo esc_attr($image_src[1]) ?>" height="<?php echo esc_attr($image_src[2]) ?>" class="img lazy-loading" />
								<?php
								}
							}
							else{
								echo wp_get_attachment_image($img_id, 'full', 0, array('class'=>'img'));
							}
						}
						?> 
						<span class="overlay"></span>
					</a>
				</div>
				<?php endif; ?>
				
					<header class="feature-header">
				
					<?php if( strlen($title) > 0 ): ?>
						<h3 class="feature-title heading-title entry-title">
							<a target="<?php echo esc_attr($target); ?>" href="<?php echo ($link != '')?esc_url($link):'javascript: void(0)' ?>"><?php echo esc_html($title); ?></a>
						</h3>
					<?php endif; ?>
					
					<?php if( strlen($excerpt) > 0 ): ?>
						<div class="feature-excerpt">
							<?php echo esc_html($excerpt); ?>
						</div>
					<?php endif; ?>

					</header>
			</div>
		</div>
	</div>
	<?php
	
	return ob_get_clean();
}
add_shortcode('ts_feature', 'ts_feature_shortcode');

/*** Shortcode Price Table ***/
function ts_price_table_shortcode( $atts ){
	extract(shortcode_atts(array(
						'active_table' 		=> 0
						,'title'			=> ''
						,'price' 			=> ''
						,'currency' 		=> ''
						,'during_price' 	=> '/month'
						,'description'		=> ''
						,'button_text'		=> ''
						,'link'				=> '#'
					), $atts ));
	
	ob_start();
	?>
	<div class="ts-price-table <?php echo ($active_table)?'active-table':'' ?>">
		<header>
			<h3 class="table-title"><?php echo esc_html($title) ?></h3>
		</header>
		<div class="table-info">
			<span class="price"><span><?php echo esc_html($currency) ?></span><?php echo esc_html($price) ?></span>
			<span class="during"><?php echo esc_html($during_price) ?></span>
			<div class="table-description">
				<?php echo strip_tags($description, '<ul></ul><li></li><b></b><strong></strong><i></i>'); ?>
				<a class="button" href="<?php echo esc_url($link) ?>"><?php echo esc_html($button_text) ?></a>
			</div>
		</div>
	</div>
	<?php
	
	return ob_get_clean();
}
add_shortcode('ts_price_table', 'ts_price_table_shortcode');

/*** Shortcode Testimonial ***/
function ts_testimonial_shortcode($atts){
	extract(shortcode_atts(array(
						'categories'			=> ''
						,'per_page'				=> 4
						,'show_avatar'			=> 1
						,'text_color_style'		=> 'text-default'
						,'bg_color'				=> '#ffffff'
						,'has_padding'			=> 0
						,'ids'					=> ''
						,'excerpt_words'		=> 50
						,'is_slider'			=> 1
						,'show_nav'				=> 0
						,'show_dots'			=> 1
						,'auto_play'			=> 1
					), $atts ));
	
	if( !is_numeric($excerpt_words) ){
		$excerpt_words = 50;
	}
	
	if( $show_dots ){
		$show_nav = 0;
	}
	
	ob_start();
	
	global $post, $ts_testimonials;
	
	$args = array(
			'post_type'				=> 'ts_testimonial'
			,'post_status'			=> 'publish'
			,'ignore_sticky_posts'	=> true
			,'posts_per_page' 		=> $per_page
			,'orderby' 				=> 'date'
			,'order' 				=> 'desc'
		);
		
	$categories = str_replace(' ', '', $categories);
	if( strlen($categories) > 0 ){
		$categories = explode(',', $categories);
	}
	
	if( is_array($categories) && count($categories) > 0 ){
		$field_name = is_numeric($categories[0])?'term_id':'slug';
		$args['tax_query'] = array(
								array(
									'taxonomy' => 'ts_testimonial_cat',
									'terms' => $categories,
									'field' => $field_name,
									'include_children' => false
								)
							);
	}
	
	if( strlen(trim($ids)) > 0 ){
		$ids = array_map('trim', explode(',', $ids));
		if( is_array($ids) && count($ids) > 0 ){
			$args['post__in'] = $ids;
		}
	}
	
	$testimonials = new WP_Query($args);
	if( $testimonials->have_posts() ){
		if( isset($testimonials->post_count) && $testimonials->post_count <= 1 ){
			$is_slider = false;
		}
		?>
		<div style="background-color:<?php echo $bg_color ?>" class="ts-testimonial-wrapper <?php echo ($has_padding)?'has-padding':'' ?> <?php echo esc_attr($text_color_style) ?> <?php echo ($show_nav)?'show-navi':'';?> <?php echo ($show_dots)?'show-dots':'';?> <?php echo ($is_slider)?'ts-slider loading':''; ?>" 
			data-nav="<?php echo esc_attr($show_nav) ?>" data-dots="<?php echo esc_attr($show_dots) ?>" data-autoplay="<?php echo esc_attr($auto_play) ?>">
		<?php 
	
		
		while( $testimonials->have_posts() ){
			$testimonials->the_post();
			if( function_exists('sanzo_the_excerpt_max_words') ){
				$content = sanzo_the_excerpt_max_words($excerpt_words, $post, true, '', false);
			}
			else{
				$content = substr(wp_strip_all_tags($post->post_content), 0, 300);
			}
			$byline = get_post_meta($post->ID, 'ts_byline', true);
			$url = get_post_meta($post->ID, 'ts_url', true);
			if( $url == '' ){
				$url = '#';
			}
			$rating = get_post_meta($post->ID, 'ts_rating', true);
			$rating_percent = '0';
			if( $rating != '-1' && $rating != '' ){
				$rating_percent = $rating * 100 / 5;
			}
			
			$gravatar_email = get_post_meta($post->ID, 'ts_gravatar_email', true);
			$has_image = false;
			if( has_post_thumbnail() || ($gravatar_email != '' && is_email($gravatar_email)) ){
				$has_image = true;
			}
			?>
			
			<div class="item <?php echo (($has_image) && ($show_avatar))?'has-image':'no-image'; ?>">
				
				<?php if( ($has_image) && ($show_avatar) ): ?>
				<div class="image">
					<?php echo $ts_testimonials->get_image($post->ID); ?>
				</div>
				<?php endif; ?>
				<div class="testimonial-content">
					
					<?php if( $rating != '-1' && $rating != '' ): ?>
					<div class="rating" title="<?php printf(esc_html__('Rated %s out of 5', 'themesky'), $rating); ?>">
						<span style="width: <?php echo $rating_percent.'%'; ?>"><?php printf(esc_html__('Rated %s out of 5', 'themesky'), $rating); ?></span>
					</div>
					<?php endif; ?>
					
					<h4 class="name">
						<a href="<?php echo esc_url($url); ?>" target="_blank">
							<?php echo get_the_title($post->ID); ?>
						</a>
					</h4>
					
					<?php if( $byline ): ?>
					<div class="byline">
						<?php echo esc_html($byline); ?>
					</div>
					<?php endif; ?>
					
					<div class="content">
						<?php echo esc_html($content); ?>
					</div>
					
				</div>
			</div>
			<?php
		}
		?>
		</div>
		<?php
	}
	
	wp_reset_postdata();
	return ob_get_clean();
}
add_shortcode('ts_testimonial', 'ts_testimonial_shortcode');

/*** Shortcode Portfolio ***/
if( !function_exists('ts_portfolio_shortcode') ){
	function ts_portfolio_shortcode( $atts ){
		extract(shortcode_atts(array(
							'title'				=> ''
							,'title_style'		=> ''
							,'columns'			=> 2
							,'per_page'			=> 8
							,'categories'		=> ''
							,'orderby'			=> 'none'
							,'order'			=> 'DESC'
							,'show_filter_bar'	=> 1
							,'show_load_more'	=> 1
							,'load_more_text'	=> 'Show more'
							,'show_title'		=> 1
							,'show_link_icon'	=> 1
							,'show_like_icon'	=> 1
							,'is_slider'		=> 0
							,'show_nav'			=> 1
							,'auto_play'		=> 1
							,'margin'			=> 30
						), $atts ));
						
		if( $is_slider ){
			$show_filter_bar = 0;
			$show_load_more = 0;
			$margin = absint($margin);
		}
		
		$args = array(
			'post_type'				=> 'ts_portfolio'
			,'posts_per_page'		=> $per_page
			,'post_status'			=> 'publish'
			,'ignore_sticky_posts'	=> 1
			,'orderby'				=> $orderby
			,'order'				=> $order
		);	
		$categories = str_replace(' ', '', $categories);
		if( strlen($categories) > 0 ){
			$ar_categories = explode(',', $categories);
			if( is_array($ar_categories) && count($ar_categories) > 0 ){
				$field_name = is_numeric($ar_categories[0])?'term_id':'slug';
				$args['tax_query']	= array(
							array(
								'taxonomy'	=> 'ts_portfolio_cat'
								,'field'	=> $field_name
								,'terms'	=> $ar_categories
							)
						);
			}
		}
		ob_start();
		global $post, $wp_query, $ts_portfolios;
		$extra_class = "";
		if(strlen($title) <= 0 && $is_slider){
			$extra_class .= "no-title ".$title_style;
		}
		if(strlen($title) > 0 && $is_slider){
			$extra_class .= $title_style.' nav-top';
		}
		$posts = new WP_Query( $args );
		if( $posts->have_posts() ){
			$atts = compact('columns', 'per_page', 'categories', 'orderby', 'order', 'show_filter_bar', 'show_title'
							, 'show_link_icon', 'show_like_icon', 'is_slider', 'show_nav', 'auto_play', 'margin');
			?>
			<div class="ts-portfolio-wrapper ts-shortcode loading <?php echo $extra_class; ?> <?php echo ($is_slider)?'ts-slider':'ts-masonry columns-'.$columns ?>" data-atts="<?php echo htmlentities(json_encode($atts)); ?>">
			
			<?php if( strlen($title) > 0 && $is_slider ): ?>
				<header class="shortcode-heading-wrapper">
					<h3 class="heading-title"><?php echo esc_html($title); ?></h3>
				</header>
			<?php endif; ?>
			
			<?php
			/* Get filter bar */
			if( $show_filter_bar ){
				$terms = array();
				foreach( $posts->posts as $p ){
					$post_terms = wp_get_post_terms($p->ID, 'ts_portfolio_cat');
					if( is_array($post_terms) ){
						foreach( $post_terms as $term ){
							$terms[$term->slug] = $term->name;
						}
					}
				}
				
				if( !empty($terms) ){
					?>
					<ul class="filter-bar">
						<li data-filter="*" class="current"><?php esc_html_e('All', 'themesky'); ?></li>
						<?php
						foreach( $terms as $slug => $name ){
						?>
						<li data-filter="<?php echo '.'.$slug; ?>"><?php echo esc_attr($name) ?></li>
						<?php
						}
						?>
					</ul>
					<?php
				}
			}
			?>
				<div class="portfolio-inner">
				<?php
					ts_get_portfolio_items_content_shortcode($atts, $posts);
				?>
				</div>
				<?php if( $show_load_more ){ ?>
				<div class="load-more-wrapper">
					<a href="#" class="load-more button" data-paged="2"><?php echo esc_html($load_more_text) ?></a>
				</div>
				<?php } ?>
			</div>
			
			<?php
		}
		
		wp_reset_postdata();
		return ob_get_clean();
	}
}
add_shortcode('ts_portfolio', 'ts_portfolio_shortcode');

add_action('wp_ajax_ts_portfolio_load_items', 'ts_get_portfolio_items_content_shortcode');
add_action('wp_ajax_nopriv_ts_portfolio_load_items', 'ts_get_portfolio_items_content_shortcode');
if( !function_exists('ts_get_portfolio_items_content_shortcode') ){
	function ts_get_portfolio_items_content_shortcode($atts, $posts = null){
		
		global $post, $ts_portfolios;
		
		if( defined( 'DOING_AJAX' ) && DOING_AJAX ){
			if( !isset($_POST['atts']) ){
				die('0');
			}
			$atts = $_POST['atts'];
			$paged = isset($_POST['paged'])?absint($_POST['paged']):1;
			
			extract($atts);
			
			$args = array(
				'post_type'				=> 'ts_portfolio'
				,'posts_per_page'		=> $per_page
				,'post_status'			=> 'publish'
				,'ignore_sticky_posts'	=> 1
				,'paged' 				=> $paged
				,'orderby'				=> $orderby
				,'order'				=> $order
			);	
			$categories = str_replace(' ', '', $categories);
			if( strlen($categories) > 0 ){
				$categories = explode(',', $categories);
				if( is_array($categories) ){
					$field_name = is_numeric($categories[0])?'term_id':'slug';
					$args['tax_query']	= array(
								array(
									'taxonomy'	=> 'ts_portfolio_cat'
									,'field'	=> $field_name
									,'terms'	=> $categories
								)
							);
				}
			}
			$posts = new WP_Query( $args );
			ob_start();
		}
		
		extract($atts);
		
		if( $posts->have_posts() ):
			while( $posts->have_posts() ): $posts->the_post();
				$classes = '';
				$post_terms = wp_get_post_terms($post->ID, 'ts_portfolio_cat');
				if( is_array($post_terms) ){
					foreach( $post_terms as $term ){
						$classes .= $term->slug . ' ';
					}
				}
				
				$link = esc_url(get_post_meta($post->ID, 'ts_portfolio_url', true));
				if( $link == '' ){
					$link = get_permalink();
				}
				
				$bg_color = get_post_meta($post->ID, 'ts_bg_color', true);
				if( $bg_color == '' ){
					$bg_color = '#000';
				}
				
				/* Get Like */
				$like_num = 0;
				$user_already_like = false;
				if( is_a($ts_portfolios, 'TS_Portfolios') ){
					$like_num = $ts_portfolios->get_like( $post->ID );
					$user_already_like = $ts_portfolios->user_already_like( $post->ID );
				}
				?>
				<div class="item <?php echo esc_attr($classes) ?>">
					<figure>
						<span class="bg-hover" style="background-color: <?php echo esc_attr($bg_color); ?>"></span>
						<?php 
						if( has_post_thumbnail() ){
							the_post_thumbnail('sanzo_blog_shortcode_thumb');
						}
						?>							
					</figure>
					<div class="portfolio-meta">
						<?php if( $show_title ){ ?>
							<h3>
								<a href="<?php echo esc_url($link); ?>">
									<?php echo get_the_title(); ?>
								</a>
							</h3>
						<?php } ?>
						<div class="icon-group">
							<?php if( $show_link_icon ){ ?>
							<a href="<?php echo esc_url($link); ?>" class="link"></a>
							<?php } ?>
							<?php if( $show_like_icon ){ ?>
							<a href="#" class="like <?php echo ($user_already_like)?'already-like':'' ?>" 
								data-post_id="<?php echo esc_attr($post->ID) ?>" title="<?php echo ($user_already_like)?esc_html__('You liked it', 'themesky'):esc_html__('Like it', 'themesky') ?>"
								data-liked-title="<?php esc_html_e('You liked it', 'themesky') ?>" data-like-title="<?php esc_html_e('Like it', 'themesky') ?>">
								<?php echo esc_html($like_num); ?>
							</a>
							<?php } ?>
						</div>
					</div>
				</div>
			<?php
			endwhile;
		endif;
		
		wp_reset_postdata();
		
		if( defined( 'DOING_AJAX' ) && DOING_AJAX ){
			die(ob_get_clean());
		}
		
	}
}

/*** Shortcode Banner ***/
function ts_banner_shortcode( $atts ){
	extract(shortcode_atts(array(
						'font_size'							=> 'font-normal'
						,'text_align'						=> 'text-left'
						,'heading_title'					=> ''
						,'heading_title_2'					=> ''
						,'title_many_colors'					=> 0
						,'description'						=> ''
						,'position_description'				=> 'bottom-title'
						,'show_price'						=> 0
						,'price'							=> '$100'
						,'show_discount'					=> 0
						,'discount'							=> 'Discount 10%'
						,'show_button'						=> 1
						,'button_border'					=> 0
						,'button_text'						=> 'Shop now'
						,'title_text_color'					=> '#ffffff'
						,'title_text_2_color'				=> '#ffffff'
						,'description_text_color'			=> '#ffffff'
						,'price_color'						=> '#f5a72c'
						,'discount_color'					=> '#f5a72c'
						,'button_text_color'				=> '#ffffff'
						,'button_background_color'			=> '#3f3f3f'
						,'button_text_color_hover'			=> '#ffffff'
						,'button_background_color_hover'	=> '#f5a72c'
						,'bg_id'							=> ''
						,'bg_url'							=> ''
						,'bg_color'							=> '#ffffff'
						,'position_content'					=> 'left-top'
						,'link' 							=> ''
						,'style_effect'						=> 'background-scale'
						,'responsive_size'					=> 1
						,'link_title' 						=> ''						
						,'target' 							=> '_blank'
						,'extra_class'						=> ''
					), $atts ));

	static $ts_banner_counter = 1;
	$unique_class = 'ts-banner-'.$ts_banner_counter;
	$selector = '.' . $unique_class;
	$ts_banner_counter++;
	
	$style = '<div class="ts-shortcode-custom-style hidden">';
	$style .= $selector. ' .banner-wrapper{
				background-color: '. $bg_color . ';
			}';
	$style .= $selector.'.background-scale-opacity-line .banner-wrapper:before,'.
			  $selector.'.background-scale-and-line .banner-wrapper:before,'.
			  $selector.'.background-dark-and-line .banner-wrapper:before,'.
			  $selector.'.background-scale-and-dark-line .banner-wrapper:before,'.
			  $selector.'.eff-line .banner-wrapper:before,'.
			  $selector.'.background-opacity-and-line .banner-wrapper:before{
				border-color: '. $bg_color . ';
			}';
	$style .= $selector.' header h3{
			  color:'.$title_text_color.';
			  }';
	if($title_many_colors){
		$style .= $selector.' header h3 span{
			  color:'.$title_text_2_color.';
			  }';
	}
	$style .= $selector.' header .description{
			  color:'.$description_text_color.';
			  }';
	if($show_discount){
	$style .= $selector.' .discount{
			  color:'.$discount_color.';
			  }';
	}
	if($show_price){
	$style .= $selector.' .price{
			  color:'.$price_color.';
			  }';
	}
	if($button_border && $show_button){
		$style .= $selector.' .button-banner{
				  color:'.$button_text_color.';
				  border-color:'.$button_background_color.';
				  }';
		$style .= $selector.' .button-banner:hover{
				  color:'.$button_text_color_hover.';
				  background-color:'.$button_background_color_hover.';
				  border-color:'.$button_background_color_hover.';
				  }';
	}elseif( !$button_border && $show_button){
		$style .= $selector.' .button-banner{
				  color:'.$button_text_color.';
				  background-color:'.$button_background_color.';
				  border-color:'.$button_background_color.';
				  }';
		$style .= $selector.' .button-banner:hover{
				  color:'.$button_text_color_hover.';
				  background-color:'.$button_background_color_hover.';
				  border-color:'.$button_background_color_hover.';
				  }';
	}
	$style .= '</div>';
	
	ob_start();
	
	?>
	<div class="ts-banner <?php echo $font_size ?> <?php echo $text_align ?> <?php echo esc_attr($unique_class) ?> <?php echo esc_attr($style_effect) ?> <?php echo ($responsive_size)?'responsive-size':'' ?> <?php echo esc_attr($position_content) ?> <?php echo esc_attr($extra_class) ?>">
		<?php echo trim($style); ?>
		<div class="banner-wrapper">
			<?php if( $link != '' && !$show_button ): ?>
				<a title="<?php echo esc_attr($link_title) ?>" target="<?php echo esc_attr($target); ?>" class="banner-link" href="<?php echo esc_url($link) ?>" ></a>
			<?php endif;?>
			
			<div class="ts-banner-wrapper">
				<div class="banner-bg">
				<?php 
				if( $bg_url != '' ){
				?>
					<img alt="<?php echo esc_attr($link_title) ?>" title="<?php echo esc_attr($link_title) ?>" class="img" src="<?php echo esc_url($bg_url); ?>">
				<?php
				}
				else{
					echo wp_get_attachment_image($bg_id, 'full', 0, array('class'=>'img'));
				}
				?>
				</div>
				
				<header>
					<?php if( $show_discount):?>
					<div class="discount"><?php echo $discount ?></div>
					<?php endif; ?>
					
					<?php if($position_description == "top-title"): ?>
					<div class="description desc-top"><?php echo esc_attr($description) ?></div>
					<?php endif; ?>
					
					<h3>
					<?php echo esc_attr($heading_title) ?>
					<?php if ($title_many_colors){ ?>
					<span><?php echo esc_attr($heading_title_2) ?></span>
					<?php } ?>
					</h3>
					
					<?php if($position_description == "bottom-title"): ?>
					<div class="description desc-bottom"><?php echo esc_attr($description) ?></div>
					<?php endif; ?>
					
					<?php if( $show_price):?>
					<div class="price"><?php echo $price ?></div>
					<?php endif; ?>
					
					<?php if( $show_button):?>
					<a title="<?php echo esc_attr($link_title) ?>" target="<?php echo esc_attr($target); ?>" href="<?php echo esc_url($link) ?>" class="button-banner"><?php echo esc_attr($button_text) ?></a>
					<?php endif; ?>
					
				</header>
			</div>
		</div>
	</div>
	<?php
	
	return ob_get_clean();
}
add_shortcode('ts_banner', 'ts_banner_shortcode');

/*** Shortcode Single Image ***/
if( !function_exists('ts_single_image_shortcode') ){
	function ts_single_image_shortcode( $atts ){
		extract(shortcode_atts(array(
							'img_id'			=> ''
							,'img_url'			=> ''
							,'img_size'			=> ''
							,'style_effect'		=> 'eff-widespread-corner-left-right'
							,'link' 			=> ''
							,'link_title' 		=> ''						
							,'target' 			=> '_blank'
						), $atts ));
						
		if( $img_size == '' ){
			$img_size = 'full';
		}
		
		ob_start();
		?>
		<div class="ts-single-image ts-effect-image <?php echo esc_attr($style_effect) ?>">
			<a title="<?php echo esc_attr($link_title) ?>" target="<?php echo esc_attr($target); ?>" class="image-link" href="<?php echo esc_url($link) ?>" >
				<?php 
				if( $img_url != '' ){
				?>
					<img alt="<?php echo esc_attr($link_title) ?>" title="<?php echo esc_attr($link_title) ?>" class="img" src="<?php echo esc_url($img_url); ?>">
				<?php
				}
				else{
					echo wp_get_attachment_image($img_id, $img_size, 0, array('class'=>'img'));
				}
				?> 
				<span class="overlay"></span>
			</a>
		</div>
		<?php
		
		return ob_get_clean();
	}
}
add_shortcode('ts_single_image', 'ts_single_image_shortcode');


/*** Shortcode Logo ***/
if( !function_exists('ts_logos_slider_shortcode') ){
	function ts_logos_slider_shortcode( $atts, $content = null ){
		extract(shortcode_atts(array(
					'categories' 		=> ''
					,'style_logo'		=> 'style-default'
					,'content_border'	=> 0
					,'per_page' 		=> 7
					,'rows' 			=> 1
					,'active_link'		=> 1
					,'show_nav' 		=> 1
					,'auto_play' 		=> 1
					,'margin_image'		=> 30
					,'extra_class'		=> ''
					), $atts));
		if( !class_exists('TS_Logos') )
			return;
		
		$args = array(
			'post_type'				=> 'ts_logo'
			,'post_status'			=> 'publish'
			,'ignore_sticky_posts'	=> 1
			,'posts_per_page' 		=> $per_page
			,'orderby' 				=> 'date'
			,'order' 				=> 'desc'
		);
		
		$categories = str_replace(' ', '', $categories);
		if( strlen($categories) > 0 ){
			$categories = explode(',', $categories);
		}
		if( is_array($categories) && count($categories) > 0 ){
			$field_name = is_numeric($categories[0])?'term_id':'slug';
			$args['tax_query'] = array(
									array(
										'taxonomy' => 'ts_logo_cat'
										,'terms' => $categories
										,'field' => $field_name
										,'include_children' => false
									)
								);
		}
		
		$logos = new WP_Query($args);
		
		global $post;
		ob_start();
		if( $logos->have_posts() ):
			$count_posts = $logos->post_count;
			
			$classes = array();
			$classes[] = $style_logo;
			if( $count_posts > 1 && $count_posts > $rows ){
				$classes[] = 'loading';
			}
			if( $show_nav ){
				$classes[] = 'show-nav';
			}
			else{
				$classes[] = 'no-nav';
			}
			if($content_border){
				$classes[] = 'content-border';
			}
			$classes[] = $extra_class;
			
			$settings_option = get_option('ts_logo_setting', array());
			$data_break_point = isset($settings_option['responsive']['break_point'])?$settings_option['responsive']['break_point']:array();
			$data_item = isset($settings_option['responsive']['item'])?$settings_option['responsive']['item']:array();
			
			$data_attr = array();
			$data_attr[] = 'data-margin="'.absint($margin_image).'"';
			$data_attr[] = 'data-nav="'.$show_nav.'"';
			$data_attr[] = 'data-auto_play="'.$auto_play.'"';
			$data_attr[] = 'data-break_point="'.htmlentities(json_encode( $data_break_point )).'"';
			$data_attr[] = 'data-item="'.htmlentities(json_encode( $data_item )).'"';
			?>
			<div class="ts-logo-slider-wrapper ts-slider ts-shortcode <?php echo esc_attr( implode(' ', $classes) ); ?>" <?php echo implode(' ', $data_attr); ?>>
				<div class="content-wrapper">
					<div class="logos">
					<?php 
					$count = 0;
					while( $logos->have_posts() ): $logos->the_post(); 
						if( $rows > 1 && $count % $rows == 0 ){
							echo '<div class="logo-group">';
						}
					?>
						<div class="item">
							<?php if( $active_link ):
							$logo_url = get_post_meta($post->ID, 'ts_logo_url', true);
							$logo_target = get_post_meta($post->ID, 'ts_logo_target', true);
							?>
								<a href="<?php echo esc_url($logo_url); ?>" target="<?php echo esc_attr($logo_target); ?>">
							<?php endif; ?>
								<?php 
								if( has_post_thumbnail() ){
									the_post_thumbnail('ts_logo_thumb');
								}
								?>
							<?php if( $active_link ): ?>
								</a>
							<?php endif; ?>
						</div>
					<?php 
						if( $rows > 1 && ($count % $rows == $rows - 1 || $count == $count_posts - 1) ){
							echo '</div>';
						}
						$count++;
					endwhile; 
					?>
					</div>
				</div>
			</div>
		<?php
		endif;
		wp_reset_postdata();
		return ob_get_clean();
	}
}
add_shortcode('ts_logos_slider', 'ts_logos_slider_shortcode');

/************************************
*** Element Shortcodes
*************************************/

/*** Shortcode Button ***/
function ts_button_shortcode($atts, $content=null){
	extract(shortcode_atts(array(	
					'link'					=> '#'
					,'bg_color'				=> '#f5a72c'
					,'bg_color_hover'		=> '#3f3f3f'
					,'border_color'			=> '#e8e8e8'
					,'border_color_hover'	=> '#f5a72c'
					,'border_width'			=> '0'
					,'text_color'			=> '#ffffff'
					,'text_color_hover'		=> '#ffffff'
					,'font_icon'			=> ''
					,'target'				=> '_self' /* _self, _blank */
					,'size'					=> 'small' /* small, medium, large, x-large */
					,'popup'				=> 0
					,'popup_content'		=> ''
					), $atts));
	static $ts_button_counter = 1;		
	$style = '';
	$style_hover = '';
	$selector = '.ts-button-wrapper a.ts-button-'.$ts_button_counter;
	
	if( $bg_color ){
		$style .= 'background:'.$bg_color.';';
	}
	if( $border_color ){
		$style .= 'border-color:'.$border_color.';';
	}
	if( $border_width != '' ){
		$style .= 'border-width:'.absint($border_width).'px ;';
	}
	if( $text_color ){
		$style .= 'color:'.$text_color.';';
	}
		
	if( $bg_color_hover ){
		$style_hover .= 'background:'.$bg_color_hover.';';
	}
	if( $border_color_hover ){
		$style_hover .= 'border-color:'.$border_color_hover.';';
	}
	if( $text_color_hover ){
		$style_hover .= 'color:'.$text_color_hover.';';
	}
	
	$html = '<div class="ts-button-wrapper">';
	$html .= '<div class="ts-shortcode-custom-style hidden">';
	$html .= $selector.'{';
	$html .= $style;
	$html .= '}';
	
	$html .= $selector.':hover{';
	$html .= $style_hover;
	$html .= '}';
	$html .= '</div>';
	
	if( $font_icon ){
		$font_icon = 'fa '.$font_icon;
	}
	
	$is_popup = ($popup && !empty($popup_content))?true:false;
	
	$extra_class = '';
	
	if( $is_popup ){
		$extra_class = ' ts-button-popup ';
		$link = '#ts-button-popup-'.$ts_button_counter;
	}
	else{
		$link = esc_url($link);
	}
	
	$html .= '<a href="'.$link.'" target="'.$target.'" class="ts-button ts-button-'.$ts_button_counter.' '.$size.' '.$font_icon.$extra_class.' ">'.do_shortcode($content).'</a>';
	$html .= '</div>';
	
	if( $is_popup ){
		$html .= '<div id="ts-button-popup-'.$ts_button_counter.'" style="display: none">';
			$html .= do_shortcode(stripslashes(urldecode(base64_decode($popup_content))));
		$html .= '</div>';
	}
	
	$ts_button_counter++;
	return $html;
}
add_shortcode('ts_button', 'ts_button_shortcode');

if( !function_exists('ts_feedburner_subscription_shortcode') ){
	function ts_feedburner_subscription_shortcode( $atts ){
		extract(shortcode_atts(array(	
					'title'					=> 'Newsletter'
					,'intro_text'			=> ''
					,'button_text'			=> 'Subscribe'
					,'placeholder_text'		=> 'Enter your email address'
					,'feedburner_id'		=> ''
					,'style'				=> 'style-fullwidth'
					), $atts));
					
		if( !class_exists('Sanzo_Feedburner_Subscription_Widget') ){
			return;
		}
		
		$instance = compact('title', 'intro_text', 'button_text', 'placeholder_text', 'feedburner_id');
		
		ob_start();
		
		echo '<div class="ts-feedburner-subscription-shortcode '.$style.'">';
		
		the_widget('Sanzo_Feedburner_Subscription_Widget', $instance);
		
		echo '</div>';
		
		return ob_get_clean();
	}
}
add_shortcode('ts_feedburner_subscription', 'ts_feedburner_subscription_shortcode');

/*** Shortcode Dropcap ***/
function ts_dropcap_shortcode($atts, $content=null){
	extract(shortcode_atts(array(	
					'style'					=> '1'
					), $atts));
	return '<span class="ts-dropcap'.' style-'.$style.'">' .do_shortcode($content). '</span>';
}
add_shortcode('ts_dropcap', 'ts_dropcap_shortcode');

/*** Shortcode Quote ***/
function ts_quote_shortcode($atts, $content=null){
	return '<span class="ts-quote">'.do_shortcode($content).'</span>';
}
add_shortcode('ts_quote', 'ts_quote_shortcode');

/*** Shortcode Heading ***/
if( !function_exists('ts_heading_shortcode') ){
	function ts_heading_shortcode($atts, $content = null){
		extract(shortcode_atts(array(
			'size' 				=> '1'
			,'text' 			=> ''
			,'style' 			=> 'style-1'
		), $atts));
		return '<div class="ts-heading heading-'.$size.' '.$style.'"><h'.$size.'>'.do_shortcode($text).'</h'.$size.'>'.'</div>';
	}
}
add_shortcode('ts_heading', 'ts_heading_shortcode');

/*** Shortcode Blog ***/
if( !function_exists('ts_blogs_shortcode') ){
	function ts_blogs_shortcode( $atts, $content = null){
		extract(shortcode_atts(array(
					'title'				=> ''
					,'title_style'		=> ''
					,'layout'			=> 'grid'
					,'columns'			=> 1
					,'per_page'			=> 5
					,'categories'		=> ''
					,'orderby'			=> 'none'
					,'order'			=> 'DESC'
					,'show_title'		=> 1
					,'show_thumbnail'	=> 1
					,'show_author'		=> 0
					,'show_comment'		=> 1
					,'show_date'		=> 1
					,'show_excerpt'		=> 1
					,'show_readmore'	=> 0
					,'excerpt_words'	=> 16
					,'show_load_more'	=> 0
					,'load_more_text'	=> 'Show more'
					,'show_nav'			=> 1
					,'auto_play'		=> 1
					,'margin'			=> 30
				), $atts));
		
		if( !is_numeric($excerpt_words) ){
			$excerpt_words = 20;
		}
		
		$is_slider = 0;
		$is_masonry = 0;
		if( $layout == 'slider' ){
			$is_slider = 1;
		}
		if( $layout == 'masonry' ){
			$is_masonry = 1;
		}
		
		$columns = absint($columns);
		if( !in_array($columns, array(1, 2, 3, 4, 6)) ){
			$columns = 4;
		}
		
		$args = array(
			'post_type' 			=> 'post'
			,'post_status' 			=> 'publish'
			,'ignore_sticky_posts' 	=> 1
			,'posts_per_page'		=> $per_page
			,'orderby'				=> $orderby
			,'order'				=> $order
		);
		
		$categories = str_replace(' ', '', $categories);
		if( strlen($categories) > 0 ){
			$ar_categories = explode(',', $categories);
			if( is_array($ar_categories) && count($ar_categories) > 0 ){
				$field_name = is_numeric($ar_categories[0])?'term_id':'slug';
				$args['tax_query'] = array(
										array(
											'taxonomy' => 'category'
											,'terms' => $ar_categories
											,'field' => $field_name
											,'include_children' => false
										)
									);
			}
		}
		
		global $post;
		$posts = new WP_Query($args);
		
		ob_start();
		if( $posts->have_posts() ):
			if( $posts->post_count <= 1 ){
				$is_slider = 0;
			}
			if( $is_slider ){
				$show_load_more = 0;
			}
			
			$classes = array();
			$classes[] = 'ts-blogs-wrapper ts-shortcode ts-blogs';
			if( $is_slider ){
				$classes[] = 'ts-slider loading';
			}
			if( $is_masonry ){
				$classes[] = 'ts-masonry';
			}
			
			$atts = compact('title', 'columns', 'categories', 'per_page', 'orderby', 'order'
							,'show_title', 'show_thumbnail', 'show_author'
							,'show_date', 'show_comment', 'show_excerpt', 'show_readmore', 'excerpt_words'
							,'is_slider', 'show_nav', 'auto_play', 'margin', 'is_masonry', 'show_load_more');
			?>
			<div class="<?php echo esc_attr($title_style);?> <?php echo esc_attr(implode(' ', $classes)); ?>" data-atts="<?php echo htmlentities(json_encode($atts)); ?>">
				<?php if( strlen($title) > 0 ): ?>
				<header class="shortcode-heading-wrapper">
					<h3 class="heading-title"><?php echo esc_html($title); ?></h3>
				</header>
				<?php endif; ?>
				<div class="content-wrapper">
					<div class="blogs">
						<?php ts_get_blog_items_content_shortcode($atts, $posts); ?>
					</div>
					<?php if( $show_load_more ): ?>
					<div class="load-more-wrapper">
						<a href="#" class="load-more button" data-paged="2"><?php echo esc_html($load_more_text) ?></a>
					</div>
					<?php endif; ?>
				</div>
			</div>
		<?php
		endif;
		wp_reset_postdata();
		return ob_get_clean();
	}	
}
add_shortcode('ts_blogs', 'ts_blogs_shortcode');

add_action('wp_ajax_ts_blogs_load_items', 'ts_get_blog_items_content_shortcode');
add_action('wp_ajax_nopriv_ts_blogs_load_items', 'ts_get_blog_items_content_shortcode');
if( !function_exists('ts_get_blog_items_content_shortcode') ){
	function ts_get_blog_items_content_shortcode($atts, $posts = null){
		
		global $post;
		
		if( defined( 'DOING_AJAX' ) && DOING_AJAX ){
			if( !isset($_POST['atts']) ){
				die('0');
			}
			$atts = $_POST['atts'];
			$paged = isset($_POST['paged'])?absint($_POST['paged']):1;
			
			extract($atts);
			
			$args = array(
				'post_type' 			=> 'post'
				,'post_status' 			=> 'publish'
				,'ignore_sticky_posts' 	=> 1
				,'posts_per_page'		=> $per_page
				,'orderby'				=> $orderby
				,'order'				=> $order
				,'paged'				=> $paged
			);
			
			$categories = str_replace(' ', '', $categories);
			if( strlen($categories) > 0 ){
				$categories = explode(',', $categories);
			}
			if( is_array($categories) && count($categories) > 0 ){
				$field_name = is_numeric($categories[0])?'term_id':'slug';
				$args['tax_query'] = array(
										array(
											'taxonomy' => 'category'
											,'terms' => $categories
											,'field' => $field_name
											,'include_children' => false
										)
									);
			}
			
			$posts = new WP_Query($args);
			ob_start();
		}
		
		extract($atts);
		
		if( $posts->have_posts() ):
			$item_class = '';
			if( !$is_slider ){
				$item_class = 24/(int)$columns;
				$item_class = 'ts-col-'.$item_class;
			}
			$key = -1;
			while( $posts->have_posts() ): $posts->the_post();
			
				$post_format = get_post_format(); /* Video, Audio, Gallery, Quote */
				if( $is_slider && $post_format == 'gallery' ){ /* Remove Slider in Slider */
					$post_format = false;
				}
				
				$key++;
				$item_extra_class = ($key % $columns == 0)?'first':(($key % $columns == $columns - 1)?'last':''); ?>
				<article class="item <?php echo esc_attr($post_format); ?> <?php echo esc_attr($item_class.' '.$item_extra_class) ?>">
					<div class="thumbnail-content">
						<?php 
						if( $show_thumbnail ){
							if( $post_format == 'gallery' || $post_format === false || $post_format == 'standard' ){
							?>
								<a class="thumbnail <?php echo esc_attr($post_format); ?> <?php echo ($post_format == 'gallery')?'loading':''; ?>" href="<?php echo ($post_format == 'gallery')?'javascript: void(0)':get_permalink() ?>">
									<figure>
									<?php 
									
									if( $post_format == 'gallery' ){
										$gallery = get_post_meta($post->ID, 'ts_gallery', true);
										if( $gallery ){
											$gallery_ids = explode(',', $gallery);
										}
										else{
											$gallery_ids = array();
										}
										if( is_array($gallery_ids) && has_post_thumbnail() ){
											array_unshift($gallery_ids, get_post_thumbnail_id());
										}
										foreach( $gallery_ids as $gallery_id ){
											echo wp_get_attachment_image( $gallery_id, 'sanzo_blog_shortcode_thumb' );
										}
									}
									
									if( $post_format === false || $post_format == 'standard' ){
										if( has_post_thumbnail() ){
											the_post_thumbnail('sanzo_blog_shortcode_thumb'); 
										}
									}
									
									?>
									</figure>
									<div class="effect-thumbnail"></div>
								</a>
							<?php 
							}
							
							if( $post_format == 'video' ){
								$video_url = get_post_meta($post->ID, 'ts_video_url', true);
								echo do_shortcode('[ts_video src="'.$video_url.'"]');
							}
							
							if( $post_format == 'audio' ){
								$audio_url = get_post_meta($post->ID, 'ts_audio_url', true);
								if( strlen($audio_url) > 4 ){
									$file_format = substr($audio_url, -3, 3);
									if( in_array($file_format, array('mp3', 'ogg', 'wav')) ){
										echo do_shortcode('[audio '.$file_format.'="'.$audio_url.'"]');
									}
									else{
										echo do_shortcode('[ts_soundcloud url="'.$audio_url.'" width="100%" height="122"]');
									}
								}
							}
						}
						?>
					</div>
					
					<?php if( $post_format != 'quote' ): ?>
						
					<div class="entry-content">
						<div class="entry-meta <?php echo ($show_date && $show_comment && $show_author)?"show-all-meta":"" ?>">
							<!-- Blog Date Time -->
							<?php if( $show_date ): ?>
							<span class="date-time">
								<?php echo get_the_time(get_option('date_format')); ?>
							</span>
							<?php endif; ?>
							
							<!-- Blog Comment -->
							<?php if( $show_comment ): ?>
							<span class="comment-count">
								<i class="fa fa-comments-o"></i>
								<span class="number">
									<?php 
									$comments_count = wp_count_comments($post->ID); 
									$comment_number = $comments_count->approved;
									echo sprintf( _n('%s comment', '%s comments', $comment_number, 'themesky'), $comment_number > 0?zeroise($comment_number, 2):$comment_number );
									?>
								</span>
							</span>
							<?php endif; ?>
							
							<!-- Blog Author -->
							<?php if( $show_author ): ?>
								<span class="vcard author">
									<?php esc_html_e('Post by ','themesky'); the_author_posts_link(); ?>
								</span>
							<?php endif; ?>
						</div>
						
						<!-- Blog Title -->
						<?php if( $show_title ): ?>
						<header><h3 class="heading-title blog-title entry-title"><a class="post-title heading-title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3></header>
						<?php endif; ?>
						
						<div class="entry-summary">
							<?php if( $show_excerpt && function_exists('sanzo_the_excerpt_max_words') ): ?>
							<div class="short-content"><?php sanzo_the_excerpt_max_words($excerpt_words, '', true, '', true); ?></div>
							<?php endif; ?>
							
							<?php if( $show_readmore ): ?>
							<a href="<?php the_permalink(); ?>" class="button button-readmore"><?php esc_html_e('Read More','themesky') ?></a>
							<?php endif; ?>
						</div>
					</div>
						
					<?php else: /* Post format is quote */ ?>
						<blockquote>
							<?php 
							$quote_content = get_the_excerpt();
							if( !$quote_content ){
								$quote_content = get_the_content();
							}
							echo do_shortcode($quote_content);
							?>
						</blockquote>
						<div class="blockquote-meta">
							<?php if( $show_date ): ?>
							<span class="date-time">
								<?php echo get_the_time(get_option('date_format')); ?>
							</span>
							<?php endif; ?>
							
							<?php if( $show_author ): ?>
								<span class="vcard author"><?php esc_html_e('Post by ','themesky'); the_author_posts_link(); ?></span>
							<?php endif; ?>
						</div>
					<?php endif; ?>
					
				</article>
			<?php 
			endwhile;
		endif;
		
		wp_reset_postdata();
		
		if( defined( 'DOING_AJAX' ) && DOING_AJAX ){
			die(ob_get_clean());
		}
		
	}
}

/*** Shortcode Blog List ***/
if( !function_exists('ts_blogs_split_shortcode') ){
	function ts_blogs_split_shortcode( $atts, $content = null){
		extract(shortcode_atts(array(
					'title'				=> ''
					,'title_style'		=> ''
					,'categories'		=> ''
					,'orderby'			=> 'none'
					,'order'			=> 'DESC'
					,'show_title'		=> 1
					,'show_thumbnail'	=> 1
					,'show_author'		=> 0
					,'show_comment'		=> 1
					,'show_date'		=> 1
					,'show_excerpt'		=> 1
					,'show_readmore'	=> 0
					,'excerpt_words'	=> 16
				), $atts));
		
		if( !is_numeric($excerpt_words) ){
			$excerpt_words = 16;
		}
		
		$args = array(
			'post_type' 			=> 'post'
			,'post_status' 			=> 'publish'
			,'ignore_sticky_posts' 	=> 1
			,'posts_per_page'		=> 3
			,'orderby'				=> $orderby
			,'order'				=> $order
		);
		
		$categories = str_replace(' ', '', $categories);
		if( strlen($categories) > 0 ){
			$categories = explode(',', $categories);
			$field_name = is_numeric($categories[0])?'term_id':'slug';
			$args['tax_query'] = array(
									array(
										'taxonomy' => 'category'
										,'terms' => $categories
										,'field' => $field_name
										,'include_children' => false
									)
								);
			
		}
		
		global $post;
		$posts = new WP_Query($args);
		
		ob_start();
		if( $posts->have_posts() ):
			?>
			<div class="<?php echo esc_attr($title_style);?> ts-blogs-split-wrapper ts-shortcode ts-blogs">
				<?php if( strlen($title) > 0 ): ?>
				<header class="shortcode-heading-wrapper">
					<h3 class="heading-title"><?php echo esc_html($title); ?></h3>
				</header>
				<?php endif; ?>
				<div class="content-wrapper">
					<div class="blogs">
					<?php 
					$count = 0;
					while( $posts->have_posts() ): 
						$posts->the_post(); 
						$count++;
						if( $count == 1 ){
							echo '<div class="item-large">';
						}
						if( $count == 2 ){
							echo '<div class="item-small">';
						}
					?>
						<article class="item">
							<?php if( $show_thumbnail ): ?>
							<a href="<?php the_permalink(); ?>" class="thumbnail">
								<figure>
								<?php 
								if( has_post_thumbnail() ){
									the_post_thumbnail('sanzo_blog_shortcode_thumb'); 
								}
								?>
								</figure>
								<div class="effect-thumbnail"></div>
							</a>
							<?php endif; ?>
							<div class="entry-content">
								<?php if( $show_title && $count != 1 ): ?>
								<header>
									<h3 class="heading-title blog-title entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
								</header>
								<?php endif; ?>
								
								<div class="entry-meta <?php echo ($show_date && $show_comment && $show_author)?'show-all-meta':'' ?>">
									<?php if( $show_date ): ?>
									<span class="date-time"><?php echo get_the_time(get_option('date_format')) ?></span>
									<?php endif; ?>
									
									<?php if( $show_comment ): ?>
									<span class="comment-count">
										<i class="fa fa-comments-o"></i>
										<span class="number">
											<?php 
											$comments_count = wp_count_comments($post->ID); 
											$comment_number = $comments_count->approved;
											echo sprintf( _n('%s comment', '%s comments', $comment_number, 'themesky'), $comment_number > 0?zeroise($comment_number, 2):$comment_number );
											?>
										</span>
									</span>
									<?php endif; ?>
									
									<?php if( $show_author ): ?>
									<span class="vcard author">
										<?php esc_html_e('Post by ','themesky'); the_author_posts_link(); ?>
									</span>
									<?php endif; ?>
								</div>
								
								<?php if( $show_title && $count == 1 ): ?>
								<header>
									<h3 class="heading-title blog-title entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
								</header>
								<?php endif; ?>
								
								<?php if( $show_excerpt && function_exists('sanzo_the_excerpt_max_words') && $count != 1 ): ?>
								<div class="short-content"><?php sanzo_the_excerpt_max_words($excerpt_words, '', true, '', true); ?></div>
								<?php endif; ?>
								
								<?php if( $show_readmore && $count != 1 ): ?>
								<a href="<?php the_permalink(); ?>" class="button button-readmore"><?php esc_html_e('Read More','themesky') ?></a>
								<?php endif; ?>
							</div>
						</article>
						<?php 
						if( $count == 1 || $count == 3 || $count == $posts->post_count ){
							echo '</div>';
						}
						endwhile;
						?>
					</div>
				</div>
			</div>
		<?php
		endif;
		wp_reset_postdata();
		return ob_get_clean();
	}	
}
add_shortcode('ts_blogs_split', 'ts_blogs_split_shortcode');

/* TS Google Map shortcode */
if( !function_exists('ts_google_map_shortcode') ){
	function ts_google_map_shortcode($atts, $content = null){
		extract(shortcode_atts(array(
						'address'			=> ''
						,'height'			=> 360
						,'zoom'				=> 12
						,'map_type'			=> 'ROADMAP'
						,'title'			=> ''
					), $atts));
					
		ob_start();	
		wp_enqueue_script('gmap-api');
		?>
		<div class="google-map-container" style="height:<?php echo esc_attr($height); ?>px" 
			data-address="<?php echo esc_attr($address) ?>" data-zoom="<?php echo esc_attr($zoom) ?>" data-map_type="<?php echo esc_attr($map_type) ?>" data-title="<?php echo esc_attr($title) ?>">
			<div style="height:<?php echo esc_attr($height); ?>px"></div>
		</div>
		<?php
		return ob_get_clean();
	}
}
add_shortcode('ts_google_map', 'ts_google_map_shortcode');

/* Shortcode Video - Support Youtube and Vimeo video */
if( !function_exists('ts_video_shortcode') ){
	function ts_video_shortcode($atts){
		extract( shortcode_atts(array(
				'src' 		=> '',
				'height' 	=> '450',
				'width' 	=> '800'
			), $atts
		));
	if( $src == '' ){
		return;
	}
	
	$extra_class = '';
	if( !isset($atts['height']) || !isset($atts['width']) ){
		$extra_class = 'auto-size';
	}
	
	$src = ts_parse_video_link($src);
    ob_start();
	?>
		<div class="ts-video <?php echo esc_attr($extra_class); ?>" style="width:<?php echo esc_attr($width) ?>px; height:<?php echo esc_attr($height) ?>px;">
			<iframe width="<?php echo esc_attr($width) ?>" height="<?php echo esc_attr($height) ?>" src="<?php echo esc_url($src); ?>" allowfullscreen></iframe>
		</div>
	<?php
	return ob_get_clean();
	}
}
add_shortcode('ts_video', 'ts_video_shortcode');

if( !function_exists('ts_parse_video_link') ){
	function ts_parse_video_link( $video_url ){
		if( strstr($video_url, 'youtube.com') || strstr($video_url, 'youtu.be') ){
			preg_match('%(?:youtube\.com/(?:user/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $video_url, $match);
			if( count($match) >= 2 ){
				return '//www.youtube.com/embed/' . $match[1];
			}
		}
		elseif( strstr($video_url, 'vimeo.com') ){
			preg_match('~^http://(?:www\.)?vimeo\.com/(?:clip:)?(\d+)~', $video_url, $match);
			if( count($match) >= 2 ){
				return '//player.vimeo.com/video/' . $match[1];
			}
			else{
				$video_id = explode('/', $video_url);
				if( is_array($video_id) && !empty($video_id) ){
					$video_id = $video_id[count($video_id) - 1];
					return '//player.vimeo.com/video/' . $video_id;
				}
			}
		}
		return $video_url;
	}
}

/* Shortcode SoundCloud */
if( !function_exists('ts_soundcloud_shortocde') ){
	function ts_soundcloud_shortocde( $atts, $content ){
		extract(shortcode_atts(array(
			'params'		=> "color=ff5500&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false"
			,'url'			=> ''
			,'width'		=> '100%'
			,'height'		=> '166'
			,'iframe'		=> 1
		),$atts));
		
		$atts = compact( 'params', 'url', 'width', 'height', 'iframe' );
		
		if( $iframe ){
			return ts_soundcloud_iframe_widget( $atts );
		}
		else{ 
			return ts_soundcloud_flash_widget( $atts );
		}
	}
}
add_shortcode('ts_soundcloud','ts_soundcloud_shortocde');


function ts_soundcloud_iframe_widget($options) {
	$url = 'https://w.soundcloud.com/player/?url=' . $options['url'] . '&' . $options['params'];
	$unique_class = 'ts-soundcloud-'.rand();
	$style = '.'.$unique_class.' iframe{width: '.$options['width'].'; height:'.$options['height'].'px;}';
	$style = '<style type="text/css" scoped>'.$style.'</style>';
	return '<div class="ts-soundcloud '.$unique_class.'">'.$style.'<iframe src="'.esc_url( $url ).'"></iframe></div>';
}

function ts_soundcloud_flash_widget( $options ){
	$url = 'https://player.soundcloud.com/player.swf?url=' . $options['url'] . '&' . $options['params'];
	
	return preg_replace('/\s\s+/', '', sprintf('<div class="ts-soundcloud"><object width="%s" height="%s">
							<param name="movie" value="%s"></param>
							<param name="allowscriptaccess" value="always"></param>
							<embed width="%s" height="%s" src="%s" allowscriptaccess="always" type="application/x-shockwave-flash"></embed>
						  </object></div>', $options['width'], $options['height'], esc_url( $url ), $options['width'], $options['height'], esc_url( $url )));
}

/* Twitter Slider Shortcode */
if( !function_exists('ts_twitter_slider_shortcode') ){
	function ts_twitter_slider_shortcode($atts){
		extract(shortcode_atts(array(
			'username'				=> ''
			,'limit'				=> 4
			,'exclude_replies'		=> 'false'
			,'bg_color'				=> '#ffffff'
			,'text_color_style'		=> 'text-default'
			,'has_padding'			=> 0
			,'show_nav'				=> 0
			,'show_dots'			=> 1
			,'auto_play'			=> 1
			,'cache_time'			=> 12
			,'consumer_key'			=> ''
			,'consumer_secret'		=> ''
			,'access_token'			=> ''
			,'access_token_secret'	=> ''
		),$atts));
		
		if( $username == '' || !class_exists('TwitterOAuth') ){
			return;
		}
		
		if( $show_dots ){
			$show_nav = 0;
		}
		
		if( $consumer_key == '' || $consumer_secret == '' || $access_token == '' || $access_token_secret == '' ){
			$consumer_key 			= "p8INAW7UwqwXj7tfrtX2bxtll";
			$consumer_secret 		= "G2spyyyDdwKsQb3zQiGDgj52V5CfNSYE1iBiUHQC1A70LT6hBo";
			$access_token 			= "746695847090413569-sO05A5zdwngvgTCFndmQdIVFbe0Yxcd";
			$access_token_secret	= "GZgIEchIJ0eTuFVLGmsODNQAdzfF6fcms9CwjoiBhCbUl";
		}
		
		unset($atts['consumer_key']);
		unset($atts['consumer_secret']);
		unset($atts['access_token']);
		unset($atts['access_token_secret']);
		$atts['text_color_style'] = ($text_color_style == 'text-default')? 1: 2;
		$atts['exclude_replies'] = ($exclude_replies == 'false')? 1: 2;
		
		$transient_key = 'twitter_'.implode('', $atts);
		$cache = get_transient($transient_key);
		
		if( $cache !== false ){
			return $cache;
		}
		else{
			$connection = new TwitterOAuth($consumer_key, $consumer_secret, $access_token, $access_token_secret);
			$tweets = $connection->get('https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name='.$username.'&count='.$limit.'&exclude_replies='.$exclude_replies);
			if( !isset($tweets->errors) && is_array($tweets) ){
				ob_start();
				$extra_class = $text_color_style;
				if( $show_nav || $show_dots ){
					$extra_class .= ' show-navi';
				}
				echo '<div style="background-color:'.$bg_color.'" ';
				echo 'class="ts-twitter-slider ts-slider loading '.$extra_class ;
				echo ($has_padding)?' has-padding':'';
				echo ($show_nav)?' show-navi':'';
				echo ($show_dots)?' show-dots':'';
				echo '" data-nav="'.$show_nav.'" data-dots="'.$show_dots.'" data-autoplay="'.$auto_play.'"';
				echo '>';
				foreach( $tweets as $tweet ){
					$tweet_link = 'http://twitter.com/'.$tweet->user->screen_name.'/statuses/'.$tweet->id;
					$user_link = 'http://twitter.com/'.$tweet->user->screen_name;
					?>
					<div class="item">
						<div class="icon">
							<i class="fa fa-twitter"></i>
						</div>
						<div class="twitter-content">
							<h4 class="name">
								<a href="<?php echo esc_url($user_link); ?>" target="_blank"><?php echo '@'.esc_html($tweet->user->name); ?></a>
							</h4>
							<div class="date-time">
							<?php 
								echo ts_relative_time($tweet->created_at); 
								esc_html_e(' on ', 'themesky');
							?>
								<a href="<?php echo esc_url($tweet_link); ?>" target="_blank"><?php esc_html_e('Twitter.com', 'themesky') ?></a>
							</div>
							<div class="content">
								<?php echo esc_html($tweet->text); ?>
							</div>
						</div>
					</div>
					<?php
				}
				echo '</div>';
				
				$output = ob_get_clean();
				set_transient($transient_key, $output, $cache_time * HOUR_IN_SECONDS);
				return $output;
			}
		}
		
	}
}
add_shortcode('ts_twitter_slider', 'ts_twitter_slider_shortcode');

if( !function_exists('ts_relative_time') ){
	function ts_relative_time( $time = '' ){
		if( empty($time) ){
			return '';
		}
		
		$second = 1;
		$minute = 60 * $second;
		$hour = 60 * $minute;
		$day = 24 * $hour;
		$month = 30 * $day;

		$delta = strtotime('+0 hours') - strtotime($time);
		if ($delta < 2 * $minute) {
			return esc_html__('1 min ago', 'themesky');
		}
		if ($delta < 45 * $minute) {
			return floor($delta / $minute) . esc_html__(' min ago', 'themesky');
		}
		if ($delta < 90 * $minute) {
			return esc_html__('1 hour ago', 'themesky');
		}
		if ($delta < 24 * $hour) {
			return floor($delta / $hour) . esc_html__(' hours ago', 'themesky');
		}
		if ($delta < 48 * $hour) {
			return esc_html__('yesterday', 'themesky');
		}
		if ($delta < 30 * $day) {
			return floor($delta / $day) . esc_html__(' days ago', 'themesky');
		}
		if ($delta < 12 * $month) {
			$months = floor($delta / $day / 30);
			return $months <= 1 ? esc_html__('1 month ago', 'themesky') : $months . esc_html__(' months ago', 'themesky');
		} else {
			$years = floor($delta / $day / 365);
			return $years <= 1 ? esc_html__('1 year ago', 'themesky') : $years . esc_html__(' years ago', 'themesky');
		}
	}
}

/* Milestone shortcode */
if( !function_exists('ts_milestone_shortcode') ){
	function ts_milestone_shortcode( $atts ){
		extract( shortcode_atts(array(
				'number'			=> 0
				,'subject'			=> ''
				,'text_color_style'	=> 'text-default'
			), $atts)
		);
		
		if( !is_numeric($number) ){
			$number = 0;
		}
		
		ob_start();
		?>
		<div class="ts-milestone <?php echo esc_attr($text_color_style) ?>" data-number="<?php echo esc_attr($number); ?>">
			<span class="number">
				<?php echo esc_html($number); ?>
			</span>
			<h3 class="subject">
				<?php echo esc_html($subject); ?>
			</h3>
		</div>
		<?php
		return ob_get_clean();
	}
}
add_shortcode('ts_milestone', 'ts_milestone_shortcode');

/* Countdown shortcode */
if( !function_exists('ts_countdown_shortcode') ){
	function ts_countdown_shortcode( $atts ){
		extract( shortcode_atts(array(
				'day'				=> ''
				,'month'			=> ''
				,'year'				=> ''
				,'label_bg_color'	=> '#f5a72c'
				,'label_text_color'	=> '#ffffff'
			), $atts)
		);
		
		if( empty($month) || empty($day) || empty($year) ){
			return;
		}
		
		if( !checkdate($month, $day, $year) ){
			return;
		}
		
		$date = mktime(0, 0, 0, $month, $day, $year);
		$current_time = current_time('timestamp');
		$delta = $date - $current_time;
		
		if( $delta <= 0 ){
			return;
		}
		
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
		
		static $ts_countdown_counter = 1;
		$unique_class = 'ts-countdown-'.$ts_countdown_counter;
		$selector = '.'.$unique_class;
		$ts_countdown_counter++;
		
		$style = '<style type="text/css" scoped>';
		$style .= $selector . ' .counter-wrapper div .ref-wrapper{background-color:'.$label_bg_color.'; color: '.$label_text_color.'}';
		$style .= '</style>';
		
		ob_start();
		?>
		<div class="ts-countdown <?php echo esc_attr($unique_class) ?>">
			<?php echo trim($style); ?>
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
		</div>
		<?php
		return ob_get_clean();
	}
}
add_shortcode('ts_countdown', 'ts_countdown_shortcode');

/* Image Gallery */
if( !function_exists('ts_image_gallery_shortcode') ){
	function ts_image_gallery_shortcode( $atts ){
		extract( shortcode_atts(array(
				'title' 				=> ''
				,'title_style'			=> ''
				,'images' 				=> ''
				,'layout' 				=> 'slider' /* slider, grid */
				,'columns' 				=> 1
				,'on_click'				=> 'none' /* none, prettyphoto, custom_link */
				,'custom_links' 		=> ''
				,'custom_link_target' 	=> '_self' /* _self, _blank */
				,'show_nav' 			=> 1
				,'auto_play' 			=> 1
				,'margin' 				=> 30
			), $atts)
		);
		
		$images = str_replace(' ', '', $images);
		if( $images == '' ){
			return;
		}
		$images = explode(',', $images);
		
		if( $custom_links != '' ){
			$custom_links = array_map('trim', explode(',', $custom_links));
		}
		else{
			$custom_links = array();
		}
		
		if( $on_click == 'prettyphoto' ){
			$rel_id = 'ts-gallery-'.mt_rand();
		}
		
		ob_start();
		$classes = array();
		$classes[] = 'ts-image-gallery-wrapper ts-shortcode';
		$classes[] = $layout == 'slider'?'ts-slider':$layout;
		
		$data_attr = array();
		if( $layout == 'slider' ){
			$data_attr[] = 'data-nav="'.$show_nav.'"';
			$data_attr[] = 'data-autoplay="'.$auto_play.'"';
			$data_attr[] = 'data-columns="'.absint($columns).'"';
			$data_attr[] = 'data-margin="'.absint($margin).'"';
		}
		?>
		<div class="<?php echo esc_attr($title_style);?> <?php echo esc_attr(implode(' ', $classes)); ?>" <?php echo implode(' ', $data_attr); ?>>
			<?php if( strlen($title) > 0 ): ?>
			<header class="shortcode-heading-wrapper">
				<h3 class="heading-title"><?php echo esc_html($title); ?></h3>
			</header>
			<?php endif; ?>
			<div class="images <?php echo ($layout == 'slider')?'loading':''; ?>">
				<?php 
				foreach( $images as $index => $image ): 
				$item_classes = array();
				if( $layout == 'grid' ){
					$columns = absint($columns);
					if( $columns > 1 ){
						$item_classes[] = 'ts-col-'.(24/$columns);
						if( $index % $columns == 0 ){
							$item_classes[] = 'first';
						}
						if( $index % $columns == $columns - 1 || $index == count($images) - 1 ){
							$item_classes[] = 'last';
						}
					}
				}
				?>
				<div class="item <?php echo implode(' ', $item_classes); ?>">
					<?php 
					if( $on_click == 'prettyphoto' || $on_click == 'custom_link' ){
						if( $on_click == 'prettyphoto' ){
							$href = wp_get_attachment_url($image);
							$data_rel = 'data-rel="prettyPhoto['.$rel_id.']"';
							$target = '';
						}
						else{
							$href = isset($custom_links[$index])?$custom_links[$index]:'#';
							$data_rel = '';
							$target = 'target="'.$custom_link_target.'"';
						}
						echo '<a class="'.$on_click.'" href="'.esc_url($href).'" '.$data_rel.' '.$target.'>';
					}
					echo wp_get_attachment_image($image, 'sanzo_blog_shortcode_thumb');
					if( $on_click == 'prettyphoto' || $on_click == 'custom_link' ){
						echo '</a>';
					}
					?>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}
add_shortcode('ts_image_gallery', 'ts_image_gallery_shortcode');
?>