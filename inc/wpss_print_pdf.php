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

use Dompdf\Dompdf;
use Dompdf\Options;

$content_id = ( isset( $_GET['print_content'] ) ? $_GET['print_content'] : false );

if ( $content_id ):

	if ( is_null( get_post( $content_id ) ) ):
		wp_redirect( home_url(), 301 );
		exit;
	endif;

	$options = new Options();
	$options->set( 'isRemoteEnabled', true );
	$pdf = new Dompdf( $options );

	$post_type = get_post( $content_id )->post_type;

	$html = "<style>img{max-width:100%; height:auto;}</style>";
	$html .= "<h2 style='margin:0 0 10px 0;'>" . get_post( $content_id )->post_title . "</h2>";
	$html .= "<small style='display:block; text-align:right; margin: 0 0 10px 0; padding:5px 0 5px 0; border-top:1px solid #EEE; border-bottom:1px solid #EEE;'>Postado em: " . get_the_date( 'd-m-Y', $content_id );

	if ( $post_type === 'clipping' ):
		$html .= '<strong style="margin-left:15px;">Fonte: </strong>' . get_post_meta( $content_id, '_clipping_font', true );
	endif;

	$html .= '</small>';

	if ( has_post_thumbnail( $content_id ) ):
		#$html .= get_the_post_thumbnail( $content_id );
	endif;

	$html .= "<div style='text-align:justify'>" . wpautop( get_post( $content_id )->post_content ) . "</div>";

	$context = stream_context_create( array(
		'ssl' => array(
			'verify_peer'       => false,
			'verify_peer_name'  => false,
			'allow_self_signed' => true
		)
	) );

	$pdf->setHttpContext( $context );
	$pdf->loadHtml( $html );
	$pdf->setPaper( 'A4', 'portrait' );

	$pdf->render();

	$pdf->stream( sanitize_title( get_post( $content_id )->post_title ), array( "Attachment" => false ) );

	exit;
endif;