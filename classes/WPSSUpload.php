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

class WPSSUpload {

	/**
	 * @var $files
	 * Define files to upload
	 */
	public $files;

	/**
	 * @var $post_id
	 * Set a post id to define the attachment post parent
	 */
	public $post_id = 0;

	/**
	 * @var $file_size
	 * Define max file size to upload in bytes
	 * 1000000 bytes = 10 MB
	 */
	public $file_size = 1000000;

	public function __construct() {

	}

	/**
	 * Set uploaded file as file attachment post
	 *
	 * @param bool $is_thumbnail
	 * If is true, define file as post thumbnail to post parent
	 *
	 * @return bool|int|WP_Error
	 */
	public function wpss_set_attachment( $is_thumbnail = false ) {

		if ( ! self::wpss_check_filesize() ):
			return false;
		endif;

		$file_data     = self::wpss_upload_file();
		$attachment    = array(
			'guid'           => $file_data['url'],
			'post_mime_type' => $file_data['mime'],
			'post_title'     => $file_data['name'],
			'post_content'   => '',
			'post_status'    => 'inherit'
		);
		$attachment_id = wp_insert_attachment( $attachment, $file_data['path'], $this->post_id );
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		$metadata = wp_generate_attachment_metadata( $attachment_id, $file_data['path'] );
		wp_update_attachment_metadata( $attachment_id, $metadata );

		if ( $is_thumbnail ):
			set_post_thumbnail( $this->post_id, $attachment_id );
		endif;

		return $attachment_id;
	}

	/**
	 * Upload file
	 * @return array|bool
	 */
	public function wpss_upload_file() {
		require_once( ABSPATH . 'wp-admin/includes/admin.php' );
		$file_name = pathinfo( $this->files['name'] )['filename'];
		$file_ext  = pathinfo( $this->files['name'] )['extension'];
		$get_file  = wp_handle_upload( $this->files, array( 'test_form' => false ), null );

		if ( ! $get_file && ! isset( $get_file['error'] ) ):
			return false;
		endif;

		$file_url   = $get_file['url'];
		$file_path  = $get_file['file'];
		$file_mime  = $get_file['type'];
		$attachment = array( 'name' => $file_name, 'ext' => $file_ext, 'url' => $file_url, 'path' => $file_path, 'mime' => $file_mime );

		return $attachment;
	}

	/**
	 * Check file size
	 * @return bool
	 */
	public function wpss_check_filesize() {
		if ( $this->files['size'] > $this->file_size ):
			echo '<div class="alert alert-warning text-center">'.__( 'File size not allowed, please check your file and try again.', 'wpss' ).'</div>';

			return false;
		endif;

		return true;
	}

}