<?php
/**
 * @param bool $show_interval
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

function wpss_content_date( $show_interval = true ) {
	$days_calc     = 60 * 60 * 24;
	$modified_date = get_post_modified_time( 'd-m-Y' );
	$current_date  = current_time( 'd-m-Y' );
	$days_count    = ( strtotime( $current_date ) - strtotime( $modified_date ) ) / $days_calc;

	$days_interval = '';

	if ( $days_count == 0 ):
		$days_interval = __( '(today)', 'wpss' );
	endif;

	if ( $days_count == 1 ):
		$days_interval = __( '(yesterday)', 'wpss' );
	endif;

	if ( $days_count > 1 ):
		$days_interval = sprintf( __( '(%1$s days ago)', 'wpss' ), $days_count );
	endif;
	?>
    <ul class="list-unstyled border-top border-bottom mt-4 py-2 text-monospace font-italic text-muted">
        <li>
            <small>
				<?= __( 'Posted by', 'wpss' ); ?> <?= wpss_author( get_post( get_the_ID() )->post_author ); ?>
				<?= __( 'on', 'wpss' ); ?> <?= get_the_date( 'd-m-Y' ); ?> <?= __( 'at', 'wpss' ); ?>
				<?= get_the_date( 'H\hi' ); ?>
            </small>
        </li>
		<?php if ( $show_interval ): ?>
            <li>
                <small>
					<?= __( 'Last updated by ', 'wpss' ); ?> <strong><?= get_the_modified_author(); ?></strong>
					<?= __( 'on', 'wpss' ); ?> <?= $modified_date; ?> <?= __( 'at', 'wpss' ); ?>
					<?= get_post_modified_time( 'H\hi' ); ?>
					<?= $days_interval; ?>
                </small>
            </li>
		<?php endif; ?>
    </ul>
	<?php
}