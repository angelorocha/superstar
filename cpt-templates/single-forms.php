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

$forms = new WPSSForms();

if(isset($_POST['submit-form-' . $forms->get_form_id()])):

    if($forms->get_form_info()['form_type'] === '0'):
        $forms->form_make_pdf($forms->forms_get_html_posted_data());
        exit;
    endif;

    if($forms->get_form_info()['form_type'] === '1'):
        $forms->send_mail_form();
        $forms->form_insert_post();
    endif;

    if($forms->get_form_info()['form_type'] === '2'):
        wp_redirect($forms->forms_send_whatsapp(), 301);
        exit;
    endif;

endif;

if(isset($_GET['entry_pdf'])):
    $forms->form_print_pdf();
endif;

$view_form = ($forms->check_form_access() ? is_user_logged_in() : true);

if($view_form):
    if(!isset($_GET['form_entries']) && !isset($_GET['form_entry'])):
        wpss_form_frontend();
    endif;

    if(current_user_can('administrator') || $forms->check_form_perms()):
        if(isset($_GET['form_entries'])):
            $forms->form_get_entries();
        endif;
        if(isset($_GET['form_entry'])):
            $entry = $forms->form_get_entry();
            echo $entry;
        endif;
    endif;
else:
    echo '<div class="alert alert-danger text-center">';
    _e('<h3>You can not access this form!</h3>', 'wpss');

    $login_url = wp_login_url(get_permalink($forms->get_form_id()));
    sprintf(__('<a href="%s" title="Login" class="btn btn-outline-danger">Login</a>', 'wpss'), $login_url);
    echo '</div>';
endif;

echo "<aside class='widget'>";
echo __("<h3>Form Info</h3>", "wpss");
echo "<div>";
echo $forms->form_get_desc();
echo "</div>";
echo "</aside>";

if($forms->check_form_expire(get_queried_object_id())):
    echo "<aside class='widget'>";
    echo __("<h3>Expire</h3>", 'wpss');
    echo "<div>";
    echo $forms->form_get_date();
    echo "</div>";
    echo "</aside>";
endif;

if($forms->check_form_perms()):
    echo "<aside class='widget'>";
    echo __("<h3>Data</h3>", "wpss");
    echo "<div>";
    $forms->form_entries_widget();
    echo "</div>";
    echo "</aside>";
endif;