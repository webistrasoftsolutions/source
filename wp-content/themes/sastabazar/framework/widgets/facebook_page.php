<?php
add_action('widgets_init', 'sanzo_facebook_page_load_widgets');

function sanzo_facebook_page_load_widgets()
{
	register_widget('Sanzo_Facebook_Page_Widget');
}

if( !class_exists('Sanzo_Facebook_Page_Widget') ){
	class Sanzo_Facebook_Page_Widget extends WP_Widget {

		function __construct() {
			$widgetOps = array('classname' => 'ts-facebook-page-widget', 'description' => esc_html__('Display the Facebook Page', 'sanzo'));
			parent::__construct('ts_facebook_page', esc_html__('TS - Facebook Page', 'sanzo'), $widgetOps);
		}

		function widget( $args, $instance ) {
			
			extract($args);
			$title = apply_filters('widget_title', $instance['title']);
			if( strlen(trim($instance['url'])) == 0 ){
				return;
			}
			
			$url 				= $instance['url'];
			$show_faces 		= empty($instance['show_faces'])?'false':'true';
			$show_posts 		= empty($instance['show_posts'])?'false':'true';
			$hide_cover_photo 	= empty($instance['show_cover_photo'])?'true':'false';
			$small_header 		= empty($instance['small_header'])?'false':'true';
			$box_height 		= (absint($instance['box_height']) == 0)?250:absint($instance['box_height']);
			
			echo $before_widget;
			
			if( $title ){
				echo $before_title . $title . $after_title; 
			}
			?>
			<div class="ts-facebook-page-wrapper">
				<div class="fb-page" data-href="<?php echo esc_url($url) ?>" data-small-header="<?php echo esc_attr($small_header) ?>" data-adapt-container-width="true" data-height="<?php echo esc_attr($box_height) ?>" 
					data-hide-cover="<?php echo esc_attr($hide_cover_photo) ?>" data-show-facepile="<?php echo esc_attr($show_faces) ?>" data-show-posts="<?php echo esc_attr($show_posts) ?>">
				</div>
			</div>
			
			<div id="fb-root"></div>
			<script>(function(d, s, id) {
			  var js, fjs = d.getElementsByTagName(s)[0];
			  if (d.getElementById(id)) return;
			  js = d.createElement(s); js.id = id;
			  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5";
			  fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));</script>
			<?php
			echo $after_widget;
		}

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;	
			$instance['title'] 					=  strip_tags($new_instance['title']);
			$instance['url'] 					=  $new_instance['url'];
			$instance['show_faces'] 			=  $new_instance['show_faces'];
			$instance['show_posts'] 			=  $new_instance['show_posts'];									
			$instance['show_cover_photo'] 		=  $new_instance['show_cover_photo'];																																	
			$instance['small_header'] 			=  $new_instance['small_header'];																																	
			$instance['box_height'] 			=  $new_instance['box_height'];															
			return $instance;
		}

		function form( $instance ) {
			$array_default = array(
							'title'					=> 'Find us on Facebook'
							,'url'					=> ''
							,'show_faces'			=> 1
							,'show_posts'			=> 0
							,'show_cover_photo'		=> 1
							,'small_header'			=> 0
							,'box_height'			=> 250
							);
							
			$instance = wp_parse_args( (array) $instance, $array_default );
		?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Enter your title', 'sanzo'); ?> </label>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('url'); ?>"><?php esc_html_e('Facebook page URL', 'sanzo'); ?> </label>
				<input class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" value="<?php echo esc_attr($instance['url']); ?>" />
			</p>
			<p>
				<input value="1" class="" type="checkbox" id="<?php echo $this->get_field_id('show_faces'); ?>" name="<?php echo $this->get_field_name('show_faces'); ?>" <?php checked($instance['show_faces'], 1); ?> />
				<label for="<?php echo $this->get_field_id('show_faces'); ?>"><?php esc_html_e('Show Faces', 'sanzo'); ?></label>
			</p>
			<p>
				<input value="1" class="" type="checkbox" id="<?php echo $this->get_field_id('show_posts'); ?>" name="<?php echo $this->get_field_name('show_posts'); ?>" <?php checked($instance['show_posts'], 1); ?> />
				<label for="<?php echo $this->get_field_id('show_posts'); ?>"><?php esc_html_e('Show Posts', 'sanzo'); ?></label>
			</p>
			<p>
				<input value="1" class="" type="checkbox" id="<?php echo $this->get_field_id('show_cover_photo'); ?>" name="<?php echo $this->get_field_name('show_cover_photo'); ?>" <?php checked($instance['show_cover_photo'], 1); ?> />
				<label for="<?php echo $this->get_field_id('show_cover_photo'); ?>"><?php esc_html_e('Show cover photo', 'sanzo'); ?></label>
			</p>
			<p>
				<input value="1" class="" type="checkbox" id="<?php echo $this->get_field_id('small_header'); ?>" name="<?php echo $this->get_field_name('small_header'); ?>" <?php checked($instance['small_header'], 1); ?> />
				<label for="<?php echo $this->get_field_id('small_header'); ?>"><?php esc_html_e('Small header', 'sanzo'); ?></label>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('box_height'); ?>"><?php esc_html_e('Box height', 'sanzo'); ?> </label>
				<input class="widefat" id="<?php echo $this->get_field_id('box_height'); ?>" name="<?php echo $this->get_field_name('box_height'); ?>" type="number" value="<?php echo esc_attr($instance['box_height']); ?>" />
			</p>
			
		<?php
		}
	}
}