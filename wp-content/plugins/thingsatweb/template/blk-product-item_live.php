<?php
global $wpdb;
$category = isset($data['category']) ? $data['category'] : "";
$order = isset($data['order']) ? $data['order'] : "";
$orderby = isset($data['orderby']) ? $data['orderby'] : "";
$searchText = isset($data['searchText']) ? $data['searchText'] : "";
$page = isset($data['page']) ? $data['page'] : "";
$layoutType = isset($data['layout']) ? $data['layout'] : "grid";
$firstIndex=null;
$attributes = isset($data['filter']) ? $data['filter'] : [];
//echo var_dump($_SERVER['QUERY_STRING']);die();
//echo "<script>console.log('attributes: " . json_encode($attributes) . "' );</script>";
$lang = getSiteCurrentLang(); 
$sql_products_sku = array();

if (!empty($searchText)) {
$args = array(
    'post_type'      => 'product',
'post_status'    => 'publish',
    'posts_per_page' => 12,
    'offset'         => ($page - 1) * 12,
        'meta_query'     => array(
            array(
                'key'     => '_sku',
                'value'   => $searchText,
                'compare' => 'LIKE'
            )
        ),
    );

    if(!empty($category)){
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => $category
            )
    );
    }

    $products_query = new WP_Query($args);

    if ($products_query->have_posts()) {
        while ($products_query->have_posts()) {
            $products_query->the_post();
            $product = wc_get_product(get_the_ID());
            if ($product) {
                $sql_products_sku[] = $product;
            }
        }
        wp_reset_postdata();
    }
}



// WP_Query search query
$args = array(
    'post_type'      => 'product',
    'posts_per_page' => 12,
    'paged'          => $page,
    'post_status'    => 'publish',
    'meta_query'     => array(
        array(
            'key'     => '_sku', // Meta key for SKU
            'compare' => 'EXISTS', // Make sure the SKU exists
        ),
    ),
);
if (!empty($searchText)) {    
    $args['s']  = $searchText;
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
        else if (($key === 'picking-method')) {
            $temp1[] = implode(",", $val);
          //  $ids_count += count(explode(",", $temp));
          $ids_count1 += count($val);
        }
        else
        {
            $rrr="pa_" . $key;
            $temp2[$rrr] = implode(",", $val);
            $meth2[$rrr] = explode(',', str_replace('"', '', $temp2[$rrr]));
        }
    }
    
    $ids_name = $attribute_names;
   // echo "<script>console.log('rrr: " . json_encode($rrr) . "' );</script>";
// Print the contents of the array to the console
//echo "<script>console.log('Attribute Names: ' + " . $attribute_names_json . ");</script>";
//echo "<script>console.log('pfsdfasdfroduct_term_taxonomy_names: " . json_encode($meth2) . "' );</script>";

   // echo "<script>console.log('productids Objects: " . json_encode($ids_name) . "' );</script>";
    $ids = implode(",",$attribute_ids);
   // $ids_count = sizeof(explode(",",$ids));
 //  $ids_methods=$temp;
  // $ids_methods = implode(",",$temp);
  // Convert the string into an array
$meth = array_values($temp);
if(!empty($meth))
$meth = explode(',', str_replace('"', '', $meth[0]));
$meth1 = array_values($temp1);
if(!empty($meth1))
$meth1 = explode(',', str_replace('"', '', $meth1[0]));

// Get the values as individual elements in the array
//$ids_meth = explode(',', $meth[0]);
//echo "<script>console.log('Meth: " . json_encode($meth) . "' );</script>";
//echo "<script>console.log('Meth1: " . json_encode($meth1) . "' );</script>";

$check=0;
$check=0;
$ch=0;
$r=0;
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
if (!is_array($term_taxonomy_ids)) {
    // Handle the case where $term_taxonomy_ids is not an array, e.g., convert it to an array
    $term_taxonomy_ids = explode(",", $term_taxonomy_ids);
}
//echo "<script>console.log('term_taxonomy_ids Objects: " . $term_taxonomy_ids . "' );</script>";
// Create the tax query argument
$test=[];
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
     /*  if ($id_name === 'pa_load-metod' || $id_name === 'pa_picking-method') {
            $tax_query[] = array(
                'taxonomy' => $id_name,
                'field'    => 'term_taxonomy_id',
                'terms'    => $term_taxonomy_id,
               
            );
        } else { */
            $tax_query[] = array(
                'taxonomy' => $id_name,
                'field'    => 'term_taxonomy_id',
                'terms'    => $term_taxonomy_id,
                'operator' => 'IN',
            );
       }
   // }
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
    

    $test[] = $product_id;
  // echo "<script>console.log('term_taxonomy_ids Objects: " . json_encode($product_id) . "' );</script>"; 
    // Check if the product has all the term_taxonomy_ids
   // $product_terms = get_the_terms($product_id, $attribute);
  // $product_terms = wp_get_post_terms($product_id, 'pa_load-metod', array('fields' => 'all'));
  $product_terms = wp_get_post_terms($product_id, $ids_name, array('fields' => 'all'));
  //echo "<script>console.log('Product ids: " . json_encode($product_terms) . "' );</script>";
  
 //echo "<script>console.log('Product ids: " . json_encode($product_terms->term_id) . "' );</script>";
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


      
      // echo "<script>console.log('term_filtered_ids Objects: " . json_encode($product_term_taxonomy_ids1) . "' );</script>";
   
        }
    }
    // $term_taxonomy_ids_array = explode(',', $term_taxonomy_ids);
  //  echo "<script>console.log('product_term_taxonomy_names: " . json_encode($product_term_taxonomy_names) . "' );</script>";
