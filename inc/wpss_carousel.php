<?php
/**
 * @param $element
 * @param array $args
 * @return string
 * @author              Angelo Rocha <contato@angelorocha.com.br>
 * @link                https://angelorocha.com.br
 * @copyleft            2020
 * @license             GNU GPL 3 (https://www.gnu.org/licenses/gpl-3.0.html)
 * @package WordPress
 * @subpackage superstar
 * @since 1.0.0
 * @author              Angelo Rocha
 */

function wpss_carousel($element, $args = array()){
    $settings = array(
        'autoplay'       => 'true',
        'autoplaySpeed'  => 3000,
        'infinite'       => 'true',
        'fade'           => 'false',
        'speed'          => 300,
        'slidesToShow'   => 3,
        'slidesToScroll' => 1,
        'dots'           => 'false',
        'arrows'         => 'true',
        'focusOnSelect'  => 'false',
        'centerMode'     => 'false',
        'variableWidth'  => 'false',
        'adaptiveHeight' => 'false',
        'prevArrow'      => "'<button type=\"button\" class=\"slick-prev\">Previous</button>'",
        'nextArrow'      => "'<button type=\"button\" class=\"slick-next\">Next</button>'",
        'lazyLoad'       => "'ondemand'",
        'responsive'     => array(
            1024 => array(
                'slidesToShow'   => 3,
                'slidesToScroll' => 1,
                'infinite'       => 'true',
                'dots'           => 'false',
            )
        )
    );

    if(!wp_style_is('wpss-slick', 'enqueued ')):
        wp_enqueue_style('wpss-slick', _WPSS_CSS_DIR . 'slick.css', '', _WPSS_FILE_VERSION, 'all');
    endif;

    if(!wp_style_is('wpss-slick-theme', 'enqueued ')):
        wp_enqueue_style('wpss-slick-theme', _WPSS_CSS_DIR . 'slick-theme.css', '', _WPSS_FILE_VERSION, 'all');
    endif;

    if(!wp_script_is('wpss-slick', 'enqueued ')):
        wp_enqueue_script('wpss-slick', _WPSS_JS_DIR . 'slick.min.js', array('jquery'), _WPSS_FILE_VERSION, true);
    endif;

    $get_params = array_replace($settings, $args);
    $script     = "\n<script>\n";
    $script     .= "jQuery(function($){\n";
    $script     .= "$('$element').slick({\n";
    foreach($get_params as $key => $val):
        if($key !== 'responsive'):
            $script .= "$key:$val,\n";
        else:

            if($get_params['responsive']):
                $script .= "$key:[\n";
                $size   = count($val);
                $count  = 0;
                foreach($val as $k => $v):
                    $count++;
                    $script .= "{\nbreakpoint: $k,\n";
                    $script .= "settings: " . wp_json_encode($v);
                    $script .= "\n}" . ($count < $size ? ",\n" : "");
                endforeach;
                $count  = 0;
                $script .= "\n]";
            endif;

        endif;
    endforeach;
    $script .= "})\n";
    $script .= "});";
    $script .= "\n</script>\n";

    return $script;
}