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

final class WPSSwidgetSidebar {
	public function __construct() {
		add_action( 'widgets_init', array( $this, 'wpss_sidebar_widget' ) );
	}

	/**
	 * @param $id - Unique widget ID
	 * @param $name - Widget name
	 * @param $desc - Widget desc
	 * @param $class - widget css class
	 * @param $before_title
	 * @param $after_title
	 *
	 * @return string
	 */
	public static function wpss_sidebar_widget( $id, $name, $desc = '', $class = '', $before_title = '', $after_title = '' ) {
		return register_sidebar( array(
			'name'          => $name,
			'id'            => $id,
			'description'   => $desc,
			'before_widget' => '<aside id="%1$s" class="widget %2$s ' . $class . '">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3>' . $before_title,
			'after_title'   => $after_title . '</h3>',
		) );
	}
}