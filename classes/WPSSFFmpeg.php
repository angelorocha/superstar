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

use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Format\Video\WebM;
use FFMpeg\Format\Video\X264;
use FFMpeg\Media\Audio;
use FFMpeg\Media\Frame;
use FFMpeg\Media\Gif;
use FFMpeg\Media\Video;

class WPSSFFmpeg {

	public $media;
	public $from_sec = 1;
	public $to_sec = 5;

	public function __construct() {

	}

	/**
	 * Make converted media
	 */
	public function wpss_make_media() {

		self::wpss_ffmpeg();

		if ( in_array( self::wpss_get_media_ext(), self::wpss_allowed_files() ) ):
			self::wpss_upload_media();
			self::wpss_get_media();
			self::wpss_get_media()
			    ->filters()
				//->resize( new Dimension( 320, 240 ) )
				->synchronize();
			self::wpss_media_convert();
			self::wpss_media_thumbnail();
			self::wpss_media_clip();
			self::wpss_media_gif();
		else:
			_e( 'File type not allowed', 'wpss' );
		endif;
	}

	/**
	 * Upload media
	 * @return array
	 * Absolute path or full file URL
	 */
	public function wpss_upload_media() {

		if ( ! is_dir( self::wpss_set_uploads_dir() ) ):
			mkdir( self::wpss_set_uploads_dir(), 0777, true );
		endif;

		move_uploaded_file( $this->media['tmp_name'], self::wpss_set_uploads_dir() . self::wpss_set_media_name() );

		$media = array(
			'url'  => self::wpss_get_uploads_url() . self::wpss_set_media_name(),
			'path' => self::wpss_set_uploads_dir() . self::wpss_set_media_name()
		);

		return $media;
	}

	/**
	 * Set ffmpeg bin file
	 * @return FFMpeg
	 */
	public function wpss_ffmpeg() {

		$ffmpeg  = ( PHP_OS === 'WINNT' ? 'ffmpeg.exe' : 'ffmpeg' );
		$ffprobe = ( PHP_OS === 'WINNT' ? 'ffprobe.exe' : 'ffprobe' );

		return FFMpeg::create( array(
			'ffmpeg.binaries'  => get_template_directory() . '/lib/bin/ffmpeg/' . $ffmpeg,
			'ffprobe.binaries' => get_template_directory() . '/lib/bin/ffmpeg/' . $ffprobe,
			'timeout'          => 3600,
			'ffmpeg.threads'   => 12,
		) );
	}

	/**
	 * Get media upload name
	 * @return string
	 */
	public function wpss_get_media_name() {
		return sanitize_title( pathinfo( $this->media['name'] )['filename'] );
	}

	/**
	 * Set upload media name
	 * @return string
	 */
	public function wpss_set_media_name() {
		return self::wpss_get_media_name() . '.' . self::wpss_get_media_ext();
	}

	/**
	 * Get media extension
	 * @return mixed
	 */
	public function wpss_get_media_ext() {
		return pathinfo( $this->media['name'] )['extension'];
	}

	/**
	 * Set media upload dir
	 * @return string
	 */
	public function wpss_set_media_dir() {
		return (string) date( 'Y' ) . '/' . date( 'm' ) . '/' . self::wpss_get_media_name() . '/';
	}

	/**
	 * Define upload directory
	 * @return string
	 */
	public function wpss_set_uploads_dir() {
		return wp_upload_dir()['basedir'] . '/videos/' . self::wpss_set_media_dir();
	}

	/**
	 * Get media url
	 * @return string
	 */
	public function wpss_get_uploads_url() {
		return wp_upload_dir()['baseurl'] . '/videos/' . self::wpss_set_media_dir();
	}

	/**
	 * Get uploaded media
	 * @return Audio|Video
	 */
	public function wpss_get_media() {
		return self::wpss_ffmpeg()->open( self::wpss_upload_media()['path'] );
	}

	/**
	 * Convert media to mp4 file
	 * @return Audio|Video
	 */
	public function wpss_media_convert() {
		return self::wpss_get_media()->save( new X264( 'libmp3lame', 'libx264' ), self::wpss_set_uploads_dir() . self::wpss_get_media_name() . '.mp4' );
	}

	/**
	 * Define video thumbnail
	 * @return Frame
	 */
	public function wpss_media_thumbnail() {
		$thumbnail = self::wpss_get_media()->frame( TimeCode::fromSeconds( $this->from_sec ) );

		return $thumbnail->save( self::wpss_set_uploads_dir() . self::wpss_get_media_name() . '.png' );
	}

	/**
	 * Generate a media clip
	 * @return Video
	 */
	public function wpss_media_clip() {
		$clip = self::wpss_get_media()->clip( TimeCode::fromSeconds( $this->from_sec ), TimeCode::fromSeconds( $this->to_sec ) );

		return $clip->save( new WebM(), self::wpss_set_uploads_dir() . self::wpss_get_media_name() . '.webm' );
	}

	/**
	 * Generate video gif
	 * @return Gif
	 */
	public function wpss_media_gif() {
		return self::wpss_get_media()
		           ->gif( TimeCode::fromSeconds( $this->from_sec ), new Dimension( 370, 250 ), $this->to_sec )
		           ->save( self::wpss_set_uploads_dir() . self::wpss_get_media_name() . '.gif' );
	}

	/**
	 * Insert media in post attachment and define featured image or no
	 *
	 * @param $post_id
	 * @param bool $is_thumbnail
	 */
	public function wpss_insert_media_attachment( $post_id, $is_thumbnail = false ) {
		$get_file   = self::wpss_set_uploads_dir() . self::wpss_set_media_name();
		$attachment = array(
			'guid'           => self::wpss_get_uploads_url() . self::wpss_set_media_name(),
			'post_mime_type' => wp_check_filetype( basename( $get_file ), null )['type'],
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $get_file ) ),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);

		$attachment_id = wp_insert_attachment( $attachment, $get_file, $post_id );
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		$attach_data = wp_generate_attachment_metadata( $attachment_id, $get_file );
		wp_update_attachment_metadata( $attachment_id, $attach_data );

		if ( $is_thumbnail ):
			set_post_thumbnail( $post_id, $attachment_id );
		endif;
	}

	/**
	 * Define allowed file extensions
	 * @return array
	 */
	public function wpss_allowed_files() {
		return array( 'mp3', 'mp4', 'mpeg', 'avi', 'flv', 'rmvb', 'wmv' );
	}

}