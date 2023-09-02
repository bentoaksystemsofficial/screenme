<?php
if (isset($_POST['import_customers'])) {

    $customers_data = file_get_contents(NTW_PLUGIN_DIR.'/data/customers.json');
    $customers_data= json_decode($customers_data,true);
    var_dump(count($customers_data['Customers']['Customer']));
    die();
    if (!empty($customers_data) && is_array($customers_data['Customers']['Customer'])){

        foreach ($customers_data['Customers']['Customer'] as $customer){
            $user_id = username_exists( $customer['Username'] );

            if ( ! $user_id && false == email_exists( $customer['Email'] ) ) {
                $random_password = wp_generate_password( $length = 12, $include_standard_special_chars = false );
                $user_id = wp_create_user( $customer['Username'], $random_password, $customer['Email'] );

            } else {
                $random_password = __( 'User already exists.  Password inherited.', 'textdomain' );
            }
        }
    }else {
        echo 'Invalid JSON file or empty data.';
    }
}