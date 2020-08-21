<?php
/**
 * @param $table_id
 * @param array $table_args
 *
 * @link                https://angelorocha.com.br
 * @copyleft            2020
 * @license             GNU GPL 3 (https://www.gnu.org/licenses/gpl-3.0.html)
 * @package WordPress
 * @subpackage superstar
 * @since 1.0.0
 * @author              Angelo Rocha
 * @author              Angelo Rocha <contato@angelorocha.com.br>
 *
 * Usage:
 * wpss_datatables_script(
 * 'table',                                                // The table id without "#"
 * array(                                                  // Array options
 * 'scroll_x'           => 'true',                     // Define true to scroll X
 * 'order'              => array( 1, "ASC" ),          // Select column order by and ASC or DESC to order
 * 'table_size'         => array( array(),array() ),   // Select table limit
 * 'column'             => array( 100, 100, 250 ),     // Default columns definition
 * 'search_label'       => 'Pesquisar',                // Default search label
 * 'search_placeholder' => 'Buscar na tabela',         // Default input search placeholder
 * 'print'              => true                        // Enable print buttons
 * )
 * );
 *
 *
 */

function wpss_datatables_script( $table_id, $table_args = array(), $before_script = '', $after_script = '' ) {

	wp_enqueue_script( 'wpss-datatables', _WPSS_JS_DIR . 'datatables.min.js', array( 'jquery' ), _WPSS_FILE_VERSION, true );
	wp_enqueue_style( 'wpss-datatables', _WPSS_CSS_DIR . 'datatables.min.css', '', _WPSS_FILE_VERSION, 'all' );

	$table_args = array(
		'scroll_x'           => ( empty( $table_args['scroll_x'] ) ? 'false' : $table_args['scroll_x'] ),
		'order'              => ( empty( $table_args['order'] ) ? array( 0, '"desc"' ) : $table_args['order'] ),
		'table_size'         => ( empty( $table_args['table_size'] ) ? array( array( 30, 60, 90, - 1 ), array( 30, 60, 90, __("All", "wpss") ) ) : $table_args['table_size'] ),
		'column'             => ( empty( $table_args['column'] ) ? array( '100' ) : $table_args['column'] ),
		'search_label'       => ( empty( $table_args['search_label'] ) ? __('Search', 'wpss') : $table_args['search_label'] ),
		'search_placeholder' => ( empty( $table_args['search_placeholder'] ) ? __('Search in table', 'wpss') : $table_args['search_placeholder'] ),
		'print'              => ( empty( $table_args['print'] ) ? false : $table_args['print'] ),
	);

	?>
    <script>
        jQuery(function ($) {
            $.fn.dataTableExt.ofnSearch['string'] = function ( data ) {
                return ! data ?
                    '' :
                    typeof data === 'string' ?
                        data
                            .replace( /\n/g, ' ' )
                            .replace( /á/g, 'a' )
                            .replace( /é/g, 'e' )
                            .replace( /í/g, 'i' )
                            .replace( /ó/g, 'o' )
                            .replace( /ú/g, 'u' )
                            .replace( /ê/g, 'e' )
                            .replace( /î/g, 'i' )
                            .replace( /ô/g, 'o' )
                            .replace( /è/g, 'e' )
                            .replace( /ï/g, 'i' )
                            .replace( /ü/g, 'u' )
                            .replace( /ç/g, 'c' ) :
                        data;
            };
            $('#<?=$table_id;?>').DataTable({
				<?=$before_script;?>
                "scrollX": <?=$table_args['scroll_x']; ?>,
                "order": [[<?=implode( ',', $table_args['order'] );?>]],
                "lengthMenu": <?=wp_json_encode( $table_args['table_size'] )?>,
                "columnDefs": [
					<?php foreach ((array) $table_args['column'] as $key => $column): ?>
                    {"width": "<?=$column;?>", "targets": <?=(int) $key?>},
					<?php endforeach;?>
                ],
                "oLanguage": {
                    "sEmptyTable": "<?= __("No records found", "wpss"); ?>",
                    "sInfo": "<?= __("Showing from _START_ to _END_ of _TOTAL_ records", "wpss"); ?>",
                    "sInfoEmpty": "<?= __("Showing 0 to 0 of 0 entries", "wpss"); ?>",
                    "sInfoFiltered": "<?= __("(Filtered from _MAX_ records)", "wpss"); ?>",
                    "sInfoPostFix": "",
                    "sInfoThousands": ".",
                    "sLengthMenu": "<?= __("_MENU_ Items per page", "wpss")?>",
                    "sLoadingRecords": "<?= __("Loading...", "wpss"); ?>",
                    "sProcessing": "<?= __("Processing ...", "wpss"); ?>",
                    "sZeroRecords": "<?= __("No records found", "wpss"); ?>",
                    "sSearch": "<?=$table_args['search_label'];?>",
                    "sSearchPlaceholder": "<?=$table_args['search_placeholder'];?>",
                    "oPaginate": {
                        "sNext": "&raquo;",
                        "sPrevious": "&laquo;",
                        "sFirst": "<?= __("First", "wpss"); ?>",
                        "sLast": "<?= __("Last", "wpss"); ?>"
                    },
                    "oAria": {
                        "sSortAscending": "<?= __(": Sort columns in ascending order", "wpss"); ?>",
                        "sSortDescending": "<?= __(": Sort columns in descending order", "wpss"); ?>"
                    },
                }<?php if($table_args['print']): ?>,
                dom: 'Bfrtip',
                buttons: [
                    {extend: 'excelHtml5', text: '<i class="fa fa-table" aria-hidden="true"></i> XLS'},
                    {extend: 'pdfHtml5', download: 'open', text: '<i class="far fa-file-pdf"></i> PDF'},
                    {extend: 'csvHtml5', text: '<i class="fa fa-align-justify" aria-hidden="true"></i> CSV'}
                ],
				<?php endif;?>
				<?=$after_script;?>

            });
        });
    </script>
	<?php
}