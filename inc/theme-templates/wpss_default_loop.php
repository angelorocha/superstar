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

function wpss_default_loop() {

	if ( have_posts() ):

		while ( have_posts() ): the_post();

			do_action( 'wpss_loop_item_begin' );

			wpss_loop_block_1();

			do_action( 'wpss_loop_item_end' );

		endwhile;

		echo wpss_pagination();

	else:

        echo "<h5 class='text-center'>" . __('No information entered in this section', 'wpss') . "</h5>";

	endif;
}