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

add_filter( 'cmb2_render_filename', 'wpss_cmb2_render_filename_field_callback', 10, 5 );
function wpss_cmb2_render_filename_field_callback( $field, $value, $object_id, $object_type, $field_type ) {
	$value = wp_parse_args( $value, array(
		'file_name' => '',
		'file_url'  => ''
	) );
	?>
    <style>
        .wpss-filename-field{display:flex; align-items:self-start; max-width:100%;}
        .wpss-filename-field li{display:inline-block; padding:5px;}
        .wpss-filename-field li label{display:block;}
        .wpss-filename-field li input{}
    </style>
    <ul class="wpss-filename-field">
        <li>
            <label for="<?php echo $field_type->_id( '_file_name' ); ?>">
				<?php echo esc_html( $field_type->_text( 'filename_field_id_label', 'Título' ) ); ?>
            </label>
			<?php
			echo $field_type->input( array(
				'name'  => $field_type->_name( '[file_name]' ),
				'id'    => $field_type->_id( '_file_name' ),
				'value' => $value['file_name'],
				'desc'  => ''
			) );
			?>
        </li>

        <li>
            <label for="<?php echo $field_type->_id( '_file_url' ); ?>">
				<?php echo esc_html( $field_type->_text( 'filename_field_id_label', 'URL/Arquivo' ) ); ?>
            </label>
			<?php
			echo $field_type->file( array(
				'name'       => $field_type->_name( '[file_url]' ),
				'id'         => $field_type->_id( '_file_url' ),
				'value'      => $value['file_url'],
				'desc'       => ''
			) );
			?>
        </li>
    </ul>

	<?php if ( $field_type->_desc() ) : ?>
        <p class="clear">
			<?php echo $field_type->_desc(); ?>
        </p>
	<?php endif;
}

add_filter( 'cmb2_sanitize_filename', 'wpss_cmb2_sanitize_filename_field', 10, 4 );
function wpss_cmb2_sanitize_filename_field( $check, $meta_value, $object_id, $field_args ) {
	if ( ! is_array( $meta_value ) || empty( $field_args['repeatable'] ) ):
		return $check;
	endif;

	foreach ( $meta_value as $key => $val ):
		$meta_value[ $key ] = array_map( 'sanitize_text_field', $val );
	endforeach;

	return $meta_value;
}

add_filter( 'cmb2_types_esc_filename', 'wpss_cmb2_esc_filename_field', 10, 3 );
function wpss_cmb2_esc_filename_field( $check, $meta_value, $field_args ) {
	if ( ! is_array( $meta_value ) || empty( $field_args['repeatable'] ) ):
		return $check;
	endif;

	foreach ( $meta_value as $key => $val ):
		$meta_value[ $key ] = array_map( 'esc_attr', $val );
	endforeach;

	return $meta_value;
}