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

class WPSSForms{

	public function __construct() {
	}

	/**
	 * Get form ID
	 * @return int
	 */
	public function get_form_id() {
		return get_queried_object_id();
	}

	/**
	 * Get fields keys
	 * @return array
	 */
	public function get_form_fields() {

		$fields = get_post_meta( self::get_form_id(), '_form_fields', true );

		$form_fields = array();
		foreach ( $fields as $field ):
			$form_fields[] = [
				'name'  => $field['id'],
				'type'  => $field['type'],
				'label' => $field['label']
			];
		endforeach;

		return $form_fields;
	}

	/**
	 * Get all field values
	 *
	 * @param bool $files
	 *
	 * @return array
	 */
	public function get_fields_value( $files = false ) {
		$field_val = null;

		$values      = array();
		$attachments = array();

		foreach ( self::get_form_fields() as $key => $val ):

			if ( $val['type'] === 'file' ):
				$field_val = $_FILES[ $val['name'] ];
			endif;

			if ( $val['type'] === 'checkbox' ):
                if(!empty($_POST[ $val['name'] ])):
				    $checkbox = implode( ', ', $_POST[ $val['name'] ]);
                else:
                    $checkbox = $_POST[ $val['name'] ];
                endif;
				$field_val = sanitize_text_field($checkbox);
			endif;

			if ( $val['type'] !== 'checkbox' && $val['type'] !== 'file' ):
				$field_val = sanitize_text_field($_POST[ $val['name'] ]);
			endif;

			if ( $val['type'] !== 'file' && $field_val !== '' && ! is_null( $field_val ) ):
				$values[] = [
					'key'   => $val['name'],
					'label' => $val ['label'],
					'value' => $field_val
				];
			endif;

			if ( $val['type'] === 'file' && $field_val['name'] !== '' ):
                $file_name = pathinfo($field_val['name'])['filename'].'-'.md5(time()).'.'.pathinfo($field_val['name'])['extension'];
				$attachments[] = [
					'file_key'   => $val['name'],
					'file_label' => $val['label'],
					'file_name'  => $field_val['name'],
					'file_dir'   => self::upload_form_files( $field_val['tmp_name'], $file_name ),
					'file_url'   => self::upload_form_files( $field_val['tmp_name'], $file_name, false, false )
				];
			endif;

		endforeach;

		return ( ! $files ? $values : $attachments );
	}

	/**
	 * Upload form files
	 *
	 * @param $file_to_upload
	 * Insert the file to submit
	 * @param $file_upload_name
	 * Insert file name
	 * @param string $sub_dir
	 * Define a subdir to upload
	 * @param bool $get_dir
	 * If is false, return file URL
	 *
	 * @return string
	 */
	public function upload_form_files( $file_to_upload, $file_upload_name, $sub_dir = '', $get_dir = true ) {
		$uploads_dir = self::form_get_upload_path() . $sub_dir;
		$uploads_url = self::form_get_upload_path( true ) . $sub_dir;

		$file_dir = $uploads_dir . $file_upload_name;
		$file_url = $uploads_url . $file_upload_name;

		if ( ! is_dir( $uploads_dir ) ):
			mkdir( $uploads_dir );
		endif;

		move_uploaded_file( $file_to_upload, $uploads_dir . $file_upload_name );

		return ( $get_dir ? $file_dir : $file_url );
	}

	/**
	 * Set forms upload dir
	 *
	 * @param bool $url
	 *
	 * @return string
	 */
	public function form_get_upload_path( $url = false ) {
		$dir_path = wp_upload_dir()['basedir'] . '/forms-attachments/';
		$url_path = wp_upload_dir()['baseurl'] . '/forms-attachments/';

		return ( ! $url ? $dir_path : $url_path );
	}

	/**
	 * Get form info
	 * @return array
	 */
	public function get_form_info() {
		$form_info = array(
			'to_mail'     => get_post_meta( self::get_form_id(), '_form_to_mail', true ),
			'data_start'  => get_post_meta( self::get_form_id(), '_form_start_date', true ),
			'data_end'    => get_post_meta( self::get_form_id(), '_form_end_date', true ),
			'form_desc'   => get_post_meta( self::get_form_id(), '_form_desc', true ),
			'success_msg' => get_post_meta( self::get_form_id(), '_form_send_success', true ),
			'form_expire' => get_post_meta( self::get_form_id(), '_form_expiration_op', true ),
			'form_perms'  => get_post_meta( self::get_form_id(), '_forms_user_admin', true ),
			'form_access' => get_post_meta( self::get_form_id(), '_forms_user_access', true ),
			'form_type'   => get_post_meta( self::get_form_id(), '_forms_type_of_form', true ),
			'form_wpp'    => get_post_meta( self::get_form_id(), '_form_whatsapp_number', true ),
		);

		return $form_info;
	}

