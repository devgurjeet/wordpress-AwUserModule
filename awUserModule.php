<?php
/*
	Plugin Name: Aw User Module.
	Plugin URI:
	Description: Plguin to Manage Custom User Roles.
	Version: 1.0.0
	Author: G0947
	Author URI:
	License:
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

include 'classes/AwUserModuleClass.php';
class AwUserModule{

	function __construct(){
		add_filter( 'user_contactmethods', array('AwUserModuleClass','new_contact_methods'), 10, 1 );
		add_filter( 'manage_users_columns', array('AwUserModuleClass','new_modify_user_table') );
		add_filter( 'manage_users_custom_column', array('AwUserModuleClass','new_modify_user_table_row'), 10, 3 );

		/*add custom field */
		add_action('user_new_form', array('AwUserModuleClass','display_parent_field'));

		/*save custom field. */
		add_action( 'user_register', array('AwUserModuleClass','aw_user_register') );

		/*register post types */
		register_activation_hook( __FILE__, array('AwUserModuleClass', 'add_roles_on_plugin_activation'));

		/*Check if current page is login page */
		add_action( 'login_init', array('AwUserModuleClass', 'is_login_page') );
	}
}
new AwUserModule;
