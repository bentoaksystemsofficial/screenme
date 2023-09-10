<?php
if (isset($_POST['import_customers'])) {

    $customers_data = file_get_contents(NTW_PLUGIN_DIR.'/data/customers.json');
    $customers_data= json_decode($customers_data,true);

    if (!empty($customers_data) && is_array($customers_data['Customers']['Customer'])){

        foreach ($customers_data['Customers']['Customer'] as $customer){
            $userdata = array(
              //  'ID' 					=> $customer['CustomerId'], 	//(int) User ID. If supplied, the user will be updated.
                'user_pass'				=> '', 	//(string) The plain-text user password.
                'user_login' 			=> $customer['Email'], 	//(string) The user's login username.
                'user_nicename' 		=> '', 	//(string) The URL-friendly user name.
                'user_url' 				=> '', 	//(string) The user URL.
                'user_email' 			=> $customer['Email'], 	//(string) The user email address.
                'display_name' 			=> '', 	//(string) The user's display name. Default is the user's username.
                'nickname' 				=> '', 	//(string) The user's nickname. Default is the user's username.
                'first_name' 			=> '', 	//(string) The user's first name. For new users, will be used to build the first part of the user's display name if $display_name is not specified.
                'last_name' 			=> '', 	//(string) The user's last name. For new users, will be used to build the second part of the user's display name if $display_name is not specified.
              //  'description' 			=> '', 	//(string) The user's biographical description.
             //   'rich_editing' 			=> '', 	//(string|bool) Whether to enable the rich-editor for the user. False if not empty.
             //   'syntax_highlighting' 	=> '', 	//(string|bool) Whether to enable the rich code editor for the user. False if not empty.
             //   'comment_shortcuts' 	=> '', 	//(string|bool) Whether to enable comment moderation keyboard shortcuts for the user. Default false.
            ///    'admin_color' 			=> '', 	//(string) Admin color scheme for the user. Default 'fresh'.
              //  'use_ssl' 				=> '', 	//(bool) Whether the user should always access the admin over https. Default false.
             //   'user_registered' 		=> '', 	//(string) Date the user registered. Format is 'Y-m-d H:i:s'.
                'show_admin_bar_front' 	=> false, 	//(string|bool) Whether to display the Admin Bar for the user on the site's front end. Default true.
              //  'role' 					=> $customer[''], 	//(string) User's role.
              //  'locale' 				=> '', 	//(string) User's locale. Default empty.
            );

            $user_id = username_exists( $customer['Username'] );

            if ( ! $user_id && false == email_exists( $customer['Email'] ) ) {
                $random_password = wp_generate_password( $length = 12, $include_standard_special_chars = false );
                $user_id = wp_insert_user( $userdata ) ;
                update_user_meta($user_id,'nop_user_id',$customer['CustomerId']);
                // On success.
                if ( ! is_wp_error( $user_id ) ) {
                    echo '<div id="message" class="success"><p>User created :  '.$user_id.'</p></div>';
                }else{
                    $error_string = $user_id->get_error_message();
                    echo '<div id="message" class="error"><p>' . $error_string . '</p></div>';
                }
            } else {

                $random_password = __( 'User already exists.  Password inherited.', 'textdomain' );
            }
        }
    }else {
        echo '<div id="message" class="error"><p>Invalid JSON file or empty data.</p></div>';
    }
}