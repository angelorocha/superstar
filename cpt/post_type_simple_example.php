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

add_action( 'init', 'wpss_simple_example_cpt' );
function wpss_simple_example_cpt() {
    /**
     * Main post type
     */
    $example                           = new WPSScpt();                             // Instance of CPT
    $example->param_cpt_key            = 'simple_cpt';                              // Post type key
    $example->param_cpt_name           = 'Simple CPT';                              // Post type name
    $example->param_cpt_new            = 'Page';                                    // Name to label "new item"
    $example->param_cpt_all            = 'Pages';                                   // Label to all CPT items
    $example->param_menu_position      = 5;                                         // Post type position
    $example->param_cpt_hierarchical   = true;                                      // Add support "Attributes", like a 'page' cpt
    $example->param_supports           = array( 'title', 'editor', 'thumbnail' );   // Post type supports
    $example->param_custom_input       = 'Type your page title';                    // Custom input title placeholder
    $example->param_add_cap            = array( 'administrator' );                  // Add cap to roles, accept array

    $example->wpss_make_cpt();                                                      // Make new post type
    $example->wpss_flush_rewrite_rules();                                           // Flush rewrite rules to show new cpt
}