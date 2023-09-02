<?php

// Plugin activation hook
register_activation_hook(__FILE__, 'noptowoo_importer_activate');

function noptowoo_importer_activate()
{
    // Perform any tasks upon plugin activation
}

// Plugin deactivation hook
register_deactivation_hook(__FILE__, 'noptowoo_importer_deactivate');

function my_product_importer_deactivate()
{
    // Perform any tasks upon plugin deactivation
}

// Add admin menu page
add_action('admin_menu', 'my_product_importer_menu');

function my_product_importer_menu()
{
    add_menu_page(
        'NopCommerce TO WooCommerce Importer',
        'NopCommerce TO WooCommerce Importer',
        'manage_options',
        'noptowoo-importer',
        'importer_page',
        'dashicons-upload',
        30
    );
}



function importer_page()
{
    global $CategoryIdToId;
    // Display the admin page content and handle product import on form submission
    echo '<h1>Product Importer</h1>';
    echo '<form method="post">';
    echo '<input type="submit" name="import_products" value="Import Products">';
    echo '</form>';

    echo '<h1>Customer Importer</h1>';
    echo '<form method="post" >';
    echo '<input type="submit" name="import_customers" value="Import Customers">';
    echo '</form>';

    require __DIR__ . '/customers_migration.php';
    require __DIR__ . '/products_migration.php';

//    remove_all_products_in_woocommerce();
//    remove_all_categories_in_woocommerce();
//    die();
    // Handle the product import logic here



}
