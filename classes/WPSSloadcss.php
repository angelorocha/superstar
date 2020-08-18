<?php
/**
 * @author              Angelo Rocha
 * @author              Angelo Rocha <contato@angelorocha.com.br>
 * @link                https://angelorocha.com.br
 * @copyleft            2020
 * @license             GNU GPL 3 (https://www.gnu.org/licenses/gpl-3.0.html)
 * @package WordPress
 * @subpackage superstar
 * @since 1.0.0
 */

final class WPSSloadcss {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'wpss_enqueue_styles' ) );
	}

	/**
	 * Load theme styles
	 */
	public function wpss_enqueue_styles() {
		self::wpss_load_styles( 'bootstrap_css', 'bootstrap.min' );
		self::wpss_load_styles( 'fontawesome_css', 'fontawesome.min' );
		self::wpss_load_styles( 'wp_css', 'wordpress' );
		self::wpss_load_styles( 'likely_css', 'likely' );
		self::wpss_load_styles( 'light_gallery_css', 'lightgallery.min' );
		self::wpss_load_styles( 'widgets_css', 'widgets' );
		self::wpss_load_styles( 'headers_css', 'content-headers' );
		self::wpss_load_styles( 'slide_css', 'slide' );
		#self::wpss_load_styles( 'perfect_scroll_css', 'perfect-scrollbar' );
		self::wpss_load_styles( 'login_css', 'login' );
		self::wpss_load_styles( 'modal_login', 'wpss-modal-login' );
		self::wpss_load_styles( 'reset_css', 'reset' );
		self::wpss_load_styles( 'print_css', 'print', '', 'print' );
		self::wpss_load_styles( 'style_css' );
	}

	/**
	 * @param $handle = File handler
	 * @param string $file = File name, empty to add theme style
	 * @param array $dep = Style deps
	 * @param string $media = Media screen, default "screen"
	 */
	public function wpss_load_styles( $handle, $file = '', $dep = array(), $media = 'screen' ) {
		$css_path = ( ! empty( $file ) ? _WPSS_CSS_DIR . $file . '.css' : get_stylesheet_uri() );
		wp_enqueue_style( $handle, $css_path, $dep, _WPSS_FILE_VERSION, $media );
	}
}

new WPSSloadcss();