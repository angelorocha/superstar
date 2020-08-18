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
 *
 */

function wpss_like_scripts() {
	wp_localize_script( 'super_like', 'ajax_var', array(
		'url'   => admin_url( 'admin-ajax.php' ),
		'nonce' => wp_create_nonce( 'ajax-nonce' )
	) );
}

if ( ! is_admin() ):
	add_action( 'wp_enqueue_scripts', 'wpss_like_scripts' );
endif;

/**
 * (3) Save like data
 */
add_action( 'wp_ajax_nopriv_wpss-post-like', 'wpss_post_like' );
add_action( 'wp_ajax_wpss-post-like', 'wpss_post_like' );
function wpss_post_like() {
	$nonce = $_POST['nonce'];
	if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ) {
		wp_die( 'Oooooooh boy...' );
	}

	if ( isset( $_POST['wpss_post_like'] ) ) {

		$post_id         = $_POST['post_id']; // post id
		$post_like_count = get_post_meta( $post_id, "_post_like_count", true ); // post like count

		if ( is_user_logged_in() ) { // user is logged in
			$user_id     = get_current_user_id(); // current user
			$meta_POSTS  = get_user_option( "_liked_posts", $user_id ); // post ids from user meta
			$meta_USERS  = get_post_meta( $post_id, "_user_liked" ); // user ids from post meta
			$liked_POSTS = null; // setup array variable
			$liked_USERS = null; // setup array variable

			if ( count( $meta_POSTS ) != 0 ) { // meta exists, set up values
				$liked_POSTS = $meta_POSTS;
			}

			if ( ! is_array( $liked_POSTS ) ) // make array just in case
			{
				$liked_POSTS = array();
			}

			if ( count( $meta_USERS ) != 0 ) { // meta exists, set up values
				$liked_USERS = $meta_USERS[0];
			}

			if ( ! is_array( $liked_USERS ) ) // make array just in case
			{
				$liked_USERS = array();
			}

			$liked_POSTS[ 'post-' . $post_id ] = $post_id; // Add post id to user meta array
			$liked_USERS[ 'user-' . $user_id ] = $user_id; // add user id to post meta array
			$user_likes                        = count( $liked_POSTS ); // count user likes

			if ( ! AlreadyLiked( $post_id ) ) { // like the post
				update_post_meta( $post_id, "_user_liked", $liked_USERS ); // Add user ID to post meta
				update_post_meta( $post_id, "_post_like_count", ++ $post_like_count ); // +1 count post meta
				update_user_option( $user_id, "_liked_posts", $liked_POSTS ); // Add post ID to user meta
				update_user_option( $user_id, "_user_like_count", $user_likes ); // +1 count user meta
				echo $post_like_count; // update count on front end

			} else { // unlike the post
				$pid_key = array_search( $post_id, $liked_POSTS ); // find the key
				$uid_key = array_search( $user_id, $liked_USERS ); // find the key
				unset( $liked_POSTS[ $pid_key ] ); // remove from array
				unset( $liked_USERS[ $uid_key ] ); // remove from array
				$user_likes = count( $liked_POSTS ); // recount user likes
				update_post_meta( $post_id, "_user_liked", $liked_USERS ); // Remove user ID from post meta
				update_post_meta( $post_id, "_post_like_count", -- $post_like_count ); // -1 count post meta
				update_user_option( $user_id, "_liked_posts", $liked_POSTS ); // Remove post ID from user meta
				update_user_option( $user_id, "_user_like_count", $user_likes ); // -1 count user meta
				echo "already" . $post_like_count; // update count on front end

			}

		} else {
			$ip        = $_SERVER['REMOTE_ADDR']; // user IP address
			$meta_IPS  = get_post_meta( $post_id, "_user_IP" ); // stored IP addresses
			$liked_IPS = null; // set up array variable

			if ( count( $meta_IPS ) != 0 ) { // meta exists, set up values
				$liked_IPS = $meta_IPS[0];
			}

			if ( ! is_array( $liked_IPS ) ) // make array just in case
			{
				$liked_IPS = array();
			}

			if ( ! in_array( $ip, $liked_IPS ) ) // if IP not in array
			{
				$liked_IPS[ 'ip-' . $ip ] = $ip;
			} // add IP to array

			if ( ! AlreadyLiked( $post_id ) ) { // like the post
				update_post_meta( $post_id, "_user_IP", $liked_IPS ); // Add user IP to post meta
				update_post_meta( $post_id, "_post_like_count", ++ $post_like_count ); // +1 count post meta
				echo $post_like_count; // update count on front end

			} else { // unlike the post
				$ip_key = array_search( $ip, $liked_IPS ); // find the key
				unset( $liked_IPS[ $ip_key ] ); // remove from array
				update_post_meta( $post_id, "_user_IP", $liked_IPS ); // Remove user IP from post meta
				update_post_meta( $post_id, "_post_like_count", -- $post_like_count ); // -1 count post meta
				echo "already" . $post_like_count; // update count on front end

			}
		}
	}

	exit;
}

