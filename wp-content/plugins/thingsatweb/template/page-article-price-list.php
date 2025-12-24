
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
        $action['edit'] = '<a style="cursor:pointer" title="Article Price"  href="#TB_inline?&width=400&height=300&inlineId=taw-form-article-price-form"  id="edit-btn" class="thickbox taw-price-row-edit">'.__( 'Edit', TAW_TEXT_DOMAIN ).'</a>';
        $action['delete'] = '<a style="cursor:pointer" class="taw-price-row-delete">'.__( 'Delete', TAW_TEXT_DOMAIN ).'</a>';
        //$action['quick-edit'] = '<a>'.__( 'Update', TAW_TEXT_DOMAIN ).'</a>';
        //$action['view'] = '<a>'.__( 'View', TAW_TEXT_DOMAIN ).'</a>';

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

        $query="SELECT * FROM `taw_article_price`";
        $countQ="SELECT count(id) as count FROM `taw_article_price`";
        if(!empty($search_term)){
            $query.= " where art_no like '%$search_term%'";
            $countQ.= " where art_no like '%$search_term%'";
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
                'taw_id'                => '<div class="data-hld"  data-action="taw_delete_article_price" data-id="'.$item->id.'" data-artno="'.$item->art_no.'" data-priceb2b="'.$item->price_b2b.'" data-priceresellereur="'.$item->price_reseller_eur.'" data-priceresellersek="'.$item->price_reseller_sek.'" >'.$item->id.'</div>',
                'taw_artno'             => $item->art_no,
                'taw_priceb2b'              => $item->price_b2b,
                'taw_priceresellersek'              => $item->price_reseller_sek,
                'taw_priceresellereur'              => $item->price_reseller_eur,
                'taw_date'      => $item->updated_at                
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
            'taw_artno'         => __( 'Art no', TAW_TEXT_DOMAIN ),
            'taw_priceb2b'          => __( 'Price B2B', TAW_TEXT_DOMAIN ),          
            'taw_priceresellersek'          => __( 'Price Reseller SEK', TAW_TEXT_DOMAIN ),          
            'taw_priceresellereur'          => __( 'Price Reseller EUR', TAW_TEXT_DOMAIN ),          
            'taw_date'  => __( 'Updated Date', TAW_TEXT_DOMAIN ),
        );
        return $columns;
    }

    /**
     * Return column value
     */
    public function column_default( $item, $column_name ) {

        switch ($column_name) {
            case 'taw_id':
            case 'taw_artno':
            case 'taw_priceb2b':
            case 'taw_priceresellersek':
            case 'taw_priceresellereur':
            case 'taw_date':
            return $item[$column_name];
            default:
            return 'no list found';
        }
    }

    public function get_sortable_columns()
    {
        return array('taw_id' => array('id', false),'taw_artno' => array('art_no', false),
        'taw_priceb2b' => array('price_b2b', false),'taw_priceresellersek' => array('price_reseller_sek', false),
        'taw_priceresellereur' => array('price_reseller_eur', false));
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

 <div style="float:left;width:100%;"><h2 style="float: left;margin: 0;"><?php _e( 'Article Price List', TAW_TEXT_DOMAIN ); ?></h2> 
<a style="float:right;display:block;margin-left:20px;text-decoration: none;font-size:12px;border: solid 1px;padding: 5px 8px;border-radius:4px;" href="#TB_inline?&amp;width=400&amp;height=340&amp;inlineId=taw-form-article-price-form" name="Create Article" id="add-new-btn" class="thickbox taw-price-row-edit">+ Add New</a>
<form method="post">
        <?php $object->search_box('Search', 'taw_artno');?>
 </form>
</div>
 <a style="float:left;display:block;margin-right:20px;text-decoration: none;font-size:12px;border: solid 1px;padding: 3px 8px;display:none;" href="#TB_inline?&width=400&height=300&inlineId=taw-form-article-price-form" name="Article Price"  id="edit-btn" class="thickbox">+ <?php echo __( 'Add New', TAW_TEXT_DOMAIN )?></a>

<?php
$object->display();
?>
</div>
<script> var aurl='<?php echo admin_url('admin-ajax.php');?>';</script>
 <div id="taw-form-article-price-form" style="display:none;">
 
 <input type="hidden" value="" id="taw-form-article-id"/>
    <p class="wlt-popup">
    <label id="artno-label"><?php _e( 'Art no '); ?></label>
        <input type="text" class="input-text" id="taw-form-article-price-artno" style="width:100%">
    </p>

    <p class="wlt-popup">
        <label><?php _e( 'Price B2B' ); ?></label>
        <input type="text" class="input-text" id="taw-form-article-price-priceb2b" style="width:100%" >
    </p>
    <p class="wlt-popup">
        <label><?php _e( 'Price Reseller SEK' ); ?></label>
        <input type="text" class="input-text" id="taw-form-article-price-priceresellersek" style="width:100%" >
    </p>
    <p class="wlt-popup">
        <label><?php _e( 'Price Reseller EUR' ); ?></label>
        <input type="text" class="input-text" id="taw-form-article-price-priceresellereur" style="width:100%" >
    </p>
    
    

    <input id="taw-form-article-price-submit" class="button-primary" type="button" value="<?php _e( 'Save', TAW_TEXT_DOMAIN ); ?>">
</div>


