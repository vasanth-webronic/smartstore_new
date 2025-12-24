<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;
$productcategory=$product->get_categories();
//icl_register_string('TAW_TEXT_DOMAIN','Datasheet','Datasheet');
?>

<div class="border p-3 rounded-md hidden md:block lg:block" id="rel_card">
    <a href="<?php echo get_permalink();?>" class="text-black" style="text-decoration: none;">  
        <?php
            $image = get_the_post_thumbnail_url($product->get_id());
            $default = wc_placeholder_img_src(200);
            if(empty($image)) { $image = $default; }
        ?>
        <div class="!h-40 sm:!h-44 md:!h-40 lg:!h-40 xl:!h-44 !w-auto flex items-center justify-center">
            <img class="!w-auto !max-h-40 sm:!max-h-44 md:!max-h-40 lg:!max-h-40 xl:!max-h-44 mx-auto" id="prod_img" src=<?php  echo $image;?>
                onerror="this.onerror=null;this.src='<?php  echo $default;?>'">
        </div>
        
        <h3 class="w-full h-16 overflow-hidden text-sm font-semibold text-center mt-3" id="prod_name"><?php echo get_the_title() ?></h3>
        <div class="my-7 flex flex-col items-center">
	        <span class="text-sm font-semibold text-red-600 py-2 block">
	            <?php 
                    $price=$product->get_price_html();
                    echo product_price($product->get_price_html());
                    
                ?>
	        </span>
	        <form class="addToCartformRel">  
            <?php if ((strpos($productcategory, "Tailor-made product") !== false) ) : ?>
                <?php // if($product->is_type('grouped')) : ?>
                <?php  
            $imageurl = THINGSATWEB_BASE . '/img/Group-121.png';
            $type = pathinfo($imageurl, PATHINFO_EXTENSION);
            $image_data = file_get_contents($imageurl);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($image_data);
            echo '<img class="filter-item-image mx-auto my-0 md:my-4 !h-6 sm:!h-9 md:!h-11 w-auto" src="' . $base64 . '" alt="Manual">';
        ?>
                <?php else : ?>
<input type="text" name="product_id" value="<?php echo esc_attr( $product->get_id() ); ?>" hidden>
        <input type="text" name="product_qty" value="1" hidden>

		      <!--  <button class="text-sm bg-red-600 w-24 px-6 py-2 text-white rounded-full hover:bg-black">-->
              <button class="text-sm bg-red-600 w-24 px py-2 text-white rounded-full hover:bg-black">
                    <?php
                    
                   // echo 
                   
                   $r=product_buy_or_quote($price);
                   if($r==='Quote'){
                    //$r='More Info';
                   }
					echo __($r,"TAW_TEXT_DOMAIN");
                   ?>
                </button>
                <?php endif; ?>
	        </a>
        </div>
        <!-- <img class="w-4 mx-0" src="<?php //echo THINGSATWEB_BASE.'/img/heart-o.svg';?>" alt=""> -->
    </form>

</div>

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




    
var forms = document.querySelectorAll('.addToCartformRel');

forms.forEach(function(form) {
    form.addEventListener('submit', function(event) { // Fix the parameter name to 'event'
        event.preventDefault();
        showLoader()
        var productImage = form.closest('#rel_card').querySelector('#prod_img').src;
        var productTitle = form.closest('#rel_card').querySelector('#prod_name').innerHTML;

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