	/**
	 * Insert form entry
	 */
	public function form_entrie_prefix() {
		$prefix = self::sender_info()['name'] . '|';
		if ( ! self::check_form_access() ):
			$prefix = '';
		endif;

		return $prefix;
	}

	/**
	 * Insert post after send data
	 */
	public function form_insert_post() {

		$fields = self::get_fields_value();
		$files  = self::get_fields_value( true );

		$meta = array();

		foreach ( $fields as $field ):
			$meta [ $field['key'] ] = [ $field['label'], $field['value'] ];
		endforeach;

		if ( ! empty( $files ) ):
			foreach ( $files as $file ):
				$meta [ $file['file_key'] ] = [ $file['file_label'], $file['file_url'] ];
			endforeach;
		endif;

		$args = array(
			'post_author' => get_current_user_id(),
			'post_title'  => self::form_entrie_prefix() . get_the_title( $this->get_form_id() ),
			'post_status' => 'inherit',
			'post_type'   => 'form-entry',
			'post_parent' => self::get_form_id(),
			'meta_input'  => $meta
		);

		wp_insert_post( $args );
	}

	/**
	 * Get form entries
	 *
	 * @param bool $table
	 */

	public function form_get_entries( $table = true ) {
		$fields  = self::get_form_fields();
		$args    = array(
			'post_type'      => 'form-entry',
			'post_parent'    => self::get_form_id(),
			'posts_per_page' => - 1,
            'post_status'    => 'inherit'
		);
		$entries = new WP_Query( $args );

		if ( ! $table ):
			echo "<ul class='list-unstyled wpss-form-widget'>";
			while( $entries->have_posts() ): $entries->the_post();
				$url = get_permalink( self::get_form_id() ) . '?form_entry=' . get_the_ID();
				echo '<li><a href="' . $url . '" title="' . get_the_title() . '"><span>[' . get_the_date( 'd-m-Y H:i' ) . ']</span> ' . get_the_title() . '</a></li>';
			endwhile;
			echo "</ul>";
		endif;

		if ( $table ):
			self::form_datatables( self::get_form_id() );
			echo "<div class='content-text mt20 mb20'>";
			echo "<table id='table-" . self::get_form_id() . "' class='table table-bordered table-condensed table-striped nowrap'>";
			echo "<thead><tr>";
			echo "<th>"._('User', 'wpss')."</th>";
			foreach ( $fields as $field ):
				echo "<th>" . $field['label'] . "</th>";
			endforeach;
			echo "</tr></thead>";
			echo "<tbody>";
			while( $entries->have_posts() ): $entries->the_post();
				echo "<tr>";
				echo "<td>" . get_userdata( get_post( get_the_ID() )->post_author )->user_login . "</td>";
				foreach ( $fields as $meta ):
					$values  = get_post_meta( get_the_ID(), $meta['name'], true );
					$content = ( ! empty( $values[1] ) ? $values[1] : '--' );
					if ( $meta['type'] === 'file' ):
						$link    = "<a href='" . $values[1] . "' title='" . $values[0] . "' target='_blank' class='btn btn-xs btn-outline-secondary'>Download</a>";
						$content = ( ! empty( $values[1] ) ? $link : '--' );
					endif;
					echo '<td>' . $content . '</td>';
				endforeach;
				echo "</tr>";
			endwhile;
			wp_reset_postdata();
			echo "</tbody>";
			echo "</table></div>";

			echo self::forms_back_to_form();
		endif;

	}