foreach($product_term_taxonomy_names as $r){
    if ($r === "pa_load-metod" ) {
       /* if( count(array_diff($meth, $product_term_taxonomy_ids)) === 0 && count($product_term_taxonomy_ids)=== $ids_count ){
       // if( count(array_diff($meth, $product_term_taxonomy_ids)) === 0){
        $product_ids[] = $product_id;
              $check=1;
       $matching_product_ids=$product_ids;
   // } */
 //  echo "<script>console.log('prodcit tax Objects: " . json_encode($meth) . "' );</script>";
   $check=1;
   foreach ($meth as  $values) {
    if (in_array($values, $product_term_taxonomy_ids)) {
       // echo "<script>console.log('prodcit tax Objects: " . json_encode($product_term_taxonomy_ids) . "' );</script>";
     //   echo "<script>console.log('values Objects: " . json_encode($values) . "' );</script>";
     //   echo "<script>console.log('values Objects: " . json_encode($product_id) . "' );</script>";
        $product_ids[] = $product_id;
       
 $matching_product_ids[]=$product_id;
    }
}
      //  }
} else if($r === "pa_picking-method"){
   /* if( count(array_diff($meth1, $product_term_taxonomy_ids2)) === 0 && count($product_term_taxonomy_ids2)=== $ids_count1 ){
        $product_ids2[] = $product_id;
           $checks=2;
       $matching_product_ids=$product_ids2;
    } */
    foreach ($meth1 as  $values) {
        if (in_array($values, $product_term_taxonomy_ids2)) {
           // echo "<script>console.log('prodcit tax Objects: " . json_encode($product_term_taxonomy_ids2) . "' );</script>";
           // echo "<script>console.log('values Objects: " . json_encode($values) . "' );</script>";
           // echo "<script>console.log('values Objects: " . json_encode($product_id) . "' );</script>";
            $product_ids2[] = $product_id;
            $checks=2;
     $matching_product_ids[]=$product_id;
        }
}
}

else {
      //$matching_term_taxonomy_ids = array_intersect($term_taxonomy_ids_array, $product_term_taxonomy_ids1);
    /*  foreach ($meth2 as $innerArray) {
        foreach ($innerArray as $element) {
            if (in_array($element, $product_term_taxonomy_ids1)) {
                $h[] = $r;
                $prod[$r][$element] = $product_id;
            }
        }
    }*/
    $ch = 3;
  //  echo "<script>console.log('product_term_taxonomy_names: " . json_encode($meth2) . "' );</script>";

    foreach ($meth2 as $index => $values) {
        // Initialize an empty array for each index
        $productArray[$index] = array();
    
        // Loop through the values for the current index
        foreach ($values as $element) {
            if (in_array($element, $product_term_taxonomy_ids1)) {
                // Add product to the current index's array
              
                $productArray[$index][] = $product_id;
            /*    if (!in_array($product_id, $product_ids1)) {
                    $product_ids1[] = $product_id;
                }*/
            }
        }
    }
    

    /*
     if((!empty($matching_product_ids))&&($r==0)){
       if (in_array($product_id, $matching_product_ids)) {
            $product_ids1[] = $product_id;
            $ch=3;
        }
    }
      else{
        $product_ids1[] = $product_id;
        $matching_product_ids=$product_ids1;
        $ch=3;
        $r=1;
      }
   */
 

}
}


if(!empty($productArray))
foreach ($productArray as $index => $value) {
    $combinedProd[$index][] = $value;
    
 //   echo "<script>console.log('array: " . json_encode($value) . "' );</script>";
}

