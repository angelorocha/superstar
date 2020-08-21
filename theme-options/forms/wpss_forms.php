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
                '0' => __('Make PDF', 'wpss'),
                '1' => __('Send Mail', 'wpss'),
                '2' => __('Send to WhatsApp', 'wpss'),
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
            'name'             => __( 'Form Report Access', 'wpss' ),
            'desc'             => '',
            'id'               => '_forms_user_admin',
            'type'             => 'select',
            'show_option_none' => true,
            'options_cb'       => array( 'FormFrontEnd', 'forms_get_admins' ),
            'repeatable'       => true,
            'text'             => array(
                'add_row_text' => __( 'Add User', 'wpss' ),
            ),
        )
    );
}

/**
 * Form Instance
 * @return WPSSForms
 */
function wpss_form_instance(){
    return new WPSSForms();
}

/**
 * Insert form actions
 */

add_action('wpss_before_head', 'wpss_form_actions');
function wpss_form_actions(){
    if(is_singular('wpss_form')):
        if(isset($_POST['submit-form-' . wpss_form_instance()->get_form_id()])):
            if(wpss_form_instance()->get_form_info()['form_type'] === '0'):
                wpss_form_instance()->form_make_pdf(wpss_form_instance()->forms_get_html_posted_data());
                exit;
            endif;
            if(wpss_form_instance()->get_form_info()['form_type'] === '1'):
                wpss_form_instance()->send_mail_form();
                wpss_form_instance()->form_insert_post();
            endif;
            if(wpss_form_instance()->get_form_info()['form_type'] === '2'):
                wp_redirect(wpss_form_instance()->forms_send_whatsapp(), 301);
                exit;
            endif;
        endif;
        if(isset($_GET['entry_pdf'])):
            wpss_form_instance()->form_print_pdf();
        endif;
    endif;

    if(isset($_GET['entry_pdf'])):
        wpss_form_instance()->form_print_pdf();
    endif;
}

/**
 * Embed form on singular page
 */
add_action( 'wpss_inside_content_begin', 'wpss_form_embed' );
function wpss_form_embed(){
    $view_form = ( wpss_form_instance()->check_form_access() ? is_user_logged_in() : true);

    if($view_form):
        wpss_form_instance()->form_enqueue_front_scripts();
        if(!isset($_GET['form_entries']) && !isset($_GET['form_entry'])):
            wpss_form_frontend();
        endif;

        if(current_user_can('administrator') ||  wpss_form_instance()->check_form_perms()):
            if(isset($_GET['form_entries'])):
                wpss_form_instance()->form_get_entries();
            endif;
            if(isset($_GET['form_entry'])):
                $entry =  wpss_form_instance()->form_get_entry();
                echo $entry;
            endif;
        endif;
    else:
        echo '<div class="alert alert-danger text-center">';
        _e('<h3>You can not access this form!</h3>', 'wpss');

        $login_url = wp_login_url(get_permalink( wpss_form_instance()->get_form_id()));
        sprintf(__('<a href="%s" title="Login" class="btn btn-outline-danger">Login</a>', 'wpss'), $login_url);
        echo '</div>';
    endif;
}

/**
 * Embed form extra data in sidebar
 */
add_action( 'wpss_sidebar_before', 'wpss_form_sidebar' );
function wpss_form_sidebar(){
    if(is_singular('wpss_form')):
        global $wpss_custom_sidebar;
        $wpss_custom_sidebar = true;

        echo "<aside class='widget'>";
        echo __("<h3>Form Info</h3>", "wpss");
        echo "<div>";
        echo wpss_form_instance()->form_get_desc();
        echo "</div>";
        echo "</aside>";

        if(wpss_form_instance()->check_form_expire(get_queried_object_id())):
            echo "<aside class='widget'>";
            echo __("<h3>Expire</h3>", 'wpss');
            echo "<div>";
            echo wpss_form_instance()->form_get_date();
            echo "</div>";
            echo "</aside>";
        endif;

        if(wpss_form_instance()->check_form_perms()):
            echo "<aside class='widget'>";
            echo __("<h3>Form Data</h3>", "wpss");
            echo "<div>";
            wpss_form_instance()->form_entries_widget();
            echo "</div>";
            echo "</aside>";
        endif;
    endif;

}

/**
 * Form front end
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
        wpss_form_instance()->form_enqueue_front_scripts();
        wpss_form_instance()->send_mail_success();
        wpss_form_instance()->forms_send_whatsapp();
        if ( wpss_form_instance()->get_form_info()['form_type'] === '2' ):
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
                $field_required = ( $val['required'] === 'on' ? ' required="required"' : '' );
                $field_options  = ( isset( $val['options'] ) ? explode( ',', $val['options'] ) : '' );

                $value          = ( isset( $_POST[ $submit_action ] ) ? ' value="' . $_POST[ $field_id ] . '"' : '' );
                $textarea_value = ( isset( $_POST[ $submit_action ] ) ? $_POST[ $field_id ] : '' );

                $input_text     = "<input type='text' id='$field_id' name='$field_id'$field_required class='form-control'$value>";
                $input_email    = "<input type='email' id='$field_id' name='$field_id'$field_required class='form-control'$value>";
                $input_date     = "<input type='text' id='$field_id' name='$field_id'$field_required class='form-control date' autocomplete='off'$value>";
                $input_money    = "<input type='text' id='$field_id' name='$field_id'$field_required class='form-control money'$value>";
                $input_cpf      = "<input type='text' id='$field_id' name='$field_id'$field_required class='form-control cpf'$value>";
                $input_phone    = "<input type='text' id='$field_id' name='$field_id'$field_required class='form-control phone'$value>";
                $input_cep      = "<input type='text' id='$field_id' name='$field_id'$field_required class='form-control cep'$value>";
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

/**
 * Expire form
 */
function wpss_form_expire(){
    global $wpdb;
    $query = "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_form_expiration_op' AND meta_value = '1'";
    $get_forms = $wpdb->get_results($query, ARRAY_A);
    foreach($get_forms as $form):
        $expire_date = get_post_meta($form['post_id'], '_form_end_date', true);
        $current_time = strtotime(current_time('Y-m-d H:i'));

        if($expire_date <= $current_time):
            wp_update_post(array(
                    'ID'          => $form['post_id'],
                    'post_status' => 'draft'
                ));
        endif;
    endforeach;
}
add_action( 'wpss_expire_form_hook', 'wpss_form_expire' );
if ( ! wp_next_scheduled( 'wpss_expire_form_hook' ) ) {
    wp_schedule_event( time(), 'minute', 'wpss_expire_form_hook' );
}