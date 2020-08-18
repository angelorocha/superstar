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

abstract class WPSSinit {

    /**
     * @param $dir
     * Get all files from folder
     */
    public static function wpss_load_files( $dir ) {
        foreach ( glob( plugin_dir_path( __DIR__ ) . "$dir/*.php" ) as $file ):
            require_once "$file";
        endforeach;
    }

    /**
     * @param $class
     * Load theme classes
     */
    public static function wpss_load_class( $class ) {
        $class_dir = plugin_dir_path( __DIR__ ) . "classes/$class.php";

        if ( file_exists( $class_dir ) ):
            require_once "$class_dir";
        else:
            echo sprintf( __( 'Class %s not defined...', 'wpss' ), $class );
        endif;
    }
}