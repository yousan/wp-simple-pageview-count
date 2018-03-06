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

	/**
	 * optionsに保存されるキー名
	 */
	const WSPC_OPTION_NAME = 'wspc_options';

	/**
	 * メニュー登録フック。
	 */
	public static function add_menu() {
		add_action( 'admin_menu', array( 'WSPC_AdminOptions', 'register_submenu' ) );
	}

	/**
	 * 管理画面へのメニュー登録
	 */
	public static function register_submenu() {
		add_submenu_page(
			'options-general.php',
			'WP Simple Pageview Count',
			'WP Simple Pageview Count',
			'manage_options',
			'wp-simple-pageview-count',
			array( 'WSPC_AdminOptions', 'submenu_page' ) );
	}

	/**
	 * いらない子
	 */
	function wpdocs_my_custom_submenu_page_callback() {
		echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';
		echo '<h2>My Custom Submenu Page</h2>';
		echo '</div>';
	}

	/**
	 * メニュー描画
	 */
	public static function submenu_page() {
		$options = get_option( self::WSPC_OPTION_NAME );
		register_setting(
			'wspc_options', // Option group
			self::WSPC_OPTION_NAME, // Option name
			array( 'WSPC_AdminOptions', 'sanitize' ) // Sanitize
		);
		?>
		<div class="wrap">
			<div id="icon-tools" class="icon32"></div>
			<h2>WP Simple Pageview Count</h2>
		</div>
		<form method="post" action="options.php">
			サンプリングレート<input type="text" value="<?php echo $options['rate']; ?>" name="rate" size="5"
			                placeholder="e.g. 1000" style="width: 200px;"><br>
			サンプリング（間引き集計）を行う間隔を入力してください。実際には前後50%の値でサンプリングが行われます。<br>
			<?php
			// This prints out all hidden setting fields
			// settings_fields('apft_option_group');
			// do_settings_sections('apft-setting-admin');
			submit_button();
			?>
		</form>
		<?php
	}
}


WSPC_AdminOptions::add_menu();

class WSPC_Loader {

	/**
	 * カスタムフィールド名。
	 */
	const POSTMETA_KEY = 'wspc_count';

	public static function load() {
		add_action( 'wp_enqueue_scripts', array( 'WSPC_Loader', 'enqueue_scripts' ) );
	}

	public static function enqueue_scripts() {
		if ( is_single() ) {
			$post    = get_post();
			$post_id = $post->ID;
		} else { // シングルじゃないときには動かないようにしておく
			$post_id = 0;
		}
		// @link https://wordpress.stackexchange.com/questions/190297/ajaxurl-not-defined-on-front-end
		wp_enqueue_script( 'wspc', plugins_url( '/js/wspc.js', __FILE__ ), array( 'jquery' ) );
		wp_localize_script( 'wspc', 'wspc',
			array( 'ajax_url' => admin_url( 'admin-ajax.php' ),
				'post_id' => $post_id,
			) );
	}

	/**
	 * Ajaxでの待ち受けを設定する.
	 *
	 * @link https://codex.wordpress.org/AJAX_in_Plugins
	 */
	public static function register_ajax_listener() {
		add_action( 'wp_ajax_wspc_count_up', array( self::class, 'count_up' ) );
		add_action( 'wp_ajax_nopriv_wspc_count_up', array( self::class, 'count_up' ) );
	}

	/**
	 * カウントをアップする。
	 */
	public static function count_up() {
		// global $wpdb; // this is how you get access to the database
		$post_id      = (int) $_POST['post_id'];
		$count_to_up  = (int) $_POST['count']; // 増やす数値　
		$count_before = (int) get_post_meta( $post_id, self::POSTMETA_KEY, true );
		$count        = $count_before + $count_to_up;
		update_post_meta( $post_id, self::POSTMETA_KEY, $count );
		$return = array(
			'post_id'      => $post_id,
			'count_before' => $count_before,
			'count_to_up'  => $count_to_up,
			'count'        => $count
		);
		echo json_encode( $return );
		wp_die(); // this is required to terminate immediately and return a proper response
	}
}

WSPC_Loader::load();
WSPC_Loader::register_ajax_listener();

