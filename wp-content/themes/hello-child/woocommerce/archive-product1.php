<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
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

get_header('shop');

$lang = getSiteCurrentLang();

$cur_lang = apply_filters('wpml_current_language', null);
$currentLanguage = empty($cur_lang) ? 'en' : $cur_lang;

$c = get_queried_object();

$cate_id="";
$cate_title="";
if(!empty($c->term_id)){
    $cate_id=$c->slug;
    $cate_title=$c->name;
}
//echo "<script>console.log('check " . $cate_title . "')</script>";
global $wpdb;

$emp_fetch = $wpdb->prepare(
    "SELECT distinct(filt_enable) 
    FROM taw_filter_setting 
    WHERE cate_no = %s",
    $cate_title);
$emp_res = $wpdb->get_results($emp_fetch);
foreach ($emp_res as $item) {
    $fil_en = $item->filt_enable;
    if($fil_en === '1')
    {
        $ee = 0;
       // echo "<script>console.log('check emp_res " .json_encode($fil_en) . "')</script>";
        break;
    }
   
}

//echo "<script>console.log('check " .json_encode($emp_res) . "')</script>";

// Replace 'your_table_prefix' with your actual table prefix if it's different
$table_name = 'taw_filter_setting';

$query = $wpdb->prepare(
    "SELECT attribute, att_value, display
     FROM $table_name
     WHERE cate_no = %s",
    $cate_title
);

$results = $wpdb->get_results($query);
$ff=0;
// Loop through the results and do something with them
if (!empty($results)) {
    foreach ($results as $result) {
        $attribute = $result->attribute;

        $query1 = $wpdb->prepare(
            "SELECT filt_enable 
            FROM taw_filter_setting 
            WHERE cate_no = %s AND attribute = %s 
            LIMIT 1",
            $cate_title,
            $attribute
        );
        
        $ct = $wpdb->get_var($query1);
      if($ct>0){ 
        $display = $result->display;
        if($display==1){
            
            if (($attribute != 'pa_load-option')||($attribute != 'pa_load-way')) {
                               
                $att_value = urldecode($result->att_value);
                if (strpos($att_value, 'long-goods-i-pipes-i-bars') !== false) {
                    $parts = explode('-i-', $att_value); // Split by hyphen
                
                    foreach ($parts as $part) {
                        $part = trim(ucwords(str_replace("-", " ", $part))); // Convert, capitalize, and trim
                        $element[$attribute][] = $part;
                        $orgele[$attribute][] = toTitleCase($part);
                    }
                }

                    $element[$attribute][]=$att_value;
                    //$orgele[$attribute][]=$att_value;

                    $orgele[$attribute][] = toTitleCase($att_value);
                    //else
                    //$orgele[$attribute][] = ($att_value);
            }else
            {
            $element[$attribute][]=$att_value;
            //$orgele[$attribute][]=$att_value;

            $orgele[$attribute][] = toTitleCase($att_value);
        }
    }
   // $ee=0;
        // Do something with $attribute, $att_value, and $display
     //   echo "Attribute: $attribute, Att_Value: $att_value, Display: $display<br>";
}else
{
    if (empty($orgele[$attribute])) {
        $count=$count+1;
    } 
   
    
}
    }
} else {
    $ee=1;
  //  echo "<script>console.log('no records')</script>";
   // echo "No results found.";
}


function toTitleCase($str) {
   
        return ucwords(str_replace("-", " ", $str));
}
//echo "<script>console.log('1stcheck " .json_encode($element) . "')</script>";
//echo "<script>console.log('check " .json_encode($orgele) . "')</script>";

?>
<style>
/* header*/
#masthead {
  position: fixed !important;
  width: 100%;
  background: white;
  top: 0px;
  z-index: 200 !important; 
}
button#taw_filter_item_load_more {
    font-size: 14px;
    padding: 4px 20px;
    margin: 50px auto;
    border-color: #dc2626;
    color: #dc2626;
}
button#taw_filter_item_load_more:hover{
    background:#dc2626;
    color:#fff;
}
button#taw_filter_item_load_more:focus{
    background:transparent;
    border-color: #dc2626;
    color: #dc2626;
}
.mobile-bottom-nav{
    display:none;
}

