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
            echo __("<h3>Data</h3>", "wpss");
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