/**
 * (4) Test if user already liked post
 */
function AlreadyLiked( $post_id ) { // test if user liked before
	if ( is_user_logged_in() ) { // user is logged in
		$user_id     = get_current_user_id(); // current user
		$meta_USERS  = get_post_meta( $post_id, "_user_liked" ); // user ids from post meta
		$liked_USERS = ""; // set up array variable

		if ( count( $meta_USERS ) != 0 ) { // meta exists, set up values
			$liked_USERS = $meta_USERS[0];
		}

		if ( ! is_array( $liked_USERS ) ) // make array just in case
		{
			$liked_USERS = array();
		}

		if ( in_array( $user_id, $liked_USERS ) ) { // True if User ID in array
			return true;
		}

		return false;

	} else { // user is anonymous, use IP address for voting

		$meta_IPS  = get_post_meta( $post_id, "_user_IP" ); // get previously voted IP address
		$ip        = $_SERVER["REMOTE_ADDR"]; // Retrieve current user IP
		$liked_IPS = ""; // set up array variable

		if ( count( $meta_IPS ) != 0 ) { // meta exists, set up values
			$liked_IPS = $meta_IPS[0];
		}

		if ( ! is_array( $liked_IPS ) ) // make array just in case
		{
			$liked_IPS = array();
		}

		if ( in_array( $ip, $liked_IPS ) ) { // True is IP in array
			return true;
		}

		return false;
	}
}

/**
 * (5) Front end button
 */
add_action( 'wpss_loop_item_end', 'wpss_like_modal' );
add_action( 'wpss_content_end', 'wpss_like_modal' );
function wpss_like_modal() {
	$post_id  = get_the_ID();
	$who_like = get_post_meta( $post_id, '_user_liked', true );

	echo "<div class='modal fade user-liked-post' id='who-like-$post_id' tabindex='-1' role='dialog' aria-hidden='true'>";
	echo '<div class="modal-dialog" role="document">';
	echo '<div class="modal-content">';
	echo '<div class="modal-header">';
	echo '<h5 class="modal-title">Quem Curtiu?</h5>';
	echo '<button type="button" class="close btn btn-xs" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
	echo '</div>';
	echo '<div class="modal-body">';
	if ( ! empty( $who_like ) ) {
		echo '<ul class="clearfix row">';
		foreach ( $who_like as $user ) {
			echo '<li class="col-4">';
			echo wpss_author( $user, true );
			echo '</li>';
		}
		echo '</ul>';
	} else {
		echo '<p class="alert alert-info text-center">Seja o primeiro a curtir!</p>';
	}
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
}

function getPostLikeLink( $post_id ) {

	$like_count = get_post_meta( $post_id, "_post_like_count", true );
	$count      = ( empty( $like_count ) || $like_count == "0" ) ? 'Curtir!' : esc_attr( $like_count );

	$like_link = '';
	$output    = '';

	if ( AlreadyLiked( $post_id ) ) {
		$like_link .= '<a href="javascript:;" title="Veja quem curtiu!" class="wpss-like-already" data-toggle="modal" data-target="#who-like-' . $post_id . '"><i class="fas fa-thumbs-up"></i> ' . $count . '</a>';
	} else {
		$like_link .= '<a href="javascript:;" title="Curtir!" class="wpss-post-like" data-post_id="' . $post_id . '"><i class="far fa-thumbs-up"></i> Curtir!</a>';
	}
	if ( is_user_logged_in() ) {
		$output .= $like_link;
	} else {
		$output .= '<a href="javascript:;" title="É preciso estar logado para curtir!" class="wpss-like-already" data-toggle="modal" data-target="#who-like-' . $post_id . '"><i class="fa fa-thumbs-up"></i></a>';
	}

	return $output;
}

