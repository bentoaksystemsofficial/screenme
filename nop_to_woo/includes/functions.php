<?php
function my_import_category_recursively($category_data, $parent_id = 0)
{
    global $CategoryIdToId;
    $category_id = my_create_category_in_woocommerce($category_data, $parent_id);
    if ($category_id) {
        $CategoryIdToId[$category_data['Id']] = $category_id;
        echo 'Category "' . $category_data['Name'] . '" imported successfully.<br>';
        $CategoryIdToId[$category_data['Id']] = $category_id;

        if (isset($category_data['SubCategories']['Category'])) {
            // If 'SubCategories' is not an array, convert it into an array with one element
            if (isset($category_data['SubCategories']['Category']['Id'])) {
                $tmp = $category_data['SubCategories']['Category'];
                $category_data['SubCategories']['Category'] = [];
                $category_data['SubCategories']['Category'][0] = $tmp;
            }
            foreach ($category_data['SubCategories']['Category'] as $subcategory_data) {
                my_import_category_recursively($subcategory_data, $category_id);
            }
        }
    } else {
        echo 'Failed to import category "' . $category_data['Name'] . '".<br>';
    }
}

function my_create_category_in_woocommerce($category_data, $parent_id = 0)
{
    // Assuming WooCommerce is active and installed

    if (!isset($category_data['Name'])) {
        $x = 1;
    }
    // Prepare category data for insertion
    $category = array(
        'cat_name' => $category_data['Name'],
        'category_nicename' => sanitize_title($category_data['Name']),
        'taxonomy' => 'product_cat',
        'category_parent' => $parent_id,
    );

    // Insert the category into the database
    $category_id = wp_insert_category($category);

    return $category_id;
}

function my_create_product_in_woocommerce($product_data)
{
    // Assuming WooCommerce is active and installed

    // Prepare product data for insertion
    $product = array(
        'post_title' => $product_data['Name'],
        'post_content' => $product_data['FullDescription'],
        'post_status' => 'publish',
        'post_type' => 'product',
    );

    // Insert the product into the database
    $product_id = wp_insert_post($product);

    // Update product meta (e.g., price, SKU, etc.)
    if ($product_id) {
        update_post_meta($product_id, '_regular_price', $product_data['Price']);
        update_post_meta($product_id, '_price', $product_data['Price']);
        // Add other meta fields as needed
    }

    return $product_id;
}

function my_get_category_id_by_id($category_id)
{
    // Assuming WooCommerce is active and installed

    $term = get_term_by('term_id', $category_id, 'product_cat');
    if ($term) {
        return $term->term_id;
    }
    return 0;
}

function remove_all_products_in_woocommerce()
{
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
    );

    $products = get_posts($args);

    if (!empty($products)) {
        foreach ($products as $product) {
            wp_delete_post($product->ID, true);
        }
        echo 'All products have been removed successfully.';
    } else {
        echo 'No products found to remove.';
    }
}

// Function to remove all categories in WooCommerce
function remove_all_categories_in_woocommerce()
{
    $taxonomy = 'product_cat';
    $args = array(
        'taxonomy' => $taxonomy,
        'hide_empty' => false,
    );

    $categories = get_terms($args);

    if (!empty($categories) && !is_wp_error($categories)) {
        foreach ($categories as $category) {
            wp_delete_term($category->term_id, $taxonomy);
        }
        echo 'All categories have been removed successfully.';
    } else {
        echo 'No categories found to remove.';
    }
}