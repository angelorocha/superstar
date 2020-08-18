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

function wpss_top_menu() {
	wp_nav_menu( array(
		'theme_location' => 'top_menu',
		'fallback_cb'    => array( 'WPSSmenu', 'fallback' ),
	) );
}

function wpss_main_menu() {

}