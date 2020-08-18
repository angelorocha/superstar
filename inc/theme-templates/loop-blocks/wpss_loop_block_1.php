<?php
/**
 * @param int $excerpt
 *
 * @author              Angelo Rocha <contato@angelorocha.com.br>
 * @link                https://angelorocha.com.br
 * @copyleft            2020
 * @license             GNU GPL 3 (https://www.gnu.org/licenses/gpl-3.0.html)
 * @package WordPress
 * @subpackage superstar
 * @since 1.0.0
 * @author              Angelo Rocha
 */

function wpss_loop_block_1( $excerpt = 20 ) {
	?>
    <div class="container">
        <article class="row wpss-loop-item">
            <div class="col-md-4">
				<?= wpss_thumbnail(); ?>
            </div>
            <div class="col-md-8">
                <h4><?= get_the_title(); ?></h4>
                <p><?= wpss_excerpt( $excerpt ); ?></p>
                <div class="wpss-loop-meta">
                    <div class="row">
                        <div class="col-md-9 mr-0">
                            <ul class="list-inline">
                                <li><i class="far fa-calendar-alt"></i> <?= get_the_date( 'd-m-Y' ); ?></li>
                                <li><i class="far fa-eye"></i> <?= wpss_get_post_views( get_the_ID() ); ?></li>
                                <li><?= getPostLikeLink( get_the_ID() ); ?></li>
                            </ul>
                        </div><!-- col-md-8 -->
                        <div class="col-md-3 ml-0 text-right">
							<?= wpss_readmore_btn( 'btn btn-outline-secondary btn-sm' ); ?>
                        </div><!-- .col-md-4 -->
                    </div><!-- .row -->
                </div><!-- .wpss-loop-meta -->
            </div><!-- .col-md-8 -->
        </article><!-- .wpss-loop-item -->
    </div><!-- .container -->
	<?php
}