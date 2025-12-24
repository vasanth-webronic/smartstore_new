<?php
global $wpdb;
$category = isset($data['category']) ? $data['category'] : "";
$order = isset($data['order']) ? $data['order'] : "";
$orderby = isset($data['orderby']) ? $data['orderby'] : "";
$searchText = isset($data['searchText']) ? $data['searchText'] : "";
$page = isset($data['page']) ? $data['page'] : "";
$layoutType = isset($data['layout']) ? $data['layout'] : "grid";

$attributes = isset($data['filter']) ? $data['filter'] : [];

$args = array(
    'post_type'      => 'product',
    'posts_per_page' => 12,
    'paged' => $page,
    'post_status' => 'publish'
);

if (!empty($searchText)) {    
    $args['s']  = $searchText;
}else{
    $tax_query = [];

    foreach ($attributes as $key => $val) {
        $tax_query[] = array(
            'taxonomy'          => "pa_".$key,
            'terms'             => $val,
            'field'             => 'term_id',
            'operator'          => 'IN'
        );
    }  

    if(!empty($category)){
        $tax_query[] = array(
            'taxonomy'          => "product_cat",
            'field'             => 'slug',
            'terms'             => $category,           
            'operator'          => 'IN',
            'include_children'  => false
        );
       
    }

    if (!empty($tax_query)) {
        
        $args['tax_query'] = $tax_query;
    }
}

if (!empty($orderby)) {
    $args['orderby'] = $orderby;
    $args['order'] =  $order;
}else{
    $args['orderby'] = 'menu_order';
    $args['order'] =  'asc';
    
}