/*
if (!empty($combinedProd)) {
    // Initialize $product_ids1 with the first index's product IDs
    //$indexes = array_keys($combinedProd);

    // Get the first index of $productArray
   // $firstIndex = "pa_load";

    // Store all elements of the first index in $product_ids1
   // $product_ids1 = $combinedProd[$firstIndex];
   $product_ids1 = $combinedProd[array_key_first($combinedProd)];
   $product_ids1 = array_values(array_filter($combinedProd[array_key_first($combinedProd)], function($value) {
    return !empty($value);
}));
$product_ids1 = array_unique(array_merge(...$product_ids1));
    echo "<script>console.log('insisisdfasd picking Objects: " . json_encode($product_ids1) . "' );</script>";
// Loop through the rest of the indexes
foreach ($combinedProd as $index => $products) {
    // Find the common product IDs with the current index
    $product_ids1[]= array_intersect($product_ids1, $products);

    // If $product_ids1 becomes empty, you can break the loop since there can't be any more common elements
    
}
} */


/*
foreach ($productArray as $subArray) {
    // Loop through the sub-array and add its values to the one-dimensional array
    foreach ($subArray as $value) {
        if (!empty($value)) {
            $product_ids1[] = $value;
        }
    }
}
*/

if (!empty($combinedProd)) {
    foreach ($combinedProd as $index => $values) {
        if (!empty($values)) {
           // $firstIndex = $index;
         //   $firstValues = $values;
          //  break;
        //  if (empty($firstIndex)) {
       //     $firstIndex = array_key_last($combinedProd);
      //  }
      foreach ($ids_name as $name) {
       if ($name !== "pa_load-metod" && $name !== "pa_picking-method") {
            $firstIndex = $name;
            break; // Stop the loop once a non-matching value is found
        }
    }
        }
    }
    
    
    if ($firstIndex !== null) {
     /*   $product_ids1 = array_values(array_filter($combinedProd[$firstIndex], function($value) {
            return !empty($value);
        })); */
    
  
 //  $firstIndex = array_keys($combinedProd)[0];
    $firstValues = array_values(array_filter($combinedProd[$firstIndex], function($value) {
        return !empty($value);
    }));
    // Initialize $product_ids1 with the values from the first index
    $product_ids1 = $firstValues;
    foreach ($product_ids1 as $value) {
        $product_ids1[] = $value[0];
    }

    // Loop through the rest of the indexes
    foreach ($combinedProd as $index => $products) {
        if ($index !== $firstIndex) {
            // Find the common values with the current index's values
            $commonValues = array_values(array_filter($products, function($value) {
                return !empty($value);
            }));
           
            $flattenedArray = [];

        // Iterate over the multidimensional array and add each element to the flattened array.
        foreach ($commonValues as $value) {
            $flattenedArray[] = $value[0];
        }
            // Find the intersection of commonValues and product_ids1
         //   $commonValuesFlat = array_unique(array_merge(...$commonValues));

            // Find the common values between $product_ids1 and $commonValuesFlat
            $common = [];

            // Loop through $product_ids1 and add common values to $common
            foreach ($product_ids1 as $value) {
                if (in_array($value, $flattenedArray)) {
                    $common[] = $value;
                }
            }
          
          //$product_ids1 = array_values($product_ids1);
          $product_ids1 = $common;
         
            
          $newProductIds1 = [];
          foreach ($product_ids1 as $value) {
              if (is_array($value) && isset($value[0])) {
                  $newProductIds1[] = $value[0];
              } else {
                  $newProductIds1[] = $value;
              }
          }
          $product_ids1 = $newProductIds1;
          /*
          foreach ($product_ids1 as $value) {
            $product_ids1[] = $value[0];
            
        } */
        $matching_product_ids = array_values(array_filter(array_unique(array_reduce($product_ids1, function ($carry, $item) {
            if (!is_null($item)) {
                $carry[] = $item;
            }
            return $carry;
        }, []))));
    
        }else
        {
            $newProductIds1 = [];
foreach ($product_ids1 as $value) {
    if (is_array($value) && isset($value[0])) {
        $newProductIds1[] = $value[0];
    } else {
        $newProductIds1[] = $value;
    }
}
$product_ids1 = $newProductIds1;
/*
            foreach ($product_ids1 as $value) {
                $product_ids1[] = $value[0];
               // $matching_product_ids= $value[0];
            } */
            
            $matching_product_ids = array_values(array_filter(array_unique(array_reduce($product_ids1, function ($carry, $item) {
                if (!is_null($item)) {
                    $carry[] = $item;
                }
                return $carry;
            }, []))));
        }

    }

  //  echo "<script>console.log('product_ids1: " . json_encode($product_ids1) . "' );</script>";
  //  $product_ids1 = array_merge(...$product_ids1);
}
    // Now $product_ids1 contains the common values among different indexes
    
 //  echo "<script>console.log('c1 Objects: " . json_encode($common) . "' );</script>";
  //  echo "<script>console.log('commonValues: " . json_encode($commonValues) . "' );</script>";
   
}

