<?php
/**
 * @param $text
 * @param $icon
 * @param bool $url
 *
 * @return string
 * @author              Angelo Rocha
 * @author              Angelo Rocha <contato@angelorocha.com.br>
 * @link                https://angelorocha.com.br
 * @copyleft            2020
 * @license             GNU GPL 3 (https://www.gnu.org/licenses/gpl-3.0.html)
 * @package WordPress
 * @subpackage superstar
 * @since 1.0.0
 */

function wpss_content_header_1( $text, $icon, $url = false ) {
	?>
    <h3 class="wpss-content-header-1 text-center">
		<?= ( $url ? "<a href='$url' title='$text'>" : "" ); ?>
        <span><?= $text; ?></span>
        <i class="<?= $icon ?>"></i>
		<?= ( $url ? "</a>" : "" ); ?>
    </h3>
	<?php
}