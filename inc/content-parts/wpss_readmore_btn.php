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
 *
 * @param string $class - define bootstrap 4 default classes
 * @param string $style - define button style
 *
 * @return string
 */

function wpss_readmore_btn( $class = "btn btn-outline-secondary") {

	return "<a href='" . get_permalink() . "' title='" . esc_html( get_the_title() ) . "' class='$class'>" . __( 'Read More', 'wpss' ) . "</a>";
}