$product_ids1 = array_filter($product_ids1, function ($value) {
    return $value !== null;
});

$product_ids2 = array_filter($product_ids2, function ($value) {
    return $value !== null;
});

$product_ids = array_filter($product_ids, function ($value) {
    return $value !== null;
});





if((!empty($product_ids2))&&(!empty($product_ids1))&&(!empty($product_ids)))
{
    $matching_product_ids = array_intersect($product_ids, $product_ids1,$product_ids2);
}
elseif((!empty($product_ids1))&&(!empty($product_ids))) {
    $matching_product_ids = array_intersect($product_ids, $product_ids1);
}
elseif((!empty($product_ids2))&&(!empty($product_ids))) {
    $matching_product_ids = array_intersect($product_ids, $product_ids2);
}


if ($check == 1 && $ch == 3 && $checks == 2) {
    // All conditions are met
    
    $matching_product_ids = array_intersect($product_ids, $product_ids1, $product_ids2);
   
} elseif ($check == 1 && $ch == 3) {
    // Only the first two conditions are met
    
    $matching_product_ids = array_intersect($product_ids, $product_ids1);
  //  echo "<script>console.log('within if MAtching IDS: " . json_encode($matching_product_ids) . "' );</script>";
    
} elseif ($check == 1 && $checks == 2) {
    // The first and third conditions are met
    
    $matching_product_ids = array_intersect($product_ids, $product_ids2);
   
} elseif ($ch == 3 && $checks == 2) {
    // The second and third conditions are met
    
    $matching_product_ids = array_intersect($product_ids1, $product_ids2);
   
} 

if(($check ==1)&&(empty($product_ids)))
{
    $matching_product_ids=[];
}
/*elseif ($check == 1) {
    // Only the first condition is met
    $matching_product_ids = $product_ids;
     if ( empty($product_ids) || empty($product_ids1) || empty($product_ids2)) {
        $matching_product_ids = array(); // If any array is empty, set matching_product_ids to empty
    }
} elseif ($ch == 3) {
    // Only the second condition is met
    $matching_product_ids = $product_ids1;
    
} elseif ($checks == 2) {
    // Only the third condition is met
    $matching_product_ids = $product_ids2;
   
} 
*/
$check=0;
$ch=0;
$checks=0;
    
} 
//$matching_product_ids=array_unique($test);
/*foreach ($matching_product_ids as &$element) {
    // Check if the element is an array with a single value
    if (is_array($element) && count($element) === 1) {
        // Remove the brackets and update the element with the single value
        $element = reset($element);
    }
} */
if(!empty($matching_product_ids)){
if (is_array($matching_product_ids)) {
$matching_product_ids= array_unique($matching_product_ids);
}
$matching_product_ids = array_filter($matching_product_ids, function ($value) {
    return $value !== null;
});

//echo "<script>console.log('taxnomys: " . json_encode($product_term_taxonomy_ids1) . "' );</script>";
//echo "<script>console.log('load Objects: " . json_encode($product_ids) . "' );</script>";
//echo "<script>console.log('picking Objects: " . json_encode($product_ids1) . "' );</script>";
//echo "<script>console.log('ohter Objects: " . json_encode($product_ids2) . "' );</script>";
//echo "<script>console.log('MAtching IDS: " . json_encode($matching_product_ids) . "' );</script>";

//echo "<script>console.log('h value: " . json_encode($productArray) . "' );</script>";
//echo "<script>console.log('array: " . json_encode($flattenedArray) . "' );</script>";
//echo "<script>console.log('taxnomys: " . json_encode($firstIndex) . "' );</script>";
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
}
$matching_product_ids=[];
echo '<input type="hidden" id="taw_prod_count" value="' . $i . '">'; 
//exit;

 } else {
    
    $loop = new WP_Query($args);
$filter_products = array();
    
    while ($loop->have_posts()) : $loop->the_post();
        global $product;
        $filter_products[] = $product;
// echo var_dump($product);die();
    endwhile;



// Merge sku products with WP_Query products and remove duplicates
if (!empty($searchText) && $sql_products_sku) {


if (!empty($sql_products_sku)) {
    if (empty($filter_products)) {
        $filter_products = $sql_products_sku;
    } else {
        foreach ($sql_products_sku as $product) {
            $filter_products[] = $product;
        }
    }
        
        // Remove duplicates from $filter_products
        $filter_products = array_unique($filter_products);
    }
}




 }
 
 if (sizeof($filter_products) > 0) {
    $have_data=true;
}
// else{
//     echo "<!-- paramQ_end -->";
//  }

// Fetch and filter restricted products
$current_user = wp_get_current_user();
// $user_roles = $current_user->roles;
// $user_role = $user_roles[0];
// $userid = $current_user->ID;

