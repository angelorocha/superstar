<?php
/**
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

function wpss_widget_block_6() {
	?>
    <div class="wpss-widget-block-6">
        <a class="wpss-block-link" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
        <img src="<?= wpss_image_src( get_the_ID(), 'wpss_thumbnail_wide' ) ?>" alt="<?php the_title(); ?>">
        <div class="wpss-block-content">
            <div class="wpss-block-info">
                <h4 class="text-center"><?php the_title(); ?></h4>
                <p class="text-justify"><?= wpss_excerpt( 20 ); ?></p>
            </div>
        </div>
    </div>
	<?php
}