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

global $wpss_custom_sidebar;

$wpss_custom_sidebar = false;

echo "<div class='wpss-main-sidebar col-md-3'>";
do_action( 'wpss_sidebar_before' );
if ( ! $wpss_custom_sidebar ):
	dynamic_sidebar( 'wpss_sidebar_widgets' );
endif;
do_action( 'wpss_sidebar_after' );
echo "</div>";

