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

function wpss_breadcrumbs(){
    $showOnHome  = 0;                                    // 1 - show breadcrumbs on the homepage, 0 - don't show
    $delimiter   = '<i class="fas fa-arrow-right"></i>'; // delimiter between crumbs
    $home        = 'Home';                               // text for the 'Home' link
    $showCurrent = 1;                                    // 1 - show current post/page title in breadcrumbs, 0 - don't show
    $before      = '<span>';                             // tag before the current crumb
    $after       = '</span>';                            // tag after the current crumb

    global $post;

    global $wpss_breadcrumb_home_link;
    $wpss_breadcrumb_home_link = home_url();

    do_action('wpss_before_breadcrumb');

    $homeLink = $wpss_breadcrumb_home_link;

    if(is_front_page()){
        if($showOnHome === 1){
            echo '<nav class="wpss-breadcrumb" aria-label="breadcrumb"><a href="' . $homeLink . '">' . $home . '</a></nav>';
        }
    }else{
        echo '<nav class="wpss-breadcrumb" aria-label="breadcrumb">' . $before . '<a href="' . $homeLink . '">' . $home . '</a> ' . $after . $delimiter;

        if(is_category()){
            $thisCat = get_category(get_query_var('cat'), false);
            if($thisCat->parent != 0){
                echo get_category_parents($before . $thisCat->parent, true, $after);
            }
            echo $before . single_cat_title('', false) . $after;
        }else if(is_search()){
            echo $before . 'Resultados da busca por: "' . get_search_query() . '"' . $after;
        }else if(is_day()){
            echo $before . '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $after;
            echo $before . '<a href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $after;
            echo $before . get_the_time('d') . $after;
        }else if(is_month()){
            echo $before . '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a>' . $after;
            echo $before . get_the_time('F') . $after;
        }else if(is_year()){
            echo $before . get_the_time('Y') . $after;
        }else if(is_single() && !is_attachment()){
            if(get_post_type() != 'post'){
                $post_type = get_post_type_object(get_post_type());
                $slug      = get_post_type_archive_link(get_post_type());
                echo $before . '<a href="' . $slug . '">' . $post_type->labels->singular_name . '</a>' . $delimiter . $after;
                if($showCurrent == 1){
                    echo $before . get_the_title() . $after;
                }
            }else{
                $cat  = get_the_category();
                $cat  = $cat[0];
                $cats = get_category_parents($cat, true, '', '');
                if($showCurrent == 0){
                    $cats = preg_replace("#^(.+)\s$delimiter\s$#", "$1", $cats);
                }
                echo $before . $cats . $after;
                if($showCurrent == 1){
                    echo $delimiter . $before . get_the_title() . $after;
                }
            }
        }else if(!is_single() && !is_page() && get_post_type() != 'post' && !is_404()){
            $post_type = get_post_type_object(get_post_type());
            if(is_tax()){
                echo $before . '<a href="' . esc_url(home_url('/')) . $post_type->rewrite['slug'] . '" title="">' . $post_type->labels->singular_name . '</a>' . $delimiter . $after;
            }else{
                echo $before . post_type_archive_title('', false) . $after;
            }
            if(is_tax()){
                echo $before;
                echo single_tag_title();
                echo $after;
            }
        }else if(is_attachment()){
            $parent = get_post($post->post_parent);
            $cat    = get_the_category($parent->ID);
            $cat    = $cat[0];
            echo get_category_parents($cat, true, ' ' . $delimiter . ' ');
            echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a>';
            if($showCurrent == 1){
                echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
            }
        }else if(is_page() && !$post->post_parent){
            if($showCurrent == 1){
                echo $before . get_the_title() . $after;
            }
        }else if(is_page() && $post->post_parent){
            $parent_id   = $post->post_parent;
            $breadcrumbs = array();
            while($parent_id){
                $page          = get_post($parent_id);
                $breadcrumbs[] = $before . '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>' . $after;
                $parent_id     = $page->post_parent;
            }
            $breadcrumbs = array_reverse($breadcrumbs);
            for($i = 0; $i < count($breadcrumbs); $i++){
                echo $breadcrumbs[$i];
                if($i != count($breadcrumbs) - 1){
                    ;
                }
            }
            if($showCurrent == 1){
                echo $before . get_the_title() . $after;
            }
        }else if(is_tag()){
            echo $before . 'Postagens marcadas como "' . single_tag_title('', false) . '"' . $after;
        }else if(is_author()){
            global $author;
            $userdata = get_userdata($author);
            echo $before . 'Postagens de: ' . $userdata->user_firstname . " " . $userdata->user_lastname . $after;
        }else if(is_404()){
            echo $before . 'Erro 404' . $after;
        }

        if(get_query_var('paged')){
            if(is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) /*echo ' ('*/{
                ;
            }
            if(!is_search()):
                echo "$before $delimiter Página " . get_query_var('paged') . " $after";;
            endif;
            if(is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) /*echo ')'*/{
                ;
            }
        }
        if(is_home()):
            echo $before . get_the_title(get_queried_object_id()) . $after;
        endif;
        echo '</nav>';
    }
}