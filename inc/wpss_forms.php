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

$forms                           = new WPSScpt();
$forms->param_cpt_name           = __( 'Form', 'wpss' );
$forms->param_cpt_key            = 'forms';
$forms->param_supports           = array( 'title' );
$forms->param_custom_input       = __( 'Type form title', 'wpss' );
$forms->param_remove_cpt_columns = true;
$forms->param_redirect_archive   = true;

$forms_meta                = new WPSSMetaBox();
$forms_meta->metabox_id    = 'form_options';
$forms_meta->metabox_title = __( 'Form Options', 'wpss' );
$forms_meta->post_type     = 'forms';
$forms_meta->fields        = array(
	array(
		'name'       => __( 'Fields', 'wpss' ),
		'desc'       => __( 'For field types: <br><strong>Radio, Select and Checkbox,</strong> separate the values with commas', 'wpss' ),
		'id'         => '_form_fields',
		'type'       => 'formfield',
		'repeatable' => true,
		'text'       => array(
			'add_row_text' => __( 'Add Field', 'wpss' ),
		),
	),
	array(
		'name'    => __( 'Form Type', 'wpss' ),
		'desc'    => __( 'Select the form type, if is "PDF", data can\'t be saved!', 'wpss' ),
		'id'      => '_forms_type_of_form',
		'type'    => 'radio',
		'default' => '1',
		'options' => array(
			'0' => 'Make PDF',
			'1' => 'Save Data',
			'2' => 'Send to WhatsApp'
		)
	),
	array(
		'name'       => __( 'Send to', 'wpss' ),
		'desc'       => '',
		'id'         => '_form_to_mail',
		'type'       => 'text_email',
		//'text'       => array(
		//	'add_row_text' => 'Adicionar email',
		//),
		'column'     => array(
			'name'     => 'Send to',
			'position' => 2, // Set as the second column.
		),
		'attributes' => array(
			'data-conditional-id'    => '_forms_type_of_form',
			'data-conditional-value' => wp_json_encode( array( '1' ) ),
			'required'               => true,
			'autocomplete'           => 'off',
			'placeholder'            => 'xxxxx@email.com'
		)
	),
	array(
		'name'       => __( 'WhatsApp Number', 'wpss' ),
		'desc'       => __( 'Type your WhatsApp number, don\'t type spaces or special characters.', 'wpss' ),
		'id'         => '_form_whatsapp_number',
		'type'       => 'text',
		'attributes' => array(
			'data-conditional-id'    => '_forms_type_of_form',
			'data-conditional-value' => wp_json_encode( array( '2' ) ),
			'required'               => true,
			'autocomplete'           => 'off',
			'placeholder'            => __( 'DDI+DDD+Phone Number', 'wpss' )
		)
	),
	array(
		'name'    => __( 'Button Text', 'wpss' ),
		'desc'    => '',
		'id'      => '_form_submit_text',
		'default' => __( 'Send', 'wpss' ),
		'type'    => 'text'
	),
	array(
		'name'    => __( 'Expire/Publish form', 'wpss' ),
		'desc'    => '',
		'id'      => '_form_expiration_op',
		'type'    => 'radio_inline',
		'default' => 0,
		'options' => array(
			'0' => __( 'No', 'wpss' ),
			'1' => __( 'Yes', 'wpss' )
		)
	),
	array(
		'name'        => __( 'Publish date', 'wpss' ),
		'desc'        => '',
		'id'          => '_form_start_date',
		'type'        => 'text_datetime_timestamp',
		'date_format' => __( 'd-m-Y', 'wpss' ),
		'time_format' => 'H:i',
		'column'      => array(
			'name'     => __( 'Active in', 'wpss' ),
			'position' => 3, // Set as the second column.
		),
		'attributes'  => array(
			'data-conditional-id'    => '_form_expiration_op',
			'data-conditional-value' => wp_json_encode( array( '1' ) ),
			'required'               => true,
			'autocomplete'           => 'off'
		)
	),
	array(
		'name'        => __( 'Expire in', 'wpss' ),
		'desc'        => '',
		'id'          => '_form_end_date',
		'type'        => 'text_datetime_timestamp',
		'date_format' => 'd-m-Y',
		'time_format' => 'H:i',
		'column'      => array(
			'name'     => __( 'Expired in', 'wpss' ),
			'position' => 4, // Set as the second column.
		),
		'attributes'  => array(
			'data-conditional-id'    => '_form_expiration_op',
			'data-conditional-value' => wp_json_encode( array( '1' ) ),
			'required'               => true,
			'autocomplete'           => 'off'
		)
	),
	array(
		'name'       => __( 'Form Description', 'wpss' ),
		'desc'       => '',
		'id'         => '_form_desc',
		'type'       => 'textarea',
		'attributes' => array(
			'style' => 'width:100%; height: 80px;'
		)
	),
	array(
		'name'       => __( 'After send message', 'wpss' ),
		'desc'       => '',
		'id'         => '_form_send_success',
		'type'       => 'textarea',
		'default'    => __( 'Form successfully submitted', 'wpss' ),
		'attributes' => array(
			'style' => 'width:100%; height: 80px;'
		)
	),
	array(
		'name'    => __( 'Form Access', 'wpss' ),
		'desc'    => '',
		'id'      => '_forms_user_access',
		'type'    => 'radio',
		'default' => '1',
		'options' => array(
			'0' => __( 'Logged in Users', 'wpss' ),
			'1' => __( 'Public', 'wpss' )
		)
	),
	array(
		'name'             => __( 'Form Report Acess', 'wpss' ),
		'desc'             => '',
		'id'               => '_forms_user_admin',
		'type'             => 'select',
		'show_option_none' => true,
		'options_cb'       => array( 'FormFrontEnd', 'forms_get_admins' ),
		'repeatable'       => true,
		'text'             => array(
			'add_row_text' => __( 'Add User', '' ),
		),
	)
);

