<?php
global $CategoryIdToId;
$CategoryIdToId = array();
if (isset($_POST['import_products'])) {

    // Get the JSON data from files
    $products_json_data = file_get_contents(NTW_PLUGIN_DIR . '/data/products.json');
    $categories_json_data = file_get_contents(NTW_PLUGIN_DIR . '/data/categories.json');

    // Decode JSON data
    $products_data = json_decode($products_json_data, true);
    $categories_data = json_decode($categories_json_data, true);

    // Import categories and subcategories first
    if (is_array($categories_data['Categories']['Category'])) {
        foreach ($categories_data['Categories']['Category'] as $category_data) {
            my_import_category_recursively($category_data, 0);
        }
    } else {
        echo 'Invalid JSON file or empty data.';
    }

    if (is_array($products_data) && isset($categories_data['Categories']['Category'])) {
        // Import products and assign them to categories
        $counter=1;
        foreach ($products_data['Products']['Product'] as $product_data) {
            $product_id = my_create_product_in_woocommerce($product_data);
            if ($product_id) {
                echo 'Product "' . $product_data['Name'] . '" imported successfully.<br>';

                // Assign product to categories
                if (isset($product_data['ProductCategories']['ProductCategory'])) {
                    $category_ids = array();

                    if (isset($product_data['ProductCategories']['ProductCategory']['ProductCategoryId'])) {
                        $tmp = $product_data['ProductCategories']['ProductCategory'];
                        $product_data['ProductCategories']['ProductCategory'] = [];
                        $product_data['ProductCategories']['ProductCategory'] [0] = $tmp;
                    }
                    foreach ($product_data['ProductCategories']['ProductCategory'] as $category) {
                        if (!isset($CategoryIdToId[$category['CategoryId']])) {
                            $x = 1;
                        }
                        $category_id = my_get_category_id_by_id($CategoryIdToId[$category['CategoryId']]);
                        if ($category_id) {
                            $category_ids[] = $category_id;
                        }
                    }
                    if (!empty($category_ids)) {
                        wp_set_object_terms($product_id, $category_ids, 'product_cat');
                    }
                }
                $counter++;
            } else {
                echo 'Failed to import product "' . $product_data['Name'] . '".<br>';
            }
        }
        echo $counter;
    } else {
        echo 'Invalid JSON file or empty data.';
    }
}