if (!($current_user instanceof WP_User) || $current_user->ID == 0) {
    $user_role = 'custom_uam_guest';
    $userid = 'guest';
} else {
    $user_roles = $current_user->roles;
    $user_role = isset($user_roles[0]) ? $user_roles[0] : 'guest';
    $userid = $current_user->ID;
}

// User
$restrictuserout = "SELECT art_no FROM taw_restrict_product WHERE roleid != '$userid' and Type='user';";
$restrictuseroutres = $wpdb->get_results($restrictuserout);

$restrictuserin = "SELECT art_no FROM taw_restrict_product WHERE roleid = '$userid' and Type='user';";
$restrictuserinres = $wpdb->get_results($restrictuserin);

// Role
$restrictroleout = "SELECT art_no FROM taw_restrict_product WHERE roleid != '$user_role' and Type='role';";
$restrictroleoutres = $wpdb->get_results($restrictroleout);

$restrictrolein = "SELECT art_no FROM taw_restrict_product WHERE roleid = '$user_role' and Type='role';";
$restrictroleinres = $wpdb->get_results($restrictrolein);

// Merge results
$mergedout_results = array_merge($restrictroleoutres, $restrictuseroutres);

$mergedin_results = array_merge($restrictroleinres, $restrictuserinres);

// Extract art_no values from $mergedin_results

$uniquein_art_nos = array_map(function($obj) {
    return $obj->art_no;
}, $mergedin_results);

// Extract art_no values into an array
$restrict_art_nos = array_map(function($obj) {
    return $obj->art_no;
}, $mergedout_results);

$restrict_art_nos = array_unique($restrict_art_nos);

// Remove values in $restrict_art_nos that are present in $uniquein_art_nos
$final_restrict_art_nos = array_diff($restrict_art_nos, $uniquein_art_nos);

// Filter $filter_products to exclude restricted products
$filtered_products = array_filter($filter_products, function($product) use ($final_restrict_art_nos) {
    return !in_array($product->get_sku(), $final_restrict_art_nos);
});

foreach ($filtered_products as $key => $instance) :
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
               <?php 
                if ($lang == 'en') {
                    $label = 'Article Number:';
                } elseif ($lang == 'sv') {
                    $label = 'Artikelnummer:';
                } else {
                    $label = 'Article Number:'; // Default to English if the language is not recognized
                } ?>
                <h3 class="w-full overflow-hidden text-sm font-semibold text-center mt-3">
            <?php    echo '<span style="color: red;">' . $label . ' ' . $instance->get_sku() . '</span><br>'; ?>
                </h3>
              <h3 class="w-full h-16 overflow-hidden text-sm font-semibold text-center" id="prod_title"><?php 
               // echo "ART No: ";
               // echo $instance->get_sku(); echo "<br>";
              
                echo $instance->get_name(); ?></h3>
                <div class="my-4 xl:my-5 flex flex-col items-center">
                    <span class="text-sm font-semibold text-red-600 block !py-2">
                        <?php echo product_price($instance->get_price_html()); ?>
                    </span>
<?php /*
                    <a href="<?php echo $instance->get_permalink(); ?>">
                        <?php $buyOrQuote = product_buy_or_quote($instance->get_price_html()); ?>

                        <?php if ($buyOrQuote == 'Quote') : ?>
                            <button class="text-sm bg-red-600 w-24 px-6 py-2 mx-2 text-white rounded-full hover:bg-black quotedesign">
                                <?php echo __($buyOrQuote,"default"); ?>
                            </button>
                        <?php elseif ($buyOrQuote == 'Buy') : ?>
                            <button class="text-sm bg-red-600 w-24 px-6 py-2 mx-2 text-white rounded-full hover:bg-black">
                                <?php echo __($buyOrQuote,"default"); ?>
                            </button>
                        <?php endif; ?>
                    </a> */ ?>

                    <?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<form class="cart mt-6 addToCartform" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $instance->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
    <?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

    <div class="flex justify-center lg:justify-start">

        <?php
           /*  do_action( 'woocommerce_before_add_to_cart_quantity' );

           woocommerce_quantity_input(
                array(
                    'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
                    'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
                    'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
                )
            );

            do_action( 'woocommerce_after_add_to_cart_quantity' ); */
        ?>
         <input type="text" name="product_id" value="<?php echo esc_attr( $instance->get_id() ); ?>" hidden>
        <input type="text" name="product_qty" value="1" hidden>

        <button  class="ml-3 w-28 h-7 text-sm text-center flex items-center justify-center bg-red-600 text-white rounded-full hover:bg-black">
        <?php //echo product_buy_or_quote($product->get_price_html());
       $result = product_buy_or_quote($instance->get_price_html());
       
        if ($result === 'Quote') {
            // Assuming you have 'Quote' translated in the 'Your Text Domain' with WPML
            echo __($result, 'default');
        } else {
           // echo $result;
         
        
         $s = __($result, 'TAW_TEXT_DOMAIN');

            if (mb_strtolower($s, 'UTF-8') === 'kop') {
                echo 'köp';
            }else
            {
                echo __($result, 'TAW_TEXT_DOMAIN');
            }

        // echo esc_html__($result, 'TAW_TEXT_DOMAIN');
        }
        
        
        ?>
    </button>

    </div>

    <?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
