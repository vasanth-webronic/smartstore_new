<div class="form-wrap" id="_wpii_prod_img_pointer_info_hld">

<?php
global $post;

$field='_wpii_prod_img_pointer_info';


// See if there's a media id already saved as post meta
$item = get_post_meta( $post->ID, $field);
if(!empty($item)){
    $item=json_decode($item[0],true);
}

$list=[];
if(!empty($item['data'])){
    $list=$item['data'];
}

$your_img_src="";
$your_img_src_id="";
if(!empty($item['imgId'])){
    $your_img_src_id=$item['imgId'];
    $your_img_src = wp_get_attachment_image_src($your_img_src_id, 'full');
    if(!empty($your_img_src)){
        $your_img_src=$your_img_src[0];
    }   
}
wp_nonce_field( 'my-custom-fields', 'my-custom-fields_wpnonce', false, true );

?>

<!-- Your image container, which can be manipulated with js -->
<div class="custom-img-container" 
style="text-align: center; margin-top:50px;" 
data-item-img="<?php echo $your_img_src_id;?>" 
data-item-img-lst="<?php echo esc_html(json_encode($list));?>" >
    
	<div id="_wpii_prod_img_drop_hld" style="<?php echo (empty($your_img_src)?'':'background-image:url('.$your_img_src.')'); ?>" >
       
        <!-- Your add & remove image links -->
        <div class="hide-if-no-js">     
            <button id="_wpii_prod_img_add_bg" class="button button-primary button-large" style="margin: 280px auto;display: <?php echo (empty($your_img_src)?'block':'none');?>" > <span style="line-height: 30px;" class="dashicons dashicons-plus"></span> <?php _e('Select image','woo-proudct-img-info') ?></button>          
          
            <span id="_wpii_prod_img_remove_bg" style="font-size: 30px;color: red;cursor: pointer;display:<?php echo ( empty($your_img_src)?"none":"block"); ?>" class="dashicons dashicons-no-alt" title="Remove"></span>
        </div>

        
        <?php   $maxKey = 0;  if (!empty($list)) {
            $keyCounters = array();
            foreach ($list as $key => $value) {
                $txt = $value['txt'];
                $qty = $value['qty'];
                // If the "txt" is not already a key in $keyCounters, create an entry for it
                if (!isset($keyCounters[$txt])) {
                    $keyCounters[$txt] = count($keyCounters) + 1;
                    $maxKey = max($maxKey, $keyCounters[$txt]);
                }
              
                // Display the item with the appropriate key
                echo '<div id="_wpii_prod_img_drop' . $keyCounters[$txt] . '" class="after_dragged_point badgee" style="cursor:pointer;padding-bottom:1px; height:20px;width:20px;position:absolute;';
                echo 'top:' . $value['top'] . 'px;left:' . $value['left'] . 'px" data-val="' . $value['txt'] . '" data-qty="' . $value['qty'] . '" data-key="' . $keyCounters[$txt] . '">' . $keyCounters[$txt] . '</div>';
            }
        } 
        echo '<script>var maxKey = ' . $maxKey . ';</script>';
        ?>

   		<div id="_wpii_prod_img_pointer_info">
            <span id="_wpii_prod_img_pointer_info_close">X</span> 
            <input style="display: block;width: 100%;margin: 15px 0 20px 0;" id="_wpii_prod_img_pointer_input" type="text" placeholder="<?php echo __('Enter article no','woo-proudct-img-info')?>" />
            <input style="display: block;width: 100%;margin: 20px 0;" id="_wpii_prod_img_pointer_min_qty" type="number" placeholder="<?php echo __('Minimum Qty Required','woo-proudct-img-info')?>" />
<div id="prod_title" style="margin-bottom: 10px; height:60px; background-color: rgba(0, 0, 0, 0.459); border-radius: 5px; padding: 10px 4px;"></div>
           
            <span id="_wpii_prod_img_pointer_info_save">Save</span>
<span id="_wpii_prod_img_pointer_info_copy">Copy</span>

           
            <div id="_wpii_prod_img_pointer_info_arrow"></div>
        </div>

        <div class="_wpii_prod_img_box" id="_wpii_prod_img_drag_hld">

        <div class="badgee drag-point">i</div>
            <p><?php echo  __('Drag pointer to add','woo-proudct-img-info');?>
        </div>
        <div class="_wpii_prod_img_box" id="_wpii_prod_img_delete_hld">
            <i class="dashicons dashicons-trash" style="display: block;color: red;margin: 0px auto;"></i>
            <p><?php echo  __('Drop point here to remove','woo-proudct-img-info');?>
        </div>

	</div>

   
   
</div>


