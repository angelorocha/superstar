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
		'table_size'         => ( empty( $table_args['table_size'] ) ? array( array( 30, 60, 90, - 1 ), array( 30, 60, 90, "All" ) ) : $table_args['table_size'] ),
		'column'             => ( empty( $table_args['column'] ) ? array( '100' ) : $table_args['column'] ),
		'search_label'       => ( empty( $table_args['search_label'] ) ? 'Pesquisar' : $table_args['search_label'] ),
		'search_placeholder' => ( empty( $table_args['search_placeholder'] ) ? 'Buscar na tabela' : $table_args['search_placeholder'] ),
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
                    "sEmptyTable": "Nenhum registro encontrado",
                    "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                    "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                    "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                    "sInfoPostFix": "",
                    "sInfoThousands": ".",
                    "sLengthMenu": "_MENU_ Itens por página",
                    "sLoadingRecords": "Carregando...",
                    "sProcessing": "Processando...",
                    "sZeroRecords": "Nenhum registro encontrado",
                    "sSearch": "<?=$table_args['search_label'];?>",
                    'sSearchPlaceholder': '<?=$table_args['search_placeholder'];?>',
                    "oPaginate": {
                        "sNext": "&raquo;",
                        "sPrevious": "&laquo;",
                        "sFirst": "Primeiro",
                        "sLast": "Último"
                    },
                    "oAria": {
                        "sSortAscending": ": Ordenar colunas de forma ascendente",
                        "sSortDescending": ": Ordenar colunas de forma descendente"
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