</form>

<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>


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
            <h3 class="text-lg font-semibold text-center md:text-start" id="prod_title_list"><?php echo $instance->get_name(); ?></h3>
        </a>
        <?php $price = $instance->get_price_html(); ?>
        
        <span class="text-sm font-semibold text-red-600 flex justify-center md:justify-start">
            <?php echo product_price($price) ?>
        </span>

        <div class="flex justify-center md:justify-start items-start mt-5">
            <?php if ( $instance->is_in_stock() ) : ?>

                <?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

                <form class="cart flex justify-center lg:justify-start addToCartformList" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $instance->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
                    <?php //do_action( 'woocommerce_before_add_to_cart_button' ); ?>

                    <?php do_action( 'woocommerce_before_add_to_cart_quantity' );

                        woocommerce_quantity_input(
                            array(
                            'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $instance->get_min_purchase_quantity(), $instance ),
                            'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $instance->get_max_purchase_quantity(), $instance ),
                            'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $instance->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
                            'input_name'  => 'product_qty',
                            ),

                        );

                        do_action( 'woocommerce_after_add_to_cart_quantity' );
                    ?>
<input type="text" name="product_id" value="<?php echo esc_attr( $instance->get_id() ); ?>" hidden>

                    <button name="add-to-cart" value="<?php echo esc_attr( $instance->get_id() ); ?>" class="ml-3 w-28 h-7 text-sm text-center flex items-center justify-center bg-red-600 text-white rounded-full hover:bg-black">
                    <?php echo product_buy_or_quote($instance->get_price_html()); ?></button>

                    <?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
                </form>

                <?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

            <?php endif; ?>
        </div>
        <h4 class="text-xs text-gray-500 mt-4 mb-0 text-center md:text-start"><?php if($lang=='en'){?> Article Number: <?php }elseif($lang=='sv'){ ?> Artikelnummer:  <?php } ?> 
         <?php echo $instance->get_sku(); ?></h4>       
        <h4 class="text-xs text-gray-500 text-center md:text-start mt-0 mb-0">
            <?php if($lang=='en'){?> Category: <?php }elseif($lang=='sv'){ ?> Kategori:  <?php } ?> 

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
            $countQ = new WP_Query($args);
        $post_ids = []; // Array to store unique post IDs

    // Count unique posts from the main query
    while ($countQ->have_posts()) {
        $countQ->the_post();
        $post_ids[] = get_the_ID(); // Store the post ID
    }

    // If additional products were retrieved separately (e.g., $sql_products_sku), count them and add to the total
    if (!empty($c)) {
        foreach ($sql_products_sku as $product) {
            $product_id = $product->get_id();
            if (!in_array($product_id, $post_ids)) {
                // Only count if the product ID is not already in the array
                $post_ids[] = $product_id;
            }
        }
    }
    // Retrieve SKU numbers for the post IDs
    $sku_numbers = [];
    foreach ($post_ids as $post_id) {
        $product = wc_get_product($post_id);
        if ($product) {
            $sku_numbers[] = $product->get_sku();
        }
    }

    // User
    $restrictuserout = "SELECT art_no FROM taw_restrict_product WHERE roleid != '$userid' and Type='user';";
    $restrictuseroutres = $wpdb->get_results($restrictuserout);

    $restrictuserin = "SELECT art_no FROM taw_restrict_product WHERE roleid = '$userid' and Type='user';";
    $restrictuserinres = $wpdb->get_results($restrictuserin);

    // Role
    $restrictroleout = "SELECT art_no FROM taw_restrict_product WHERE roleid != '$user_role' and Type='role';";
    $restrictroleoutres = $wpdb->get_results($restrictroleout);

    $restrictrolein = "SELECT art_no FROM taw_restrict_product WHERE roleid = '$user_role' and Type='role';";
    $restrictroleinres = $wpdb->get_results($restrictrolein);

    // Merge results
    $mergedout_results = array_merge($restrictroleoutres, $restrictuseroutres);

    $mergedin_results = array_merge($restrictroleinres, $restrictuserinres);

    // Extract art_no values from $mergedin_results

    $uniquein_art_nos = array_map(function($obj) {
        return $obj->art_no;
    }, $mergedin_results);

    // Extract art_no values into an array
    $restrict_art_nos = array_map(function($obj) {
        return $obj->art_no;
    }, $mergedout_results);

    
    $restrict_art_nos = array_unique($restrict_art_nos);

    // Remove values in $restrict_art_nos that are present in $uniquein_art_nos
    $final_restrict_art_nos = array_diff($restrict_art_nos, $uniquein_art_nos);

    // Filter $filter_products to exclude restricted products
    $filtered_products = array_filter($sku_numbers, function($sku) use ($final_restrict_art_nos) {
        return !in_array($sku, $final_restrict_art_nos);
    });

    // Count the filtered SKU numbers
    $unique_count = count($filtered_products);
    echo "<input type='hidden' id='taw_prod_count' value='$unique_count' />";   
}
wp_reset_query();