/**
 * CMB2 Custom Field Type for forms
 */
function jt_cmb2_render_formfield_field_callback( $field, $value, $object_id, $object_type, $field_type ) {

	$value        = wp_parse_args( $value, array(
		'id'       => '',
		'label'    => '',
		'type'     => 'text',
		'options'  => '',
		'size'     => ''
	) );
	$type_options = array(
		'text'     => $field_type->_text( 'formfield_text_field_option_label', 'Texto' ),
		'cpf'      => $field_type->_text( 'formfield_cpf_field_option_label', 'CPF' ),
		'phone'    => $field_type->_text( 'formfield_phone_field_option_label', 'Telefone' ),
		'cep'      => $field_type->_text( 'formfield_cep_field_option_label', 'CEP' ),
		'email'    => $field_type->_text( 'formfield_email_field_option_label', 'Email' ),
		'money'    => $field_type->_text( 'formfield_money_field_option_label', 'Dinheiro' ),
		'date'     => $field_type->_text( 'formfield_date_field_option_label', 'Data' ),
		'select'   => $field_type->_text( 'formfield_select_field_option_label', 'Seleção' ),
		'radio'    => $field_type->_text( 'formfield_radio_field_option_label', 'Radio' ),
		'checkbox' => $field_type->_text( 'formfield_checkbox_field_option_label', 'Checkbox' ),
		'file'     => $field_type->_text( 'formfield_file_field_option_label', 'Arquivo' ),
		'textarea' => $field_type->_text( 'formfield_textarea_field_option_label', 'Area de Texto' ),
	);
	$types        = '';
	foreach ( $type_options as $type => $label ) {
		$selected = selected( $value['type'], $type, false );
		$label    = esc_html( $label );
		$types    .= "<option value=\"{$type}\" {$selected}>{$label}</option>";
	}
	?>
    <table class="form_field_table">
        <tr>
            <td id="form_field_id">
                <label for="<?php echo $field_type->_id( '_id' ); ?>"><?php echo esc_html( $field_type->_text( 'formfield_field_id_label', 'ID do Campo' ) ); ?></label>
				<?php
				echo $field_type->input( array(
					'name'  => $field_type->_name( '[id]' ),
					'id'    => $field_type->_id( '_id' ),
					'value' => $value['id'],
					'desc'  => ''
				) )
				?>
            </td>

            <td>
                <label for="<?php echo $field_type->_id( '_label' ); ?>"><?php echo esc_html( $field_type->_text( 'formfield_field_label_label', 'Legenda' ) ); ?></label>
				<?php
				echo $field_type->input( array(
					'name'  => $field_type->_name( '[label]' ),
					'id'    => $field_type->_id( '_label' ),
					'value' => $value['label'],
					'desc'  => ''
				) )
				?>
            </td>

            <td id="form_field_select">
                <label for="<?php echo $field_type->_id( '_type' ); ?>"><?php echo esc_html( $field_type->_text( 'formfield_field_type_label', 'Tipo' ) ); ?></label>
				<?php
				echo $field_type->select( array(
					'name'    => $field_type->_name( '[type]' ),
					'id'      => $field_type->_id( '_type' ),
					'options' => $types,
					'desc'    => ''
				) )
				?>
            </td>

            <td id="formfield_options" class="hide">
                <label for="<?php echo $field_type->_id( '_options' ); ?>"><?php echo esc_html( $field_type->_text( 'formfield_field_options_label', 'Opções' ) ); ?></label>
				<?php
				echo $field_type->input( array(
					'name'  => $field_type->_name( '[options]' ),
					'id'    => $field_type->_id( '_options' ),
					'value' => $value['options'],
					'desc'  => ''
				) )
				?>
            </td>

            <td id="form_field_size">
                <label for="<?php echo $field_type->_id( '_size' ); ?>"><?php echo esc_html( $field_type->_text( 'formfield_field_size_label', 'Tamanho' ) ); ?></label>
		        <?php
		        echo $field_type->input( array(
			        'name'  => $field_type->_name( '[size]' ),
			        'id'    => $field_type->_id( '_size' ),
			        'value' => $value['size'],
			        'desc'  => ''
		        ) )
		        ?>
            </td>

            <td id="form_field_required">
                <label for="<?php echo $field_type->_id( '_required' ); ?>"><?php echo esc_html( $field_type->_text( 'formfield_field_required_label', 'Required' ) ); ?></label>
		        <?php
		        echo $field_type->checkbox( array(
			        'name'  => $field_type->_name( '[required]' ),
			        'id'    => $field_type->_id( '_required' ),
			        #'value' => $value['required'],
			        'desc'  => ''
		        ) )
		        ?>
            </td>

        </tr>
    </table>
	<?php if ( $field_type->_desc() ) : ?>
        <p class="clear">
			<?php echo $field_type->_desc(); ?>
        </p>
	<?php endif;
}

