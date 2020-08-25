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

if(!class_exists('Redux')){
    return;
}

$opt_name = 'wpss_option';

$theme = wp_get_theme();

$args = array(
    'display_name'       => $theme->get('Name'),
    'display_version'    => $theme->get('Version'),
    'menu_icon'          => 'dashicons-star-filled',
    'menu_type'          => 'menu',
    'menu_title'         => esc_html__('Theme Options', 'wpss'),
    //'page_parent'     => 'themes.php',
    'page_priority'      => 9,
    'page_permissions'   => 'manage_options',
    'page_slug'          => '_wpss_options_page',
    'page_title'         => 'Theme Options Page',
    'show_import_export' => true,
    'customizer'         => true,
    'dev_mode'           => false,
    'footer_text'        => '',
);

$args['share_icons'][] = array(
    'url'   => 'https://www.instagram.com/angelorocha.wp/',
    'title' => 'Follow us on Instagram',
    'icon'  => 'el el-instagram'
);
$args['share_icons'][] = array(
    'url'   => 'https://www.facebook.com/angelorochawp/',
    'title' => 'Follow us on Facebook',
    'icon'  => 'el el-facebook'
);
$args['share_icons'][] = array(
    'url'   => 'https://br.linkedin.com/in/angelorocha',
    'title' => 'Follow us on Linkedin',
    'icon'  => 'el el-linkedin'
);
$args['share_icons'][] = array(
    'url'   => 'https://www.youtube.com/user/softwarelivretv',
    'title' => 'Follow us on YouTube',
    'icon'  => 'el el-youtube'
);
$args['share_icons'][] = array(
    'url'   => 'https://github.com/angelorocha/superstar',
    'title' => 'Github',
    'icon'  => 'el el-github'
);

Redux::setArgs($opt_name, $args);

/*** Header options */
Redux::setSection($opt_name, array(
    'title'  => esc_html__('Header', 'wpss'),
    'id'     => 'header_options',
    'desc'   => esc_html__('Theme header options.', 'wpss'),
    'icon'   => 'el el-home',
    'fields' => array(
        array(
            'id'       => 'wpss-header-logo',
            'type'     => 'media',
            'title'    => esc_html__('Site Logo', 'wpss'),
            'desc'     => esc_html__('Define your site logo.', 'wpss'),
            'subtitle' => esc_html__('Upload your logo file.', 'wpss'),
            //'hint'     => array(
            //    'content' => '',
            //)
        )
    )
));

/*** Custom Javascript */
Redux::setSection($opt_name, array(
    'title'  => esc_html__('Custom Scripts', 'wpss'),
    'id'     => 'custom_scripts',
    'desc'   => esc_html__('Insert your custom scripts here.', 'wpss'),
    'icon'   => 'el el-quote-alt',
    'fields' => array(
        array(
            'id'       => 'wpss_header_script',
            'type'     => 'ace_editor',
            'title'    => __('Header Javascript Code', 'wpss'),
            'subtitle' => __('Paste your javascript code here.', 'wpss'),
            'mode'     => 'javascript',
            'theme'    => 'monokai',
            'desc'     => esc_html__('Your script will be inserted immediately before the </head> tag', 'wpss'),
            'options'  => array(
                'minLines' => 30,
            ),
            'default'  => "//$('#your-script-here');"
        ),
        array(
            'id'       => 'wpss_footer_script',
            'type'     => 'ace_editor',
            'title'    => __('Footer Javascript Code', 'wpss'),
            'subtitle' => __('Paste your javascript code here.', 'wpss'),
            'mode'     => 'javascript',
            'theme'    => 'monokai',
            'desc'     => esc_html__('Your script will be inserted immediately before the </body> tag', 'wpss'),
            'options'  => array(
                'minLines' => 30,
            ),
            'default'  => "//$('#your-script-here');"
        )
    )
));

Redux::setSection($opt_name, array(
    'title'  => esc_html__('Custom CSS', 'wpss'),
    'id'     => 'custom_css',
    'desc'   => esc_html__('Insert your custom CSS here.', 'wpss'),
    'icon'   => 'el el-css',
    'fields' => array(
        array(
            'id'       => 'wpss_custom_css',
            'type'     => 'ace_editor',
            'title'    => __('CSS Code', 'wpss'),
            'subtitle' => __('Paste your CSS code here.', 'wpss'),
            'mode'     => 'css',
            'theme'    => 'monokai',
            'desc'     => esc_html__('Your css will be inserted immediately before the </head> tag', 'wpss'),
            'options'  => array(
                'minLines' => 30,
            ),
            'default'  => "/* Custom CSS Code */"
        )
    )
));

/***
 * Get options
 */
/*** Get global option */
function wpss_get_option(){
    global $wpss_option;
    return $wpss_option;
}

/*** Get header javascript */
add_action('wpss_after_inside_head', 'wpss_get_header_scripts_op');
function wpss_get_header_scripts_op(){
    $op = wpss_get_option()['wpss_header_script'];
    if(!empty($op)):
        echo "<script>\n $op \n</script>\n";
    endif;
}

/*** Get footer javascript */
add_action('wpss_body_end', 'wpss_get_footer_scripts_op');
function wpss_get_footer_scripts_op(){
    $op = wpss_get_option()['wpss_footer_script'];
    if(!empty($op)):
        echo "\n<script>\n $op \n</script>\n";
    endif;
}

/*** Get CSS Code */
add_action('wpss_after_inside_head', 'wpss_get_css_op');
function wpss_get_css_op(){
    $op = wpss_get_option()['wpss_custom_css'];
    if(!empty($op)):
        echo "<style>\n $op \n</style>\n";
    endif;
}