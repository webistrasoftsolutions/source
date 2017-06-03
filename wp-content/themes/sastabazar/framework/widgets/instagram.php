<?php
add_action('widgets_init', 'sanzo_instagram_load_widgets');

function sanzo_instagram_load_widgets()
{
	register_widget('Sanzo_Instagram_Widget');
}

if(!class_exists('Sanzo_Instagram_Widget')){
	class Sanzo_Instagram_Widget extends WP_Widget {

		function __construct(){
			$widgetOps = array('classname' => 'ts-instagram-widget', 'description' => esc_html__('Display your photos from Instagram', 'sanzo'));
			parent::__construct('ts_instagram', esc_html__('TS - Instagram', 'sanzo'), $widgetOps);
		}

		function widget( $args, $instance ) {
			
			extract($args);
			$title = apply_filters('widget_title', $instance['title']);
			if( strlen(trim($instance['username'])) == 0 ){
				return;
			}
			
			$username 		= $instance['username'];
			$number 		= absint($instance['number']);
			$column 		= absint($instance['column']);
			$size 			= $instance['size'];
			$target 		= $instance['target'];
			$cache_time 	= absint($instance['cache_time']);
			
			if( $cache_time == 0 ){
				$cache_time = 12;
			}
			
			echo $before_widget;
			if( $title ){
				echo $before_title . $title . $after_title; 
			}
			unset($instance['title']);
			$transient_key = 'instagram_'.implode('', $instance);
			
			$cache = get_transient($transient_key);
			
			if( $cache !== false ){
				echo $cache;
			}
			else{
				$media_array = $this->scrape_instagram( $username, $number );
				if ( is_wp_error( $media_array ) ) {
					echo wp_kses_post( $media_array->get_error_message() );
				} else {
					ob_start();
					?>
					<div class="ts-instagram-wrapper columns-<?php echo esc_attr($column); ?>">
						<?php foreach( $media_array as $index => $item ){
							$item_class = '';
							if( $index % $column == 0 ){
								$item_class = 'first';
							}
							elseif( $index % $column == ($column - 1) ){
								$item_class = 'last';
							}
						?>
						<div class="item <?php echo esc_attr($item_class); ?>">
							<a href="<?php echo esc_url( $item['link'] ) ?>" target="<?php echo esc_attr( $target ) ?>">
								<img src="<?php echo esc_url( $item[$size] ) ?>" alt="<?php echo esc_attr( $item['description'] ) ?>" title="<?php echo esc_attr( $item['description'] ) ?>" />
							</a>
						</div>
						<?php } ?>
					</div>
					<?php
					$output = ob_get_clean();
					echo $output;
					set_transient($transient_key, $output, $cache_time * HOUR_IN_SECONDS);
				}
			}
			echo $after_widget;
		}

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;	
			$instance['title'] 				=  strip_tags($new_instance['title']);
			$instance['username'] 			=  $new_instance['username'];
			$instance['number'] 			=  $new_instance['number'];
			$instance['column'] 			=  $new_instance['column'];
			$instance['size'] 				=  $new_instance['size'];									
			$instance['target'] 			=  $new_instance['target'];									
			$instance['cache_time'] 		=  $new_instance['cache_time'];									
			return $instance;
		}

		function form( $instance ) {
			$array_default = array(
							'title'			=> 'Instagram'
							,'username' 	=> ''
							,'number' 		=> 9
							,'column' 		=> 3
							,'size' 		=> 'large'
							,'target' 		=> '_self'
							,'cache_time'	=> 12
							);
							
			$instance = wp_parse_args( (array) $instance, $array_default );
		?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Enter your title', 'sanzo'); ?> </label>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('username'); ?>"><?php esc_html_e('Username', 'sanzo'); ?> </label>
				<input class="widefat" id="<?php echo $this->get_field_id('username'); ?>" name="<?php echo $this->get_field_name('username'); ?>" type="text" value="<?php echo esc_attr($instance['username']); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('number'); ?>"><?php esc_html_e('Number of photos', 'sanzo'); ?> </label>
				<input class="widefat" type="number" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" value="<?php echo esc_attr($instance['number']); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('column'); ?>"><?php esc_html_e('Column', 'sanzo'); ?> </label>
				<select class="widefat" id="<?php echo $this->get_field_id('column'); ?>" name="<?php echo $this->get_field_name('column'); ?>" >
					<?php for( $i = 1; $i <= 4; $i++ ): ?>
					<option value="<?php echo $i; ?>" <?php selected($instance['column'], $i); ?> ><?php echo $i; ?></option>
					<?php endfor; ?>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('size'); ?>"><?php esc_html_e('Size', 'sanzo'); ?> </label>
				<select class="widefat" id="<?php echo $this->get_field_id('size'); ?>" name="<?php echo $this->get_field_name('size'); ?>" >
					<option value="thumbnail" <?php selected($instance['size'], 'thumbnail'); ?> ><?php esc_html_e('Thumbnail', 'sanzo') ?></option>
					<option value="small" <?php selected($instance['size'], 'small'); ?> ><?php esc_html_e('Small', 'sanzo') ?></option>
					<option value="large" <?php selected($instance['size'], 'large'); ?> ><?php esc_html_e('Large', 'sanzo') ?></option>
					<option value="original" <?php selected($instance['size'], 'original'); ?> ><?php esc_html_e('Original', 'sanzo') ?></option>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('target'); ?>"><?php esc_html_e('Target', 'sanzo'); ?> </label>
				<select class="widefat" id="<?php echo $this->get_field_id('target'); ?>" name="<?php echo $this->get_field_name('target'); ?>" >
					<option value="_self" <?php selected($instance['target'], '_self'); ?> ><?php esc_html_e('Self', 'sanzo') ?></option>
					<option value="_blank" <?php selected($instance['target'], '_blank'); ?> ><?php esc_html_e('New Window Tab', 'sanzo') ?></option>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('cache_time'); ?>"><?php esc_html_e('Cache time (hours)', 'sanzo'); ?> </label>
				<input class="widefat" type="number" min="1" id="<?php echo $this->get_field_id('cache_time'); ?>" name="<?php echo $this->get_field_name('cache_time'); ?>" value="<?php echo esc_attr($instance['cache_time']); ?>" />
			</p>
			
			<?php 
		}
		
		function scrape_instagram( $username, $slice = 9 ) {

			$username = strtolower( $username );
			$username = str_replace( '@', '', $username );

			$remote = wp_remote_get( 'http://instagram.com/'.trim( $username ) );

			if ( is_wp_error( $remote ) ){
				return new WP_Error( 'site_down', esc_html__( 'Unable to communicate with Instagram.', 'sanzo' ) );
			}

			if ( 200 != wp_remote_retrieve_response_code( $remote ) ){
				return new WP_Error( 'invalid_response', esc_html__( 'Instagram did not return a 200.', 'sanzo' ) );
			}

			$shards = explode( 'window._sharedData = ', $remote['body'] );
			$insta_json = explode( ';</script>', $shards[1] );
			$insta_array = json_decode( $insta_json[0], TRUE );

			if ( ! $insta_array ){
				return new WP_Error( 'bad_json', esc_html__( 'Instagram has returned invalid data.', 'sanzo' ) );
			}

			if ( isset( $insta_array['entry_data']['ProfilePage'][0]['user']['media']['nodes'] ) ) {
				$images = $insta_array['entry_data']['ProfilePage'][0]['user']['media']['nodes'];
			} else {
				return new WP_Error( 'bad_json_2', esc_html__( 'Instagram has returned invalid data.', 'sanzo' ) );
			}

			if ( ! is_array( $images ) ){
				return new WP_Error( 'bad_array', esc_html__( 'Instagram has returned invalid data.', 'sanzo' ) );
			}

			$instagram = array();

			foreach ( $images as $image ) {

				$image['thumbnail_src'] = preg_replace( '/^https?\:/i', '', $image['thumbnail_src'] );
				$image['display_src'] = preg_replace( '/^https?\:/i', '', $image['display_src'] );

				/* handle both types of CDN url */
				if ( ( strpos( $image['thumbnail_src'], 's640x640' ) !== false ) ) {
					$image['thumbnail'] = str_replace( 's640x640', 's160x160', $image['thumbnail_src'] );
					$image['small'] = str_replace( 's640x640', 's320x320', $image['thumbnail_src'] );
				} else {
					$urlparts = wp_parse_url( $image['thumbnail_src'] );
					$pathparts = explode( '/', $urlparts['path'] );
					array_splice( $pathparts, 3, 0, array( 's160x160' ) );
					$image['thumbnail'] = '//' . $urlparts['host'] . implode( '/', $pathparts );
					$pathparts[3] = 's320x320';
					$image['small'] = '//' . $urlparts['host'] . implode( '/', $pathparts );
				}

				$image['large'] = $image['thumbnail_src'];

				if ( $image['is_video'] == true ) { /* Don't show video */
					continue;
				}

				$caption = __( 'Instagram Image', 'sanzo' );
				if ( ! empty( $image['caption'] ) ) {
					$caption = $image['caption'];
				}

				$instagram[] = array(
					'description'   => $caption,
					'link'		  	=> trailingslashit( '//instagram.com/p/' . $image['code'] ),
					'time'		  	=> $image['date'],
					'comments'	  	=> $image['comments']['count'],
					'likes'		 	=> $image['likes']['count'],
					'thumbnail'	 	=> $image['thumbnail'],
					'small'			=> $image['small'],
					'large'			=> $image['large'],
					'original'		=> $image['display_src'],
					'type'		  	=> 'image'
				);
			}

			if ( ! empty( $instagram ) ) {
				return array_slice( $instagram, 0, $slice );
			} else {
				return new WP_Error( 'no_images', esc_html__( 'Instagram did not return any images.', 'sanzo' ) );
			}
		}
	}
}

