<?php
$id=get_query_var('id');
if ($id > 0 && !empty($name)) {
     $print=$_GET['print'] ?? '';
     if($print='generate_datasheet'){   
          print_order($id);
          exit;
     }
     else{
          $path = get_attached_file($id);
          $path_array = explode(".", $path);
          $extension = end($path_array);
          do_action( 'woocommerce_download_file_force', $path, $name.'.'.$extension);
     }
     }
     
?>