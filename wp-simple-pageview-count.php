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
		add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function = '', $icon_url = '',
			$position = null );
	}
}
