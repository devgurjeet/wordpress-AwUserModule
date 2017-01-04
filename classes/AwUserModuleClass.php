<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class AwUserModuleClass{

	/* Remove login page logo. */
	public static  function is_login_page() {
    	if( in_array($GLOBALS['pagenow'], array('wp-login.php')) ){
    		$head = '';
    		$head .= '<style>';
    		$head .= '#login h1{display:none;}';
    		$head .= '</style>';
    		echo $head;
    	}
	}

	public static  function new_contact_methods( $contactmethods ) {
	    $contactmethods['parent'] = 'Parent';
	    return $contactmethods;
	}

	public static  function new_modify_user_table( $column ) {

		$temp['cb']       = '<input type = "checkbox" />';
		$temp['username'] = 'Username';
		$temp['name']     = 'Name';
		$temp['email']    = 'Email';
		$temp['parent']   = 'Parent';
		$temp['role']     = 'Role';

	    return $temp;
	}

	public static function new_modify_user_table_row( $val, $column_name, $user_id ) {
	    switch ($column_name) {
	    	case 'parent' :
	            return  self::getParentUser( $user_id  );
	            break;
	        default:
	    }
	    return $val;
	}

	public static function getParentUser( $user_id  ){
		$userParentID = get_user_meta( $user_id, 'parent',true );
		if( is_numeric($userParentID) ){
			$user_info    = get_userdata($userParentID);
			return "<strong>".$user_info->user_login ." ( " . implode(', ', $user_info->roles) ." )</strong>";
		}else{
			return '';
		}
	}

	/*add custom fields in add new user */
	public static  function display_parent_field() {
		$blogusers = get_users( array( 'fields' => array( 'ID', 'display_name' ) ) );
		// Array of stdClass objects.
		$option = '<option value="" selected>Select Parent</option>';
		foreach ( $blogusers as $user ) {
			$option .= '<option value="'.esc_html( $user->ID ).'">' . esc_html( $user->display_name  ) . '</span>';
		}
	?>
		<table class="form-table">
	        <tr>
	            <th><label for="parent">Parent</label></th>
	            <td>
	            	<select class="regular-text" name="parent" id="parent">
	            		<?php echo $option; ?>
	            	</select>
	            </td>
	        </tr>
	    </table>
	<?php
	}

	/* save metaFiled */
	public static function aw_user_register( $user_id ) {
        if ( ! empty( $_POST['parent'] ) ) {
            update_user_meta( $user_id, 'parent', trim( $_POST['parent'] ) );
        }
    }

    public static function add_roles_on_plugin_activation() {
    	remove_role( 'subscriber' );
    	remove_role( 'contributor' );
    	remove_role( 'author' );
    	remove_role( 'editor' );

    	remove_role( 'admin' );
    	remove_role( 'user' );
    	remove_role( 'users' );
    	remove_role( 'ext_user' );

       	add_role( 'admin', 'Admin', array( 'read' => true, 'level_0' => true ) );
       	add_role( 'user', 'User', array( 'read' => true, 'level_0' => true ) );
       	add_role( 'ext_user', 'External User', array( 'read' => true, 'level_0' => true ) );
   	}
}