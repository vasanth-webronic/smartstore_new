<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;


do_action( 'woocommerce_before_edit_account_form' ); 
     // $user_data = get_userdata($user->id);
     // echo var_dump($user_data->roles);die(); ?>
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/colorPickerResource/colorPicker.css">
    <script src="<?php echo get_stylesheet_directory_uri(); ?>/colorPickerResource/colorPicker.min.js"></script>

<form class="woocommerce-EditAccountForm edit-account ml-0 lg:ml-8" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?>  enctype="multipart/form-data">

	<?php do_action( 'woocommerce_edit_account_form_start' ); ?>
	<?php
	if (is_user_logged_in()) {   
    $current_user = wp_get_current_user();
    $user_roles = $current_user->roles;

    // Check if the user has the "custom_uam_reseller_eur" role
   // if (in_array('custom_uam_reseller_eur', $user_roles)) {
    if (in_array('custom_uam_reseller_eur', $user_roles) || in_array("custom_uam_reseller_sek", $user_roles)) {



        // User has the specified role, display the additional fields
?>
	<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
		<label for="account_company_name"><?php esc_html_e( 'Company name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
		<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_company_name" id="account_company_name"  value="<?php echo esc_attr( $user->account_company_name ); ?>"  />
	</p>
		<p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
		<label for="account_company_website"><?php esc_html_e( 'Company website', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
		<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_company_website" id="account_company_website" value="<?php echo esc_attr( $user->account_company_website ); ?>" />
	</p>
<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first" style="position: relative;  margin: 0px 0px;">
    <label for="account_company_logo"><?php esc_html_e( 'Company Logo', 'woocommerce' ); ?></label>

    <input type="file" class="woocommerce-Input woocommerce-Input--text input-text" name="account_company_logo" style="position: absolute ; border: none; height: 80px;  z-index:-10; background-image: url('<?php echo esc_url($user->account_company_logo ? $user->account_company_logo : ''); ?>'); background-size: contain !important; background-position: center !important; background-repeat: no-repeat !important;" id="account_company_logo" accept=".svg, image/*" onchange="previewImage(this)" />
    <input type="text" name="account_company_logo_trash" class="woocommerce-Input woocommerce-Input--text input-text" id="account_company_logo_trash_input" style="height: 80px; background:transparent;"  value="<?php echo $user->account_company_logo ? '1' : ''; ?>" onclick="triggerFileInputLogo()" >


    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" class="placeholder-image" style="z-index: -20;">
        <path fill="#666" d="M21.02 5H19V2.98c0-.54-.44-.98-.98-.98h-.03c-.55 0-.99.44-.99.98V5h-2.01c-.54 0-.98.44-.99.98v.03c0 .55.44.99.99.99H17v2.01c0 .54.44.99.99.98h.03c.54 0 .98-.44.98-.98V7h2.02c.54 0 .98-.44.98-.98v-.04c0-.54-.44-.98-.98-.98zM16 9.01V8h-1.01c-.53 0-1.03-.21-1.41-.58c-.37-.38-.58-.88-.58-1.44c0-.36.1-.69.27-.98H5c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-8.28c-.30.17-.64.28-1.02.28A2 2 0 0 1 16 9.01zM15.96 19H6a.5.5 0 0 1-.4-.8l1.98-2.63c.21-.28.62-.26.82.02L10 18l2.61-3.48c.20-.26.59-.27.79-.01l2.95 3.68c.26.33.03.81-.39.81z"/>
    </svg>
    
	<svg class="placeholder-edit accounts-icons"  xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="cursor: pointer; <?php echo $user->account_company_logo ? 'display:block' : 'display: none;'; ?>">
        <path fill="currentColor" d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a.996.996 0 0 0 0-1.41l-2.34-2.34a.996.996 0 0 0-1.41 0l-1.83 1.83l3.75 3.75l1.83-1.83z"/>
    </svg>
<svg xmlns="http://www.w3.org/2000/svg" onclick="showDeleteAlert('Company Logo')" class="placeholder-trash" id="placeholder-trash" width="24" height="24" viewBox="0 0 24 24" style="cursor: pointer; <?php echo $user->account_company_logo ? 'display:block' : 'display: none;'; ?>"><path fill="#DC2626" d="M9 3v1H4v2h1v13a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V6h1V4h-5V3H9m0 5h2v9H9V8m4 0h2v9h-2V8Z"/></svg>

</p>


		<p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last" style="position: relative;  margin: 0px 0px;">
		<label for="account_company_theme"><?php esc_html_e( 'Color Theme', 'woocommerce' ); ?></label>
		<input type="color" class="woocommerce-Input woocommerce-Input--text input-text" name="account_company_theme" hidden style="height:80px; background-color: rgba(0, 0, 0, 0);" value="<?php echo esc_attr( ! empty( $user->account_company_theme ) ? $user->account_company_theme : '#FFFFFF' ); ?>" onchange="updateColorPicker(this)" id="account_company_theme"  /> 
		<input type="text" name="account_company_theme_overlay" readonly class="woocommerce-Input woocommerce-Input--text input-text" id="account_company_theme_overlay" style="height: 80px; cursor: pointer; background:<?php echo esc_attr( ! empty( $user->account_company_theme ) ? $user->account_company_theme : 'transparent' ); ?>;" onclick="triggerFileInputCl()" >
        
		<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 512 512" class="placeholder-image" id="colorpalletIcon" onclick="triggerFileInputCl()" style="z-index: 2; cursor: pointer; display: none;"><path fill="#666" d="M416 352c-12.6-.84-21-4-28-12c-14-16-14-36 5.49-52.48l32.82-29.14c50.27-44.41 50.27-117.21 0-161.63C389.26 64.14 339.54 48 287.86 48c-60.34 0-123.39 22-172 65.11c-90.46 80-90.46 210.92 0 290.87c45 39.76 105.63 59.59 165.64 60h1.84c60 0 119.07-19.5 161.2-56.77C464 390 464 385 444.62 355.56C440 348 431 353 416 352ZM112 208a32 32 0 1 1 32 32a32 32 0 0 1-32-32Zm40 135a32 32 0 1 1 32-32a32 32 0 0 1-32 32Zm40-199a32 32 0 1 1 32 32a32 32 0 0 1-32-32Zm64 271a48 48 0 1 1 48-48a48 48 0 0 1-48 48Zm72-239a32 32 0 1 1 32-32a32 32 0 0 1-32 32Z"/></svg>
		    <svg class="placeholder-edit-cl accounts-icons" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="display: none; cursor: pointer;">
        <path fill="currentColor" d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a.996.996 0 0 0 0-1.41l-2.34-2.34a.996.996 0 0 0-1.41 0l-1.83 1.83l3.75 3.75l1.83-1.83z"/>

    </svg>
    <svg xmlns="http://www.w3.org/2000/svg" onclick="showDeleteAlert('Color Theme')" class="placeholder-trash accounts-icons" id="placeholder-trash-cl" width="24" height="24" viewBox="0 0 24 24" style="cursor: pointer; <?php echo $user->account_company_theme ? 'display:block' : 'display: none;'; ?>"><path fill="#DC2626" d="M9 3v1H4v2h1v13a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V6h1V4h-5V3H9m0 5h2v9H9V8m4 0h2v9h-2V8Z"/></svg>
    <span id="colorCode" style="color: #fff; font-weight:bold;"><?php echo esc_attr( ! empty( $user->account_company_theme ) ? $user->account_company_theme : '' ); ?></span>

	</p>
	<p class="text-gray-500 woocommerce-form-row--wide form-row-wide"><em><?php esc_html_e( 'Recommended logo size 480 x 120 px. The logo and color theme will be reflected in the datasheet.', 'woocommerce'); ?></em></p>
<?php
    }
}
?>

	
	<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
		<label for="account_first_name"><?php esc_html_e( 'First name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
		<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_first_name" id="account_first_name" autocomplete="given-name" value="<?php echo esc_attr( $user->first_name ); ?>" />
	</p>
	<p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
		<label for="account_last_name"><?php esc_html_e( 'Last name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
		<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_last_name" id="account_last_name" autocomplete="family-name" value="<?php echo esc_attr( $user->last_name ); ?>" />
	</p>
	<div class="clear"></div>

	<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
		<label for="account_display_name"><?php esc_html_e( 'Display name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
		<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_display_name" id="account_display_name" value="<?php echo esc_attr( $user->display_name ); ?>" /> 
		<span class="text-gray-500"><em><?php esc_html_e( 'This will be how your name will be displayed in the account section and in reviews', 'woocommerce' ); ?></em></span>
	</p>
	<div class="clear"></div>

	<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
		<label for="account_email"><?php esc_html_e( 'Email address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
		<input type="email" class="woocommerce-Input woocommerce-Input--email input-text" name="account_email" id="account_email" autocomplete="email" value="<?php echo esc_attr( $user->user_email ); ?>" />
	</p>

	<p class="bg-gray-200 p-3 font-semibold text-black mt-7"><?php esc_html_e( 'Change password', 'woocommerce' ); ?></p>

	<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
		<label for="password_current"><?php esc_html_e( 'Current password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
		<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_current" id="password_current" autocomplete="off" />
	</p>
	<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
		<label for="password_1"><?php esc_html_e( 'New password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
		<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_1" id="password_1" autocomplete="off" />
	</p>
	<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
		<label for="password_2"><?php esc_html_e( 'Confirm new password', 'woocommerce' ); ?></label>
		<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_2" id="password_2" autocomplete="off" />
	</p>
	
	<div class="clear"></div>

	<?php do_action( 'woocommerce_edit_account_form' ); ?>

	<p>
		<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
		<button class="woocommerce-Button bg-red-600 text-white rounded-full font-semibold hover:bg-black mt-3" name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>"><?php esc_html_e( 'Save changes', 'woocommerce' ); ?></button>
		<input type="hidden" name="action" value="save_account_details" />
	</p>

	<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
</form>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>
<div class="">
	<div class="company_logo_delete_popup_main_container" id="company_logo_delete_popup_container">
    <div class="company_logo_delete_popup_container">
    	<div class="delete_alert_title">
    		<svg xmlns="http://www.w3.org/2000/svg" class="trashIcon" getElementById="trashIcon" width="22" height="22" viewBox="0 0 24 24" ><path fill="#DC2626" d="M9 3v1H4v2h1v13a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V6h1V4h-5V3H9m0 5h2v9H9V8m4 0h2v9h-2V8Z"/></svg>
    		<p class="font-bold" >Delete <span class="deleteText"></span>?</p>
    	</div>

        <p style="text-align: center; font-size: 12px;">Are you sure you want to delete the <span class="deleteText"></span>? You cannot undo this action.</p>
    	<div class="delete_alert_btns">
    	 <span style="background: #DC2626; color:#fff; display:none;" onclick="confirmDeleteAlert()" id="confirmBtnLogo">Yes, Delete</span>
         <span style="background: #DC2626; color:#fff; display:none;" onclick="resetColorPicker()" id="confirmBtnCl">Yes, Delete</span>
    	 <span style="background: #d9d5d5; color:#000;" onclick="closeDeleteAlert()">No, Cancel</span>

    	  </div>

        
    </div>
</div>
</div>


<div class="colorPickerContainer">
    <div class="color-container">
        <div class="colorjoe"></div>
        <div class="config">
            <div class="selected-color"></div>
            <div style="width:160px;">
                <div class="config-title"><?php esc_html_e( 'Selected Color', 'woocommerce' ); ?></div>
                <input class="selected-color-text"></input>
            </div>

            
        </div>
                        	<div class="delete_alert_btns mb-5" >
    	 <span style="background: #DC2626; color:#fff;" onclick="setColor()"><?php esc_html_e( 'Set Color', 'woocommerce' ); ?></span>
    	 <span style="background: #d9d5d5; color:#000;" onclick="closeColorPopUp()">Cancel</span>

    	  </div>
    </div>
</div>
<style type="text/css">
    #account_company_logo_trash_input::selection {
    background-color: transparent;
}
#account_company_logo_trash_input {
    cursor: pointer;
    color: transparent;
    user-select: none;
}


</style>

<script type="text/javascript">
	
    
	       class ColorPicker {
            constructor(root) {
                this.root = root;
                this.colorjoe = colorjoe.rgb(this.root.querySelector(".colorjoe"));
                this.selectedColor = null;
                this.savedColors = this.getSavedColors();

                this.colorjoe.show();
                const initialColor = document.getElementById('account_company_theme').value || '#FFFFFF';

        
                this.setSelectedColor(initialColor);

                this.colorjoe.on("change", color => {
                    this.setSelectedColor(color.hex(), true);
                });

                this.root.querySelectorAll(".saved-color").forEach((el, i) => {
                    this.showSavedColor(el, this.savedColors[i]);

                    el.addEventListener("mouseup", e => {
                        if (e.button == 1) {
                            this.saveColor(this.selectedColor, i);
                            this.showSavedColor(el, this.selectedColor);
                        }

                        this.setSelectedColor(el.dataset.color);
                    });
                });
                var companyThemeElement = document.getElementById('account_company_theme');

                // Check if the color is not #ffffff
                if (initialColor === '#FFFFFF' || initialColor === '#ffffff') {
                    

                var parentElement = companyThemeElement.parentElement;

                // Find the .placeholder-edit icon within the parent
                var editIcon = parentElement.querySelector('.placeholder-edit-cl');
                var placeholder = parentElement.querySelector('#colorpalletIcon');
                var colorCode = parentElement.querySelector('#colorCode');
        // console.log('ss',placeholder)
                // Display the edit icon
                if (editIcon) {
                    editIcon.style.display = 'none';
                }
                if (colorCode) {
                    colorCode.style.display = 'none';
                }
                 if (placeholder) {
                    placeholder.style.display = 'block';
                }
                   
                } 
            }

            setSelectedColor(color, skipCjUpdate = false) {
                this.selectedColor = color;
                this.root.querySelector(".selected-color-text").value = color;
                this.root.querySelector(".selected-color").style.background = color;

                if (!skipCjUpdate) {
                    this.colorjoe.set(color);
                }
            }

            getSavedColors() {
                const saved = JSON.parse(localStorage.getItem("colorpicker-saved") || "[]");

                return new Array(5).fill("#ffffff").map((defaultColor, i) => {
                    return saved[i] || defaultColor;
                });
            }

            showSavedColor(element, color) {
                element.style.background = color;
                element.dataset.color = color;
            }

            saveColor(color, i) {
                this.savedColors[i] = color;
                localStorage.setItem("colorpicker-saved", JSON.stringify(this.savedColors));
            }
        }
        const cp = new ColorPicker(document.querySelector(".color-container"));
    
</script>


