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

function wpss_footer_default() {
	?>
    </div><!-- .row -->
    </div><!-- .wpss-main-container -->
    </div><!-- .container -->

    <footer class="wpss-footer">
        <div class="container">
            <div class="row">
				<?php
				if ( is_active_sidebar( 'wpss_footer_sidebbar' ) ):
					dynamic_sidebar( 'wpss_footer_sidebbar' );
				endif;
				?>
            </div><!-- .row -->
        </div><!-- .container -->

        <div class="wpss-footer-credits">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <p>&copy; <?php echo current_time( 'Y' ) ?> - <?php echo _WPSS_SITENAME . ', ' . _WPSS_SITEDESC; ?></p>
                    </div>
                </div>
            </div>
        </div>

    </footer><!-- .wpss-footer -->

	<?php
}