/**
 * (5.5)Front End Counter
 */
function getPostLikeCount( $post_id ) {
	$like_count = get_post_meta( $post_id, "_post_like_count", true ); // get post likes
	$count      = ( empty( $like_count ) || $like_count == "0" ) ? 'Curtir!' : esc_attr( $like_count );
	if ( AlreadyLiked( $post_id ) ) {
		$class = esc_attr( ' liked' );
		$title = esc_attr( 'Deixar de Curtir' );
	} else {
		$class = esc_attr( '' );
		$title = esc_attr( 'Curtir!' );
	}
	$output = '<a href="#" class="wpss-post-like-content' . $class . '" data-post_id="' . $post_id . '" title="' . $title . '">' . $count . '</a>';

	return $output;
}

/**
 * (6) Retrieve User Likes and Show on Profile
 */
add_action( 'show_user_profile', 'show_user_likes' );
add_action( 'edit_user_profile', 'show_user_likes' );
function show_user_likes( $user ) { ?>
    <ul class="user-posts">
		<?php
		$user_likes = get_user_option( "_liked_posts", $user );
		if ( ! empty( $user_likes ) && count( $user_likes ) > 0 ) {
			$the_likes = $user_likes;
		} else {
			$the_likes = '';
		}
		if ( ! is_array( $the_likes ) ) {
			$the_likes = array();
		}
		$count = count( $the_likes );

		if ( $count > 0 ) {
			$like_list = '';

			foreach ( $the_likes as $the_like ) {
				$like_list .= "<li><a href=\"" . esc_url( get_permalink( $the_like ) ) . "\" title=\"" . esc_attr( get_the_title( $the_like ) ) . "\">" . get_the_title( $the_like ) . "</a></li>";
			}
			echo $like_list;
		} else {
			echo "<td>Nada Curtido Ainda...</td>";
		} ?>
    </ul>
<?php }

/**
 * (7) Add a shortcode to your posts instead
 * type [jmliker] in your post to output the button
 */
function wpss_like_shortcode() {
	return getPostLikeLink( get_the_ID() );
}

add_shortcode( 'wpssliker', 'wpss_like_shortcode' );

/**
 * (8) If the user is logged in, output a list of posts that the user likes
 * Markup assumes sidebar/widget usage
 */
function frontEndUserLikes() {
	if ( is_user_logged_in() ) { // user is logged in
		$like_list  = '';
		$user_id    = get_current_user_id(); // current user
		$user_likes = get_user_option( "_liked_posts", $user_id );
		if ( ! empty( $user_likes ) && count( $user_likes ) > 0 ) {
			$the_likes = $user_likes;
		} else {
			$the_likes = '';
		}
		if ( ! is_array( $the_likes ) ) {
			$the_likes = array();
		}
		$count = count( $the_likes );
		if ( $count > 0 ) {
			$limited_likes = array_slice( $the_likes, 0, 5 ); // this will limit the number of posts returned to 5
			$like_list     .= "<aside>\n";
			$like_list     .= "<h3>" . __( 'You Like:' ) . "</h3>\n";
			$like_list     .= "<ul>\n";
			foreach ( $limited_likes as $the_like ) {
				$like_list .= "<li><a href='" . esc_url( get_permalink( $the_like ) ) . "' title='" . esc_attr( get_the_title( $the_like ) ) . "'>" . get_the_title( $the_like ) . "</a></li>\n";
			}
			$like_list .= "</ul>\n";
			$like_list .= "</aside>\n";
		}
		echo $like_list;
	}
}

/**
 * (9) Outputs a list of the 5 posts with the most user likes TODAY
 * Markup assumes sidebar/widget usage
 */