	/**
	 * @param bool $table_attr
	 * @param bool $thead_attr
	 * @param bool $tr_attr
	 * @param bool $td_attr
	 * @param bool $back_btn
	 * @param bool $extra_header
	 *
	 * @return string
	 */
	public function form_get_entry( $table_attr = false, $thead_attr = false, $tr_attr = false, $td_attr = false, $back_btn = true, $extra_header = false ) {
		$fields = self::get_form_fields();

		$entry_id = null;
		if ( isset( $_GET['form_entry'] ) ):
			$entry_id = $_GET['form_entry'];
		endif;

		if ( isset( $_GET['entry_pdf'] ) ):
			$entry_id = $_GET['entry_pdf'];
		endif;

		$entry = '';
		if ( ! is_null( $entry_id ) ):
			$entry     .= "<div class='content-text mt20'><table$table_attr class='table table-bordered table-condensed table-striped'>";
			$submmiter = get_userdata( get_post( $entry_id )->post_author )->display_name;

			if ( $extra_header ):
				$entry .= "$extra_header";
			endif;

			if ( ! is_null( $submmiter ) ):
				$entry .= "<thead$thead_attr><tr><th$td_attr colspan='2'>" . $submmiter . "</th></tr></thead>";
			endif;

			foreach ( $fields as $field ):
				$meta    = get_post_meta( $entry_id, $field['name'], true );
				$content = $meta[1];
				if ( $field['type'] === 'file' ):
					$content = "<a href='" . $meta[1] . "' title='" . $meta[0] . "' target='_blank' class='btn btn-xs btn-outline-secondary'>Download</a>";
				endif;
				if ( ! empty( $meta ) ):
					$entry .= "<tr$tr_attr><td$td_attr>$meta[0]</td><td$td_attr>$content</td></tr>";
				endif;
			endforeach;
			$entry .= "</table></div>";

		else:
			$entry .= "<div class='alert alert-danger text-center'>".__('Information not found!','wpss')."</div>";
		endif;

		if ( $back_btn ):
			$print_url = get_permalink( $this->get_form_id() ) . '?entry_pdf=' . $entry_id;
		    $print = __('Print', 'wpss');
			$entry     .= self::forms_back_to_form( "<a href='$print_url' title='$print' class='btn btn-secondary' target='_blank'>$print</a>" );
		endif;

		return $entry;
	}

	/**
	 * Generate back to form button
	 *
	 * @param string $extra_tags
	 *
	 * @return string
	 */
	public function forms_back_to_form( $extra_tags = '' ) {
		$href   = get_permalink( self::get_form_id() );
		$back = __('Back', 'wpss');
		$button = "<a href='$href' title='$back' class='btn btn-outline-info'>$back</a>";
		$html   = "<div class='mb20 text-center'>$button $extra_tags</div>";

		return $html;
	}

	/**
	 * Make form PDF
	 *
	 * @param $data
	 *
	 * @param bool $attachment
	 *
	 * @param null $data_id
	 *
	 * @return string
	 */
	public function form_make_pdf( $data, $attachment = true, $data_id = null ) {

		$pdf = new \Dompdf\Dompdf();
		$pdf->loadHtml( $data );
		$pdf->setPaper( 'A4', 'portrait' );
		$pdf->render();

		$file_name = md5( current_time( 'Y-m-d H:i:s' ) ) . '.pdf';

		if ( $attachment ):
			$pdf->stream( $file_name, array( "Attachment" => false ) );
			exit;
		endif;

		if ( ! is_null( $data_id ) ):
			$file_name = get_the_title( $data_id ) . '.pdf';
		endif;

		file_put_contents( self::form_get_upload_path() . $file_name, $pdf->output() );

		return self::form_get_upload_path( true ) . $file_name;
	}

	/**
	 * Generate pdf from forms entry
	 */
	public function form_print_pdf() {
		self::form_make_pdf( self::form_get_entry( ' style="border:1px solid #000; border-collapse: collapse; width: 100%; "', ' style="background:#DDD; color: #000;"', '', ' style="padding: 5px; border: 1px solid #000; width: 50%;"', false, "<thead style='background:#DDD; color:#000;'><tr><th colspan='2' style='padding:5px;'>" . get_the_title( $_GET['entry_pdf'] ) . "</th></tr></thead>" ) );
		exit;
	}

	/**
	 * Get all post info without save in DB
	 * @return string
	 */
	public function forms_get_html_posted_data() {
		$post_data = self::get_fields_value();
		$post      = array();
		foreach ( $post_data as $data ):
			$post[] = [ $data['label'], $data['value'] ];
		endforeach;
		$table_style = "style='border-collapse: collapse;'";
		$thead_style = "style='background: #DDD;';";
		$td_style    = "style='padding: 5px; border: 1px solid #000; width: 50%;'";
		$html        = "<table border='1' width='100%' $table_style>";
		$html        .= "<thead $thead_style>";
		$html        .= "<tr>";
		$html        .= "<th $td_style colspan='2'>" . get_the_title( self::get_form_id() ) . "</th>";
		$html        .= "</tr>";

		if ( ! is_null( self::sender_info()['name'] ) ):
			$html .= "<tr>";
			$html .= "<th $td_style colspan='2'>" . self::sender_info()['name'] . "</th>";
			$html .= "</tr>";
		endif;

		$html .= "</thead>";
		foreach ( $post as $item ):
			$html .= "<tr>";
			$html .= "<td $td_style>$item[0]</td><td $td_style>$item[1]</td>";
			$html .= "</tr>";
		endforeach;
		$html .= "</table>";

		return $html;
	}