$have_data=false;
$filter_products = [];
if (empty($searchText) && !empty($attributes)) {

    $attribute_ids = [];
    $attribute_names = [];
    $ids_count=0;
    $ids_count1=0;
    $temp=[];
    $temp1=[];
    foreach ($attributes as $key => $val) {
        $attribute_ids[] = implode(",",$val);
        $attribute_names[] = "pa_" . $key;
        if (($key === 'load-metod')) {
            $temp[] = implode(",", $val);
          //  $ids_count += count(explode(",", $temp));
          $ids_count += count($val);
        }
        if (($key === 'picking-method')) {
            $temp1[] = implode(",", $val);
          //  $ids_count += count(explode(",", $temp));
          $ids_count1 += count($val);
        }
    }
    
    $ids_name = $attribute_names;
   // echo "<script>console.log('idsname: " . json_encode($ids_name) . "' );</script>";
// Print the contents of the array to the console
//echo "<script>console.log('Attribute Names: ' + " . $attribute_names_json . ");</script>";

   // echo "<script>console.log('productids Objects: " . json_encode($ids_name) . "' );</script>";
    $ids = implode(",",$attribute_ids);
   // $ids_count = sizeof(explode(",",$ids));
 //  $ids_methods=$temp;
  // $ids_methods = implode(",",$temp);
  // Convert the string into an array
$meth = array_values($temp);
$meth = explode(',', str_replace('"', '', $meth[0]));
$meth1 = array_values($temp1);
$meth1 = explode(',', str_replace('"', '', $meth1[0]));
// Get the values as individual elements in the array
//$ids_meth = explode(',', $meth[0]);
  

$check=0;
$ch=0;

   $checks=0;
    $i=0;
   // echo "<script>console.log('Ids Names: ' + " . $ids_name . ");</script>";
    // set the number of items to display per page
    $items_per_page = 12;
    $offset = ($page - 1) * $items_per_page;


//echo "<script>console.log('Debug Objects: " . $ids . "' );</script>";
//echo "<script>console.log('idsname: " . json_encode($ids_name) . "' );</script>";

 // Set the attribute slug and term_taxonomy_ids
$attribute = 'pa_load-metod';
$term_taxonomy_ids = $ids; // Replace with your desired term_taxonomy_ids
//echo "<script>console.log('term_taxonomy_ids Objects: " . $term_taxonomy_ids . "' );</script>";
// Create the tax query argument
$tax_query = array(
    'relation' => 'AND', // Fetch products that satisfy all term_taxonomy_ids
);
/*
foreach ($term_taxonomy_ids as $term_taxonomy_id) {
    $tax_query[] = array(
        'taxonomy' => $ids_name,
        'field'    => 'term_taxonomy_id',
        'terms'    => $term_taxonomy_id,
    );
}*/
if (!empty($category)) {
    $tax_query[] = array(
        'taxonomy' => 'product_cat',
        'field'    => 'slug', // You can use 'id' if you have the category ID
        'terms'    => $category,
    );
}
foreach ($term_taxonomy_ids as $term_taxonomy_id) {
    foreach ($ids_name as $id_name) {
        if ($id_name === 'pa_load-metod' || $id_name === 'pa_picking-method') {
            $tax_query[] = array(
                'taxonomy' => $id_name,
                'field'    => 'term_taxonomy_id',
                'terms'    => $term_taxonomy_id,
            );
        } else {
            $tax_query[] = array(
                'taxonomy' => $id_name,
                'field'    => 'term_taxonomy_id',
                'terms'    => $term_taxonomy_id,
                'operator' => 'IN',
            );
        }
    }
}

// Set up the product query arguments
$args = array(
    'post_type'      => 'product',
    'post_status'    => 'publish',
    'posts_per_page' => -1, // Fetch all products
    'tax_query'      => $tax_query,
);

// Create a new product query
$query = new WP_Query($args);

// Initialize an array to store matching product IDs
$product_ids = array();
$product_ids1 =array();
$product_ids2 =array();
$matching_product_ids=array();
// Loop through the products and store the matching product IDs
while ($query->have_posts()) {
    $query->the_post();
    $product_id = get_the_ID();
   // echo "<script>console.log('term_taxonomy_ids Objects: " . json_encode($product_id) . "' );</script>";
    // Check if the product has all the term_taxonomy_ids
   // $product_terms = get_the_terms($product_id, $attribute);
  // $product_terms = wp_get_post_terms($product_id, 'pa_load-metod', array('fields' => 'all'));
  $product_terms = wp_get_post_terms($product_id, $ids_name, array('fields' => 'all'));

 // echo "<script>console.log('Product ids: " . json_encode($product_terms->term_id) . "' );</script>";
    // Extract the term_taxonomy_ids from the product_terms
    $product_term_taxonomy_ids = array();
    $product_term_taxonomy_ids1 = array();
    $product_term_taxonomy_ids2 = array();
    $product_term_taxonomy_names = array();
    if ($product_terms && !is_wp_error($product_terms)) {
        foreach ($product_terms as $term) {
            
          //  $taxonomy = get_term_by('term_taxonomy_id', $product_term_taxonomy_ids);
      //  $taxonomy_name = $taxonomy->taxonomy;
      //  $product_term_taxonomy_name[$term_taxonomy_id] = $taxonomy_name;
     // $taxonomy_name = $term->taxonomy;
      $product_term_taxonomy_names[] = $term->taxonomy;
      if($term->taxonomy === "pa_load-metod" )
      {
        $product_term_taxonomy_ids[] = $term->term_taxonomy_id;
      }
      else if($term->taxonomy === "pa_picking-method"){
        $product_term_taxonomy_ids2[] = $term->term_taxonomy_id;
       // $tre[]=$product_id;
      }else{
        $product_term_taxonomy_ids1[] = $term->term_taxonomy_id;
      }


      //echo "<script>console.log('taxnomys: " . json_encode($product_term_taxonomy_names) . "' );</script>";
       // echo "<script>console.log('term_filtered_ids Objects: " . json_encode($taxonomy_name) . "' );</script>";
   
        }
    }
    $term_taxonomy_ids_array = explode(',', $term_taxonomy_ids);
foreach($product_term_taxonomy_names as $r){
    if ($r === "pa_load-metod" ) {
//if (empty(array_diff($term_taxonomy_ids_array, $product_term_taxonomy_ids))) {
   // if (count(array_diff($term_taxonomy_ids_array, $product_term_taxonomy_ids)) === 0 && count($term_taxonomy_ids_array) === count($product_term_taxonomy_ids)) {
    //echo "<script>console.log('idscount: " . ($ids_count) . "' );</script>";
//echo "<script>console.log('coutns: " . count($product_term_taxonomy_ids) . "' );</script>";
//echo "<script>console.log('meth1picking: " . json_encode($meth1) . "' );</script>";
//echo "<script>console.log('idscount: " . json_encode($product_term_taxonomy_ids2) . "' );</script>";
        if( count(array_diff($meth, $product_term_taxonomy_ids)) === 0 && count($product_term_taxonomy_ids)=== $ids_count ){

        $product_ids[] = $product_id;
       // echo "<script>console.log('productterms: " . json_encode($product_term_taxonomy_ids) . "' );</script>";
      //  echo "<script>console.log('inputed: " . json_encode($term_taxonomy_ids_array) . "' );</script>";
        //echo "<script>console.log('productids Objects: " . json_encode($product_id) . "' );</script>";
       $check=1;
       $matching_product_ids=$product_ids;
    }
} else if($r === "pa_picking-method"){
    if( count(array_diff($meth1, $product_term_taxonomy_ids2)) === 0 && count($product_term_taxonomy_ids2)=== $ids_count1 ){

        $product_ids2[] = $product_id;
       // echo "<script>console.log('productterms: " . json_encode($product_term_taxonomy_ids) . "' );</script>";
      //  echo "<script>console.log('inputed: " . json_encode($term_taxonomy_ids_array) . "' );</script>";
        //echo "<script>console.log('productids Objects: " . json_encode($product_id) . "' );</script>";
       $ch=3;
       $matching_product_ids=$product_ids2;
    }
}

else {
    //echo "<script>console.log('product_term_taxonomy_ids1: " . json_encode($product_term_taxonomy_ids1) . "' );</script>";
    //echo "<script>console.log('term_taxonomy_ids_array: " . json_encode($term_taxonomy_ids_array) . "' );</script>";
    /*if (in_array($term_taxonomy_ids_array,$product_term_taxonomy_ids1)) {
      //  if (empty(array_diff($product_term_taxonomy_ids1,$term_taxonomy_ids_array))) {
    
        $product_ids1[] = $product_id;
        $checks=2;
        //$matching_product_ids[]=$product_ids1;
      //  echo "<script>console.log('inside if product Objects: " . json_encode($matching_product_ids) . "' );</script>";
    }*/
    
    $matching_term_taxonomy_ids = array_intersect($term_taxonomy_ids_array, $product_term_taxonomy_ids1);
   
if (!empty($matching_term_taxonomy_ids)) {
    $product_ids1[] = $product_id;
    $checks=2;
   // echo "<script>console.log('matfching tax: " . json_encode($matching_term_taxonomy_ids) . "' );</script>";
  //  echo "<script>console.log('matfching tax: " . ($product_id) . "' );</script>";
    $matching_product_ids=$product_ids1;
}

}
}
if((!empty($product_ids2))&&(!empty($product_ids1))&&(!empty($product_ids)))
{
    $matching_product_ids = array_intersect($product_ids, $product_ids1,$product_ids2);
}
else if((!empty($product_ids1))&&(!empty($product_ids))) {
    $matching_product_ids = array_intersect($product_ids, $product_ids1);
}
else if((!empty($product_ids2))&&(!empty($product_ids))) {
    $matching_product_ids = array_intersect($product_ids, $product_ids2);
}


}
//echo "<script>console.log('load Objects: " . json_encode($product_ids) . "' );</script>";
//echo "<script>console.log('nonload Objects: " . json_encode($product_ids1) . "' );</script>";
//echo "<script>console.log('nonload3 Objects: " . json_encode($product_ids2) . "' );</script>";
//echo "<script>console.log('productids Objects: " . json_encode($matching_product_ids) . "' );</script>";

//echo "<script>console.log('and: " . json_encode($product_term_taxonomy_ids) . "' );</script>";
//echo "<script>console.log('nonand: " . json_encode($product_term_taxonomy_ids1) . "' );</script>";
//echo "<script>console.log('value of noand: " . json_encode($tre) . "' );</script>";
// Reset the post data
//wp_reset_postdata();

// Fetch the products based on the matching product IDs
$filter_products = array();
foreach ($matching_product_ids as $product_id) {
    $product = wc_get_product($product_id);
    if ($product) {
        $filter_products[] = $product;
        $i=$i+1;
    }
}

echo '<input type="hidden" id="taw_prod_count" value="' . $i . '">'; 

 } else {
    
    $loop = new WP_Query($args);
    while ($loop->have_posts()) : $loop->the_post();
        global $product;
        $filter_products[] = $product;
    endwhile;
 }
 
 if (sizeof($filter_products) > 0) {
    $have_data=true;
 }