//if(!$have_data){
    //    echo "-";
//}


?>
<div class="notify-of-add-to-cart" id="notify-of-add-to-cart">
    <div class="productAddedToCart">
        <div class="notifyHeader">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 14 14"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="m4 8l2.05 1.64a.48.48 0 0 0 .4.1a.5.5 0 0 0 .34-.24L10 4"/><circle cx="7" cy="7" r="6.5"/></g></svg>
            <div class="AddedToCart">
                <?php  $lang = getSiteCurrentLang(); 
                if($lang=='en'){?> Product added to cart <?php }elseif($lang=='sv'){ ?> Lägger till produkt  <?php } ?>
            </div>
        </div>
        <div id="productContent">
            <!-- Product items will be dynamically added here -->
        </div>
    </div>
</div>
<style type="text/css">
    .productAddedToCart{
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        background: white;
        border-radius: 20px 20px 20px 20px;
    }
    #productContent{
         padding: 10px 20px;
    }
    .notifyProductContent{
        background: white;
        padding: 10px 20px;
        border-radius: 0px 0px 20px 20px;
        display: flex;
        justify-content: space-around;
        gap: 20px;
        align-items: center;


        
    }
    .AddedToCart{
        font-size: 16px;
    }
    .notifyHeader{

        color: white;
                display: flex;
        
        gap: 20px;
        align-items: center;
        background: #cc071d;
        padding: 10px 10px;
        border-radius: 20px 20px 0px 0px;
    }
    .notify-of-add-to-cart{
        display: none;
        position: fixed;
        max-width: 400px;
        top: 20%;
        right: 5%;
        z-index: 1000;
    }
    @media only screen and (max-width: 500px) {
  .notify-of-add-to-cart {
        top: 15%;
        right: 2%;
  }
      .AddedToCart{
        font-size: 12px;
    }
}
@media screen and (min-width: 1900px) {
   .notify-of-add-to-cart {
        top: 15%;

  }
}
    .loader {
    border: 8px solid #f3f3f3;
    border-top: 8px solid #cc071d;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 1s linear infinite;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5); /* Adjust the opacity as needed */
    z-index: 9999; /* Set a higher z-index than the popup */
}
</style>
<script type="text/javascript">
    function showLoader() {
    var loader = document.createElement('div');
    loader.className = 'loader';
    loader.style.zIndex = '10000000000000000';
    document.body.appendChild(loader);
        var overlay = document.createElement('div');

    overlay.className = 'overlay';
    document.body.appendChild(overlay);
}

function hideLoader() {
    var overlay = document.getElementsByClassName('overlay')[0]
    document.body.removeChild(overlay);
    var loader = document.querySelector('.loader');
    if (loader) {
        loader.parentNode.removeChild(loader);
    }
}

