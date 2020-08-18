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

add_action( 'wpss_content_end', 'wpss_post_pagination' );
function wpss_post_pagination() {
	if ( is_singular( ) ):
		$next = get_adjacent_post( false, '', false );
		$prev = get_adjacent_post( false, '', true );
		?>
        <div class="container wpss-post-pagination mb-3">
            <div class="row pt-5">
                <div class="col-md-6">
					<?php if ( ! empty( $prev ) ): ?>
                        <div class="row h-100">
                            <a href="<?= get_permalink( $prev->ID ); ?>" title="<?= get_the_title( $prev->ID ); ?>"><?= get_the_title( $prev->ID ); ?></a>
                            <div class="col-md-3 d-flex align-items-center">
                                <i class="fas fa-angle-double-left"></i>
                            </div>
                            <div class="col-md-9">
                                <h4><?php _e( 'Previous', 'wpss' ); ?></h4>
                                <p><?= get_the_title( $prev->ID ); ?></p>
                            </div>
                        </div>
					<?php endif; ?>
                </div>
                <div class="col-md-6">
					<?php if ( ! empty( $next ) ): ?>
                        <div class="row h-100 text-right">
                            <a href="<?= get_permalink( $next->ID ); ?>" title="<?= get_the_title( $next->ID ); ?>"><?= get_the_title( $next->ID ); ?></a>
                            <div class="col-md-9">
                                <h4><?php _e( 'Next', 'wpss' ); ?></h4>
                                <p><?= get_the_title( $next->ID ); ?></p>
                            </div>
                            <div class="col-md-3 d-flex align-items-center justify-content-end">
                                <i class="fas fa-angle-double-right"></i>
                            </div>
                        </div>
					<?php endif; ?>
                </div>
            </div>
        </div>
	<?php
	endif;
}