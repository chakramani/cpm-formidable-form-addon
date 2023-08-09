<?php
/** 

* @package Akismet 

*/
/* 

Plugin Name: Filestack addon for Formidable Forms

Plugin URI: http://codepixelzmedia.com.np// 

Description: Use to add cutom fields to permitable addon and upload files to filestack api. 

Version: 1.0.0
Text Domain: formidable-addon
Author: Codepixelzmedia
*/


/* Main Plugin File */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PLUGIN_ROOT_DIR', plugin_dir_path( __FILE__ ) );
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );


if ( is_plugin_active( 'formidable/formidable.php' ) ) {
	$init_file = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . "cpm-formidable-form-addon" . DIRECTORY_SEPARATOR  ."cpm_formidable_form_loader.php";
	require_once $init_file;

} else {
	if ( ! function_exists( 'formidable_add_on_notification' ) ) {
		function formidable_add_on_notification() {
			?>
			<div id="message" class="error">
				<p><?php _e( 'Please install and activate Formidable plugin to use Formidable Addon .', 'formidable-addon' ); ?></p>
			</div>
			<?php
		}
	}
	add_action( 'admin_notices', 'formidable_add_on_notification' );
}

