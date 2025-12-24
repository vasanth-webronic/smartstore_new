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
        $action['edit'] = '<a style="cursor:pointer" title="Customer Unique Price"  href="#TB_inline?&width=400&height=300&inlineId=taw-form-article-customerprice-form" id="edit-btn" class="thickbox taw-customerprice-row-edit">'.__( 'Edit', TAW_TEXT_DOMAIN ).'</a>';
        $action['delete'] = '<a style="cursor:pointer" class="taw-customerprice-row-delete">'.__( 'Delete', TAW_TEXT_DOMAIN ).'</a>';
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

        $query="SELECT * FROM `taw_customer_unique_price`";
        $countQ="SELECT count(id) as count FROM `taw_customer_unique_price`";
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
                'taw_id'            => '<div class="data-hld" data-action="taw_delete_article_customerprice" data-id="'.$item->id.'" data-artno="'.$item->art_no.'" data-customerno="'.$item->customer_no.'" data-price="'.$item->price.'" data-currency="'.$item->currency.'" >'.$item->id.'</div>',
                'taw_artno'             => $item->art_no,
                'taw_customer_no'       => $item->customer_no,
                'taw_price'             => $item->price,
                'taw_currency'          => $item->currency,
                'taw_updated_at'        => $item->updated_at                
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
            'taw_artno'         => __( 'Artno', TAW_TEXT_DOMAIN ),
            'taw_customer_no'           => __( 'Customer No', TAW_TEXT_DOMAIN ),
            'taw_price'         => __( 'Price', TAW_TEXT_DOMAIN ),
            'taw_currency'          => __( 'Currency', TAW_TEXT_DOMAIN ),
            'taw_updated_at'    => __( 'Updated Date', TAW_TEXT_DOMAIN ),
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
            case 'taw_customer_no': 
            case 'taw_price':
            case 'taw_currency':
            case 'taw_updated_at':
            return $item[$column_name];
            default:
            return 'no list found';
        }
    }

    public function get_sortable_columns()
    {
        return array('taw_id' => array('id', false),'taw_artno' => array('art_no', false),'taw_customer_no' => array('customer_no', false),
        'taw_price' => array('price', false),'taw_currency' => array('currency', false));
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

 <div style="float:left;width:100%;"><h2 style="float: left;margin: 0;"><?php _e( 'Customer Unique Price List', TAW_TEXT_DOMAIN ); ?></h2> 
<a style="float:right;display:block;margin-left:20px;text-decoration: none;font-size:12px;border: solid 1px;padding: 5px 8px;border-radius:4px;" href="#TB_inline?&amp;width=400&amp;height=350&amp;inlineId=taw-form-article-customerprice-form" name="Create Customer Unique Price" id="add-new-btn" class="thickbox taw-customerprice-row-edit">+ Add New</a>
<form method="post">
        <?php $object->search_box('Search', 'taw_artno');?>
 </form>
</div>
 <a style="float:left;display:block;margin-right:20px;text-decoration: none;font-size:12px;border: solid 1px;padding: 3px 8px;display:none;" href="#TB_inline?&width=400&height=300&inlineId=taw-form-article-customerprice-form" name="Customer Unique Price" id="edit-btn" class="thickbox">+ <?php echo __( 'Add New', TAW_TEXT_DOMAIN )?></a>

<?php
$object->display();
?>
</div>
<script> var aurl='<?php echo admin_url('admin-ajax.php');?>';</script>
 <div id="taw-form-article-customerprice-form" style="display:none;">
 <input type="hidden" value="" id="taw-form-article-id"/>
    <p class="wlt-popup">
        <label id="artno-label"><?php _e( 'Art no '); ?></label>
        <input type="text" class="input-text" id="taw-form-article-customerprice-artno" style="width:100%">
    </p>

    <p class="wlt-popup">
        <label><?php _e( 'Customer No' ); ?></label>
        <input type="text" class="input-text" id="taw-form-article-customerprice-customerno" style="width:100%">
    </p>

    <p class="wlt-popup">
        <label><?php _e( 'Price' ); ?></label>
        <input type="text" class="input-text" id="taw-form-article-customerprice-price" style="width:100%">
    </p>

    <p class="wlt-popup">
        <label><?php _e( 'Currency' ); ?></label>
        <input type="text" class="input-text" id="taw-form-article-customerprice-currency" style="width:100%">
    </p>
    

    <input id="taw-form-article-customerprice-submit" class="button-primary" type="button" value="<?php _e( 'Save', TAW_TEXT_DOMAIN ); ?>">
</div>