function wpss_most_popular_today() {
	global $post;
	$today     = date( 'j' );
	$year      = date( 'Y' );
	$args      = array(
		'year'           => $year,
		'day'            => $today,
		'post_type'      => array( 'post', 'enter-your-comma-separated-post-types-here' ),
		'meta_key'       => '_post_like_count',
		'orderby'        => 'meta_value_num',
		'order'          => 'DESC',
		'posts_per_page' => 5
	);
	$pop_posts = new WP_Query( $args );
	if ( $pop_posts->have_posts() ) {
		echo "<aside>\n";
		echo "<h3>" . _e( 'Today\'s Most Popular Posts' ) . "</h3>\n";
		echo "<ul>\n";
		while ( $pop_posts->have_posts() ) {
			$pop_posts->the_post();
			echo "<li><a href='" . get_permalink( $post->ID ) . "'>" . get_the_title() . "</a></li>\n";
		}
		echo "</ul>\n";
		echo "</aside>\n";
	}
	wp_reset_postdata();
}

/**
 * (10) Outputs a list of the 5 posts with the most user likes for THIS MONTH
 * Markup assumes sidebar/widget usage
 */
function wpss_most_popular_month() {
	global $post;
	$month     = date( 'm' );
	$year      = date( 'Y' );
	$args      = array(
		'year'           => $year,
		'monthnum'       => $month,
		'post_type'      => array( 'post', 'enter-your-comma-separated-post-types-here' ),
		'meta_key'       => '_post_like_count',
		'orderby'        => 'meta_value_num',
		'order'          => 'DESC',
		'posts_per_page' => 5
	);
	$pop_posts = new WP_Query( $args );
	if ( $pop_posts->have_posts() ) {
		echo "<aside>\n";
		echo "<h3>" . _e( 'This Month\'s Most Popular Posts' ) . "</h3>\n";
		echo "<ul>\n";
		while ( $pop_posts->have_posts() ) {
			$pop_posts->the_post();
			echo "<li><a href='" . get_permalink( $post->ID ) . "'>" . get_the_title() . "</a></li>\n";
		}
		echo "</ul>\n";
		echo "</aside>\n";
	}
	wp_reset_postdata();
}

/**
 * (11) Outputs a list of the 5 posts with the most user likes for THIS WEEK
 * Markup assumes sidebar/widget usage
 */
function wpss_most_popular_week() {
	global $post;
	$week      = date( 'W' );
	$year      = date( 'Y' );
	$args      = array(
		'year'           => $year,
		'w'              => $week,
		'post_type'      => array( 'post', 'enter-your-comma-separated-post-types-here' ),
		'meta_key'       => '_post_like_count',
		'orderby'        => 'meta_value_num',
		'order'          => 'DESC',
		'posts_per_page' => 5
	);
	$pop_posts = new WP_Query( $args );
	if ( $pop_posts->have_posts() ) {
		echo "<aside>\n";
		echo "<h3>" . _e( 'This Week\'s Most Popular Posts' ) . "</h3>\n";
		echo "<ul>\n";
		while ( $pop_posts->have_posts() ) {
			$pop_posts->the_post();
			echo "<li><a href='" . get_permalink( $post->ID ) . "'>" . get_the_title() . "</a></li>\n";
		}
		echo "</ul>\n";
		echo "</aside>\n";
	}
	wp_reset_postdata();
}

/**
 * (12) Outputs a list of the 5 posts with the most user likes for ALL TIME
 * Markup assumes sidebar/widget usage
 */
function wpss_most_popular() {
	global $post;
	echo "<aside>\n";
	echo "<h3>" . _e( 'Most Popular Posts' ) . "</h3>\n";
	echo "<ul>\n";
	$args      = array(
		'post_type'      => array( 'post', 'enter-your-comma-separated-post-types-here' ),
		'meta_key'       => '_post_like_count',
		'orderby'        => 'meta_value_num',
		'order'          => 'DESC',
		'posts_per_page' => 5
	);
	$pop_posts = new WP_Query( $args );
	while ( $pop_posts->have_posts() ) {
		$pop_posts->the_post();
		echo "<li><a href='" . get_permalink( $post->ID ) . "'>" . get_the_title() . "</a></li>\n";
	}
	wp_reset_postdata();
	echo "</ul>\n";
	echo "</aside>\n";
}