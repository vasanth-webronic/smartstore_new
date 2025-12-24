<?php 
    $art_no=$product->get_sku();

    
    if(empty($art_no)){
        echo "Please set sku";
        return;
    }

    global $wpdb;
    $data=$wpdb->get_results("SELECT * FROM `taw_article_price` where art_no='$art_no'");   

    if(empty($data)){
      echo "empty";
      $b2b_price=0;
      $price_reseller_sek=0;
      $price_reseller_eur=0;
    }else{
      $data=$data[0];
      $b2b_price=$data->price_b2b;
      $price_reseller_sek=$data->price_reseller_sek;
      $price_reseller_eur=$data->price_reseller_eur;
    }
    
    CSF::$enqueue = true;
    CSF::add_admin_enqueue_scripts();
  
    echo '<div class="csf-onload">';
    echo '<h1 style="padding:0 15px;width:calc(100% - 30px);">Article No - '.$art_no." <button style='float:right' type='button' class='button'>Update</button></h1>";

      /**
       *  @field
       *  @value
       *  @unique
      */ 
  
      CSF::field(array(
        'id'            => 'opt-tabbed-1',
        'type'          => 'tabbed',
        'title'         => '',
        'tabs'          => array(
          array(
            'title'     => 'Price',
            // 'icon'      => 'fa fa-heart',
            'fields'    => array(
              array(
                'id'    => 'opt-text-1',
                'type'  => 'text',
                'value' => $b2b_price,
                'title' => 'B2B',
              ),array(
                'id'    => 'opt-text-1',
                'type'  => 'text',
                'value' => $price_reseller_sek,
                'title' => 'Reseller SEK',
              ),array(
                'id'    => 'opt-text-1',
                'type'  => 'text',
                'value' => $price_reseller_eur,
                'title' => 'Reseller EUR',
              ),
            )
          ),
          array(
            'title'     => 'Special Price',
            'fields'    => array(
                array(
                    'id'     => 'taw_customer_price',
                    'type'   => 'repeater',
                    'title'  => 'Customers',
                    'fields' => array(                      
                        array(
                            'id'          => 'customer',
                            'type'        => 'select',
                            'title'       => 'Customer',                          
                            'placeholder' => 'Search customer',
                            'chosen'      => true,
                            'ajax'        => true,
                            'multiple'    => false,                           
                            'options' => 'searchCustomer',                                          
                        ),array(
                            'id'    => 'price',
                            'type'  => 'text',
                            'placeholder' => '0',
                            'title' => 'Special Price',
                        ),
                    )                    
                )
            )
          ),
        )
      ));
  
  
    echo '</div>';  


