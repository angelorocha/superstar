<?php
/**
 * @param $element
 * @param $mask
 * @param bool $reverse
 * @link                https://angelorocha.com.br
 * @copyleft            2020
 * @license             GNU GPL 3 (https://www.gnu.org/licenses/gpl-3.0.html)
 * @package WordPress
 * @subpackage superstar
 * @since 1.0.0
 * @author              Angelo Rocha
 * @author              Angelo Rocha <contato@angelorocha.com.br>
 *
 * Usage:
 * wpss_datatables_script(
 * 'table',                                                // The table id without "#"
 * array(                                                  // Array options
 * 'scroll_x'           => 'true',                     // Define true to scroll X
 * 'order'              => array( 1, "ASC" ),          // Select column order by and ASC or DESC to order
 * 'table_size'         => array( array(),array() ),   // Select table limit
 * 'column'             => array( 100, 100, 250 ),     // Default columns definition
 * 'search_label'       => 'Pesquisar',                // Default search label
 * 'search_placeholder' => 'Buscar na tabela',         // Default input search placeholder
 * 'print'              => true                        // Enable print buttons
 * )
 * );
 *
 */

function wpss_mask($element, $mask, $reverse = false){
    if(!wp_script_is('wpss-masks', 'enqueued ')):
        wp_enqueue_script('wpss-masks', _WPSS_JS_DIR . 'jquery.mask.min.js', array('jquery'), _WPSS_FILE_VERSION, true);
    endif;
    $reverse = ($reverse ? ', {reverse: true}' : '');
    ?>
    <script>
        jQuery(function ($) {
            $('<?=$element;?>').mask('<?=$mask . "'" . $reverse?>);
        });
    </script>
    <?php
}