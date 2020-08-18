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

class WPSSBBPGroups {

	public $order_by = 'date_created';
	public $order = 'DESC';

	public function __construct() {
	}

	public function wpss_get_groups() {
		$groups = self::wpss_group_query();

		$group_list = '<div class="row groups-list">';
		foreach ( $groups as $key => $group ):

			if ( current_user_can( 'administrator' ) && $group['status'] === 'private' ):
				$group_list .= "<div class='col-md-4 group-item'>";
				$group_list .= "<div class='group-card group-private'>";
				#$group_list .= "<a href='" . $group['permalink'] . "' title='" . $group['name'] . "'>";
				$group_list .= "<a href='" . bbp_get_forum_permalink( $group['group_meta']['forum_id'][0] ) . "' title='" . $group['name'] . "'>";
				$group_list .= "<img src='" . $group['group_meta']['avatar'] . "' alt='" . $group['name'] . "'>";
				$group_list .= "</a>";
				#$group_list .= "<a href='" . $group['permalink'] . "' title='" . $group['name'] . "'>";
				$group_list .= "<a href='" . bbp_get_forum_permalink( $group['group_meta']['forum_id'][0] ) . "' title='" . $group['name'] . "'>";
				$group_list .= "<h3>" . $group['name'] . "</h3>";
				$group_list .= "</a>";
				$group_list .= "<p>" . $group['description'] . "</p>";
				$group_list .= "<div class='clearfix'></div>";
				$group_list .= "<ul class='list-unstyled'>";
				$group_list .= "<li><span>Membros:</span>" . $group['group_meta']['total_member_count'] . "</li>";
				$group_list .= "<li><span>Criado em:</span>" . date( 'd-m-Y', strtotime( $group['date_created'] ) ) . "</li>";
				$group_list .= "<li><span>Última Atividade:</span>" . date( 'd-m-Y H:i', strtotime( $group['group_meta']['last_activity'] ) ) . "</li>";
				$group_list .= "<li><span>Fórum:</span><a href='" . bbp_get_forum_permalink( $group['group_meta']['forum_id'][0] ) . "' title='Acessar Fórum'>Acessar Fórum</a></li>";
				$group_list .= "</ul>";
				$group_list .= "</div>"; //.group-card
				$group_list .= "</div>"; //.col-4
			endif;

			if ( $group['status'] !== 'private' ):
				$group_list .= "<div class='col-md-4 group-item'>";
				$group_list .= "<div class='group-card'>";
				#$group_list .= "<a href='" . $group['permalink'] . "' title='" . $group['name'] . "'>";
				$group_list .= "<a href='" . bbp_get_forum_permalink( $group['group_meta']['forum_id'][0] ) . "' title='" . $group['name'] . "'>";
				$group_list .= "<img src='" . $group['group_meta']['avatar'] . "' alt='" . $group['name'] . "'>";
				$group_list .= "</a>";
				#$group_list .= "<a href='" . $group['permalink'] . "' title='" . $group['name'] . "'>";
				$group_list .= "<a href='" . bbp_get_forum_permalink( $group['group_meta']['forum_id'][0] ) . "' title='" . $group['name'] . "'>";
				$group_list .= "<h3>" . $group['name'] . "</h3>";
				$group_list .= "</a>";
				$group_list .= "<p>" . $group['description'] . "</p>";
				$group_list .= "<div class='clearfix'></div>";
				$group_list .= "<ul class='list-unstyled'>";
				$group_list .= "<li><span>Membros:</span>" . $group['group_meta']['total_member_count'] . "</li>";
				$group_list .= "<li><span>Criado em:</span>" . date( 'd-m-Y', strtotime( $group['date_created'] ) ) . "</li>";
				$group_list .= "<li><span>Última Atividade:</span>" . date( 'd-m-Y H:i', strtotime( $group['group_meta']['last_activity'] ) ) . "</li>";
				$group_list .= "<li><span>Fórum:</span><a href='" . bbp_get_forum_permalink( $group['group_meta']['forum_id'][0] ) . "' title='Acessar Fórum'>Acessar Fórum</a></li>";
				$group_list .= "</ul>";
				$group_list .= "</div>"; //.group-card
				$group_list .= "</div>"; //.col-4
			endif;

		endforeach;
		$group_list .= '</div>';//.row

		return $group_list;
	}

	public function wpss_groups_frontend() {

	}

	public function wpss_group_query() {
		global $wpdb;
		$query = "SELECT * FROM " . $wpdb->prefix . "bp_groups ORDER BY $this->order_by $this->order";

		$groups     = array();
		$get_groups = $wpdb->get_results( $query );

		foreach ( $get_groups as $key => $group ):
			$groups[] = [
				'id'           => $group->id,
				'creator_id'   => $group->creator_id,
				'name'         => $group->name,
				'slug'         => $group->slug,
				'description'  => $group->description,
				'status'       => $group->status,
				'enable_forum' => $group->enable_forum,
				'date_created' => $group->date_created,
				'parent_id'    => $group->parent_id,
				'permalink'    => get_home_url() . '/' . bp_get_groups_root_slug() . '/' . $group->slug,
				'group_meta'   => [
					'forum_id'           => groups_get_groupmeta( $group->id, 'forum_id' ),
					'last_activity'      => groups_get_groupmeta( $group->id, 'last_activity' ),
					'total_member_count' => groups_get_groupmeta( $group->id, 'total_member_count' ),
					'avatar'             => self::wpss_get_bbp_avatar( $group->id ),
				]
			];
		endforeach;

		return $groups;
	}

	public function wpss_get_bbp_avatar( $object_id, $object = 'group', $size = 'full', $html = false ) {
		return bp_core_fetch_avatar( array(
			'item_id' => $object_id,
			'object'  => $object,
			'html'    => $html,
			'type'    => $size
		) );
	}
}