<input type="hidden" id="_wpii_prod_img_pointer_info_tosave" name="<?php echo $field;?>" value="<?php echo esc_html(json_encode($item));?>" />
</div>
<style type="text/css">
    ._wpii_prod_img_box{
        border: solid;
        /* padding: 2px; */
        width: 48px;
        text-align: center;       
    }

    #_wpii_prod_img_delete_hld{        
        position: absolute;
        right: -52px;
        top: 120px;
        height: 100px;       
    }

    #_wpii_prod_img_pointer_info{
        position: absolute;
        width: 240px;
        display: none;
        z-index: 1;
       /* height: 140px; */
        color: rgb(255, 255, 255);
        top: -40px;
        left: 312.109px;
        background: rgb(204, 6, 30);
        box-shadow: rgb(90, 90, 90) 3px 3px 15px;
        padding: 5px;
        vertical-align: middle;
        border: 2px solid;
        text-align: center;
    }

    #_wpii_prod_img_pointer_info textarea{
        width: 90%;
        height: 80px;
        border-radius: 0px;
        margin-top: 15px;
        margin-bottom: 15px;
        border: none;
        /* background: #cc061e; */
        color: black;
        text-align: left;
        padding: 8px;
        border-radius: 4px;
    }

    #_wpii_prod_img_pointer_info_close{
        position: absolute;
        top: -10px;
        right: -10px;
        text-align: center;
        width: 18px;
        display: inline;
        font-size: 13px;
        cursor: pointer;
        padding: 1px;
        color: rgb(204, 6, 30);
        border-radius: 50px;
        border: 1px solid;
        border-color: rgb(204, 6, 30);
        background: white;
    }
    
    #_wpii_prod_img_pointer_info_arrow{
        width: 0;
        height: 0;
        border-left: 44px solid transparent;
        border-right: 0 solid transparent;
        border-top: 60px solid #cc061e;
        position: absolute;
        bottom: -20px;
        left: 17px;
        z-index: -1;
    }
    #_wpii_prod_img_pointer_info_save{
     
        background: white;
        padding: 4px 12px;
        cursor: pointer;
        border-radius: 15px;
        color: rgb(204, 6, 30);
    }
#_wpii_prod_img_pointer_info_copy{
     
     background: white;
     padding: 4px 12px;
     cursor: pointer;
     border-radius: 15px;
     color: rgb(204, 6, 30);
 }
    #_wpii_prod_img_pointer_info_remove{
        float: left;
       background: #f35f5f;
       padding: 3px 10px;
       cursor: pointer;
    }

    #_wpii_prod_img_drag_hld{
        position: absolute;        
        display: none;
        top: 10px;
        right: -52px;
    }

    #_wpii_prod_img_drag_hld .drag-point{       
        cursor: pointer;
        position: unset;
        display: inline-block;
    }

	#_wpii_prod_img_drop_hld{
		position: relative;
        border: dotted transparent;
        /* display:inline-block; */

        display: inline-block;
        margin: 0px auto;
/* margin-left: -80px; */
        border: solid;
        height: 600px;
        width: 840px;
        /* vertical-align: middle; */
       /* background-image: url(http://smartstoring.test/wp-content/uploads/2020/06/11-6-blue.png);*/
        background-position: center;
        background-size: 90% auto;
        background-repeat: no-repeat;

	}


	#_wpii_prod_img_drop_hld.ui-state-active,#_wpii_prod_img_drop_hld.ui-state-hover{
		border: dotted green;
	}
    .after_dragged_point{
        position: absolute;
        width: 40px;
        height: 40px;

        cursor: pointer;
    }

    #_wpii_prod_img_delete_hld.ui-state-highlight{
        background: red;
    }

    #_wpii_prod_img_delete_hld.ui-state-highlight i{
        color: white !important;
    }

    @-webkit-keyframes pulse-has-unresolved-data-v-cb79cb1c{
        0%{
            -webkit-box-shadow:0 0 0 0 rgba(244,214,92,.7);
            box-shadow:0 0 0 0 rgba(244,214,92,.7)
        }
        70%{-webkit-box-shadow:0 0 0 8px transparent;box-shadow:0 0 0 8px transparent}to{-webkit-box-shadow:0 0 0 0 transparent;box-shadow:0 0 0 0 transparent}
    }

    @keyframes pulse-has-unresolved-data-v-cb79cb1c{0%{
        -webkit-box-shadow:0 0 0 0 rgba(244,214,92,.7);box-shadow:0 0 0 0 rgba(244,214,92,.7)}
        70%{-webkit-box-shadow:0 0 0 8px transparent;box-shadow:0 0 0 8px transparent}to{-webkit-box-shadow:0 0 0 0 transparent;box-shadow:0 0 0 0 transparent}
    }

     .badgee{
        position:absolute;       
        width:22px;
        height:22px;
        line-height:22px;
        border-radius:50%;
        border:1px solid #314152;
        background-color:#f2df3e;
        color:#293745;
        text-align:center;
        cursor:pointer;
        -webkit-animation:pulse-has-unresolved-data-v-cb79cb1c 2s infinite;
        animation:pulse-has-unresolved-data-v-cb79cb1c 2s infinite;
     }
</style>
<script type="text/javascript">
    jQuery(document).ready(function($) {
    // Get the value from _wpii_prod_img_pointer_input
    var artValue = $('#_wpii_prod_img_pointer_input').val();
    // Get the current top value of #_wpii_prod_img_pointer_info



    $("#_wpii_prod_img_pointer_input").on("input", function() {
        artValue = $(this).val();
        if(artValue){
             // Make an AJAX request to retrieve the title
    $.ajax({
        url: ajaxurl, // WordPress AJAX handler
        type: 'POST',
        data: {
            'action': 'get_article_title',
            'art_no': artValue,
        },
        success: function(response) {

            if(artValue.trim()){
            if(response){
                  $('#prod_title').show();

              
                $('#prod_title').html(response);
            }else{
                $('#prod_title').hide();
            }}else{
                $('#prod_title').hide();
            }
           
          
        }
    });
        }
});

   
});

</script>