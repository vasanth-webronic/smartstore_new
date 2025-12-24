<?php

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

/**
 * Class WLT_list_Table
 */
class TAW_family_list_Table extends WP_List_Table
{

    /**
     * Prepares the list of items for displaying.
     */
    public function prepare_items()
    {

        $data = $this->get_list_table_data();

        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }

    /**
     * Wp list table bulk actions 
     */
    public function get_bulk_actions()
    {

        return array(

            //'wlt_delete'  => __( 'Delete', TAW_TEXT_DOMAIN ),
            //'wlt_edit'        => __( 'Edit', TAW_TEXT_DOMAIN )
        );
    }

    /**
     * WP list table row actions
     */
    public function handle_row_actions($item, $column_name, $primary)
    {

        if ($primary !== $column_name) {
            return '';
        }

        $action = [];
        $action['edit'] = '<a style="cursor:pointer" title="Article Diagram"  href="#TB_inline?&width=400&height=360&inlineId=taw-form-article-diagram-form"  id="edit-btn" class="thickbox taw-diagram-row-edit">' . __('Edit', TAW_TEXT_DOMAIN) . '</a>';
        $action['delete'] = '<a style="cursor:pointer" class="taw-diagram-row-delete">' . __('Delete', TAW_TEXT_DOMAIN) . '</a>';
        //$action['quick-edit'] = '<a>'.__( 'Update', TAW_TEXT_DOMAIN ).'</a>';
        //$action['view'] = '<a>'.__( 'View', TAW_TEXT_DOMAIN ).'</a>';

        return $this->row_actions($action);
    }



    /**
     * Display columns datas
     */
    public function get_list_table_data()
    {

        $order_by = isset($_GET['orderby']) ? $_GET['orderby'] : '';
        $order = isset($_GET['order']) ? $_GET['order'] : '';
        $search_term = isset($_POST['s']) ? $_POST['s'] : '';

        //$data = array_slice($data['item'],(,);

?><section style="margin: 30px 0 0 0; ">


            <?php
            $data_array = [];
            global $wpdb;
            $lang = getSiteCurrentLang();

            $query = "SELECT * FROM `taw_diagram` where lang='$lang'";
            $countQ = "SELECT count(id) as count FROM `taw_diagram` where lang='$lang'";
            if (!empty($search_term)) {
                $query .= " AND (art_no LIKE '%$search_term%')";
                $countQ .= " AND (art_no LIKE '%$search_term%')";
            }
            if (!empty($order_by)) {
                $query .= "ORDER BY $order_by $order";
            }

            $perPage = 20;
            $currentPage = $this->get_pagenum();
            $query .= " limit " . (($currentPage - 1) * $perPage) . "," . $perPage;

            $sub_items = $wpdb->get_results($query);

            $totalItems = $wpdb->get_var($countQ);

            foreach ($sub_items as $item) {

                $data_array[] = [
                    'taw_id'        => '<div class="data-hld" data-action="taw_delete_article_diagram" data-id="' . $item->id . '" data-artno="' . $item->art_no . '" data-diagram="' . $item->diagram_id . '" data-diagram2="' . $item->diagram2_id . '" data-diagram3="' . $item->diagram3_id . '" >' . $item->id . '</div>',
                    'taw_artno'         => $item->art_no,
                    'taw_diagram'      => (!empty($item->diagram_id)) ? '<img src="' . wp_get_attachment_image_url($item->diagram_id) . '" style="height:75px; width:75px;" />' : ' ',
                    'taw_diagram2'       => (!empty($item->diagram2_id)) ? '<img src="' . wp_get_attachment_image_url($item->diagram2_id) . '" style="height:75px; width:75px;" />' : ' ',
                    'taw_diagram3'       => (!empty($item->diagram3_id)) ? '<img src="' . wp_get_attachment_image_url($item->diagram3_id) . '" style="height:75px; width:75px;" />' : ' ',
                    'taw_updated_at'    => $item->updated_at
                ];
            }


            $this->set_pagination_args(array(
                'total_items' => $totalItems,
                'per_page'    => $perPage
            ));


            ?></section><?php
                        return $data_array;
                    }

                    /**
                     * Gets a list of all, hidden and sortable columns
                     */
                    public function get_hidden_columns()
                    {
                        return [];
                    }

                    /**
                     * Gets a list of columns.
                     */
                    public function get_columns()
                    {

                        $columns = array(
                            'cb'                => '<input type="checkbox" class="wlt-selected" />',
                            'taw_id'            => __('ID', TAW_TEXT_DOMAIN),
                            'taw_artno'         => __('Artno', TAW_TEXT_DOMAIN),
                            'taw_diagram'           => __('Diagram', TAW_TEXT_DOMAIN),
                            'taw_diagram2'           => __('Diagram2', TAW_TEXT_DOMAIN),
                            'taw_diagram3'           => __('Diagram3', TAW_TEXT_DOMAIN),
                            // 'taw_gallery'           => __('Gallery', TAW_TEXT_DOMAIN),
                            // 'taw_colour'            => __('Colour', TAW_TEXT_DOMAIN),
                            // 'taw_alt_text'          => __('Alt Text', TAW_TEXT_DOMAIN),
                            // 'taw_updated_at'    => __('Updated Date', TAW_TEXT_DOMAIN),
                        );
                        return $columns;
                    }

