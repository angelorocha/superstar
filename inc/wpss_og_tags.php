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

add_action( 'wp_head', 'wpss_og_tags' );
function wpss_og_tags() {

	$tags = '';

	if ( is_singular( array( 'post', 'page' ) ) ):
		$post_id     = get_queried_object_id();
		$locale      = _WPSS_SITE_LANG;
		$site_name   = _WPSS_SITENAME . ' - ' . _WPSS_SITEDESC;
		$title       = get_the_title( $post_id );
		$url         = get_permalink( $post_id );
		$description = substr( strip_tags( get_post( $post_id )->post_content ), 0, 157 ) . '...';
		$thumbnail   = wpss_image_src( $post_id, 'post-thumbnail' );
		$image       = ( ! empty( $thumbnail ) ? $thumbnail : _WPSS_IMAGES_DIR . 'no-thumbnail.png' );

		$tags .= "\n\n<!-- WP SuperStar Open Graph Tags -->\n";
		$tags .= '<meta property="og:locale" content="' . $locale . '"/>' . "\n";
		$tags .= '<meta property="og:site_name" content="' . $site_name . '"/>' . "\n";
		$tags .= '<meta property="og:title" content="' . $title . '"/>' . "\n";
		$tags .= '<meta property="og:url" content="' . $url . '"/>' . "\n";
		$tags .= '<meta property="og:type" content="article"/>' . "\n";
		$tags .= '<meta property="og:description" content="' . $description . '"/>' . "\n";
		$tags .= '<meta property="og:image" content="' . $image . '"/>' . "\n\n";

		$tags .= "<!-- WP SuperStar Twitter Open Graph Tags -->\n";
		$tags .= '<meta name="twitter:title" content="' . $title . '"/>' . "\n";
		$tags .= '<meta name="twitter:url" content="' . $url . '"/>' . "\n";
		$tags .= '<meta name="twitter:description" content="' . $description . '"/>' . "\n";
		$tags .= '<meta name="twitter:image" content="' . $image . '"/>' . "\n";
		$tags .= '<meta name="twitter:card" content="summary_large_image"/>' . "\n\n";
		$tags .= "<!-- End SuperStar Open Graph Tags-->\n\n";
	endif;

	echo $tags;
}