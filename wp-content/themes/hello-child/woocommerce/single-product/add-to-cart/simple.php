<?php
/**
 * Simple product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product;
$productcategory=$product->get_categories();
if ( ! $product->is_purchasable() ) {
	return;
}

echo wc_get_stock_html( $product ); // WPCS: XSS ok.

 if ( $product->is_in_stock() ) : ?>

	<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

	<form class="cart mt-6 addToCartform" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
		<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

		<div class="flex justify-center lg:justify-start">

			<?php
				do_action( 'woocommerce_before_add_to_cart_quantity' );

				woocommerce_quantity_input(
					array(
						'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
						'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
						'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
					)
				);

				do_action( 'woocommerce_after_add_to_cart_quantity' );
			?>

			<input type="text" name="product_id" value="<?php echo esc_attr(  $product->get_id() ); ?>" hidden>

			<button name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="ml-3 w-28 h-7 text-sm text-center flex items-center justify-center bg-red-600 text-white rounded-full hover:bg-black">
			<?php if ((strpos($productcategory, "Tailor-made product") !== false) ) : ?>
				<?php //if($product->is_type('grouped')) : ?>

				<?php
                     $price_html = $product->get_price_html();
                     $buy_or_quote = product_buy_or_quote($price_html);
					 $buy_or_quote =  __($buy_or_quote_modified,"default");
                     $buy_or_quote_modified = ($buy_or_quote === 'Quote') ? 'Contact Us' : $buy_or_quote;
                     echo $buy_or_quote_modified;
					//  echo __($buy_or_quote_modified,"default");
                ?>
			<?php else : ?>
				<?php //echo product_buy_or_quote($product->get_price_html()); 
				$result = product_buy_or_quote($product->get_price_html());

					if ($result === 'Quote') {
						// Assuming you have 'Quote' translated in the 'Your Text Domain' with WPML
						echo __($result, 'default');
					} else {
					   // echo $result;
					   echo __($result, 'TAW_TEXT_DOMAIN');
					}?>
				<?php endif; ?>
		</button>
 
		</div>

		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
	</form>

	<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

<?php endif; ?>
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
        console.log('Product with title ' + title + ' is already in the cart.');
    } else {
        // Clear previous content
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



            // Update the cart content on the page
            if (cartUpdateResponse.fragments) {
                var specificSelector = '.elementor-menu-cart__toggle_button span.elementor-button-icon-qty';
                var elementToUpdate = document.querySelectorAll('.fkcart-item-count');


                console.log("before",elementToUpdate)

                if (elementToUpdate && cartUpdateResponse.fragments[specificSelector]) {
                    // Parse the HTML and update the content
                    var parsedHtml = new DOMParser().parseFromString(cartUpdateResponse.fragments[specificSelector], 'text/html');
                    var qtyElem = parsedHtml.querySelector('.elementor-button-icon-qty')
                    elementToUpdate.forEach(function (element) {
                        element.innerHTML = qtyElem.innerHTML;
                        element.setAttribute('data-item-count', qtyElem.innerHTML);
                    });
                }

 

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
// console.log(forms)

forms.forEach(function (form) {
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        showLoader();

        var productImage = form.parentElement.parentElement.querySelector('.wp-post-image').src;
        var productTitle = form.parentElement.querySelector('h1').innerHTML;

        // Manually collect form data
        var formData = new URLSearchParams();
        var formElements = event.target.elements;

        for (var i = 0; i < formElements.length; i++) {
            var element = formElements[i];

            if (element.name) {
                if (element.name === 'quantity') {
                    formData.append('product_qty', element.value);
                }  if (element.name === 'product_id') {
                    formData.append('product_id', element.value);
                }
            }
        }

        // Add additional data if needed
        formData.append('action', 'ajaxcart');

        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
            },
            body: formData
        })
            .then(response => response)
            .then(data => {
                console.log(data);

                if (data.status === 200) {
                    hideLoader();
                    addItemToCart(productImage, productTitle);
                    updateCartContent();
                    var popmessage = document.getElementById('notify-of-add-to-cart');

                    setTimeout(function () {
                        popmessage.style.display = 'none';
                    }, 4000);
                } else {
                    hideLoader();
                    console.error('Error adding to cart:', data);
                    // Handle the error as needed
                }
            })
            .catch(error => {
                hideLoader();
                console.error('Fetch error:', error);
                // Handle fetch error as needed
            });
    });
});


</script>
