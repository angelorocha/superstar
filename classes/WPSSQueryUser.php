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

class WPSSQueryUser{

	public $user_per_page = 12;
	public $user_id = null;
	public $avatar_size = 150;

	public function wpss_display_users() {
		if ( self::wpss_paginate_paged() ):
			if ( ! empty( self::wpss_get_users() ) ):
				echo "<div class='row'>";
				foreach ( self::wpss_get_users() as $user ):
					echo "<div class='col-md-4'>";
					echo "<div class='user-card'>";
					echo "<a href='" . $user['author_url'] . "' title='" . $user['nickname'] . "'>";
					echo "<img src='" . $user['avatar'] . "' alt=" . $user['nickname'] . ">";
					echo "</a>";
					echo "<ul class='list-unstyled'>";
					echo "<li><i class='fas fa-user'></i> @" . $user['nickname'] . "</li>";
					echo "<li><i class='fas fa-link'></i> " . $user['site'] . "</li>";
					echo "<li><i class='fas fa-calendar-alt'></i> " . $user['registered'] . "</li>";
					echo "<li><i class='fas fa-envelope'></i> " . $user['email'] . "</li>";
					echo "<li></li>";
					echo "</ul>";
					echo "</div>";
					echo "</div>";
				endforeach;
				echo '</div>';
			else:
				echo "<div class='alert alert-danger text-center'>Nennhum usuário encontrado</div>";
			endif;
			echo "<div class='user-paginate'>" . self::wpss_user_paginate() . "</div>";
		else:
			echo "<div class='alert alert-danger text-center'>Nennhum usuário encontrado</div>";
		endif;
	}

	public function wpss_user_search() {
		$val     = ( isset( $_GET['search_users'] ) ? $_GET['search_users'] : false );
		$options = array(
			'user_registered' => 'Data de Registro',
			'display_name'    => 'Nome',
			'user_name'       => 'Nome de Usuário',
			'post_count'      => 'Mais Ativos',
		);

		$output = '<form method="get" action="" class="user-search-top">';
		$output .= '<div class="form-row">';
		$output .= '<div class="col-md-4"><input type="text" placeholder="Buscar Usuário" class="form-control" name="search_users" value="' . $val . '"></div>';
		$output .= '<div class="col-md-2"><input type="submit" value="Buscar" class="btn btn-outline-dark"></div>';
		$output .= '<div class="col-md-3"></div>';
		$output .= '<div class="col-md-3">';
		$output .= '<select name="user_orderby" class="form-control" onchange="this.form.submit()">';
		foreach ( $options as $key => $op ):
			$selected = '';
			if ( isset( $_GET['user_orderby'] ) ):
				if ( $_GET['user_orderby'] === $key ):
					$selected = ' selected';
				endif;
			endif;
			$output .= '<option value="' . $key . '" ' . $selected . '>' . $op . '</option>';
		endforeach;
		$output .= '</select>';
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</form>';

		return $output;
	}

	public function wpss_get_users() {
		$users = array();
		foreach ( self::wpss_query_users()->get_results() as $user ):
			$site    = "<a href='" . $user->user_url . "' title='" . $user->user_url . "'>" . $user->user_url . "</a>";
			$users[] = [
				'nickname'    => $user->user_login,
				'name'        => $user->user_nicename,
				'email'       => $user->user_email,
				'registered'  => date( 'd-m-Y', strtotime( $user->user_registered ) ),
				'site'        => ( ! empty( $user->user_url ) ? $site : '---' ),
				'avatar'      => get_avatar_url( $user->ID, [ 'size' => $this->avatar_size ] ),
				'author_url'  => bp_core_get_user_domain( $user->ID ) . 'profile/',
				'post_count'  => count_user_posts( $user->ID ),
				'topic_count' => count_user_posts( $user->ID, 'topic' ),
				'reply_count' => count_user_posts( $user->ID, 'reply' ),
				'activity'    => date( 'd-m-Y', strtotime( bp_get_user_last_activity( $user->ID ) ) )
			];
		endforeach;

		return $users;
	}

	public function wpss_user_paginate() {
		$total    = self::wpss_total_users();
		$paginate = paginate_links( array(
			'base'      => add_query_arg( 'user_page', '%#%' ),
			'format'    => '',
			'prev_text' => '&laquo;',
			'next_text' => '&raquo;',
			'total'     => ceil( $total / $this->user_per_page ),
			'current'   => self::wpss_paginate_paged(),
			'type'      => 'list'
		) );

		return $paginate;
	}

	public function wpss_paginate_paged() {
		return isset( $_GET['user_page'] ) ? abs( (int) $_GET['user_page'] ) : 1;
	}

	public function wpss_total_users() {
		return self::wpss_query_users()->get_total();
	}

	public function wpss_query_users() {
		return self::wpss_user_object( self::wpss_query_args() );
	}

	public function wpss_user_object( $args ) {
		return new WP_User_Query( $args );
	}

	public function wpss_query_args() {
		$offset   = $offset = ( self::wpss_paginate_paged() * $this->user_per_page ) - $this->user_per_page;
		$order_by = ( isset( $_GET['user_orderby'] ) ? $_GET['user_orderby'] : 'registered' );
		$search   = false;
		if ( isset( $_GET['search_users'] ) ):
			if ( ! empty( $_GET['search_users'] ) ):
				$search = '*' . esc_attr( $_GET['search_users'] ) . '*';
			endif;
		endif;

		return array(
			'paged'   => self::wpss_paginate_paged(),
			'number'  => $this->user_per_page,
			'offset'  => $offset,
			'order'   => 'DESC',
			'orderby' => $order_by,
			'search'  => $search,
			'include' => $this->user_id
		);
	}
}