<?php
global $wpdb;
$category = isset($data['category']) ? $data['category'] : "";
$order = isset($data['order']) ? $data['order'] : "asc";
$orderby = isset($data['orderby']) ? $data['orderby'] : "menu_order";
$searchText = isset($data['searchText']) ? $data['searchText'] : "";
$page = isset($data['page']) ? $data['page'] : 1;
$layoutType = isset($data['layout']) ? $data['layout'] : "grid";
$attributes = isset($data['filter']) ? $data['filter'] : [];

$lang = getSiteCurrentLang(); 

// Start building the query
$sql = "
    SELECT DISTINCT p.ID
    FROM {$wpdb->posts} p
    LEFT JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
    LEFT JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
    LEFT JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
    LEFT JOIN {$wpdb->terms} t ON tt.term_id = t.term_id
    WHERE p.post_type = 'product'
    AND p.post_status = 'publish'
    AND NOT EXISTS (
        SELECT 1
        FROM {$wpdb->postmeta} pm2
        WHERE pm2.post_id = p.ID
        AND pm2.meta_key = '_product_type'
        AND pm2.meta_value = 'variable'
    )
";

// Add SKU and title search
if (!empty($searchText)) {
    $searchWords = explode(' ', $searchText);
    $searchText = trim($searchText);
    $searchText = $wpdb->esc_like($searchText);

    $sql .= $wpdb->prepare("
        AND (
            pm.meta_key = '_sku'
            AND pm.meta_value LIKE %s
        )
        ", "%$searchText%");
}



$current_user = wp_get_current_user();

global $wpdb;
if (!($current_user instanceof WP_User) || $current_user->ID == 0) {
    $user_role = 'custom_uam_guest';
    $userid = 'guest';
} else {
    $user_roles = $current_user->roles;
    $current_user_role = isset($user_roles[0]) ? $user_roles[0] : '';

    // Step 2: Fetch the serialized roles data from the tsm_options table
    $option_name = 'tsm_user_roles'; // Replace with the actual option name where roles are stored
    $serialized_roles_data = $wpdb->get_var($wpdb->prepare("SELECT option_value FROM {$wpdb->prefix}options WHERE option_name = %s", $option_name));

    $roles_data = unserialize($serialized_roles_data);
    // Step 3: Check if the current user's role is a subrole and get the main role
    $main_role = null;

    foreach ($user_roles as $role) {
        if (isset($roles_data[$role]['roleissubrole']) && $roles_data[$role]['roleissubrole'] == '1') {
            // If the role is a subrole, get the corresponding main role
            foreach ($roles_data as $role_key => $role_data) {
                if (isset($role_data['subroles']) && in_array($current_user_role, $role_data['subroles'])) {
                    $user_role = $role_key;
                    break;
                }
            }
        } else {
            $user_role = isset($user_roles[0]) ? $user_roles[0] : 'guest';
        }
    }

    $userid = $current_user->ID;
}

// Fetch the customerno for the current user
$customerno_query = $wpdb->prepare("SELECT meta_value FROM tsm_usermeta WHERE user_id = %d AND meta_key LIKE %s", $userid, '%customer_no%');
$customerno = $wpdb->get_var($customerno_query);

if ($customerno) {
    // Fetch all user IDs with the same customerno
    $user_ids_query = $wpdb->prepare("SELECT user_id FROM tsm_usermeta WHERE meta_value = %s", $customerno);
    $related_user_ids = $wpdb->get_col($user_ids_query);
} else {
    $related_user_ids = [$current_user->ID];
}

$roleid_list = implode(',', array_map('intval', $related_user_ids));

// Fetch restricted products for users
$restrictcategoryuserout = "
    SELECT art_no, roleid, 'user' as Type
    FROM taw_restrict_category 
    WHERE roleid NOT IN ($roleid_list) 
    AND Type='user'
";

$restrictcategoryuseroutres = $wpdb->get_results($restrictcategoryuserout, ARRAY_A);

$restrictcategoryuserin = "
    SELECT art_no, roleid, 'user' as Type
    FROM taw_restrict_category 
    WHERE roleid IN (" . implode(',', array_map('intval', $related_user_ids)) . ") 
    AND Type='user'
";
$restrictcategoryuserinres = $wpdb->get_results($restrictcategoryuserin, ARRAY_A);

// Fetch restricted products for roles
$restrictcategoryroleout = "
    SELECT art_no, roleid, 'role' as Type
    FROM taw_restrict_category 
    WHERE roleid != '$user_role' 
    AND Type='role';
";
$restrictcategoryroleoutres = $wpdb->get_results($restrictcategoryroleout, ARRAY_A);

$restrictcategoryrolein = "
    SELECT art_no, roleid, 'role' as Type
    FROM taw_restrict_category 
    WHERE roleid = '$user_role' 
    AND Type='role';
";
$restrictcategoryroleinres = $wpdb->get_results($restrictcategoryrolein, ARRAY_A);

// Merge results
$mergedout_categoryresults = array_merge($restrictcategoryroleoutres, $restrictcategoryuseroutres);
$mergedin_categoryresults = array_merge($restrictcategoryroleinres, $restrictcategoryuserinres);

// Get logged-in user info (WordPress example)
$logged_in_user_id = get_current_user_id();  // WordPress function to get the logged-in user ID
$logged_in_user_role = wp_get_current_user()->roles[0];  // Assuming the first role is sufficient

// Initialize the visibleRestrict flag
$visibleRestrict = false;

// Check in $mergedout_categoryresults and $mergedin_categoryresults
foreach ($mergedout_categoryresults as $item) {
    if ($item['roleid'] === $logged_in_user_role || $item['art_no'] === $logged_in_user_id) {
        $visibleRestrict = true;
        break; // Exit loop if a match is found
    }
}

if (!$visibleRestrict) {
    foreach ($mergedin_categoryresults as $item) {
        if ($item['roleid'] === $logged_in_user_role || $item['art_no'] === $logged_in_user_id) {
            $visibleRestrict = true;
            break; // Exit loop if a match is found
        }
    }
}


// if (!empty($mergedin_categoryresults)) 
// {
//     if($lang=='en') {
//         $exclude_category = $category;   
//     } else if($lang=='sv') {
//         // Ensure $category is properly escaped for use in SQL
//         $category = esc_sql($category); 

//         // The query with proper escaping of $category
//         $q = $wpdb->prepare(
//             "SELECT tsm_terms.slug 
//             FROM tsm_icl_translations 
//             JOIN tsm_terms ON tsm_icl_translations.element_id = tsm_terms.term_id 
//             WHERE tsm_icl_translations.trid = (
//                 SELECT tsm_icl_translations.trid 
//                 FROM tsm_terms 
//                 JOIN tsm_icl_translations ON tsm_terms.term_id = tsm_icl_translations.element_id 
//                 WHERE tsm_terms.slug = %s 
//                 AND tsm_icl_translations.element_type = 'tax_product_cat' 
//                 AND tsm_icl_translations.language_code = 'sv' 
//                 AND tsm_icl_translations.source_language_code = 'en'
//             ) 
//             AND tsm_icl_translations.language_code = 'en';", 
//             $category // Pass the category here to ensure proper escaping
//         );

//         $result = $wpdb->get_results($q);

//         if ($result) {
//             $exclude_category = $result[0]->slug; // Store the slug from the result
//         }
//     }

//     // Get the art_no values for the products you want to exclude from merged results
//     $exclude_art_nos = [];
//     foreach ($mergedin_categoryresults as $result) {
    
//         if (isset($result['art_no']) && $result['art_no'] == $exclude_category) {
//             $exclude_art_nos[] = $result['art_no'];
//         }
//     }
//     // If the category is found in merged results, remove the corresponding product IDs
//     if (empty($exclude_art_nos)) 
//     {
//         $product_ids = []; 
        
//     }else{
//         // Add category filter if needed
//         if (!empty($category)) {
//             $sql .= $wpdb->prepare("
//                 AND tt.taxonomy = 'product_cat' AND t.slug = %s
//             ", $category);
//         }
        
//         // Execute the query
//         $product_ids = $wpdb->get_col($sql);
        
//         // Remove duplicate IDs (if any)
//         $product_ids = array_unique($product_ids);
//     }
// }
// else{

    if (!empty($category)) {
        $sql .= $wpdb->prepare("
            AND tt.taxonomy = 'product_cat' AND t.slug = %s
        ", $category);
    }
    
    // Execute the query
    $product_ids = $wpdb->get_col($sql);
    
    // Remove duplicate IDs (if any)
    $product_ids = array_unique($product_ids);
// }

// Check if no products found
if (empty($product_ids)) {
    $total_pages = 0;
    echo "<input type='hidden' id='taw_page_count' value='0' />";
    $filter_products = array();
} else {

// Initialize query arguments
$args = array(
    'post_type'      => 'product',
    'posts_per_page' => 12,
    'paged'          => $page,
    'post_status'    => 'publish',
    'post__in'       => $product_ids, // Include only these IDs
    'orderby'        => 'post__in', // Preserve the order of IDs
);

// Initialize tax_query
$tax_query = array('relation' => 'AND');

// Add attribute filters if needed
foreach ($attributes as $key => $val) {
    $tax_query[] = array(
        'taxonomy' => "pa_$key",
        'terms'    => $val,
        'field'    => 'term_id',
        'operator' => 'IN',
    );
}

// If there are any tax_query elements, add them to $args
if (!empty($tax_query)) {
    $args['tax_query'] = $tax_query;
}

// Execute the query
$loop = new WP_Query($args);
// Get the total number of pages
$total_pages = $loop->max_num_pages;
echo "<input type='hidden' id='taw_page_count' value='$total_pages' />";
$filter_products = array();

while ($loop->have_posts()) : $loop->the_post();
    $filter_products[] = wc_get_product(get_the_ID());
endwhile;
}
wp_reset_postdata();

$current_user = wp_get_current_user();

global $wpdb;
if (!($current_user instanceof WP_User) || $current_user->ID == 0) {
    $user_role = 'custom_uam_guest';
    $userid = 'guest';
} else {
    $user_roles = $current_user->roles;
    $current_user_role = isset($user_roles[0]) ? $user_roles[0] : '';

    // Step 2: Fetch the serialized roles data from the tsm_options table
    $option_name = 'tsm_user_roles'; // Replace with the actual option name where roles are stored
    $serialized_roles_data = $wpdb->get_var($wpdb->prepare("SELECT option_value FROM {$wpdb->prefix}options WHERE option_name = %s", $option_name));

    $roles_data = unserialize($serialized_roles_data);
    // Step 3: Check if the current user's role is a subrole and get the main role
    $main_role = null;

    foreach ($user_roles as $role) {
        if (isset($roles_data[$role]['roleissubrole']) && $roles_data[$role]['roleissubrole'] == '1') {
            // If the role is a subrole, get the corresponding main role
            foreach ($roles_data as $role_key => $role_data) {
                if (isset($role_data['subroles']) && in_array($current_user_role, $role_data['subroles'])) {
                    $user_role = $role_key;
                    break;
                }
            }
        } else {
            $user_role = isset($user_roles[0]) ? $user_roles[0] : 'guest';
        }
    }

    $userid = $current_user->ID;
}

// Fetch the customerno for the current user
$customerno_query = $wpdb->prepare("SELECT meta_value FROM tsm_usermeta WHERE user_id = %d AND meta_key LIKE %s", $userid, '%customer_no%');
$customerno = $wpdb->get_var($customerno_query);

if ($customerno) {
    // Fetch all user IDs with the same customerno
    $user_ids_query = $wpdb->prepare("SELECT user_id FROM tsm_usermeta WHERE meta_value = %s", $customerno);
    $related_user_ids = $wpdb->get_col($user_ids_query);
} else {
    $related_user_ids = [$current_user->ID];
}

$roleid_list = implode(',', array_map('intval', $related_user_ids));

// Fetch restricted products for users
$restrictuserout = "
    SELECT art_no, roleid, 'user' as Type
    FROM taw_restrict_product 
    WHERE roleid NOT IN ($roleid_list) 
    AND Type='user'
";

$restrictuseroutres = $wpdb->get_results($restrictuserout, ARRAY_A);

$restrictuserin = "
    SELECT art_no, roleid, 'user' as Type
    FROM taw_restrict_product 
    WHERE roleid IN (" . implode(',', array_map('intval', $related_user_ids)) . ") 
    AND Type='user'
";
$restrictuserinres = $wpdb->get_results($restrictuserin, ARRAY_A);

// Fetch restricted products for roles
$restrictroleout = "
    SELECT art_no, roleid, 'role' as Type
    FROM taw_restrict_product 
    WHERE roleid != '$user_role' 
    AND Type='role';
";
$restrictroleoutres = $wpdb->get_results($restrictroleout, ARRAY_A);

$restrictrolein = "
    SELECT art_no, roleid, 'role' as Type
    FROM taw_restrict_product 
    WHERE roleid = '$user_role' 
    AND Type='role';
";
$restrictroleinres = $wpdb->get_results($restrictrolein, ARRAY_A);

// Merge results
$mergedout_results = array_merge($restrictroleoutres, $restrictuseroutres);
$mergedin_results = array_merge($restrictroleinres, $restrictuserinres);

// Create a lookup for in_results by art_no for quick access
$in_result_lookup = [];
foreach ($mergedin_results as $in_art_no_obj) {
    $in_result_lookup[$in_art_no_obj['art_no']] = $in_art_no_obj['Type'];
}

// Initialize arrays
$final_restrict_art_nos = [];

// Loop through the out_results to determine what to add to the final_restrict_art_nos
foreach ($mergedout_results as $out_art_no_obj) {
    $out_art_no = $out_art_no_obj['art_no'];
    $out_art_type = $out_art_no_obj['Type'];

    if (isset($in_result_lookup[$out_art_no])) {
        // The same art_no exists in both in_result and out_result
        $in_art_type = $in_result_lookup[$out_art_no];

        if ($in_art_type !== 'user' && $out_art_type === 'user') {
            // If the in_result's Type is not 'user' and out_result's Type is 'user', add to final_restrict_art_nos
            $final_restrict_art_nos[] = $out_art_no;
        }
    } else {
        // The art_no does not exist in in_result, so add to final_restrict_art_nos
        $final_restrict_art_nos[] = $out_art_no;
    }
}

// Filter $filter_products to exclude restricted products
$filtered_products = array_filter($filter_products, function($product) use ($final_restrict_art_nos) {
    return !in_array($product->get_sku(), $final_restrict_art_nos);
});

foreach ($filtered_products as $product) :
?>
<!-- HTML and rendering part -->
<?php if ($layoutType == "grid"): ?>
    <div class="border p-3 rounded-md">
    <style>
        .quotedesign {
            margin-top: 22px !important;
        }

        .prod_title {
            text-align: center;
            display: -webkit-box; /* Required for truncating lines */
            -webkit-box-orient: vertical; /* Sets the box orientation */
            overflow: hidden; /* Truncate text */
            text-overflow: ellipsis; /* Adds ellipsis for truncated text */
            -webkit-line-clamp: 3; /* Limit text to 3 lines */
            line-height: 1.5em; /* Line height for consistent spacing */
            height: calc(1.5em * 3); /* Match height to 3 lines */
            width: 100%;
            cursor: pointer; /* Hover effect */
        }

        /* Tooltip container */
        .tooltip {
            position: relative; /* Position tooltip relative to this container */
            display: inline-block; /* Keeps the tooltip inline */
            overflow: visible; /* Ensures tooltip visibility */
        }

        /* Tooltip content */
        .tooltip .tooltiptext {
            visibility: hidden; /* Hide the tooltip initially */
            width: 300px; /* Tooltip width */
            background-color: #555; /* Tooltip background */
            color: #fff; /* Tooltip text color */
            border-radius: 5px;
            padding: 5px 2px; /* Padding for tooltip content */
            position: absolute; /* Absolutely position relative to .tooltip */
            z-index: 1;
            bottom: 100%; /* Place tooltip above text */
            left: 50%; /* Center tooltip horizontally */
            transform: translateX(-50%); /* Adjust horizontal alignment */
            margin-bottom: 5px; /* Spacing between tooltip and text */
            opacity: 0; /* Initially make the tooltip invisible */
            transition: opacity 0.3s ease-in-out; /* Smooth fade-in effect */
            font-size: 12px; /* Adjust font size */
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.3); /* Add shadow for better visibility */
        }

        /* Show tooltip on hover if needed */
        .tooltip.show-tooltip:hover .tooltiptext {
            visibility: visible; /* Make tooltip visible on hover */
            opacity: 1; /* Fully opaque */
        }
    </style>


    <script defer>
    // console.log("Script loaded successfully");

    // Function to initialize tooltips
    function initializeTooltips() {
        const tooltipElements = document.querySelectorAll('.tooltip');
        // console.log('Tooltip script is running'); // Debug: Check if script is executing

        tooltipElements.forEach(tooltip => {
            const titleElement = tooltip.querySelector('.prod_title');
            const tooltipText = tooltip.querySelector('.tooltiptext');

            if (!titleElement || !tooltipText) {
                console.error('Tooltip or title element is missing!'); // Debug: Check if elements exist
                return;
            }

            // Detect truncation using scrollHeight and offsetHeight
            const isTruncated = titleElement.scrollHeight > titleElement.offsetHeight;

            // console.log(`Product ID: ${titleElement.id}, Is Truncated: ${isTruncated}`); // Debug: Log truncation status

            if (isTruncated) {
                tooltip.classList.add('show-tooltip'); // Add class to show the tooltip
            } else {
                tooltipText.style.display = 'none'; // Hide the tooltip if not overflowing
            }
        });
    }

    // Run tooltips initialization on page load
    window.onload = initializeTooltips;
    initializeTooltips();
    // Listen for "Load More" button clicks
    document.addEventListener('click', function (event) {
        if (event.target && event.target.id === 'taw_filter_item_load_more') {
            // console.log("Load More button clicked");

            // Wait for new products to load and reinitialize tooltips
            setTimeout(() => {
                // console.log("Reinitializing tooltips for dynamically loaded products...");
                initializeTooltips();
            }, 500); // Adjust delay based on product load time
        }
    });

    // Use MutationObserver to handle dynamically added products
    if (!window.tooltipObserverInitialized) {
        const productContainer = document.querySelector('#taw-prod-items'); // The container with dynamically loaded products

        if (productContainer) {
            const observer = new MutationObserver((mutationsList) => {
                mutationsList.forEach(mutation => {
                    if (mutation.addedNodes.length > 0) {
                        // console.log("New products detected, reinitializing tooltips...");
                        initializeTooltips();
                    }
                });
            });

            observer.observe(productContainer, { childList: true, subtree: true }); // Watch for changes in child nodes

            // Mark the observer as initialized to prevent duplication
            window.tooltipObserverInitialized = true;
        } else {
            console.error("Product container not found. Ensure the selector is correct.");
        }
    }
    </script>
        <a href="<?php echo $product->get_permalink(); ?>" class="text-black" style="text-decoration: none;">
            <div class="!h-44 !w-auto flex items-center justify-center">
                <?php
                $image = get_the_post_thumbnail_url($product->get_id());
                $default = wc_placeholder_img_src(120);
                if (empty($image)) {
                    $image = $default;
                }
                ?>
                <img class="!w-auto !max-h-40 sm:!max-h-44 md:!max-h-40 lg:!max-h-40 xl:!max-h-44 mx-auto" src="<?php echo $image; ?>" onerror="this.onerror=null;this.src='<?php echo $default; ?>'">
            </div>
            <h3 class="w-full overflow-hidden text-sm font-semibold text-center mt-3">
                <?php
                $label = $lang == 'sv' ? 'Artikelnummer:' : 'Article Number:';
                echo '<span style="color: red;">' . $label . ' ' . $product->get_sku() . '</span><br>';
                
                ?>
            </h3>
            <h3 class="w-full text-sm font-semibold text-center" style="height: 3rem;">
                <span class="tooltip">
                    <span class="prod_title" id="prod_title_<?php echo $product->get_id(); ?>"><?php echo $product->get_name(); ?></span>
                    <span class="tooltiptext"><?php echo $product->get_name(); ?></span>
                </span>
            </h3>
            <!-- <h3 class="w-full h-16 overflow-hidden text-sm font-semibold text-center" id="prod_title"><?php //echo $product->get_name(); ?></h3> -->
            <div class="flex flex-col items-center" style="margin-bottom: 5px; margin-top:-15px;">
                <span class="text-sm font-semibold text-red-600 block mt-4 !py-2">
                    <?php echo product_price($product->get_price_html()); ?>
                </span>
                <?php do_action('woocommerce_before_add_to_cart_form'); ?>
                <form class="cart mt-6 addToCartform" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype='multipart/form-data'>
                    <?php do_action('woocommerce_before_add_to_cart_button'); ?>
                    <div class="flex justify-center lg:justify-start">
                        <input type="text" name="product_id" value="<?php echo esc_attr($product->get_id()); ?>" hidden>
                        <input type="text" name="product_qty" value="1" hidden>
                        <!-- <button class="ml-3 w-28 h-7 text-sm text-center flex items-center justify-center bg-red-600 text-white rounded-full hover:bg-black"> -->
                        <button class="ml-3 w-28 h-7 text-sm text-center flex items-center justify-center bg-red-600 text-white rounded-full" 
                        style="--tw-bg-opacity: 1; transition: all 0.3s; background-color: #DC2626;" 
                        onmouseover="this.style.backgroundColor='#575656'; this.style.borderColor='#575656';"
                        onmouseout="this.style.backgroundColor='#DC2626' ; this.style.borderColor='transparent';">
                            <?php
                            $result = product_buy_or_quote($product->get_price_html());
                            if ($result === 'Quote') {
                                echo __($result, 'default');
                            } else {
                                $s = __($result, 'TAW_TEXT_DOMAIN');
                                echo mb_strtolower($s, 'UTF-8') === 'kop' ? 'köp' : __($result, 'TAW_TEXT_DOMAIN');
                            }
                            ?>
                        </button>
                    </div>
                    <?php do_action('woocommerce_after_add_to_cart_button'); ?>
                </form>
                <?php do_action('woocommerce_after_add_to_cart_form'); ?>
            </div>
        </a>
    </div>
<?php else: ?>
    <div class="flex flex-wrap justify-center md:flex-nowrap py-7">
        <div class="relative w-full md:w-1/3">
            <a href="<?php echo $product->get_permalink(); ?>" class="text-black">
                <div class="!w-auto !h-40 sm:!h-44 md:!h-40 lg:!h-40 xl:!h-44 flex items-center justify-center">
                    <?php
                    $image = get_the_post_thumbnail_url($product->get_id());
                    $default = wc_placeholder_img_src(120);
                    if (empty($image)) {
                        $image = $default;
                    }
                    ?>
                    <img class="!w-auto !max-h-40 sm:!max-h-44 md:!max-h-40 lg:!max-h-40 xl:!max-h-44 mx-auto" src="<?php echo $image; ?>" onerror="this.onerror=null;this.src='<?php echo $default; ?>'">
                </div>
            </a>
        </div>
        <div class="w-full md:w-2/3 mx-1 md:mx-7 xl:mx-10">
            <a href="<?php echo $product->get_permalink(); ?>" class="text-black">
                <h3 class="text-lg font-semibold text-center md:text-start" id="prod_title_list"><?php echo $product->get_name(); ?></h3>
            </a>
            <span class="text-sm font-semibold text-red-600 flex justify-center md:justify-start">
                <?php echo product_price($product->get_price_html()); ?>
            </span>
            <div class="flex justify-center md:justify-start items-start mt-5">
                <?php if ($product->is_in_stock()) : ?>
                    <?php do_action('woocommerce_before_add_to_cart_form'); ?>
                    <form class="cart flex justify-center lg:justify-start addToCartformList" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype='multipart/form-data'>
                        <?php
                        do_action('woocommerce_before_add_to_cart_quantity');
                        woocommerce_quantity_input(array(
                            'min_value' => apply_filters('woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product),
                            'max_value' => apply_filters('woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product),
                            'input_value' => isset($_POST['quantity']) ? wc_stock_amount(wp_unslash($_POST['quantity'])) : $product->get_min_purchase_quantity(),
                            'input_name' => 'product_qty',
                        ));
                        do_action('woocommerce_after_add_to_cart_quantity');
                        ?>
                        <input type="text" name="product_id" value="<?php echo esc_attr($product->get_id()); ?>" hidden>
                        <button name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" class="ml-3 w-28 h-7 text-sm text-center flex items-center justify-center bg-red-600 text-white rounded-full hover:bg-black">
                            <?php echo product_buy_or_quote($product->get_price_html()); ?>
                        </button>
                        <?php do_action('woocommerce_after_add_to_cart_button'); ?>
                    </form>
                    <?php do_action('woocommerce_after_add_to_cart_form'); ?>
                <?php endif; ?>
            </div>
            <h4 class="text-xs text-gray-500 mt-4 mb-0 text-center md:text-start"><?php echo $lang == 'sv' ? 'Artikelnummer:' : 'Article Number:'; ?> <?php echo $product->get_sku(); ?></h4>
            <h4 class="text-xs text-gray-500 text-center md:text-start mt-0 mb-0">
                <?php echo $lang == 'sv' ? 'Kategori:' : 'Category:'; ?> 
                <?php
                $terms = get_the_terms($product->get_ID(), 'product_cat');
                foreach ($terms as $term) {
                    echo $term->name;
                    break;
                }
                ?>
            </h4>
        </div>
    </div>
<?php endif; ?>
<?php endforeach; ?>

<?php
if ($page == 1) {
    $args['posts_per_page'] = -1;
    $countQ = new WP_Query($args);
    $post_ids = [];
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

    // // User
    // $restrictuserout = "SELECT art_no FROM taw_restrict_product WHERE roleid != '$userid' and Type='user';";
    // $restrictuseroutres = $wpdb->get_results($restrictuserout);

    // $restrictuserin = "SELECT art_no FROM taw_restrict_product WHERE roleid = '$userid' and Type='user';";
    // $restrictuserinres = $wpdb->get_results($restrictuserin);

    // // Role
    // $restrictroleout = "SELECT art_no FROM taw_restrict_product WHERE roleid != '$user_role' and Type='role';";
    // $restrictroleoutres = $wpdb->get_results($restrictroleout);

    // $restrictrolein = "SELECT art_no FROM taw_restrict_product WHERE roleid = '$user_role' and Type='role';";
    // $restrictroleinres = $wpdb->get_results($restrictrolein);

    // // Merge results
    // $mergedout_results = array_merge($restrictroleoutres, $restrictuseroutres);

    // $mergedin_results = array_merge($restrictroleinres, $restrictuserinres);

    // // Extract art_no values from $mergedin_results

    // $uniquein_art_nos = array_map(function($obj) {
    //     return $obj->art_no;
    // }, $mergedin_results);

    // // Extract art_no values into an array
    // $restrict_art_nos = array_map(function($obj) {
    //     return $obj->art_no;
    // }, $mergedout_results);

    
    // $restrict_art_nos = array_unique($restrict_art_nos);

    // // Remove values in $restrict_art_nos that are present in $uniquein_art_nos
    // $final_restrict_art_nos = array_diff($restrict_art_nos, $uniquein_art_nos);

    // Filter $filter_products to exclude restricted products
    $filtered_products = array_filter($sku_numbers, function($sku) use ($final_restrict_art_nos) {
        return !in_array($sku, $final_restrict_art_nos);
    });

    // // Filter $filter_products to exclude restricted products
    // $filtered_products = array_filter($sku_numbers, function($sku) use ($restrict_art_nos) {
    //     return !in_array($sku, $restrict_art_nos);
    // });

    // // Add $uniquein_art_nos values to $filtered_products
    // foreach ($uniquein_art_nos as $art_no) {
    //     // Retrieve product by SKU
    //     $product_id = wc_get_product_id_by_sku($art_no);
    //     if ($product_id) {
    //         $product = wc_get_product($product_id);
    //         if ($product) {
    //             $filtered_products[] = $product;
    //         }
    //     }
    // }
    // // print_r($sku_numbers);
    // $restrict ="SELECT art_no FROM taw_restrict_product WHERE roleid != '$user_role';";
    // $restrictproduct = $wpdb->get_results($restrict);
    
    // // Extract art_no values into an array
    // $restrict_art_nos = array_map(function($obj) {
    //     return $obj->art_no;
    // }, $restrictproduct);
    // // print_r($restrict_art_nos); // Print the SKU numbers for the post IDs
    // // Filter out SKU numbers that are in the restricted list
    // $filtered_skus = array_filter($sku_numbers, function($sku) use ($restrict_art_nos) {
    //     return !in_array($sku, $restrict_art_nos);
    // });

    // Count the filtered SKU numbers
    $unique_count = count($filtered_products);
   
    echo "<input type='hidden' id='taw_prod_count' value='$unique_count' />";
    wp_reset_query();
}
?>
<div class="notify-of-add-to-cart" id="notify-of-add-to-cart">
    <div class="productAddedToCart">
        <div class="notifyHeader">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 14 14"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="m4 8l2.05 1.64a.48.48 0 0 0 .4.1a.5.5 0 0 0 .34-.24L10 4"/><circle cx="7" cy="7" r="6.5"/></g></svg>
            <div class="AddedToCart"><?php echo $lang == 'sv' ? 'Lägger till produkt' : 'Product added to cart'; ?></div>
        </div>
        <div id="productContent"></div>
    </div>
</div>
<style type="text/css">
    .productAddedToCart {
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        background: white;
        border-radius: 20px;
    }
    #productContent { padding: 10px 20px; }
    .notifyProductContent {
        background: white;
        padding: 10px 20px;
        border-radius: 0 0 20px 20px;
        display: flex;
        justify-content: space-around;
        gap: 20px;
        align-items: center;
    }
    .AddedToCart { font-size: 16px; }
    .notifyHeader {
        color: white;
        display: flex;
        gap: 20px;
        align-items: center;
        background: #cc071d;
        padding: 10px;
        border-radius: 20px 20px 0 0;
    }
    .notify-of-add-to-cart {
        display: none;
        position: fixed;
        max-width: 400px;
        top: 20%;
        z-index: 1000;
    }
    @media only screen and (max-width: 500px) {
        .AddedToCart { font-size: 12px; }
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
        z-index: 10000000000000000;
    }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;
    }
</style>
<script type="text/javascript">
    function showLoader() {
        var loader = document.createElement('div');
        loader.className = 'loader';
        document.body.appendChild(loader);
        var overlay = document.createElement('div');
        overlay.className = 'overlay';
        document.body.appendChild(overlay);
    }

    function hideLoader() {
        var overlay = document.getElementsByClassName('overlay')[0];
        document.body.removeChild(overlay);
        var loader = document.querySelector('.loader');
        if (loader) {
            loader.parentNode.removeChild(loader);
        }
    }

    function addItemToCart(imgSrc, title) {
        var existingProduct = document.querySelector('.AddedToCart .notifyProductContent [data-title="' + title + '"]');
        if (existingProduct) {
            return;
        }
        var notifyProductContent = document.getElementById("productContent");
        notifyProductContent.innerHTML = '';
        var productItem = document.createElement("div");
        productItem.classList.add("productCartItem");
        productItem.classList.add("notifyProductContent");
        productItem.dataset.title = title;
        var imgElement = document.createElement("img");
        imgElement.src = imgSrc;
        imgElement.style.width = "50px";
        imgElement.style.height = "50px";
        productItem.appendChild(imgElement);
        var titleElement = document.createElement("div");
        titleElement.textContent = title;
        productItem.appendChild(titleElement);
        notifyProductContent.appendChild(productItem);
        var popmessage = document.getElementById('notify-of-add-to-cart');
        popmessage.style.display = "block";
    }

    function updateCartContent() {
        var cartUpdateXhr = new XMLHttpRequest();
        cartUpdateXhr.open('GET', '<?php echo admin_url('admin-ajax.php?action=woocommerce_get_refreshed_fragments'); ?>', true);
        cartUpdateXhr.onreadystatechange = function() {
            if (cartUpdateXhr.readyState === 4 && cartUpdateXhr.status === 200) {
                var cartUpdateResponse = JSON.parse(cartUpdateXhr.responseText);
                if (cartUpdateResponse.fragments) {
                    var specificSelector = '.elementor-menu-cart__toggle_button span.elementor-button-icon-qty';
                    var elementToUpdate = document.querySelectorAll('.fkcart-item-count');
                    if (elementToUpdate && cartUpdateResponse.fragments[specificSelector]) {
                        var parsedHtml = new DOMParser().parseFromString(cartUpdateResponse.fragments[specificSelector], 'text/html');
                        var qtyElem = parsedHtml.querySelector('.elementor-button-icon-qty');
                        elementToUpdate.forEach(function (element) {
                            element.innerHTML = qtyElem.innerHTML;
                            element.setAttribute('data-item-count', qtyElem.innerHTML);
                        });
                    }
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
        cartUpdateXhr.send();
    }

    var forms = document.querySelectorAll('.addToCartform');
    var formsList = document.querySelectorAll('.addToCartformList');

    formsList.forEach(function(form) {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            showLoader();
            var productImage = form.parentElement.parentElement.parentElement.querySelector('img').src;
            var productTitle = form.parentElement.parentElement.parentElement.querySelector('#prod_title_list').innerHTML;
            var formData = new FormData(form);
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '<?php echo admin_url('admin-ajax.php'); ?>', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.status === '1') {
                        hideLoader();
                        addItemToCart(productImage, productTitle);
                        updateCartContent();
                        var popmessage = document.getElementById('notify-of-add-to-cart');
                        setTimeout(function () { popmessage.style.display = 'none'; }, 4000);
                    } else {
                        hideLoader();
                        console.error('Error adding to cart:', response.message);
                    }
                }
            };
            formData.append('action', 'ajaxcart');
            xhr.send(new URLSearchParams(formData));
        });
    });

    forms.forEach(function(form) {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            showLoader();
            var productImage = form.parentElement.parentElement.querySelector('img').src;
            var productTitle = form.parentElement.parentElement.querySelector('.prod_title').innerHTML;
            var formData = new FormData(form);
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '<?php echo admin_url('admin-ajax.php'); ?>', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.status === '1') {
                        hideLoader();
                        addItemToCart(productImage, productTitle);
                        updateCartContent();
                        var popmessage = document.getElementById('notify-of-add-to-cart');
                        setTimeout(function () { popmessage.style.display = 'none'; }, 4000);
                    } else {
                        hideLoader();
                        console.error('Error adding to cart:', response.message);
                    }
                }
            };
            formData.append('action', 'ajaxcart');
            xhr.send(new URLSearchParams(formData));
        });
    });
</script>
