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
 * Post type definition
 */
add_action( 'init', 'wpss_custom_forms' );
function wpss_custom_forms(){
    $forms                           = new WPSScpt();                            // Instance of CPT
    $forms->param_cpt_key            = 'wpss_form';                              // Post type key
    $forms->param_cpt_name           = __('Forms', 'wpss');                      // Post type name
    $forms->param_cpt_new            = __('Form', 'wpss');                       // Name to label "new item"
    $forms->param_cpt_all            = __('Forms', 'wpss');                      // Label to all CPT items
    $forms->param_menu_position      = 5;                                        // Post type position
    $forms->param_show_in_menu       = '';                                       // Show in menu, to cpt group add a string 'edit.php?post_type=[_post_type_key]'
    $forms->param_cpt_hierarchical   = false;                                    // Add support "Attributes", like a 'page' cpt
    $forms->param_supports           = array( 'title' );                         // Post type supports
    $forms->param_custom_input       = __('Type form name', 'wpss');             // Custom input title placeholder
    $forms->param_taxonomies         = null;                                     // Post type taxonomies, accept array
    $forms->param_rewrite            = '';                                       // Add a custom rewrite url
    $forms->param_redirect_archive   = true;                                     // Redirect archive templates to site home, accept string to redirect in internal pages or archive pages.
    $forms->param_redirect_single    = false;                                    // Redirect single templates to site home, accept string to redirect in internal pages or archive pages.
    $forms->param_menu_icon          = 'dashicons-email-alt';                    // Custom admin menu icon
    $forms->param_remove_cpt_columns = true;                                     // If is "true ", remove post type default columns show only title column, accept array to remove specific columns
    $forms->param_add_cap            = array( 'administrator' );                 // Add cap to roles, accept array
    $forms->param_remove_cap         = '';                                       // Remove cap from role, accept array
    $forms->param_custom_cpt_js      = false;                                    // Add custom js to edit/new cpt screen, accept array
    $forms->param_custom_cpt_css     = false;                                    // Add custom css to edit/new cpt screen, accept array

    //$screens                         = array();
    //$forms->param_cpt_custom_menu    = $screens;                                 // Set post type archives to attach custom menu
    //$forms->param_cpt_custom_sidebar = $screens;                                 // Set post type archives to attach custom sidebar
    //$forms->param_cpt_contact_form   = false;                                    // Add post type contact form support
    //$forms->param_cpt_admin_notice   = false;                                    // Add a custom notice on post type admin page.

    $forms->wpss_make_cpt();                                                     // Make new post type
    $forms->wpss_flush_rewrite_rules();

    /*** Define custom post type metaboxes */
    $forms_meta                = new WPSSMetaBox();
    $forms_meta->metabox_id    = 'wpss_form_options';
    $forms_meta->metabox_title = __( 'Form Options', 'wpss' );
    $forms_meta->post_type     = 'wpss_form';
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
}