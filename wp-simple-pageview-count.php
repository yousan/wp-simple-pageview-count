<?php
/**
 * Plugin Name:     Wp Simple Pageview Count
 * Plugin URI:      https://github.com/yousan/wp-simple-pageview-count
 * Description:     Count pageviews by Ajax request. Reduce requests with sampling rate.
 * Author:          Yousan_O
 * Author URI:      https://github.com/yousan
 * Text Domain:     wp-simple-pageview-count
 * Domain Path:     /languages
 * Version:         0.0.1
 *
 * @package         Wp_Simple_Pageview_Count
 */

class WSPC_AdminOptions {
	public static function add_menu() {
		add_action( 'admin_menu', array( 'WSPC_AdminOptions', 'submenu' ) );
	}

	public static function submenu() {
		add_submenu_page(
			'options-general.php',
			'My Custom Submenu Page',
			'My Custom Submenu Page',
			'manage_options',
			'my-custom-submenu-page',
			'wpdocs_my_custom_submenu_page_callback' );
	}

	function wpdocs_my_custom_submenu_page_callback() {
		echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';
		echo '<h2>My Custom Submenu Page</h2>';
		echo '</div>';
	}
}

WSPC_AdminOptions::add_menu();

class WSPC_Loader {
	public static function load() {
		add_action('wp_enqueue_scripts', array('WSPC_Loader', 'enqueue_scripts'));
	}

	public static function enqueue_scripts() {
//		var_dump(plugins_url('/js/wpspc.js', __FILE__));
//		 exit;
		wp_enqueue_script( 'wspc', plugins_url('/js/wspc.js', __FILE__), array('jquery') );
	}
}

WSPC_Loader::load();
