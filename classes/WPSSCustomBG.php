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


class WPSSCustomBG {
	/*
	public $wpss_default_backgrounds = array();

	public function __construct( $manager ) {
		parent::__construct( $manager );
		$this->wpss_default_backgrounds = apply_filters( 'wpss_default_backgrounds', $this->wpss_default_backgrounds );
		if ( ! $this->setting->default && ! empty( $this->wpss_default_backgrounds ) ):
			$this->add_tab( 'default', __( 'Default', 'wpss' ), array( $this, 'wpss_tab_default_background' ) );
		endif;
	}

	public function wpss_tab_default_background() {
		if ( $this->setting->default ):
			$this->print_tab_image( $this->setting->default );
		endif;

		if ( ! empty( $this->wpss_default_backgrounds ) ):
			foreach ( $this->wpss_default_backgrounds as $bg ):
				if ( isset( $bg['thumbnail_url'] ) ):
					$bg['thumbnail_url'] = $bg['url'];
				endif;

				$image = sprintf( $bg['url'], _WPSS_THEME_DIR_URI, _WPSS_THEME_STYLE_URI );
				$thumb = sprintf( $bg['thumbnail_url'], _WPSS_THEME_DIR_URI, _WPSS_THEME_STYLE_URI );

				$this->print_tab_image( $image, $thumb );
			endforeach;
		endif;
	}*/
}