foreach ($filter_products as $key => $instance) :
?>

<?php
if($layoutType == "grid"){
?>


        <div class="border p-3 rounded-md">
            <?php //echo $instance ;
            ?>
            <style>
                .quotedesign {
                    margin-top: 22px !important;
                }
            </style>
            <a href="<?php echo $instance->get_permalink(); ?>" class="text-black" style="text-decoration: none;">
                <div class="!h-44 !w-auto flex items-center justify-center">
                    <?php
                    $image = get_the_post_thumbnail_url($instance->get_id());
                    $default = wc_placeholder_img_src(120);
                    if (empty($image)) {
                        $image = $default;
                    }
                    ?>
                    <img class="!w-auto !max-h-40 sm:!max-h-44 md:!max-h-40 lg:!max-h-40 xl:!max-h-44 mx-auto" src=<?php echo $image; ?> onerror="this.onerror=null;this.src='<?php echo $default; ?>'">
                    <?php  //echo woocommerce_get_product_thumbnail("woocommerce_thumbnail",["class"=>"!max-h-44 !w-auto mx-auto"]);
                    ?>
                </div>
                <h3 class="w-full h-16 overflow-hidden text-sm font-semibold text-center mt-3"><?php echo $instance->get_name(); ?></h3>
                <div class="my-4 xl:my-5 flex flex-col items-center">
                    <span class="text-sm font-semibold text-red-600 block !py-2">
                        <?php echo product_price($instance->get_price_html()); ?>
                    </span>

                    <a href="<?php echo $instance->get_permalink(); ?>">
                        <?php $buyOrQuote = product_buy_or_quote($instance->get_price_html()); ?>

                        <?php if ($buyOrQuote == 'Quote') : ?>
                            <button class="text-sm bg-red-600 w-24 px-6 py-2 mx-2 text-white rounded-full hover:bg-black quotedesign">
                                <?php echo $buyOrQuote; ?>
                            </button>
                        <?php elseif ($buyOrQuote == 'Buy') : ?>
                            <button class="text-sm bg-red-600 w-24 px-6 py-2 mx-2 text-white rounded-full hover:bg-black">
                                <?php echo $buyOrQuote; ?>
                            </button>
                        <?php endif; ?>
                    </a>
                </div>
                <!-- <img class="w-4 mx-0" src="<?php // echo THINGSATWEB_BASE.'/img/heart-o.svg';
                                                ?>" alt=""> -->
            </a>
        </div>


<?php

}else{
?>

<div class="flex flex-wrap justify-center md:flex-nowrap py-7">
    <div class="relative w-full md:w-1/3">
        <a href="<?php echo $instance->get_permalink();?>" class="text-black"> 
            <div class="!w-auto !h-40 sm:!h-44 md:!h-40 lg:!h-40 xl:!h-44 flex items-center justify-center">
                <?php
                    $image = get_the_post_thumbnail_url($instance->get_id());
                    $default = wc_placeholder_img_src(120);
                    if(empty($image)) { $image = $default; }
                ?>
                <img class="!w-auto !max-h-40 sm:!max-h-44 md:!max-h-40 lg:!max-h-40 xl:!max-h-44 mx-auto" src=<?php  echo $image;?>
                onerror="this.onerror=null;this.src='<?php echo $default;?>'">
                <?php  //echo woocommerce_get_product_thumbnail("woocommerce_thumbnail",["class"=>"!w-auto !max-h-40 sm:!max-h-44 md:!max-h-40 lg:!max-h-40 xl:!max-h-44 mx-auto"]);?>
            </div>
        </a>
        <!-- <img class="absolute top-0 right-0 w-6 md:w-5 xl:w-6 p-1 bg-red-600 rounded-full" src="<?php //echo THINGSATWEB_BASE.'/img/zoom.svg';?>" alt="Zoom"> -->
    </div>
    <div class="w-full md:w-2/3 mx-1 md:mx-7 xl:mx-10">
        <a href="<?php echo $instance->get_permalink();?>" class="text-black"> 
            <h3 class="text-lg font-semibold text-center md:text-start"><?php echo $instance->get_name(); ?></h3>
        </a>
        <?php $price = $instance->get_price_html(); ?>
        
        <span class="text-sm font-semibold text-red-600 flex justify-center md:justify-start">
            <?php echo product_price($price) ?>
        </span>

        <div class="flex justify-center md:justify-start items-start mt-5">
            <?php if ( $instance->is_in_stock() ) : ?>

                <?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

                <form class="cart flex justify-center lg:justify-start" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $instance->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
                    <?php //do_action( 'woocommerce_before_add_to_cart_button' ); ?>

                    <?php do_action( 'woocommerce_before_add_to_cart_quantity' );

                        woocommerce_quantity_input(
                            array(
                            'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $instance->get_min_purchase_quantity(), $instance ),
                            'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $instance->get_max_purchase_quantity(), $instance ),
                            'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $instance->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
                            )
                        );

                        do_action( 'woocommerce_after_add_to_cart_quantity' );
                    ?>

                    <button name="add-to-cart" value="<?php echo esc_attr( $instance->get_id() ); ?>" class="ml-3 w-28 h-7 text-sm text-center flex items-center justify-center bg-red-600 text-white rounded-full hover:bg-black"><?php echo product_buy_or_quote($instance->get_price_html()); ?></button>

                    <?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
                </form>

                <?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

            <?php endif; ?>
        </div>
        <h4 class="text-xs text-gray-500 mt-4 mb-0 text-center md:text-start">ART NO: <?php echo $instance->get_sku(); ?></h4>
        <h4 class="text-xs text-gray-500 text-center md:text-start mt-0 mb-0">Category: 

<?php
   $terms = get_the_terms($instance->get_ID(), 'product_cat');
       foreach ($terms as $term) {

         $product_cat = $term->name;
            echo $product_cat;
              break;
          }
   ?>

</h4>
    </div>
</div>


<?php
}
?>

<?php
endforeach;
if ($page == 1) {
    $args['posts_per_page'] = -1;
    if (empty($searchText) && !empty($attributes)) {
        $countQ = $wpdb->query($main_query);
    }else {
        $countQ = new WP_Query($args);
        $countQ = $countQ->post_count;
    }
    echo "<input type='hidden' id='taw_prod_count' value='$countQ' />";   
}
wp_reset_query();

if(!$have_data){
    echo "-";
}


?>