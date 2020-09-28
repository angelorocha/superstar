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
 * Theme Constants
 */
define('_WPSS_THEME_DIR', get_template_directory());
define('_WPSS_THEME_DIR_URI', get_template_directory_uri());
define('_WPSS_ASSETS_DIR', _WPSS_THEME_DIR_URI . '/assets/');
define('_WPSS_JS_DIR', _WPSS_THEME_DIR_URI . '/assets/js/');
define('_WPSS_CSS_DIR', _WPSS_THEME_DIR_URI . '/assets/css/');
define('_WPSS_IMAGES_DIR', _WPSS_THEME_DIR_URI . '/assets/images/');
define('_WPSS_FILE_VERSION', 20200928);
define('_WPSS_THEME_STYLE_URI', get_stylesheet_directory_uri());
define('_WPSS_SITENAME', get_bloginfo('name'));
define('_WPSS_SITEDESC', get_bloginfo('description'));
define('_WPSS_SITE_LANG', get_bloginfo('language'));
define('_WPSS_SITE_URL', home_url('/'));

/**
 * Init Theme
 */
require_once dirname(__FILE__) . '/init/WPSSinit.php';
require_once dirname(__FILE__) . '/lib/plugins/autoload.php';
require_once dirname(__FILE__) . '/lib/addons/cmb2-conditionals/cmb2-conditionals.php';
require_once dirname(__FILE__) . '/lib/addons/ReduxCore/framework.php';

/**
 * Load theme classes
 */
WPSSinit::wpss_load_class('WPSSquery');
WPSSinit::wpss_load_class('WPSSMetaBox');
WPSSinit::wpss_load_class('WPSSPostsOrder');
WPSSinit::wpss_load_class('WPSSCptContact');
WPSSinit::wpss_load_class('WPSSCptSidebar');
WPSSinit::wpss_load_class('WPSSCptMenu');
WPSSinit::wpss_load_class('WPSScpt');
WPSSinit::wpss_load_class('WPSSct');
WPSSinit::wpss_load_class('WPSSmenu');
WPSSinit::wpss_load_class('WPSSwidgetSidebar');
WPSSinit::wpss_load_class('WPSSloadcss');
WPSSinit::wpss_load_class('WPSSloadjs');
WPSSinit::wpss_load_class('WPSSFFmpeg');
WPSSinit::wpss_load_class('WPSSRest');
WPSSinit::wpss_load_class('WPSSMetaFilter');
WPSSinit::wpss_load_class('WPSSUpload');
WPSSinit::wpss_load_class('WPSSMail');
WPSSinit::wpss_load_class('WPSSForms');
WPSSinit::wpss_load_class('WPSSCPTRewrite');

/**
 * Load theme files
 */
WPSSinit::wpss_load_files('inc');
WPSSinit::wpss_load_files('inc/theme-templates');
WPSSinit::wpss_load_files('inc/theme-templates/loop-blocks');
WPSSinit::wpss_load_files('inc/content-parts');
WPSSinit::wpss_load_files('inc/widgets');
WPSSinit::wpss_load_files('inc/widgets/blocks');
WPSSinit::wpss_load_files('inc/content-headers');
WPSSinit::wpss_load_files('inc/cmb2-fields');
WPSSinit::wpss_load_files('theme-options/forms');
WPSSinit::wpss_load_files('theme-options/options');
WPSSinit::wpss_load_files('theme-options/post-views');
WPSSinit::wpss_load_files('cpt');

if(is_admin() && current_user_can('administrator')):
    WPSSinit::wpss_load_files('theme-options/role-management');
    WPSSinit::wpss_load_files('theme-options/bulk-terms');
endif;

/**
 * Theme Setup
 */
add_action('after_setup_theme', 'wpss_theme_setup');
function wpss_theme_setup(){
    load_theme_textdomain('wpss', get_template_directory() . '/lang');
    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    #set_post_thumbnail_size( 470, 270, true );
    add_image_size('wpss_thumbnail', 470, 270, true);
    add_image_size('wpss_post_cover', 870, 470, true);
    add_image_size('wpss_container_cover', 1170, 570, true);
    add_image_size('wpss_full_width_cover', 1920, 778, true);
    add_image_size('wpss_full_hd_cover', 1920, 900, true);
    add_image_size('wpss_full_hd_mobile_cover', 470, 820, true);
    add_image_size('wpss_slider_full_width', 1920, 470, true);

    register_nav_menus(
        array(
            'main_menu'   => __('Main Menu', 'wpss'),
            'top_menu'    => __('Top Menu', 'wpss'),
            'footer_menu' => __('Footer Menu', 'wpss'),
        )
    );

    add_theme_support(
        'html5', [
                   'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style'
               ]
    );

    add_theme_support(
        'custom-logo',
        array(
            'width'       => 190,
            'height'      => 60,
            'flex-width'  => false,
            'flex-height' => false,
            'header-text' => array('site-title', 'site-description'),
        )
    );

    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('wp-block-styles');
    add_theme_support('align-wide');
    add_theme_support('editor-styles');
    add_editor_style('style-editor.css');
    add_theme_support('responsive-embeds');
}