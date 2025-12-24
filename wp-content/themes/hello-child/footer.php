<?php
/**
 * The template for displaying the footer.
 *
 * Contains the body & html closing tags.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

			$tran = __( 'Location', 'TAW_TEXT_DOMAIN1' );
$lang = getSiteCurrentLang();
			//$current_language = ICL_LANGUAGE_CODE;
			$current_language = $lang;
			
		
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) {
	if ( did_action( 'elementor/loaded' ) && hello_header_footer_experiment_active() ) {
		get_template_part( 'template-parts/dynamic-footer' );
	} else {
		get_template_part( 'template-parts/footer' );
	}
}
?>

<?php 
if($current_language==='en'){ 
echo "<script>console.log('checking " . $current_language . "')</script>";?>
<nav class="mobile-bottom-nav d-lg-none">
	<div class="mobile-bottom-nav__item" onclick="triggermobilePopup(this)">
		<div class="mobile-bottom-nav__item-content" >
			<a ><i class="fa fa-map-marker-alt" aria-hidden="true"></i></a>
			<?php 
		
			
			echo $tran;
			?>
		</div>
	</div>
				<div class="mobile-bottom-nav__item">
					<div class="mobile-bottom-nav__item-content">
						<a href="mailto:info@smartstoring.se"><i class="fa fa-envelope" aria-hidden="true"></i></a>
						Email
					</div>
				</div>
				<div class="mobile-bottom-nav__item">
					<div class="mobile-bottom-nav__item-content">
						<a href="tel:+46 304 80 90 80"><i class="fa fa-phone-alt" style="margin: 0;" aria-hidden="true"></i></a>
						Phone
					</div>
				</div>
<!-- 				<div class="mobile-bottom-nav__item">
					<div class="mobile-bottom-nav__item-content">
						<a href="/product"><i class="fa fa-store"></i></a>
						Webshop
					</div>
				</div> -->
			</nav>
			  <div class="black-overlay__contact">
        <div class="popup-location__contact">
            <a href="https://maps.app.goo.gl/tZTRDCTfoATnpfv3A" class="map-location-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 256 256"><path fill="currentColor" d="M128 16a88.1 88.1 0 0 0-88 88c0 75.3 80 132.17 83.41 134.55a8 8 0 0 0 9.18 0C136 236.17 216 179.3 216 104a88.1 88.1 0 0 0-88-88m0 56a32 32 0 1 1-32 32a32 32 0 0 1 32-32"/></svg>
                <div>Head Office & Warehouse</div>
            </a>
            <a href="https://maps.app.goo.gl/ywBJB9AiSiipQc7R8" class="map-location-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 256 256"><path fill="currentColor" d="M128 16a88.1 88.1 0 0 0-88 88c0 75.3 80 132.17 83.41 134.55a8 8 0 0 0 9.18 0C136 236.17 216 179.3 216 104a88.1 88.1 0 0 0-88-88m0 56a32 32 0 1 1-32 32a32 32 0 0 1 32-32"/></svg>
                <div>Sales Office</div>
            </a>
            <div class="map-popup-close" onclick="triggermobilePopupClose()">
                <svg xmlns="http://www.w3.org/2000/svg" style="color: #fff;" width="22" height="22" viewBox="0 0 24 24"><path fill="currentColor" d="M6.4 19L5 17.6l5.6-5.6L5 6.4L6.4 5l5.6 5.6L17.6 5L19 6.4L13.4 12l5.6 5.6l-1.4 1.4l-5.6-5.6z"/></svg>
            </div>
        </div>
    </div>

<?php
}
?>

<?php 

if($current_language==='sv'){ ?> 
<nav class="mobile-bottom-nav d-lg-none">
	<div class="mobile-bottom-nav__item" onclick="triggermobilePopup(this)">
		<div class="mobile-bottom-nav__item-content " >
			<a ><i class="fa fa-map-marker-alt" aria-hidden="true"></i></a>
			Plats
		</div>
	</div>
				<div class="mobile-bottom-nav__item">
					<div class="mobile-bottom-nav__item-content">
						<a href="mailto:info@smartstoring.se"><i class="fa fa-envelope" aria-hidden="true"></i></a>
						E-post
					</div>
				</div>
				<div class="mobile-bottom-nav__item">
					<div class="mobile-bottom-nav__item-content">
						<a href="tel:+46 304 80 90 80"><i class="fa fa-phone" style="margin: 0;" aria-hidden="true"></i></a>
						Telefon
					</div>
				</div>
<!-- 				<div class="mobile-bottom-nav__item">
					<div class="mobile-bottom-nav__item-content">
						<a href="/product"><i class="fa fa-store"></i></a>
						Webshop
					</div>
				</div> -->
			</nav> 
			  <div class="black-overlay__contact">
        <div class="popup-location__contact">
            <a href="https://maps.app.goo.gl/tZTRDCTfoATnpfv3A" class="map-location-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 256 256"><path fill="currentColor" d="M128 16a88.1 88.1 0 0 0-88 88c0 75.3 80 132.17 83.41 134.55a8 8 0 0 0 9.18 0C136 236.17 216 179.3 216 104a88.1 88.1 0 0 0-88-88m0 56a32 32 0 1 1-32 32a32 32 0 0 1 32-32"/></svg>
                <div>Godsadress & Besöksadress</div>
            </a>
            <a href="https://maps.app.goo.gl/ywBJB9AiSiipQc7R8" class="map-location-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 256 256"><path fill="currentColor" d="M128 16a88.1 88.1 0 0 0-88 88c0 75.3 80 132.17 83.41 134.55a8 8 0 0 0 9.18 0C136 236.17 216 179.3 216 104a88.1 88.1 0 0 0-88-88m0 56a32 32 0 1 1-32 32a32 32 0 0 1 32-32"/></svg>
                <div>Säljkontor</div>
            </a>
            <div class="map-popup-close" onclick="triggermobilePopupClose()">
                <svg xmlns="http://www.w3.org/2000/svg" style="color: #fff;" width="22" height="22" viewBox="0 0 24 24"><path fill="currentColor" d="M6.4 19L5 17.6l5.6-5.6L5 6.4L6.4 5l5.6 5.6L17.6 5L19 6.4L13.4 12l5.6 5.6l-1.4 1.4l-5.6-5.6z"/></svg>
            </div>
        </div>
    </div>

<?php
	

	
}
?>

<?php wp_footer(); ?>
  
    <style>

        a{
            text-decoration: none;
        }
        .black-overlay__contact{
            display: none;
            width: 100%;
			top: 0;
            height: 100vh;
            position: fixed;
            z-index: 999999;
            background-color: rgba(0, 0, 0, 0.658);
        }
        .popup-location__contact{
            position: absolute;
            bottom: 0;
			width: 100%;
            border-top: 5px solid #CC071D;
            background-color: rgb(255, 255, 255);
            gap: 10px;
            padding: 10px;
            display: flex;
        }
        .map-location-btn{           background-color: #CC071D;
    width: 100%;
    display: flex;
    justify-content:center;
    align-items: center;
    flex-direction: column;
    gap: 5px;
    padding: 10px;
    border-radius: 4px;
   height: 140px
}
.map-location-btn svg {  
    color: white;
    font-size: 41px;
    font-weight: 500;
}
.map-location-btn div {  
    color: white;
    font-size: 16px;
    font-weight: 500;
    height: 40px;
    text-align: center;
    
    
}
.map-popup-close{
    background-color: #CC071D;
    border: 1px solid white;
    position: absolute;
    top: -16px;
    right: 3px;
    border-radius: 50%;
    width: 26px;
    height: 26px;
    text-align: center;
    display: flex;
    justify-content: center;
    align-items: center;
}

    </style>
<script type="text/javascript">
	 function triggermobilePopup(){
	let modPopupContain = document.querySelector('.black-overlay__contact');
	modPopupContain.style.display = 'block'
	console.log(modPopupContain)
}
function triggermobilePopupClose(){
	let modPopupContain = document.querySelector('.black-overlay__contact');
	modPopupContain.style.display = 'none'
}

</script>
</body>
</html>
