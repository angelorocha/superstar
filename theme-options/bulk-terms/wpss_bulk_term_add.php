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

add_action('admin_menu', 'wpss_bulk_term_add');
function wpss_bulk_term_add(){
    add_submenu_page(
        'tools.php',
        __('Bulk Terms Add', 'wpss'),
        __('Bulk Terms Add', 'wpss'),
        'administrator',
        'wpss-bulk-terms-add',
        'wpss_bulk_term_add_cb'
    );
}

function wpss_bulk_term_add_cb(){
    $action        = menu_page_url('wpss-bulk-terms-add', false);
    $is_admin_menu = $_GET['page'] === 'wpss-bulk-terms-add';
    if($is_admin_menu):
        wp_enqueue_style('wpss-bulk-terms', _WPSS_ASSETS_DIR . 'theme-options/css/bulk-terms.css', '', _WPSS_FILE_VERSION, 'all');
    endif;
    $taxonomies_list = get_taxonomies();
    $tax_exclude     = array('nav_menu', 'link_category', 'post_format');
    $taxonomies      = array();
    foreach($taxonomies_list as $item):
        if(!in_array($item, $tax_exclude)):
            $taxonomies[$item] = get_taxonomy($item)->label;
        endif;
    endforeach;

    $select = '<option value="">--------------</option>';
    foreach($taxonomies as $key => $tax):
        $select .= '<option value="' . $key . '">' . $tax . '</option>';
    endforeach;

    ?>
    <div class="term-container">
        <div class="box">
            <h3>Cadastrar Termos</h3>
            <form method="post" action="<?= $action; ?>">
                <label for="add_terms">Selecione a taxonomia que receberá os termos:</label>
                <select name="add_terms" id="add_terms" required="required">
                    <?= $select; ?>
                </select>

                <label for="termos">Insira a lista de termos, um por linha:</label>
                <textarea name="termos" id="termos" required="required"></textarea>
                <input type="submit" value="Inserir" name="mass_insert_terms">
            </form>
            <?php
            if(isset($_POST['mass_insert_terms'])){
                $terms = array_unique(explode("\n", $_POST['termos']));
                $tax   = sanitize_text_field($_POST['add_terms']);
                echo '<ul>';
                foreach($terms as $term){
                    if(term_exists($term, $tax)){
                        echo '<li>' . $term . ' <span class="error">Já esta cadastrado!</span></li>';
                    }else{
                        wp_insert_term(sanitize_text_field($term), $tax, array('slug' => sanitize_title($term)));
                        echo '<li>Termo Criado: <span class="success">' . $term . '</span></li>';
                    }
                }
                echo '</ul>';
            }
            ?>
        </div><!-- .box -->

        <div class="box">
            <h3>Consultar Termos</h3>
            <form method="post" action="">
                <label for="select_terms">Selecione a taxonomia para consultar:</label>
                <select name="select_terms" id="select_terms" required="required">
                    <?= $select; ?>
                </select>
                <input type="submit" value="Consultar" name="term_search">
            </form>
            <?php
            if(isset($_POST['term_search'])){
                $tax   = sanitize_text_field($_POST['select_terms']);
                $terms = get_terms(array('taxonomy' => $tax, 'hide_empty' => false));
                echo '<label class="text-ids" for="text-ids">IDs dos termos:</label>';
                echo '<textarea id="text-ids">';
                foreach($terms as $term){
                    echo $term->term_id . "\n";
                }
                echo '</textarea>';
                echo '<ul>';
                foreach($terms as $term){
                    echo '<li>' . $term->name . '</li>';
                }
                echo '</ul>';
            }
            ?>
        </div><!-- .box -->

    </div><!-- .term-container -->
    <?php
}