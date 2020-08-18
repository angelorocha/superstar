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

add_filter( 'cron_schedules', 'wpss_custom_crons' );
function wpss_custom_crons() {
	// Calc week
	$week = ( 60 * 60 * 24 * 7 );
	// Calc month
	$month      = date( 'n', strtotime( '+1 month' ) );
	$year       = current_time( 'Y' );
	$dayscount  = cal_days_in_month( CAL_GREGORIAN, $month, $year );
	$month_time = ( 60 * 60 * 24 * $dayscount );

	// Create week Cron
	$schedules['week'] = array(
		'interval' => $week,
		'display'  => __( 'Once a week' )
	);

	// Create month Cron
	$schedules['monthly'] = array(
		'interval' => $month_time,
		'display'  => __( 'Once a month' )
	);

	// Create minute Cron
	$schedules['minute'] = array(
		'interval' => 60,
		'display'  => __( 'Once a minute' )
	);

	return $schedules;
}