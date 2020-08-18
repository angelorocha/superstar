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

final class WPSSCptContact {

	public $param_cpt;
	public $param_cap;

	public function __construct() {

	}

	public function wpss_cpt_contact_init() {
		self::wpss_cpt_form_embed();
		self::wpss_cpt_contact_admin();
	}

	/**
	 * Contact form message headers
	 */
	public function wpss_cpt_contact_headers() {
		$sender  = self::wpss_cpt_form_data()['mail'];
		$headers = "Content-Type: text/html; charset=UTF-8\n";
		$headers .= "From: $sender\n";
		$headers .= "Reply-to: $sender\n";
		$headers .= "Return-Path: $sender\n";

		return $headers;
	}

	/**
	 * Contact form frontend
	 */
	public function wpss_cpt_contact_form() {
		if ( self::wpss_cpt_get_form_single_id() == get_the_ID() ):
			$disabled = ( self::wpss_cpt_to_mail() ? '' : ' disabled="disabled"' );
			?>
            <form method="post" action="<?= get_permalink(); ?>">
                <div class="row">
                    <div class="col-md-6 mb-1">
                        <label for="<?= $this->param_cpt; ?>_name">Nome:</label>
                        <input class="form-control" type="text" name="<?= $this->param_cpt; ?>_name" id="<?= $this->param_cpt; ?>_name" value="">
                    </div>

                    <div class="col-md-6 mb-1">
                        <label for="<?= $this->param_cpt; ?>_mail">Email:</label>
                        <input class="form-control" type="text" name="<?= $this->param_cpt; ?>_mail" id="<?= $this->param_cpt; ?>_mail" value="">
                    </div>

                    <div class="col-md-12 mb-1">
                        <label for="<?= $this->param_cpt; ?>_subject">Assunto:</label>
                        <input class="form-control" type="text" name="<?= $this->param_cpt; ?>_subject" id="<?= $this->param_cpt; ?>_subject" value="">
                    </div>

                    <div class="col-md-12 mb-1">
                        <label for="<?= $this->param_cpt; ?>_message">Mensagem:</label>
                        <textarea class="form-control" name="<?= $this->param_cpt; ?>_message" id="<?= $this->param_cpt; ?>_message"></textarea>
                    </div>

                    <div class="col-md-12 text-center mt-2">
                        <input class="btn btn-outline-dark" type="submit" name="<?= $this->param_cpt; ?>_submit" value="Enviar"<?= $disabled ?>>
                    </div>

                </div>
            </form>
			<?php
			if ( ! self::wpss_cpt_to_mail() ):
                echo "<div class='text-center alert alert-warning mt-3'><h6>O email do destinatário desta seção não foi preenchido, entre em contato com o setor responsável por telefone para informar este ocorrido.</h6></div>";
			else:
				self::wpss_cpt_contact_send();
			endif;

		endif;
	}

	/**
	 * Embed form inside selected page
	 */
	public function wpss_cpt_form_embed() {
		if ( self::wpss_cpt_get_form_single_id() ):
			add_filter( 'wpss_inside_content_end', array( $this, 'wpss_cpt_contact_form' ) );
		endif;
	}

	public function wpss_cpt_get_form_single_id() {
		return self::wpss_get_option( 'wpss_admin_contact_page' );
	}

	/**
	 * Get form data
	 * @return array
	 */
	public function wpss_cpt_form_data() {
		$form_data = array(
			'name'    => ( isset( $_POST[ $this->param_cpt . '_name' ] ) ? $_POST[ $this->param_cpt . '_name' ] : '' ),
			'mail'    => ( isset( $_POST[ $this->param_cpt . '_mail' ] ) ? $_POST[ $this->param_cpt . '_mail' ] : '' ),
			'subject' => ( isset( $_POST[ $this->param_cpt . '_subject' ] ) ? $_POST[ $this->param_cpt . '_subject' ] : '' ),
			'message' => ( isset( $_POST[ $this->param_cpt . '_message' ] ) ? $_POST[ $this->param_cpt . '_message' ] : '' ),
			'submit'  => ( isset( $_POST[ $this->param_cpt . '_submit' ] ) ? true : false ),
		);

		return $form_data;
	}

