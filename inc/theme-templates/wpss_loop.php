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

function wpss_loop() {

	global $wpss_custom_loop;

	echo "<section role='main' id='wpss-content' class='wpss-loop-content col-md-9'>";

	if ( ! is_home() || ! is_front_page() ):
		echo "<h3 class='wpss-archvie-title text-center' aria-labelledby='wpss-content'>" . wpss_archive_title() . "</h3>";
	endif;

	do_action( 'wpss_loop_begin' );

	if ( ! $wpss_custom_loop ):
		wpss_default_loop();
	endif;

	do_action( 'wpss_loop_end' );

	echo "</section>";
}