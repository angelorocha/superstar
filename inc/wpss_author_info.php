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
 * @param $author_id
 *
 * @param bool $author_mention
 *
 * @return string
 */

function wpss_author( $author_id, $author_mention = false ) {

	if ( $author_mention ):
		$author_name = '@' . get_the_author_meta( 'user_login', $author_id );
	else:
		$author_name = ( ! empty( get_the_author_meta( 'display_name', $author_id ) ) ?
			get_the_author_meta( 'display_name', $author_id ) : '@' . get_the_author_meta( 'user_login', $author_id ) );
	endif;

	$author = '<a href="'.get_author_posts_url($author_id).'" title="'.$author_name.'">' . $author_name . '</a>';

	return $author;
}