add_filter( 'cmb2_render_formfield', 'jt_cmb2_render_formfield_field_callback', 10, 5 );
/**
 * The following snippets are required for allowing the formfield field
 * to work as a repeatable field, or in a repeatable group
 */
function jt_cmb2_sanitize_formfield_field( $check, $meta_value, $object_id, $field_args ) {
	// Nothing needed if not array value or not a repeatable field.
	if ( ! is_array( $meta_value ) || empty( $field_args['repeatable'] ) ) {
		return $check;
	}
	foreach ( $meta_value as $key => $val ) {
		$val['type'] = isset( $val['type'] ) ? $val['type'] : 'text';
		if ( 'text' === $val['type'] ) {
			unset( $val['type'] );
			$val = array_filter( $val );
			if ( empty( $val ) ) {
				unset( $meta_value[ $key ] );
				continue;
			} else {
				$val['type'] = 'text';
			}
		}
		$meta_value[ $key ] = array_map( 'sanitize_text_field', $val );
	}

	return $meta_value;
}

add_filter( 'cmb2_sanitize_formfield', 'jt_cmb2_sanitize_formfield_field', 10, 4 );
function jt_cmb2_types_esc_formfield_field( $check, $meta_value, $field_args ) {
	// Nothing needed if not array value or not a repeatable field.
	if ( ! is_array( $meta_value ) || empty( $field_args['repeatable'] ) ) {
		return $check;
	}
	foreach ( $meta_value as $key => $val ) {
		$meta_value[ $key ] = array_map( 'esc_attr', $val );
	}

	return $meta_value;
}

