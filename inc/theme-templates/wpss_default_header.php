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

function wpss_default_header() {
	do_action( 'wpss_before_header' );
	?>
    <div class="wpss-header">

        <div class="wpss-header-container">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
						<?php echo wpss_site_logo(); ?>
                    </div><!-- .col-md-4 -->

                    <div class="col-md-8">
                        <div class="wpss-header-nav">
							<?php wpss_main_menu(); ?>
                        </div><!-- .wpss-header-nav -->
                    </div><!-- .col-md-8 -->
                </div><!-- .row -->
            </div><!-- .container -->
        </div><!-- .wpss-header-container -->

    </div><!-- .wpss-header -->
    <?php do_action( 'wpss_after_header' );?>
    <div class="container">
        <div class="wpss-main-container">
            <div class="row">
	<?php
}