	/**
	 * Send via whatsapp
	 */
	public function forms_send_whatsapp() {
		$data = '';

		foreach ( self::get_form_fields() as $key => $values ):
			$label = urlencode( $values['label'] );
			$value = ( isset( $_POST[ $values['name'] ] ) ? urlencode( $_POST[ $values['name'] ] ) : ' --- ' );
			$data  .= "*$label:*%20";
			$data  .= "$value";
			$data  .= "%0D%0A%20";
		endforeach;

		$wpp_to = self::get_form_info()['form_wpp'];

		return "https://wa.me/$wpp_to?text=$data";
	}

	/**
	 * Get form sender info
	 * @return array
	 */
	public function sender_info() {
		$user = array(
			'login' => get_userdata( get_current_user_id() )->user_login,
			'mail'  => get_userdata( get_current_user_id() )->user_email,
			'name'  => get_userdata( get_current_user_id() )->display_name,
		);

		return $user;
	}

	/**
	 * Get form headers
	 * @return string
	 */
	public function form_mail_headers() {

		$mail_from = ( ! empty( self::sender_info()['mail'] ) ? self::sender_info()['mail'] : self::get_form_id()['to_mail'] );
		$reply_to  = self::sender_info()['mail'];
		$mail_to   = self::get_form_info()['to_mail'];

		$mail_headers = "Content-Type: text/html; charset=UTF-8\n\r";
		$mail_headers .= "From: $mail_from\n\r";
		$mail_headers .= "Reply-To: $reply_to\n\r";
		$mail_headers .= "Return-Path: $mail_to\n\r";

		return $mail_headers;
	}

	/**
	 * Get mail content
	 * @return string
	 */
	public function form_mail_content() {
		$contents = self::get_fields_value();

		$mail_content = '<table width = "540" border = "0" align = "center" cellpadding = "5" style = "border-collapse:collapse; margin:0 auto;">';
		foreach ( $contents as $content ):
			$label        = $content['label'];
			$value        = $content['value'];
			$mail_content .= '<tr>';
			$mail_content .= "<td style=\"border:1px solid #DDDD; font-weight: bold; padding: 5px;\" width=\"165\" bgcolor=\"#F3F3F3\">$label</td>";
			$mail_content .= "<td style=\"border:1px solid #DDDD; padding: 5px;\" width=\"359\">$value</td>";
			$mail_content .= '</tr>';
		endforeach;
		$mail_content .= '</table>';

		return $mail_content;
	}

	/**
	 * Send form mail
	 */
	public function send_mail_form() {
		$files = self::get_fields_value( true );

		$attachments = array();

		foreach ( $files as $file ):
			$attachments[] = $file['file_dir'];
		endforeach;

		wp_mail( self::get_form_info()['to_mail'], get_the_title( self::get_form_id() ), self::form_mail_content(), self::form_mail_headers(), $attachments );

		self::send_mail_redirect();
	}

	/**
	 * Redirect after finish
	 */
	public function send_mail_redirect() {
		$paramms = '?success';
		$url     = get_permalink( self::get_form_id() ) . $paramms;
		header( "Location: $url", 301 );
	}

	/**
	 * Success Message
	 */
	public function send_mail_success() {
		$message = ( isset( $_GET['success'] ) ? '<div class="row"><div class="col-md-12"><div class="alert alert-success text-center">' . self::get_form_info()['success_msg'] . '</div></div></div>' : false );
		echo $message;
	}

	/**
	 * Get forms IDs
	 */
	public static function get_forms_list() {
		$args  = array(
			'post_type'     => 'forms',
			'post_per_page' => - 1
		);
		$ids   = array();
		$forms = new WP_Query( $args );
		while( $forms->have_posts() ): $forms->the_post();
			$ids[] = get_the_ID();
		endwhile;

		return $ids;
	}

	/**
	 * Get form report permissions
	 * @return array
	 */
	public function forms_get_admins() {
		$args      = array(
			'fields' => 'all'
		);
		$users     = new WP_User_Query( $args );
		$get_users = array();
		if ( ! empty( $users ) ):
			foreach ( $users->get_results() as $user ):
				$get_users[ $user->ID ] = $user->display_name;
			endforeach;
		endif;

		return $get_users;
	}