add_filter( 'cmb2_types_esc_formfield', 'jt_cmb2_types_esc_formfield_field', 10, 3 );

/**
 * Form Frontend
 */

function wpss_form_frontend() {
	?>
    <div class="wpss-forms-content">
        <script>
            jQuery(function ($) {
                $('.money').mask('000.000.000.000,00', {reverse: true});
                $('.date').mask('00-00-0000').datepicker({
                    dateFormat: "dd-mm-yy",
                    //maxDate: 'D',
                    monthNames: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
                    dayNames: ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado"],
                    dayNamesMin: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb"],
                });
                $('.cep').mask('00000-000');
                $('.cpf').mask('000.000.000-00');

                $('.phone').each(function () {
                    $(this).on('keypress change', function () {
                        if ($(this).val().length < 14) {
                            $(this).mask('(00) 0000-0000');
                        } else {
                            $(this).mask('(00) 00000-0000');
                        }
                    })
                })
            });
        </script>
		<?php
		$action = '';
		$target = '';
		$form   = new WPSSForms();
		$form->send_mail_success();
		$form->forms_send_whatsapp();
		if ( $form->get_form_info()['form_type'] === '2' ):
			$target = ' target="_blank"';
		endif;
		?>
        <form method="post" action="<?= $action ?>" enctype="multipart/form-data" class="wpss-forms row"<?= $target ?>>
			<?php
			$fields        = get_post_meta( get_the_ID(), '_form_fields', true );
			$submit_action = 'submit-form-' . get_the_ID();
			foreach ( $fields as $key => $val ):
				$field_id       = ( isset( $val['id'] ) ? $val['id'] : '' );
				$field_label    = ( isset( $val['label'] ) ? $val['label'] : '' );
				$field_size     = ( isset( $val['size'] ) ? $val['size'] : '' );
				$field_type     = ( isset( $val['type'] ) ? $val['type'] : '' );
				$field_options  = ( isset( $val['options'] ) ? explode( ',', $val['options'] ) : '' );

				$value          = ( isset( $_POST[ $submit_action ] ) ? ' value="' . $_POST[ $field_id ] . '"' : '' );
				$textarea_value = ( isset( $_POST[ $submit_action ] ) ? $_POST[ $field_id ] : '' );

				$input_text     = "<input type='text' id='$field_id' name='$field_id' class='form-control'$value>";
				$input_email    = "<input type='email' id='$field_id' name='$field_id' class='form-control'$value>";
				$input_date     = "<input type='text' id='$field_id' name='$field_id' class='form-control date'$value>";
				$input_money    = "<input type='text' id='$field_id' name='$field_id' class='form-control money'$value>";
				$input_cpf      = "<input type='text' id='$field_id' name='$field_id' class='form-control cpf'$value>";
				$input_phone    = "<input type='text' id='$field_id' name='$field_id' class='form-control phone'$value>";
				$input_cep      = "<input type='text' id='$field_id' name='$field_id' class='form-control cep'$value>";
				$input_radio    = $field_options;
				$input_checkbox = $field_options;
				$input_select   = $field_options;
				$input_textarea = "<textarea id='$field_id' name='$field_id' class='form-control'>$textarea_value</textarea>";
				$input_file     = "<input type='file' id='$field_id' name='$field_id' class='form-control'>";

				if ( $field_type === 'text' ):
					echo "<div class='grid-item col-md-$field_size'><div class='form-group'><label for='$field_id'>$field_label</label>$input_text</div></div>";
				endif;
				if ( $field_type === 'email' ):
					echo "<div class='grid-item col-md-$field_size'><div class='form-group'><label for='$field_id'>$field_label</label>$input_email</div></div>";
				endif;
				if ( $field_type === 'cpf' ):
					echo "<div class='grid-item col-md-$field_size'><div class='form-group'><label for='$field_id'>$field_label</label>$input_cpf</div></div>";
				endif;
				if ( $field_type === 'phone' ):
					echo "<div class='grid-item col-md-$field_size'><div class='form-group'><label for='$field_id'>$field_label</label>$input_phone</div></div>";
				endif;
				if ( $field_type === 'cep' ):
					echo "<div class='grid-item col-md-$field_size'><div class='form-group'><label for='$field_id'>$field_label</label>$input_cep</div></div>";
				endif;
				if ( $field_type === 'date' ):
					echo "<div class='grid-item col-md-$field_size'><div class='form-group'><label for='$field_id'>$field_label</label>$input_date</div></div>";
				endif;
				if ( $field_type === 'money' ):
					echo "<div class='grid-item col-md-$field_size'><div class='form-group'><label for='$field_id'>$field_label</label>$input_money</div></div>";
				endif;
				if ( $field_type === 'radio' ):
					echo "<div class='grid-item col-md-$field_size'><div class='form-group'><label class='label-option' for='$field_id'>$field_label</label>";
					foreach ( $input_radio as $radio ):
						$radio_checked = ( $_POST[ $field_id ] === $radio ? ' checked' : false );
						echo "<label class='radio-inline'><input type='radio' id='$field_id' name='$field_id' value='$radio'$radio_checked>$radio</label>";
					endforeach;
					echo "</div></div>";
				endif;
				if ( $field_type === 'checkbox' ):
					echo "<div class='grid-item col-md-$field_size'><div class='form-group'><label class='label-option' for='$field_id'>$field_label</label>";
					foreach ( $input_checkbox as $check ):
						$checked = null;
						if ( isset( $_POST[ $submit_action ] ) ):
							$checked = ( in_array( $check, $_POST[ $field_id ] ) ? ' checked' : false );
						endif;
						echo '<label class="checkbox-inline"><input type="checkbox" id="' . $field_id . '" name="' . $field_id . '[]" value="' . $check . '"' . $checked . '>' . $check . '</label>';
					endforeach;
					echo "</div></div>";
				endif;
				if ( $field_type === 'select' ):
					echo "<div class='grid-item col-md-$field_size'><div class='form-group'><label for='$field_id'>$field_label</label>";
					echo "<select name='$field_id' id='$field_id' class='form-control'><option value=''>----------</option>";
					foreach ( $input_select as $options ):
						$selected = ( $_POST[ $field_id ] === $options ? ' selected' : false );
						echo "<option value='$options'$selected>$options</option>";
					endforeach;
					echo "</select></div></div>";
				endif;
				if ( $field_type === 'textarea' ):
					echo "<div class='grid-item col-md-$field_size'><div class='form-group'><label for='$field_id'>$field_label</label>$input_textarea</div></div>";
				endif;
				if ( $field_type === 'file' ):
					echo "<div class='grid-item col-md-$field_size'><div class='form-group'><label for='$field_id'>$field_label</label>$input_file</div></div>";
				endif;

			endforeach;
			?>

            <div class="grid-item col-md-12 text-center">
                <hr>
                <button class="btn btn-outline-secondary" type="submit" name="<?php echo $submit_action; ?>">
					<?php echo get_post_meta( get_the_ID(), '_form_submit_text', true ) ?>
                </button>
            </div>
        </form>
    </div>
	<?php
}