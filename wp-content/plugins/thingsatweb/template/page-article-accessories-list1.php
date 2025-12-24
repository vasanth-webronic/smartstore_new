<?php

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Class WLT_list_Table
 */
class TAW_family_list_Table extends WP_List_Table {

    /**
     * Prepares the list of items for displaying.
     */
    public function prepare_items() {

        
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
    public function get_bulk_actions() {

        return array(

            //'wlt_delete'  => __( 'Delete', TAW_TEXT_DOMAIN ),
            //'wlt_edit'        => __( 'Edit', TAW_TEXT_DOMAIN )
        );
    }

    /**
     * WP list table row actions
     */
    public function handle_row_actions( $item, $column_name, $primary ) {
        
        if( $primary !== $column_name ) {
            return '';
        }
        $action = [];
        $action['edit'] = '<a style="cursor:pointer" title="Article Accessories"  href="#TB_inline?&width=400&height=260&inlineId=taw-form-article-accessories-form"  id="edit-btn" class="thickbox taw-accessories-row-edit">'.__( 'Edit', TAW_TEXT_DOMAIN ).'</a>';
        $action['delete'] = '<a style="cursor:pointer" class="taw-accessories-row-delete">'.__( 'Delete', TAW_TEXT_DOMAIN ).'</a>';
        return $this->row_actions( $action );
    }



    /**
     * Display columns datas
     */
    public function get_list_table_data() {

        $order_by = isset( $_GET['orderby'] ) ? $_GET['orderby'] : '';
        $order = isset( $_GET['order'] ) ? $_GET['order'] : '';
        $search_term = isset( $_POST['s'] ) ? $_POST['s'] : '';

        //$data = array_slice($data['item'],(,);

        ?><section style="margin: 30px 0 0 0; ">
            

            <?php
        $data_array = [];   
        global $wpdb;

        $query="SELECT * FROM `taw_product_accessories`";
        $countQ="SELECT count(id) as count FROM `taw_product_accessories`";
        if(!empty($search_term)){
            $query.= " where acs_article like '%$search_term%'";
            $countQ.= " where acs_article like '%$search_term%'";
        }
        if(!empty($order_by)){
            $query.=" order by $order_by $order";
        }

        $perPage = 20;
        $currentPage = $this->get_pagenum();
        $query.=" limit ".(($currentPage-1)*$perPage).",".$perPage;     

        $sub_items = $wpdb->get_results($query);
        
        $totalItems=$wpdb->get_var($countQ);

        
        foreach($sub_items as $item){

            $data_array[] = [
                'taw_id'                => '<div class="data-hld" data-action="taw_delete_article_accessories" data-id="'.$item->id.'" data-parentarticle="'.$item->parent_article.'" data-acsarticle="'.$item->acs_article.'"  data-noplates="'.$item->no_plates.'" >'.$item->id.'</div>',
                'taw_parentarticle'     => $item->parent_article,
                'taw_acsarticle'        => $item->acs_article,
                'taw_noplates'          =>  $item->no_plates,           
            ];

        }

        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );


        ?></section><?php
        return $data_array;

    }

    /**
     * Gets a list of all, hidden and sortable columns
     */
    public function get_hidden_columns() {
        return [];
    }

    /**
     * Gets a list of columns.
     */
    public function get_columns() {     

        $columns = array(
            'cb'                => '<input type="checkbox" class="wlt-selected" />',
            'taw_id'            => __( 'ID', TAW_TEXT_DOMAIN ),
            'taw_acsarticle'          => __( 'AccessoriesArtnumber', TAW_TEXT_DOMAIN ),
            'taw_parentarticle'         => __( 'ParentArtnumber', TAW_TEXT_DOMAIN ),
            'taw_noplates'            => __( 'Number of plates', TAW_TEXT_DOMAIN ),
            
        );
        return $columns;
    }

    /**
     * Return column value
     */
    public function column_default( $item, $column_name ) {

        switch ($column_name) {
            case 'taw_id':
            case 'taw_acsarticle':    
            case 'taw_parentarticle':
            case 'taw_noplates':
            return $item[$column_name];
            default:
            return 'no list found';
        }
    }

    public function get_sortable_columns()
    {
        return array('taw_id' => array('id', false),'taw_acsarticle' => array('acs_article', false),
        'taw_parentarticle' => array('parent_article', false),'taw_noplates' => array('no_plates', false));
    }

    /**
     * Rows check box
     */
    public function column_cb( $items ) {

        $top_checkbox = '<input type="checkbox" class="wlt-selected" />';
        return $top_checkbox; 
    }
}

$object = new TAW_family_list_Table();
$object->prepare_items();
?>

<div style="padding:0 30px 0 0;">

<div style="float:left;width:100%;"><h2 style="float: left;margin: 0;"><?php _e( 'Article Accessories List', TAW_TEXT_DOMAIN ); ?></h2> 
<a style="float:right;display:block;margin-left:20px;text-decoration: none;font-size:12px;border: solid 1px;padding: 5px 8px;border-radius:4px;" href="#TB_inline?&amp;width=400&amp;height=300&amp;inlineId=taw-form-article-accessories-form" name="Create Article Accessories" id="add-new-btn" class="thickbox taw-accessories-row-edit">+ Add New</a>
<form method="post">
        <?php $object->search_box('Search', 'taw_artno');?>
 </form>
</div>
 <a style="float:left;display:block;margin-right:20px;text-decoration: none;font-size:12px;border: solid 1px;padding: 3px 8px;display:none;" href="#TB_inline?&width=400&height=300&inlineId=taw-form-article-accessories-form" name="Article Accessories"  id="edit-btn" class="thickbox">+ <?php echo __( 'Add New', TAW_TEXT_DOMAIN )?></a>

<?php
$object->display();
?>
</div>
<script> var aurl='<?php echo admin_url('admin-ajax.php');?>';</script>
 <div id="taw-form-article-accessories-form" style="display:none;">   
 <input type="hidden" value="" id="taw-form-article-id"/>

    <p class="wlt-popup">
        <label><?php _e( 'AccessoriesArtnumber' ); ?></label>
        <input type="text" class="input-text" id="taw-form-article-accessories-accessoriesArtnumber" style="width:100%">
    </p>

    <p class="wlt-popup">
        <label><?php _e( 'ParentArtnumber' ); ?></label>
        <input type="text" class="input-text" id="taw-form-article-accessories-parentnumber" style="width:100%">
    </p>

    <p class="wlt-popup">
        <label><?php _e( 'Number of plates' ); ?></label>
        <input type="text" class="input-text" id="taw-form-article-accessories-plates" style="width:100%">
    </p>
    

    <input id="taw-form-article-accessories-submit" class="button-primary" type="button" value="<?php _e( 'Save', TAW_TEXT_DOMAIN ); ?>">
</div>