	/**
	 * Check if current user have permissions to view form reports
	 * @return bool
	 */
	public function check_form_perms() {
		$access     = (array) $this->get_form_info()['form_perms'];
		$add_admins = get_users( array( 'role' => 'administrator' ) );

		foreach ( $add_admins as $key => $user ):
			$new_user = (string) $user->ID;
			if ( ! in_array( $new_user, $access ) ):
				$access[] = $new_user;
			endif;
		endforeach;

		if(!is_user_logged_in()):
            return false;
        endif;

		return in_array( get_current_user_id(), $access );
	}

	/**
	 * Check if form is public or private for logged in users
	 * true = Only logged in users
	 * false = Public Access
	 * @return bool
	 */
	public function check_form_access() {
		$access = self::get_form_info()['form_access'];

		return ( $access === '0' ? true : false );
	}

	/**
	 * Check if form expire
	 *
	 * @param $post_id
	 *
	 * @return bool
	 */
	public function check_form_expire( $post_id ) {
		$meta   = get_post_meta( $post_id, '_form_expiration_op', true );
		$expire = ( $meta === '1' ? true : false );

		return $expire;
	}

	/**
	 * Single Sidebar Widgets
	 */

	public function form_get_desc() {
		return self::get_form_info()['form_desc'];
	}

	public function form_get_date() {
		$date_start = date( 'd-m-Y', self::get_form_info()['data_start'] );
		$end_date   = date( 'd-m-Y', self::get_form_info()['data_end'] );
		$countdown  = date( 'Y/m/d H:i:s', self::get_form_info()['data_end'] );
		$expired_form = __('Form Expired!','wpss');
		$days_form = __('day','wpss');
		$weeks_form = __('week','wpss');
		$widget = "
		<script>
		jQuery(function($) {
		  $('#clock').countdown('$countdown').on( 'update.countdown', function ( event) {
			let format = '%H:%M:%S';
			if ( event . offset . totalDays > 0 ) {
				format = '%-d $days_form%!d ' + format;
			}
			if ( event . offset . weeks > 0 ) {
				format = '%-w $weeks_form%!w ' + format;
			}
			$(this).html( event . strftime( format ) );
		} ). on( 'finish.countdown', function(event) {
			$(this).html( '$expired_form' ).parent().addClass( 'disabled' );

		});
		});
		</script>

		<style>
		.countdown {
		background: #FAFAFA; border:1px solid #DDD; padding:10px; text-align:center; font-size:1.2em; border-radius:5px; font-weight:bold; color:#c6c6c6; text-shadow:1px 1px 0 #FFF;}
		.countdown small {
		font-size:12px;
		display:block;
		text-transform:capitalize;
		color:#929292;}
		</style>";
		$widget .= '<div class="pd10 mt10"><ul class="list-unstyled nomp">';
		$widget .= "<li><strong>".__('Starts on:', 'wpss')."</strong> $date_start</li>";
		$widget .= "<li><strong>".__('Ends in:', 'wpss')."</strong> $end_date</li>";
		$widget .= '</ul>';
		$widget .= '<div class="countdown mt20"><small>'.__('Remaining time', 'wpss').'</small><span id="clock"></span></div>';
		$widget .= '</div>';

		return $widget;
	}

	public function form_entries_widget() {
		$link_url = get_permalink( self::get_form_id() ) . '?form_entries=' . self::get_form_id();
        $report = __('Report', 'wpss');
		echo "<div class = 'pd10'>";
		echo "<a href = '$link_url' title = '$report' class = 'btn btn-outline-secondary btn-block mb10'>$report</a>";
		self::form_get_entries( false );

		echo "</div>";
	}

	/**
	 * Form datatables
	 *
	 * @param $table_id
	 */

	public function form_datatables( $table_id ) {
	    $args = array(
            'print' => true,
            'scroll_x' => true
        );
        wpss_datatables_script("table-$table_id", $args);
	}

	/**
	 * Enqueue front-end scripts
	 */
	public function form_enqueue_front_scripts() {
			wp_enqueue_script( 'forms-count', _WPSS_JS_DIR . 'jquery.countdown.min.js', array( 'jquery' ), '20190430', true );
			wp_enqueue_script( 'forms-paginate', _WPSS_JS_DIR . 'paginating.min.js', array( 'jquery' ), '20190430', true );
			wp_enqueue_script( 'forms-functions', _WPSS_JS_DIR . 'forms-functions.js', array( 'jquery' ), '20190430', true );
	}

}