function addItemToCart(imgSrc, title) {
    // Check if the product with the same title is already in the cart
    var existingProduct = document.querySelector('.AddedToCart .notifyProductContent [data-title="' + title + '"]');

    if (existingProduct) {
        // If the product already exists, you can update the quantity or take other actions
      //  console.log('Product with title ' + title + ' is already in the cart.');
    } else {
        var notifyProductContent = document.getElementById("productContent");
        notifyProductContent.innerHTML = '';

        // Create a new product item div
        var productItem = document.createElement("div");
        productItem.classList.add("productCartItem");
        productItem.classList.add("notifyProductContent");
        productItem.dataset.title = title; // Set a data attribute to identify the product

        // Add image element to the product item
        var imgElement = document.createElement("img");
        imgElement.src = imgSrc;
        imgElement.style.width = "50px";
        imgElement.style.height = "50px";
        productItem.appendChild(imgElement);

        // Add title element to the product item
        var titleElement = document.createElement("div");
        titleElement.textContent = title;
        productItem.appendChild(titleElement);

        // Append the product item to the notifyProductContent div
       // var notifyProductContent = document.getElementById("productContent");
        notifyProductContent.appendChild(productItem);
    }

    // Show the cart notification
    var popmessage = document.getElementById('notify-of-add-to-cart');
    popmessage.style.display = "block";
}
function updateCartContent() {
    // Make an additional AJAX request to get the updated cart count and content
    var cartUpdateXhr = new XMLHttpRequest();
    cartUpdateXhr.open('GET', '<?php echo admin_url('admin-ajax.php?action=woocommerce_get_refreshed_fragments'); ?>', true);
    cartUpdateXhr.onreadystatechange = function() {
        if (cartUpdateXhr.readyState === 4 && cartUpdateXhr.status === 200) {
            var cartUpdateResponse = JSON.parse(cartUpdateXhr.responseText);

          //  console.log(cartUpdateResponse.fragments)

            // Update the cart content on the page
            if (cartUpdateResponse.fragments) {
                var specificSelector = '.elementor-menu-cart__toggle_button span.elementor-button-icon-qty';
                var elementToUpdate = document.querySelectorAll('.fkcart-item-count');


             //   console.log("before",elementToUpdate)

                if (elementToUpdate && cartUpdateResponse.fragments[specificSelector]) {
                    // Parse the HTML and update the content
                    var parsedHtml = new DOMParser().parseFromString(cartUpdateResponse.fragments[specificSelector], 'text/html');
                    var qtyElem = parsedHtml.querySelector('.elementor-button-icon-qty')
                    elementToUpdate.forEach(function (element) {
                        element.innerHTML = qtyElem.innerHTML;
                        element.setAttribute('data-item-count', qtyElem.innerHTML);
                    });
                }

              //  console.log(elementToUpdate)
              //  console.log(cartUpdateResponse.fragments[specificSelector])

                // If you have other elements to update, you can continue the loop for other selectors
                for (var selector in cartUpdateResponse.fragments) {
                    if (cartUpdateResponse.fragments.hasOwnProperty(selector) && selector !== specificSelector) {
                        var otherElementToUpdate = document.querySelector(selector);
                        if (otherElementToUpdate) {
                            otherElementToUpdate.innerHTML = cartUpdateResponse.fragments[selector];
                        }
                    }
                }
            }
        }
    };

    // Send the request to update the cart fragments
    cartUpdateXhr.send();
}




    
var forms = document.querySelectorAll('.addToCartform');
var formsList = document.querySelectorAll('.addToCartformList');

formsList.forEach(function(form) {
    form.addEventListener('submit', function(event) { // Fix the parameter name to 'event'
        event.preventDefault();
        showLoader()

        var productImage = form.parentElement.parentElement.parentElement.querySelector('img').src;
        var productTitle = form.parentElement.parentElement.parentElement.querySelector('#prod_title_list').innerHTML;
        console.log(productImage)
        var formData = new FormData(form);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '<?php echo admin_url('admin-ajax.php'); ?>', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {

                          var response = JSON.parse(xhr.responseText);
                       // console.log(response);

                        if (response.status === '1') {
                            hideLoader()
                            addItemToCart(productImage, productTitle);
                            updateCartContent();
                            var popmessage = document.getElementById('notify-of-add-to-cart');

                            setTimeout(function () {
                                popmessage.style.display = 'none';
                            }, 4000);
                        } else {
                            hideLoader()
                            console.error('Error adding to cart:', response.message);
                            // Handle the error as needed
                        }

            }
        };

        // Add an action parameter for the server to identify the request
        formData.append('action', 'ajaxcart');

        // Send the form data
        xhr.send(new URLSearchParams(formData));
    });
});

forms.forEach(function(form) {
    form.addEventListener('submit', function(event) { // Fix the parameter name to 'event'
        event.preventDefault();
        showLoader()

        var productImage = form.parentElement.parentElement.querySelector('img').src;
        var productTitle = form.parentElement.parentElement.querySelector('#prod_title').innerHTML;

        var formData = new FormData(form);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '<?php echo admin_url('admin-ajax.php'); ?>', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {

                          var response = JSON.parse(xhr.responseText);
                       // console.log(response);

                        if (response.status === '1') {
                            hideLoader()
                            addItemToCart(productImage, productTitle);
                            updateCartContent();
                            var popmessage = document.getElementById('notify-of-add-to-cart');

                            setTimeout(function () {
                                popmessage.style.display = 'none';
                            }, 4000);
                        } else {
                            hideLoader()
                            console.error('Error adding to cart:', response.message);
                            // Handle the error as needed
                        }

            }
        };

        // Add an action parameter for the server to identify the request
        formData.append('action', 'ajaxcart');

        // Send the form data
        xhr.send(new URLSearchParams(formData));
    });
});

</script>
