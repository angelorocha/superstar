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

function wpss_content(){
    global $wpss_custom_singular;
    $wpss_custom_singular = false;

    echo "<section id='wpss-main-section' class='col-md-9 wpss-article-section'>";
    echo "<h4 class='sr-only'>" . __('Content area', 'wpss') . "</h4>";
    do_action('wpss_section_begin');

    if(!$wpss_custom_singular):
        wpss_post_content();
    endif;

    do_action('wpss_section_end');

    echo "</section>";
}