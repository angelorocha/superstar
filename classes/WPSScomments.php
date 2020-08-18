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

final class WPSScomments {


	public function wpss_comments( $post_id ) {
		$args     = array(
			'ID' => $post_id
		);
		$query    = new WP_Comment_Query;
		$comments = $query->query( $args );

		if ( $comments ):
			foreach ( $comments as $comment ):
				$author_id   = $comment->user_id;
				$author_name = $comment->comment_author;
				$content     = $comment->comment_content;
				$email       = $comment->comment_author_email;
				$url         = $comment->comment_author_url;
				$date        = date( __( 'Y-m-d H:i:s', 'wpss' ), strtotime( $comment->comment_date ) );
				$approved = $comment->comment_approved;
				$parent   = $comment->comment_parent;


			endforeach;
		endif;
	}
}