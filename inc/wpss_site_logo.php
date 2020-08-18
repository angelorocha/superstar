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

function wpss_site_logo() {
	$default_logo = "<h1><a href='" . _WPSS_SITE_URL . "' title='" . _WPSS_SITENAME . "'>";
	$default_logo .= "<img src='" . _WPSS_IMAGES_DIR . "default-site-logo.png' alt='" . _WPSS_SITENAME . "'>";
	$default_logo .= "</a></h1>";
	$site_logo    = has_custom_logo() ? get_custom_logo() : $default_logo;

	return $site_logo;
}