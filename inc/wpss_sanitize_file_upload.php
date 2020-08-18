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
 * Clean special characters and spaces from uploaded files
 *
 * @param $filename
 *
 * @return string
 */

add_filter( 'sanitize_file_name', 'wpss_sanitize_uploaded_filename' );
function wpss_sanitize_uploaded_filename( $filename ) {
	$fileNewName = sanitize_title( pathinfo( $filename )['filename'] ) . '.' . pathinfo( $filename )['extension'];
	return $fileNewName;
}