	/**
	 * Check for empty fields
	 */
	public function wpss_cpt_form_validation() {
		foreach ( self::wpss_cpt_form_data() as $key => $val ):
			if ( empty( $val ) ):
				return false;
			endif;
		endforeach;

		return true;
	}

	/**
	 * Check form inputs error message
	 */
	public function wpss_cpt_form_check_message() {
		$message = "<div class='alert alert-warning text-center mt-2 mb-2'>";
		$message .= "<h4>Email não enviado!</h4>";
		$message .= "<p>Verifique o formulário, todos os campos são obrigatórios.</p>";
		$message .= "</div>";

		return $message;
	}

	/**
	 * Check "to" mail
	 */
	public function wpss_cpt_to_mail() {
		$to_mail = self::wpss_get_option( 'wpss_cpt_contact_mails' );

		return ( $to_mail ? $to_mail : false );
	}

	/**
	 * Contact form send action
	 */
	public function wpss_cpt_contact_send() {

		if ( self::wpss_cpt_form_data()['submit'] ):

			if ( ! $this->wpss_cpt_form_validation() ):
				echo self::wpss_cpt_form_check_message();
			else:
				wp_mail(
					self::wpss_get_option( 'wpss_cpt_contact_mails' ),
					self::wpss_cpt_form_data()['subject'],
					self::wpss_cpt_form_data()['message'],
					self::wpss_cpt_contact_headers()
				);
				echo self::wpss_cpt_contact_success_message();
			endif;
		endif;

	}

	/**
	 * Success message
	 */
	public function wpss_cpt_contact_success_message() {
		$message = "<div class='alert alert-success text-center mb-2 mt-2'>";
		$message .= "<h4>Email Enviado</h4>";
		$message .= "<p>Entraremos em contato em breve</p>";
		$message .= "</div>";

		return $message;
	}

	/**
	 * Contact form page options
	 */
	public function wpss_cpt_contact_admin() {
		$config                = new WPSSMetaBox();
		$config->metabox_id    = 'wpss_' . $this->param_cpt . '_contact_options_metabox';
		$config->metabox_title = 'Contato';
		$config->option_key    = 'wpss-' . $this->param_cpt . '-contact-options';
		$config->post_type     = array( 'options-page' );
		$config->parent_slug   = "edit.php?post_type=$this->param_cpt";
		$config->capability    = get_post_type_object( $this->param_cpt )->cap->edit_post;
		$config->fields        = array(
			array(
				'name' => 'Opções do formulário de contato',
				'desc' => '',
				'id'   => '_op_head_1',
				'type' => 'title',
			),

			array(
				'name'       => 'Emails',
				'desc'       => 'Selecione os emails para recebimento do formulário.',
				'id'         => 'wpss_cpt_contact_mails',
				'type'       => 'text_email',
				'repeatable' => true,
				'text'       => array(
					'add_row_text' => 'Adicionar Email',
				),
			),

			array(
				'name'             => 'Selecionar Página',
				'id'               => 'wpss_admin_contact_page',
				'desc'             => 'Selecione a página em que o formulário será exibido.',
				'type'             => 'select',
				'show_option_none' => true,
				'options_cb'       => array( $this, 'wpss_admin_contact_select_cb' ),
			),

		);
	}

	/**
	 * Contact get option
	 *
	 * @param $op
	 *
	 * @return array|bool|mixed|void
	 */
	public function wpss_get_option( $op ) {
		return WPSSMetaBox::wpss_option( $op, 'wpss-' . $this->param_cpt . '-contact-options' );
	}

	/**
	 * Contact form page select callback
	 * @return array
	 */
	public function wpss_admin_contact_select_cb() {
		return WPSSquery::wpss_get_post_ids( $this->param_cpt );
	}
}