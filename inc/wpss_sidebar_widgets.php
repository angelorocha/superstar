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

WPSSwidgetSidebar::wpss_sidebar_widget(
	'wpss_sidebar_widgets',
	__( 'Sidebar Widgets', 'wpss' ),
	'Widgets from theme sidebar',
	'wpss-sidebar-widgets'
);

WPSSwidgetSidebar::wpss_sidebar_widget(
	'wpss_footer_sidebbar',
	'Footer Widgets',
	'Widgets from footer',
	'wpss-footer-widgets col-3'
);