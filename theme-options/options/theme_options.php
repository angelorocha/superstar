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
    'show_import_export' => false,
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

Redux::setSection($opt_name, array(
    'title'  => esc_html__('Custom Scripts', 'wpss'),
    'id'     => 'custom_scripts',
    'desc'   => esc_html__('Insert your custom scripts here.', 'wpss'),
    'icon'   => 'el el-jquery',
    'fields' => array(
        array(
            'id'       => 'wpss-header-script',
            'type'     => 'ace_editor',
            'title'    => __('Header Javascript Code', 'wpss'),
            'subtitle' => __('Paste your javascript code here.', 'wpss'),
            'mode'     => 'javascript',
            'theme'    => 'monokai',
            'desc'     => esc_html__('Your script will be inserted immediately before the </head> tag', 'wpss'),
            'default'  => "$('#your-script-here');"
        )
    )
));