                    /**
                     * Return column value
                     */
                    public function column_default($item, $column_name)
                    {

                        switch ($column_name) {
                            case 'taw_id':
                            case 'taw_artno':
                            case 'taw_diagram':
                            case 'taw_diagram2':
                            case 'taw_diagram3':
                            case 'taw_updated_at':
                                return $item[$column_name];
                            default:
                                return 'no list found';
                        }
                    }

                    public function get_sortable_columns()
                    {
                        return array(
                            'taw_id' => array('id', false), 'taw_artno' => array('art_no', false), 'taw_diagram' => array('diagram', false),
                            'taw_diagram2' => array('diagram2', false), 'taw_diagram3' => array('diagram3', false)
                        );
                    }




                    /**
                     * Rows check box
                     */
                    public function column_cb($items)
                    {

                        $top_checkbox = '<input type="checkbox" class="wlt-selected" />';
                        return $top_checkbox;
                    }
                }

                $object = new TAW_family_list_Table();
                $object->prepare_items();
                        ?>

<div style="padding:0 30px 0 0;">

    <div style="float:left;width:100%;">
        <h2 style="float: left;margin: 0;"><?php _e('Article Diagram List', TAW_TEXT_DOMAIN); ?></h2>
        <a style="float:right;display:block;margin-left:20px;text-decoration: none;font-size:12px;border: solid 1px;padding: 5px 8px;border-radius:4px;" href="#TB_inline?&amp;width=400&amp;height=360&amp;inlineId=taw-form-article-diagram-form" id="add-new-btn" name="Create Article Diagram" class="thickbox taw-diagram-row-edit">+ Add New</a>
        <form method="post">
            <?php $object->search_box('Search', 'taw_artno'); ?>
        </form>
    </div>
    <a style="float:left;display:block;margin-right:20px;text-decoration: none;font-size:12px;border: solid 1px;padding: 3px 8px;display:none;" href="#TB_inline?&width=400&height=300&inlineId=taw-form-article-diagram-form" id="edit-btn" name="Article Diagram" class="thickbox">+ <?php echo __('Add New', TAW_TEXT_DOMAIN) ?></a>

    <?php
    $object->display();
    ?>
</div>
<script>
    var aurl = '<?php echo admin_url('admin-ajax.php'); ?>';
</script>

<div id="taw-form-article-diagram-form" style="display:none;">
    <input type="hidden" value="" id="taw-form-article-id" />
    <p class="wlt-popup">
        <label id="artno-label"><?php _e('Art no '); ?></label>
        <input type="text" class="input-text" id="taw-form-article-diagram-artno" style="width:100%">
    </p>
    <p class="wlt-popup">
        <label><?php _e('Diagram'); ?></label>
        <input type="button" class="button" id="taw-form-article-diagram-upload-btn" value="<?php _e('Upload Image', TAW_TEXT_DOMAIN); ?>">
        <input type="hidden" class="input-text" id="taw-form-article-diagram" name="taw-form-article-diagram">
    </p>

    <p class="wlt-popup">
        <label><?php _e('Diagram2'); ?></label>
        <input type="button" class="button" id="taw-form-article-diagram2-upload-btn" value="<?php _e('Upload Image', TAW_TEXT_DOMAIN); ?>">
        <input type="hidden" class="input-text" id="taw-form-article-diagram2" name="taw-form-article-diagram2">
    </p>

    <p class="wlt-popup">
        <label><?php _e('Diagram3'); ?></label>
        <input type="button" class="button" id="taw-form-article-diagram3-upload-btn" value="<?php _e('Upload Image', TAW_TEXT_DOMAIN); ?>">
        <input type="hidden" class="input-text" id="taw-form-article-diagram3" name="taw-form-article-diagram3">    </p>

    <input id="taw-form-article-diagram-submit" class="button-primary" type="button" value="<?php _e('Save', TAW_TEXT_DOMAIN); ?>">
</div>
<script>
    jQuery(document).ready(function($) {
        $('#taw-form-article-diagram-upload-btn').click(function(e) {
            e.preventDefault();
            var custom_uploader = wp.media({
                    title: 'Choose Image',
                    button: {
                        text: 'Choose Image'
                    },
                    multiple: false
                })
                .on('select', function() {
                    var attachment = custom_uploader.state().get('selection').first().toJSON();
                    $('#taw-form-article-diagram').val(attachment.id);
                    
                })
                .open();
        });
        $('#taw-form-article-diagram2-upload-btn').click(function(e) {
            e.preventDefault();
            var custom_uploader = wp.media({
                    title: 'Choose Image',
                    button: {
                        text: 'Choose Image'
                    },
                    multiple: false
                })
                .on('select', function() {
                    var attachment = custom_uploader.state().get('selection').first().toJSON();
                    $('#taw-form-article-diagram2').val(attachment.id);
                    
                })
                .open();
        });
        $('#taw-form-article-diagram3-upload-btn').click(function(e) {
            e.preventDefault();
            var custom_uploader = wp.media({
                    title: 'Choose Image',
                    button: {
                        text: 'Choose Image'
                    },
                    multiple: false
                })
                .on('select', function() {
                    var attachment = custom_uploader.state().get('selection').first().toJSON();
                    $('#taw-form-article-diagram3').val(attachment.id);
                    
                })
                .open();
        });
    });
</script>