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

function wpss_social_share() {
	?>
    <div class="likely wpss-social-links">
        <div class="facebook" role="link" aria-label="<?php _e( 'Share on Facebook', 'wpss' ); ?>">Facebook</div>
        <div class="twitter" role="link" aria-label="<?php _e( 'Tweet this!', 'wpss' ); ?>">Twitter</div>
        <div class="whatsapp" role="link" aria-label="<?php _e( 'Send via WhatsApp', 'wpss' ); ?>">WhatsApp</div>
        <div class="linkedin" role="link" aria-label="<?php _e( 'Share on Linkedin', 'wpss' ); ?>">Linkedin</div>
        <div class="telegram" role="link" aria-label="<?php _e( 'Send via telegram', 'wpss' ); ?>">Telegram</div>
    </div>
	<?php
}