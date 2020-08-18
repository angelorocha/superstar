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

class WPSSRest{

    public $post_type;
    public $limit;
    public $offset;

    /**
     * WPSSRest constructor.
     *
     * @param $authorize
     */
    public function __construct($authorize){
        if($_SERVER['REQUEST_METHOD'] !== 'GET'):
            echo wp_json_encode(__('Sorry, only GET methods...', 'wpss'));
            exit;
        endif;

        if(!in_array($_SERVER['REMOTE_ADDR'], (array)$authorize)):
            echo wp_json_encode(__('Access denied for this client...', 'wpss'));
            exit;
        endif;
    }

    public function wpss_rest_get_posts(){
        $posts = array();

        foreach(self::wpss_rest_execute_query() as $post):
            $thumbnails = array();

            foreach(get_intermediate_image_sizes() as $image):
                $thumbnails[$image] = wpss_image_src((int)$post['id'], $image);
            endforeach;

            $post['thumbnail_sizes'] = $thumbnails;
            $post['meta'] = self::wpss_rest_meta($post['id']);
            $posts[] = $post;
        endforeach;

        return wp_json_encode($posts);
    }

    /**
     * Execute query
     * @return array|object|null
     */
    public function wpss_rest_execute_query(){
        global $wpdb;

        return $wpdb->get_results(self::wpss_rest_query(), ARRAY_A);
    }

    /**
     * Query to get post with meta data rest
     * @return string
     */
    public function wpss_rest_query(){
        global $wpdb;

        $query = "SELECT ";

        $query .= "$wpdb->posts.ID AS 'id',";
        $query .= "$wpdb->posts.post_title AS 'title',";
        $query .= "$wpdb->posts.post_content AS 'content',";
        $query .= "$wpdb->posts.post_date AS 'date',";
        $query .= "$wpdb->posts.post_status AS 'status',";
        $query .= "$wpdb->posts.post_name AS 'name',";
        $query .= "$wpdb->posts.post_modified AS 'date_modified',";
        $query .= "$wpdb->posts.post_type AS 'type' ";
        $query .= "FROM $wpdb->posts ";
        $query .= "WHERE $wpdb->posts.post_type = '$this->post_type' ";
        $query .= "AND ";
        $query .= "$wpdb->posts.post_status = 'publish' ";
        $query .= "LIMIT $this->limit OFFSET $this->offset;";

        return $query;
    }

    /**
     * @param $post_id
     * @return array
     */
    public function wpss_rest_meta($post_id){
        global $wpdb;
        $meta = array();
        $query = "SELECT ";
        $query .= "meta_key,";
        $query .= "meta_value";
        $query .= " FROM $wpdb->postmeta WHERE post_id = '$post_id';";
        foreach($wpdb->get_results($query, ARRAY_A) as $key => $item):
            $meta[$item['meta_key']] = $item['meta_value'];
        endforeach;

        return $meta;
    }

}