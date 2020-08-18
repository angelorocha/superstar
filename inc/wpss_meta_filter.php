<?php
/**
 * @param $post_type
 * @param $meta_key
 * @param $label
 *
 * @author              Angelo Rocha
 * @author              Angelo Rocha <contato@angelorocha.com.br>
 * @link                https://angelorocha.com.br
 * @copyleft            2020
 * @license             GNU GPL 3 (https://www.gnu.org/licenses/gpl-3.0.html)
 * @package WordPress
 * @subpackage superstar
 * @since 1.0.0
 */

function wpss_meta_filter( $post_type, $meta_key, $label ) {
	$meta_filter               = new WPSSMetaFilter();
	$meta_filter->filter_label = $label;
	$meta_filter->post_type    = $post_type;
	$meta_filter->meta_key     = $meta_key;
	$meta_filter->wpss_make_column_filter();
}