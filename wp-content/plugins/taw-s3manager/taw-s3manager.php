<?php
/*
Plugin Name: Thingsatweb S3manager
Plugin URI: https://thingsatweb.com
Description: Customized prodcuts.
Version: 1.0
Author: thingsatweb
Author URI: https://thingsatweb.com
Text Domain: s3manager
Domain Path: /lang
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define('TAW_S3_MANAGER_BASE', plugin_dir_url(__FILE__));
define('TAW_S3_MANAGER_DIR', __DIR__);
define('TAW_S3_FILE_VERSION', "?v=1.0");
define('TAW_S3_META_KEY', "taw_cdn");
define('TAW_S3_CDN_YES', "y");
define('TAW_S3_CDN_NO', "n");
define('TAW_S3_CDN_NO_FILE', "nf");

include_once(__DIR__ . '/controller/S3Manager.php');
function init_taw_s3_manager() {
	$manager = new S3Manager();
	$manager->init();
}

init_taw_s3_manager();