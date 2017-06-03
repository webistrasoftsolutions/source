<?php 
/**
 * Plugin Name: ThemeSky
 * Plugin URI: http://theme-sky.com
 * Description: Add shortcodes and custom post types for Sanzo theme
 * Version: 1.0.6
 * Author: ThemeSky Team
 * Author URI: http://theme-sky.com
 */
class ThemeSky_Plugin{

	function __construct(){
		$this->load_language_file();
		$this->include_files();
		$this->register_widgets();
		
		/* Fix bbpress setup current user */
		remove_action( 'set_current_user', 'bbp_setup_current_user', 10);
		add_action( 'init', array($this, 'bbp_setup_current_user'));
		
		/* Dont support custom header */
		add_action('after_setup_theme', array($this, 'remove_theme_support_custom_header'), 99 );
	}
	
	function load_language_file(){
		load_plugin_textdomain('themesky', false, basename( dirname( __FILE__ ) ) . '/languages' );
	}
	
	function include_files(){
		require_once('register_post_type.php');
		require_once('class-shortcodes.php');
		require_once('includes/twitteroauth.php');
	}
	
	function register_widgets(){
		$file_names = array('single_image', 'footer_block', 'twitter');
		foreach( $file_names as $file_name ){
			$file = plugin_dir_path( __FILE__ ) . '/widgets/' . $file_name . '.php';
			if( file_exists($file) ){
				require_once($file);
			}
		}
	}
	
	function bbp_setup_current_user(){
		if( function_exists('bbp_setup_current_user') ){
			add_action( 'set_current_user', 'bbp_setup_current_user', 10);
		}
	}
	
	function remove_theme_support_custom_header(){
		remove_theme_support( 'custom-header' );
	}
	
}
new ThemeSky_Plugin();
?>