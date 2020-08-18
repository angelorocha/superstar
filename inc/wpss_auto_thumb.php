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
 */

function wpss_auto_thumbnail( $post_id ) {
	if ( ! has_post_thumbnail() ) {
		global $wpdb;
		$content    = get_post( $post_id )->post_content;
		$thumbnail  = [];
		$image_name = [];
		$image_url  = [];
		$image_ext  = [];
		$upload_dir = wp_upload_dir();
		$i          = 0;

		$html = new DOMDocument();
		@$html->loadHTML( $content );

		$image = $html->getElementsByTagName( 'img' );

		$have_image = count( $image ) > 0 ? true : false;

		/**** If is have image ****/
		if ( $have_image ) {
			foreach ( $image as $src ) {
				//$remote = parse_url( $src->getAttribute( 'src' ) )['host'];
				//$local  = parse_url( get_site_url() )['host'];

				$thumbnail[] = $src->getAttribute( 'src' );

				if ( ! empty( pathinfo( $thumbnail[ $i ] )['extension'] ) ) {
					$temp_ext[]   = explode( '?', pathinfo( $thumbnail[ $i ] )['extension'] );
					$image_ext[]  = count( $temp_ext[ $i ] ) > 1 ? $temp_ext[ $i ][0] : pathinfo( $thumbnail[ $i ] )['extension'];
					$image_name[] = md5( basename( $src->getAttribute( 'src' ) ) ) . '.' . $image_ext[ $i ];
				} else {
					$image_name[] = md5( basename( $src->getAttribute( 'src' ) ) ) . '.jpg';
				}

				$image_url[]  = $upload_dir['url'] . '/' . $image_name[ $i ];
				$image_pach[] = $upload_dir['path'] . '/' . $image_name[ $i ];


				/**** Attach image to post ****/
				$ch = curl_init( $thumbnail[ $i ] );
				$fp = fopen( $upload_dir['path'] . '/' . $image_name[ $i ], 'wb' );
				curl_setopt( $ch, CURLOPT_FILE, $fp );
				curl_setopt( $ch, CURLOPT_HEADER, 0 );
				curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
				curl_setopt( $ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)' );
				curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
				curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 0 );
				curl_setopt( $ch, CURLOPT_TIMEOUT, 900 );
				curl_exec( $ch );
				curl_close( $ch );
				fclose( $fp );

				$parent_post_id = $post_id;
				$filetype       = wp_check_filetype( $thumbnail[ $i ], null );
				$attachment     = array(
					'guid'           => $upload_dir['url'] . '/' . $image_name[ $i ],
					'post_mime_type' => $filetype['type'],
					'post_title'     => 'auto_thumbnail_' . $image_name[ $i ],
					'post_content'   => '',
					'post_status'    => 'inherit'
				);
				$attach_id      = wp_insert_attachment( $attachment, $image_pach[ $i ], $parent_post_id );
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
				$attach_data = wp_generate_attachment_metadata( $attach_id, $image_pach[ $i ] );
				wp_update_attachment_metadata( $attach_id, $attach_data );
				/**** Select first image for thumbnail ****/
				if ( $i < 1 ) {
					set_post_thumbnail( $parent_post_id, $attach_id );
				}
				/**** End image attach ****/

				$i ++;
			}
			$i = 0;
		}
	}
}