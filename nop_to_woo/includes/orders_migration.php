<?php
if (isset($_POST['import_orders'])) {
    $orders_json_data = file_get_contents(NTW_PLUGIN_DIR . '/data/orders.json');
    $orders_json_data = json_decode($orders_json_data, true);
    if (!empty($orders_json_data) && is_array($orders_json_data['Orders']['Order'])) {
        $counter = 1;
        foreach ($orders_json_data['Orders']['Order'] as $order_data) {

            $CustomerId = $order_data['CustomerId'];
            $customer = get_users(array(
                'meta_key' => 'nop_user_id',
                'meta_value' => $CustomerId,
            ));
            foreach ($customer as $user) {
                $user_nop_id = get_user_meta($user->ID, 'nop_user_id', true);
                if ($user_nop_id == $CustomerId) {
                    $order_data_encode = json_encode($order_data['OrderItems']['OrderItem']);
                    $order_data_redecode = json_decode($order_data_encode);
                    //$order = wc_create_order();
                    try {
                       // $order->set_customer_id($user->ID);
                        if (is_array($order_data_redecode)) {
                            foreach ($order_data_redecode as $item) {
                                $products = new WP_Query( array(
                                    'post_type' => 'product',
                                    'meta_key' => 'nop_product_id',
                                    'meta_value' => $item->Id,
                                ));
                                $product = $products->get_posts();
                                $product = $product[0]->ID;
                                echo "post id : " .$item->Id;
                                echo "<br>";
                                var_dump($product);
                                echo "<hr>";
                            }

                        } else {
                            $products = new WP_Query( array(
                                'post_type' => 'product',
                                'meta_key' => 'nop_product_id',
                                'meta_value' => $order_data_redecode->Id,
                            ));
                            $product = $products->get_posts();
                            $product = $product[0]->ID;
                            echo "post id : " .$order_data_redecode->Id;
                            echo "<br>";
                            var_dump($product);
                            echo "<hr>";
                        }

                        /*if (!empty($products)){
                            $order->add_product( wc_get_product( $products[0]->ID ) );
                        }*/


                        //  echo 'order created successfully<br>';
                    } catch (WC_Data_Exception $e) {
                        echo '<div id="message" class="error"><p>' . $e . '</p></div>';
                    }
                    /*

                    $order->calculate_totals();
                    $order->set_status( 'wc-completed' );
                    $order->save();*/
                    /*if (is_array($order_data['OrderItems']['OrderItem'])){
                        foreach ($order_data['OrderItems']['OrderItem'] as $orderItem){
                            var_dump($orderItem);
                            echo "<hr>";
                        }
                    }*/


                }
                //    echo '<div id="message" class="error"><p>user id :  '.$user->ID.' <br> nop id: '.$CustomerId.'<br> user nop id: '.$user_nop_id.'<br> Order Id : '.$order['OrderId'].'<hr></p></div>';
            }
            /*if (!empty($customer)){

            }else{
                $counter ++ ;
            }*/
        }

    } else {
        echo '<div id="message" class="error"><p>Invalid JSON file or empty data.</p></div>';
    }
    die();
}