</style>
<script>
    var currentLanguage = '<?php echo $currentLanguage; ?>';
</script>
<section>
        <div class="lg:mx-auto">
            <div class="grid grid-cols-3 relative">
                <!--LEFT SIDE MENU-->
                <?php 
                 if($ee===1){ ?>
                    <style>
                   .grid-cols-3 {
      display: grid;
      grid-template-columns: 100px 1fr 1fr; /* Fixed width for the first column, and two equal-width columns */
      margin: 0 auto; /* Center the grid container horizontally */
    }
                    </style></p>    <?php
                    } 
                if($ee===0){ ?>
                <div id="filter-container-large" class="scrollable-container w-full hidden lg:block col-span-1" style="position: sticky;">
                    <div id="blurimg"  class="flex items-center pb-5 px-7 pt-5 bg-gray-100">
                        <div class="h-7 w-[3px] bg-red-600"></div>
                        <h3 class="product-filter !mb-0 text-red-600 font-bold text-lg px-2 my-auto">
                           <?php if ($lang == "en") { ?> Product Filter <?php } elseif ($lang == "sv") { ?> Produktfilter <?php } ?>  
                        </h3>
                        <div class="filter_clear flex cursor-pointer ml-auto pl-[1px] xl:pl-[2px] border-transparent hover:border-red-600 border-[2px] border-t-0 border-l-0 border-r-0">
                            <?php
                                $close = THINGSATWEB_BASE . '/img/ic_close.png';
                            ?>
                            <h4 class="font-semibold text-black text-[12px] xl:text-sm my-auto"> 
                                 <?php if ($lang == "en") { ?> Clear Filter <?php } elseif ($lang == "sv") { ?> Rensa filter <?php } ?>  
                                
                            </h4>
                            <img class="w-auto !h-[8px] xl:!h-[10px] my-auto mx-1" src=<?php echo $close;?> alt="Arrow">
                        </div>
                    </div>
                    <div id="blurimage" class="h-full lg:overflow-auto px-7 pb-5 bg-gray-100">
                    <ul class="taw_attr_item_hld mb-16">                        
                        <?php
                            $forward_arrow = THINGSATWEB_BASE . '/img/ic_forward_arrow.svg';
                            //$data=get_option('taw_general_opt');

                            $lang=getSiteCurrentLang();
                            $prefix = 'taw_general_opt';
                            //$lang="sv";
                            if($lang!="en"){
                                $prefix.="_".$lang;
                            }

                            $data=get_option($prefix);
                            //echo "<script>console.log('values of attri " .json_encode($data) . "')</script>";
                            $attributes=$data['filter_attr'] ?? [];
                            $attrs=[];          
                         //   echo "<script>console.log('values of attri " .json_encode($attributes) . "')</script>";                
                            foreach ($attributes as $value): 
                             //   echo "<script>console.log('values of attri " .json_encode($value) . "')</script>";
                                $key=explode("::",$value['attr'])[0];
                                $title=$value['title'] ?? "none";
                                $filt='pa_'.explode("::",$value['attr'])[0];
                                $options=$value['opt'] ?? [];                           
                             // $attrs[$key."::".$title]=$options;
                           //  echo "<script>console.log('values of attri " .json_encode($options) . "')</script>";
                        if(!empty($element[$filt])){
                            
                        ?>

                        <li class="py-1 hld_<?php echo $key;?>">
                            <div class="flex items-center mt-3">
                                <img class="w-auto !h-[13px]" src=<?php echo $forward_arrow;?> alt="Arrow">
                                <span class="text-[15px] font-semibold text-black ml-1"><?php echo $title;?></span>
                            </div>
                            
                            <ul class="grid grid-cols-3 gap-2 filter-option-container" data-key="<?php echo $key;?>" id="taw_attr_<?php echo $key;?>">
                                <?php 
                                    foreach($options as $a):
                                        $a_ar=explode("::",$a['term']);
                                      //  echo "<script>console.log('values of a_ar " .json_encode($a_ar) . "')</script>";
                                        $term_id=$a_ar[1];
                                      //  echo "<script>console.log('values of term_id " .json_encode($term_id) . "')</script>";
                                        if($a_ar[0]=== 'shelves')
                                        $term_name=$a_ar[2];
                                        else 
                                        $term_name=$a['title'];  
                                       // echo "<script>console.log('values of term_name " .json_encode($term_name) . "')</script>";                       
                                        $term_name = trim($term_name);
                                    // echo "<script>console.log('values of attri " .json_encode($a) . "')</script>";
                                   //  echo "<script>console.log('values of attri sdfasd" .json_encode($orgele[$filt]) . "')</script>";
                                   // echo "<script>console.log('values of termname " .$term_name. "')</script>";
                                        if(($term_name=== 'Long Goods')||($term_name=== 'Bars')){
                                            $search_string = json_encode($orgele[$filt]);
                                            if (strpos( $search_string, $term_name) !== false) {
                                            
                                            }
                                        }
                                        if($term_name === 'FIN &amp; CHEP 1200 x 1000')
                                        $term_name='Fin Chep 1200 X 1000';
                                        $term_name_cleaned = str_replace('-', ' ', $term_name);
                                        if (strpos($term_name, '%') !== false) 
                                        $term_name_cleaned = str_replace('%', '', $term_name);
                                        
                                        $term_name_lower = urldecode(strtolower($term_name_cleaned));
                                        

// Iterate through $orgele[$filt] and convert each element to lowercase for comparison
$orgele_lower = array_map('strtolower', $orgele[$filt]);
if ($term_name==='Tvåvägs 2x70%') {
    $orgele_lower=$orgele[$filt];
    $term_name_lower='Tvåvägs 2x70%';    
} 
if (in_array($term_name_lower, $orgele_lower)) {
                                      // if (in_array($term_name, $orgele[$filt])) {  
                                        $display_type=$a['display_type'] ?? "default";
                                        $key_ar=explode("::",$key);
                                        if($display_type=="img_txt"): 
                                            $img_url=empty($a['img'])?"":$a['img']['url'];       
                                                   
                                ?>

                                            <li class="mt-3"> 
                                                <div data-id="<?php echo $term_id;?>" data-title="<?php echo $term_name;?>" class="taw_attr_option flex flex-col items-center h-[73px] xl:h-[89px] bg-white rounded-md border border-gray-700 hover:border-2 hover:border-red-600">
                                                    <?php    $img_url = str_replace("http://", "https://", $img_url);
                                                        ?>  
                                                    <img class="mx-auto my-2 xl:my-3 !h-8 xl:!h-10 w-auto" src="<?php echo $img_url;?>" alt="<?php echo $term_name;?>">
                                                    <span class="taw_attr_title w-full text-[10px] text-white font-semibold text-center p-1 bg-gray-700 truncate hover:text-clip"><?php echo $term_name;?></span>
                                                </div>
                                            </li>
                                        <?php elseif($display_type=="color_txt"):
                                            $color=$a['color'] ?? "color";
                                        ?>
                                            <li class="mt-3">
                                                <div data-id="<?php echo $term_id;?>" data-title="<?php echo $term_name;?>" style="background:<?php echo $color;?>"
                                                        class="taw_attr_option flex flex-col items-center h-[73px] xl:h-[89px] rounded-md border border-gray-700 hover:border-2 hover:border-red-600">
                                                    <span class="taw_attr_title w-full text-[10px] text-white font-semibold text-center p-1 bg-gray-700 mt-auto truncate hover:text-clip"><?php echo $term_name;?></span>
                                                </div>
                                            </li>
                                        <?php else:?>
                                            <li class="mt-3">
                                                <div data-id="<?php echo $term_id;?>" data-title="<?php echo $term_name;?>" class="taw_attr_option relative txt_only bg-white border border-gray-700 rounded-md flex items-center justify-center h-[34px] px-2 hover:border-2 hover:border-red-600">
                                                    <span class="text-black font-semibold text-center text-[10px] truncate hover:text-clip"><?php echo $term_name;?></span>
                                                </div>
                                            </li>
                                        <?php endif;?>
                                        <?php }?>
                                    <?php endforeach;?>
                            </ul>
                        </li>
                        <?php } endforeach;?>
                    </ul>
                    </div>
<div id="taw-prod-loader" class="w-full h-full grid place-content-center loader-container" style="z-index: 100;display:none;position:relative;margin-top:-150px;margin-left: 2px;">
                     <span class="aloader"></span>
                </div>
                </div> 
               
                
                <?php  } ?>
                <!--RIGHT SIDE LIST-->
                
                <div class="list-item-container w-full col-span-3 lg:col-span-2">
                    <div class="lg:mt-2 lg:mb-5 relative">
                        <?php if(!empty($cate_title)):?>
                         <h3 class="text-red-600 font-semibold text-lg p-2 rounded-lg text-center mt-4 !mx-5 md:!mx-9 xl:!mx-12"> 
                            <?php echo $cate_title;?>
                        </h3>
                        <?php endif;?>

                        <div id="filter-list-option" class="flex flex-wrap lg:flex-nowrap py-6 lg:py-3 mr-auto justify-between !mx-5 md:!mx-9 xl:!mx-12 bg-white lg:top-[103px] xl:top-[137px]" style="z-index: 100;">
                            <div class="ml-10 hidden md:flex">
                                <span id="taw-pro-img-grid" class="active w-4"></span>
                                <span id="taw-pro-img-list" class="mx-4 w-4"></span>
                                <div id="taw_input_search_holder" class="flex border-[#0F75E0] border-[2px] rounded-[20px] mr-3 pr-2">
                                    <input class="my-auto text-sm text-[#bdbdbd]" id="taw_input_search" placeholder="search" type="text" />
                                    <span id="taw_input_search_clear" class="w-5 mx-1 my-auto"></span>
                                </div>
                                <a class="flex items-center" id="taw_input_search_btn">
                                    <i class="ic_search w-4"></i>
                                </a>
                            </div>
                            <div class="mx-auto my-auto lg:mx-0">
                                <span class="text-sm font-semibold text-center" id="taw_total_prod_count"></span>
                            </div>
                            <div>
                                <select class="mx-auto md:mx-0 flex border border-gray-600 p-2 md:mr-5 text-sm font-semibold w-44" id="taw_filter_sortby">
                                    <option value="" data-orderby=""><?php echo __('Standardsortering', 'default');?></option>
                                    <option value="asc" data-orderby="title"><?php echo __('Name A-Ö', 'default');?></option>
                                    <option value="desc" data-orderby="title"><?php echo __('Name Ö-A', 'default');?></option>
                                </select>
                                <?php /*
                                <img class="w-4" src="<?php echo THINGSATWEB_BASE.'/img/drop-down.svg';?>" alt="Drop Down">*/?>
                            </div>
                        </div>
                        
                        <!--LIST ITEMS-->
                        <div class="mb-6 mt-4 lg:mb-0 px-5 md:px-9 xl:px-12" id="taw-prod-items-hld" style="position: relative;">
                            <div id="taw-prod-items" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 lg:gap-5 xl:gap-8"></div>
 <button id='taw_filter_item_load_more' style="display:none;"><?php echo __('Load More');?></button>
 <!------- bottom- content ------->
                            <div id="taw-prod-loader" class="w-full h-full grid place-content-center " style="z-index: 100;display:none;position:relative;margin-top:-200px;">
                                <span class="aloader"></span>
                            </div>
                        </div>
                    </div>
    
                    <!-- BOTTOM MENU SMALL SCREEN-->
                    <div class="fixed bottom-0 w-full lg:hidden" style="z-index:100;">
                        <!-- BOTTOM MEMU CONTENT-->
                        <div class="bottom-menu-content bg-gray-100 p-3" style="box-shadow: 0 -2px 3px -2px #333;">
                            <div class="flex items-center mb-5">
                                <div class="h-6 w-[3px] bg-red-600"></div>
                                <h3 class="product-filter !mb-0 text-red-600 font-bold text-[18px] px-[6px] my-auto">
                                <?php if ($lang == "en") { ?> Product Filter <?php } elseif ($lang == "sv") { ?> Produktfilter <?php } ?>  
                                </h3>
                                <div class="filter_clear flex cursor-pointer ml-auto pl-[1px] xl:pl-[2px] border-transparent hover:border-red-600 border-[2px] border-t-0 border-l-0 border-r-0">
                                    <?php
                                        $close = THINGSATWEB_BASE . '/img/ic_close.png';
                                    ?>
                                    <h4 class="font-semibold text-black text-[11px] sm:text-[12px] md:text-sm my-auto">
                                    <?php if ($lang == "en") { ?> Clear Filter <?php } elseif ($lang == "sv") { ?> Rensa filter <?php } ?>  
                                    </h4>
                                    <img class="w-auto !h-[8px] sm:!h-[9px] md:!h-[10px] my-auto mx-1" src=<?php echo $close;?> alt="Arrow">
                                </div>
                            </div>
                            <div class="mx-[2px] relative">
                                <div class="taw_attr_item_hld slider-bottom-menu"> 
                                    <?php
                                        $previous = THINGSATWEB_BASE . '/img/ic_arrow_previous.svg';
                                        $next = THINGSATWEB_BASE . '/img/ic_arrow_next.svg';
                                        //$data=get_option('taw_general_opt');
                                        //$attributes=$data['filter_attr'] ?? [];
                                        $attrs=[];                          
                                        foreach ($attributes as $value): 
                                            $key=explode("::",$value['attr'])[0];
                                            $title=$value['title'] ?? "none";
                                            $options=$value['opt'] ?? [];                           
                                        // $attrs[$key."::".$title]=$options;
                                    ?>
                                    <div class="py-1 hld_<?php echo $key;?>">
                                        <div class="flex items-center">
                                            <img class="filter-title-arrow w-auto !h-[11px]" src=<?php echo $forward_arrow;?> alt="Arrow">
                                            <span class="text-[13px] sm:text-[14px] md:text-[15px] truncate hover:text-clip font-semibold text-black ml-1"><?php echo $title;?></span>
                                        </div>
                                        <div class="slider-menu-content mx-5 gap-1 bg-gray-100 filter-option-container" data-key="<?php echo $key;?>" id="mobile_taw_attr_<?php echo $key;?>">
                                            <?php 
                                                $text_only = array();
                                                foreach($options as $a):
                                                $a_ar=explode("::",$a['term']);
                                                $term_id=$a_ar[1];
                                                $term_name=$a['title'];
                                                $display_type=$a['display_type'] ?? "default";
                                                $key_ar=explode("::",$key);
                                                if($display_type=="img_txt"): 
                                                    $img_url=empty($a['img'])?"":$a['img']['url']; ?>
                                                    <div class="mt-3 ml-0.5 mr-0.5 md:ml-1.5 md:mr-1.5"> 
                                                        <div data-id="<?php echo $term_id;?>" data-title="<?php echo $term_name;?>" class="taw_attr_option flex flex-col items-center h-[71px] sm:h-[85px] md:h-[102px] bg-white rounded-md border border-gray-700 hover:border-2 hover:border-red-600">
                                                        <?php    $img_url = str_replace("http://", "https://", $img_url);
                                                        ?>  
                                                            <img class="filter-item-image mx-auto my-3 md:my-4 !h-6 sm:!h-9 md:!h-11 w-auto" src="<?php echo $img_url;?>" alt="<?php echo $term_name;?>">
                                                            <span class="taw_attr_title w-full text-[9px] sm:text-[10px] md:text-[11px] text-white font-semibold text-center p-1 bg-gray-700 truncate hover:text-clip"><?php echo $term_name;?></span>
                                                        </div>
                                                    </div>
                                                <?php elseif($display_type=="color_txt"):
                                                    $color=$a['color'] ?? "color"; ?>
                                                    <div class="mt-3 ml-0.5 mr-0.5 md:ml-1.5 md:mr-1.5">
                                                        <div data-id="<?php echo $term_id;?>" data-title="<?php echo $term_name;?>" style="background:<?php echo $color;?>"
                                                            class="taw_attr_option flex flex-col items-center h-[71px] sm:h-[85px] md:h-[102px] rounded-md border border-gray-700 hover:border-2 hover:border-red-600">
                                                            <span class="taw_attr_title w-full text-[9px] sm:text-[10px] md:text-[11px] text-white font-semibold text-center p-1 bg-gray-700 mt-auto truncate hover:text-clip"><?php echo $term_name;?></span>
                                                        </div>
                                                    </div>
                                                <?php else:
                                                    $text_only[] = $a;
                                                ?>
                                                <?php endif;?>
                                            <?php endforeach;?>
                                            <?php if(count($text_only) > 0) :?>
                                                <?php if(count($text_only) > 3) :?>
                                                    <?php
                                                        $items = array_chunk($text_only,2);
                                                        foreach($items as $item): ?>
                                                            <div class="mt-3 ml-0.5 mr-0.5 md:ml-1.5 md:mr-1.5">
                                                                <?php for ($i=0; $i < count($item) ; $i++) :
                                                                    $a = $item[$i];
                                                                    $a_ar=explode("::",$a['term']);
                                                                    $term_id=$a_ar[1];
                                                                    $term_name=$a['title'];?>
                                                                    <div data-id="<?php echo $term_id;?>" data-title="<?php echo $term_name;?>" class="taw_attr_option relative txt_only <?php if($i > 0): ?> mt-1 md:mt-2 <?php endif; ?> bg-white border border-gray-700 rounded-[4px] flex items-center justify-center h-[30px] px-2 py-5 my-auto hover:border-2 hover:border-red-600">
                                                                        <span class="text-black font-semibold text-center text-[10px] sm:text-[11px] md:text-[12px]"><?php echo $term_name;?></span>
                                                                    </div>
                                                                <?php endfor; ?>
                                                            </div>
                                                        <?php endforeach; ?>
                                                <?php else :?>
                                                    <?php foreach($text_only as $a):
                                                        $a_ar=explode("::",$a['term']);
                                                        $term_id=$a_ar[1];
                                                        $term_name=$a['title'];?>
                                                        <div class="mt-3 ml-0.5 mr-0.5 md:ml-1.5 md:mr-1.5">
                                                            <div data-id="<?php echo $term_id;?>" data-title="<?php echo $term_name;?>" class="taw_attr_option relative txt_only bg-white border border-gray-700 rounded-[4px] flex items-center justify-center h-[30px] px-2 py-5 my-auto hover:border-2 hover:border-red-600">
                                                                <span class="text-black font-semibold text-center text-[10px] sm:text-[11px] md:text-[12px]"><?php echo $term_name;?></span>
                                                            </div>
                                                        </div>
                                                    <?php endforeach;?>
                                                <?php endif;?>
                                            <?php endif;?>
                                        </div>
                                    </div>
                                    <?php endforeach;?>
                                </div>
                                <div class="my-3 flex justify-center">
                                    <div id="menu-button-previous" class="mr-2 cursor-pointer">
                                        <img class="!w-auto !h-[24px] md:!h-[26px]" src=<?php echo $previous;?> alt="Previous">
                                    </div>
                                    <div id="menu-button-next" class="ml-2 cursor-pointer">
                                        <img class="!w-auto !h-[24px] md:!h-[26px]" src=<?php echo $next;?> alt="Previous">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- BOTTOM MENU -->
                        <div class="w-full grid grid-cols-5 bg-white border" style="box-shadow: 0 -2px 3px -2px #333;">
                            <?php
                                $map = THINGSATWEB_BASE . '/img/ic_map.svg';
                                $mail = THINGSATWEB_BASE . '/img/ic_mail.svg';
                                $phone = THINGSATWEB_BASE . '/img/ic_phone.svg';
                                $store = THINGSATWEB_BASE . '/img/ic_store.svg';
                                $filter = THINGSATWEB_BASE . '/img/ic_filter.svg';
                            ?>                
                            <a href="https://g.page/SmartStoring?share" target="_blank" class="text-black">
                                <div class="py-3 md:py-4">
                                    <div class="flex items-center justify-center">
                                        <img class="w-auto !h-4 md:!h-5" src=<?php echo $map;?> alt="">
                                    </div>
                                    <span class="flex items-center justify-center text-xs md:text-sm font-semibold mt-1">Location</span>
                                </div>
                            </a>
                            <a href="mailto:info@smartstoring.se" class="text-black">
                                <div class="py-3 md:py-4">
                                    <div class="flex items-center justify-center">
                                        <img class="w-auto !h-4 md:!h-5" src=<?php echo $mail;?> alt="">
                                    </div>
                                    <span class="flex items-center justify-center text-xs md:text-sm font-semibold mt-1">Email</span>
                                </div>
                            </a>
                            <a href="tel:+46 304 80 90 80" class="text-black">
                                <div class="py-3 md:py-4">
                                    <div class="flex items-center justify-center">
                                        <img class="w-auto !h-4 md:!h-5" src=<?php echo $phone;?> alt="">
                                    </div>
                                    <span class="flex items-center justify-center text-xs md:text-sm font-semibold mt-1">Phone</span>
                                </div>
                            </a>
                            <a href="/shop" target="_blank" class="text-black">
                                <div class="py-3 md:py-4">
                                    <div class="flex items-center justify-center">
                                        <img class="w-auto !h-4 md:!h-5" src=<?php echo $store;?> alt="">
                                    </div>
                                    <span class="flex items-center justify-center text-xs md:text-sm font-semibold mt-1">Webshop</span>
                                </div>
                            </a>
                            <div class="py-3 md:py-4 cursor-pointer" onclick="showHideFilter(this)">
                                <div class="flex items-center justify-center">
                                    <img class="w-auto !h-4 md:!h-5" src=<?php echo $filter;?> alt="">
                                </div>
                                <span class="flex items-center justify-center text-xs md:text-sm font-semibold mt-1">Filter</span>
                            </div>
                            <script>
                                function showHideFilter(context) {
                                    jQuery('.bottom-menu-content').toggle();
                                }
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

<div id="taw_filter_modal" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <!-- <span class="close">&times;</span>
        <p>Some text in the Modal..</p> -->
    </div>
</div>

<div style="display:none" class="lg:grid-cols-3"></div>

<script>
    var aurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    var category = '<?php echo $cate_id;?>';
    var layoutType = '<?php echo isset($_GET['layout']) ? $_GET['layout'] : "grid"; ?>';
    var existingFilter = '<?php echo isset($_GET['filter']) ? $_GET['filter'] : ""; ?>';

    jQuery( document ).ready( function( e ) {
        jQuery(".slider-bottom-menu").slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            prevArrow: jQuery("#menu-button-previous"),
            nextArrow: jQuery("#menu-button-next"),
            responsive: [
                {
                    breakpoint: 1536,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 1280,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 720,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });

        jQuery(".slider-menu-content").slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 720,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 320,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1
                    }
                }
            ]
        });
    });
</script>
<?php

get_footer( 'shop' );