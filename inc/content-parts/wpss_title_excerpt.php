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
 * @param $limit - Character title limit
 *
 * @return string
 */

function wpss_title_excerpt( $limit ) {
	$count         = strlen( get_the_title() );
	$title_excerpt = substr( get_the_title(), 0, $limit ) . '...';

	return ( $count > $limit ? $title_excerpt : get_the_title() );
}