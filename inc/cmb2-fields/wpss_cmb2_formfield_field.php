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

/**
 * CMB2 Custom Field Type for forms
 */
function wpss_cmb2_render_formfield_field_callback( $field, $value, $object_id, $object_type, $field_type ) {

    $value          = wp_parse_args( $value, array(
        'id'        => '',
        'label'     => '',
        'type'      => 'text',
        'options'   => '',
        'size'      => '',
        'required'  => 'on'
    ) );
    $type_options = array(
        'text'     => $field_type->_text( 'formfield_text_field_option_label', __('Text' ,'wpss')),
        'cpf'      => $field_type->_text( 'formfield_cpf_field_option_label', __('CPF' ,'wpss')),
        'phone'    => $field_type->_text( 'formfield_phone_field_option_label', __('Phone' ,'wpss')),
        'cep'      => $field_type->_text( 'formfield_zipcode_field_option_label', __('Zipcode' ,'wpss')),
        'email'    => $field_type->_text( 'formfield_email_field_option_label', __('Email' ,'wpss')),
        'money'    => $field_type->_text( 'formfield_money_field_option_label', __('Money' ,'wpss')),
        'date'     => $field_type->_text( 'formfield_date_field_option_label', __('Date' ,'wpss')),
        'select'   => $field_type->_text( 'formfield_select_field_option_label', __('Select' ,'wpss')),
        'radio'    => $field_type->_text( 'formfield_radio_field_option_label', __('Radio' ,'wpss')),
        'checkbox' => $field_type->_text( 'formfield_checkbox_field_option_label', __('Checkbox' ,'wpss')),
        'file'     => $field_type->_text( 'formfield_file_field_option_label', __('File' ,'wpss')),
        'textarea' => $field_type->_text( 'formfield_textarea_field_option_label', __('Textarea' ,'wpss')),
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
                <label for="<?php echo $field_type->_id( '_id' ); ?>"><?php echo esc_html( $field_type->_text( 'formfield_field_id_label', __('ID','wpss') ) ); ?></label>
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
                <label for="<?php echo $field_type->_id( '_label' ); ?>"><?php echo esc_html( $field_type->_text( 'formfield_field_label_label', __('Label','wpss') ) ); ?></label>
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
                <label for="<?php echo $field_type->_id( '_type' ); ?>"><?php echo esc_html( $field_type->_text( 'formfield_field_type_label', __('Type','wpss') ) ); ?></label>
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
                <label for="<?php echo $field_type->_id( '_options' ); ?>"><?php echo esc_html( $field_type->_text( 'formfield_field_options_label', __('Options','wpss') ) ); ?></label>
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
                <label for="<?php echo $field_type->_id( '_size' ); ?>"><?php echo esc_html( $field_type->_text( 'formfield_field_size_label', __('Size','wpss') ) ); ?></label>
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
                <label for="<?php echo $field_type->_id( '_required' ); ?>"><?php echo esc_html( $field_type->_text( 'formfield_field_required_label', __('Required','wpss') ) ); ?></label>
                <?php
                echo $field_type->checkbox(array(
                    'name'  => $field_type->_name( '[required]' ),
                    'id'    => $field_type->_id( '_required' ),
                    'value' => $value['required'],
                    'desc'  => ''
                ));
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

add_filter( 'cmb2_render_formfield', 'wpss_cmb2_render_formfield_field_callback', 10, 5 );
/**
 * The following snippets are required for allowing the formfield field
 * to work as a repeatable field, or in a repeatable group
 */
function wpss_cmb2_sanitize_formfield_field( $check, $meta_value, $object_id, $field_args ) {
    // Nothing needed if not array value or not a repeatable field.

    if ( ! is_array( $meta_value ) || empty( $field_args['repeatable'] ) ):
        return $check;
    endif;

    foreach ( $meta_value as $key => $val ):
        $val['type'] = isset( $val['type'] ) ? $val['type'] : 'text';
        if ( 'text' === $val['type'] ):
            unset( $val['type'] );
            $val = array_filter( $val );
            if ( empty( $val ) ):
                unset( $meta_value[ $key ] );
                continue;
            else:
                $val['type'] = 'text';
            endif;
        endif;

        if($val['id']):
            $val['id'] = sanitize_title($val['id']);
        endif;

        $meta_value[ $key ] = array_map( 'sanitize_text_field', $val );
    endforeach;

    return $meta_value;
}

add_filter( 'cmb2_sanitize_formfield', 'wpss_cmb2_sanitize_formfield_field', 10, 4 );

function wpss_cmb2_types_esc_formfield_field( $check, $meta_value, $field_args ) {
    // Nothing needed if not array value or not a repeatable field.
    if ( ! is_array( $meta_value ) || empty( $field_args['repeatable'] ) ) {
        return $check;
    }
    foreach ( $meta_value as $key => $val ) {
        $meta_value[ $key ] = array_map( 'esc_attr', $val );
    }

    return $meta_value;
}

add_filter( 'cmb2_types_esc_formfield', 'wpss_cmb2_types_esc_formfield_field', 10, 3 );