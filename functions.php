<?php
/**
 * Kakelbolaget Custom WooCommerce Theme
 * Based on provided screenshots
 */

// Theme setup and WooCommerce support
function kakelbolaget_setup() {
    load_theme_textdomain('kakelbolaget', get_template_directory() . '/languages');

    // Add theme support
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('custom-logo');
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => 'Primary Navigation',
        'footer' => 'Footer Navigation'
    ));
}
add_action('after_setup_theme', 'kakelbolaget_setup');

// Enqueue styles and scripts
function kakelbolaget_scripts() {
    wp_enqueue_style('kakelbolaget-style', get_stylesheet_uri(), array(), '1.0.0');
    wp_enqueue_script('kakelbolaget-script', get_template_directory_uri() . '/js/main.js', array('jquery'), '1.0.0', true);
    
    // Localize script for AJAX
    wp_localize_script('kakelbolaget-script', 'kakelbolaget_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('kakelbolaget_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'kakelbolaget_scripts');

// Register widget areas
function kakelbolaget_widgets_init() {
    register_sidebar(array(
        'name' => 'Shop Sidebar',
        'id' => 'shop-sidebar',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
    
    register_sidebar(array(
        'name' => 'Footer Column 1',
        'id' => 'footer-1',
        'before_widget' => '<div class="footer-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="footer-widget-title">',
        'after_title' => '</h4>',
    ));
    
    register_sidebar(array(
        'name' => 'Footer Column 2',
        'id' => 'footer-2',
        'before_widget' => '<div class="footer-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="footer-widget-title">',
        'after_title' => '</h4>',
    ));
    
    register_sidebar(array(
        'name' => 'Footer Column 3',
        'id' => 'footer-3',
        'before_widget' => '<div class="footer-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="footer-widget-title">',
        'after_title' => '</h4>',
    ));
}
add_action('widgets_init', 'kakelbolaget_widgets_init');

// Wall calculator modal AJAX handler
function kakelbolaget_wall_calculator() {
    check_ajax_referer('kakelbolaget_nonce', 'nonce');
    
    $width = floatval($_POST['width']);
    $height = floatval($_POST['height']);
    $unit = sanitize_text_field($_POST['unit']);
    
    // Convert to square meters if needed
    if ($unit === 'cm') {
        $area = ($width * $height) / 10000;
    } else {
        $area = $width * $height;
    }
    
    wp_send_json_success(array(
        'area' => round($area, 2),
        'formatted' => number_format($area, 2, ',', ' ') . ' m²'
    ));
}
add_action('wp_ajax_wall_calculator', 'kakelbolaget_wall_calculator');
add_action('wp_ajax_nopriv_wall_calculator', 'kakelbolaget_wall_calculator');

// Custom product fields
function kakelbolaget_add_product_fields() {
    global $post;
    
    echo '<div class="options_group">';
    
    woocommerce_wp_text_input(array(
        'id' => '_product_brand',
        'label' => 'Varumärke',
        'placeholder' => 'T.ex. Golvabia',
        'desc_tip' => 'true',
        'description' => 'Produktens varumärke'
    ));
    
    woocommerce_wp_text_input(array(
        'id' => '_price_unit',
        'label' => 'Prisenhet',
        'placeholder' => 'T.ex. m², paket',
        'desc_tip' => 'true',
        'description' => 'Enhet för prisvisning'
    ));
    
    woocommerce_wp_text_input(array(
        'id' => '_product_code',
        'label' => 'Produktkod',
        'placeholder' => 'T.ex. 369601',
        'desc_tip' => 'true',
        'description' => 'Intern produktkod'
    ));
    
    echo '</div>';
}
add_action('woocommerce_product_options_general_product_data', 'kakelbolaget_add_product_fields');

// Save custom product fields
function kakelbolaget_save_product_fields($post_id) {
    if (isset($_POST['_product_brand'])) {
        $brand = wp_unslash($_POST['_product_brand']);
        if (!empty($brand)) {
            update_post_meta($post_id, '_product_brand', sanitize_text_field($brand));
        }
    }

    if (isset($_POST['_price_unit'])) {
        $price_unit = wp_unslash($_POST['_price_unit']);
        if (!empty($price_unit)) {
            update_post_meta($post_id, '_price_unit', sanitize_text_field($price_unit));
        }
    }

    if (isset($_POST['_product_code'])) {
        $product_code = wp_unslash($_POST['_product_code']);
        if (!empty($product_code)) {
            update_post_meta($post_id, '_product_code', sanitize_text_field($product_code));
        }
    }
}
add_action('woocommerce_process_product_meta', 'kakelbolaget_save_product_fields');

// Customize WooCommerce breadcrumbs
function kakelbolaget_change_breadcrumb_delimiter($defaults) {
    $defaults['delimiter'] = ' &gt; ';
    return $defaults;
}
add_filter('woocommerce_breadcrumb_defaults', 'kakelbolaget_change_breadcrumb_delimiter');

// Custom pagination
function kakelbolaget_pagination() {
    global $wp_query;
    
    $big = 999999999;
    $pages = paginate_links(array(
        'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages,
        'type' => 'array',
        'show_all' => false,
        'end_size' => 3,
        'mid_size' => 1,
        'prev_next' => true,
        'prev_text' => '‹',
        'next_text' => '›'
    ));
    
    if (is_array($pages)) {
        echo '<div class="pagination-wrapper">';
        echo '<ul class="pagination">';
        foreach ($pages as $page) {
            echo '<li>' . $page . '</li>';
        }
        echo '</ul>';
        echo '</div>';
    }
}

// Remove default WooCommerce styles
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

// Order Sample AJAX
function kakelbolaget_order_sample_handler() {
    check_ajax_referer('kakelbolaget_nonce', 'nonce');
    
    if (!isset($_POST['product_id'])) {
        wp_send_json_error(array('message' => __('Ogiltig produkt.', 'kakelbolaget')));
    }

    if (!function_exists('WC')) {
        wp_send_json_error(array('message' => __('WooCommerce är inte tillgängligt.', 'kakelbolaget')));
    }

    if (function_exists('wc_load_cart')) {
        wc_load_cart();
    }

    $product_id = intval($_POST['product_id']);

    // Create sample product or add to cart with special price
    $sample_price = 49; // 49 kr for samples

    // Add to cart with custom price
    $cart_item_data = array(
        'is_sample' => true,
        'sample_price' => $sample_price
    );
    
    $cart = WC()->cart;
    if (!$cart) {
        wp_send_json_error(array('message' => __('Kundvagnen är inte tillgänglig just nu.', 'kakelbolaget')));
    }

    $cart_item_key = $cart->add_to_cart($product_id, 1, 0, array(), $cart_item_data);
    
    if ($cart_item_key) {
        wp_send_json_success(array(
            'message' => 'Provbit tillagd i varukorgen!'
        ));
    } else {
        wp_send_json_error(array(
            'message' => 'Kunde inte lägga till provbit'
        ));
    }
}
add_action('wp_ajax_order_sample', 'kakelbolaget_order_sample_handler');
add_action('wp_ajax_nopriv_order_sample', 'kakelbolaget_order_sample_handler');

// Modify cart item price for samples
function kakelbolaget_modify_cart_item_price($cart) {
    if (is_admin() && !defined('DOING_AJAX')) return;
    
    foreach ($cart->get_cart() as $cart_item) {
        if (isset($cart_item['is_sample']) && $cart_item['is_sample']) {
            $cart_item['data']->set_price($cart_item['sample_price']);
        }
    }
}
add_action('woocommerce_before_calculate_totals', 'kakelbolaget_modify_cart_item_price');

// Newsletter signup AJAX
function kakelbolaget_newsletter_signup_handler() {
    check_ajax_referer('kakelbolaget_nonce', 'nonce');
    
    $email = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';

    if (!is_email($email)) {
        wp_send_json_error(array('message' => 'Ogiltig e-postadress'));
    }
    
    // Add to newsletter list (integrate with your email service)
    // For now, we'll just save to database or send email
    
    wp_send_json_success(array('message' => 'Tack för din anmälan!'));
}
add_action('wp_ajax_newsletter_signup', 'kakelbolaget_newsletter_signup_handler');
add_action('wp_ajax_nopriv_newsletter_signup', 'kakelbolaget_newsletter_signup_handler');

// Live search AJAX
function kakelbolaget_live_search_handler() {
    check_ajax_referer('kakelbolaget_nonce', 'nonce');
    
    $query = isset($_POST['query']) ? sanitize_text_field(wp_unslash($_POST['query'])) : '';

    $products = wc_get_products(array(
        's' => $query,
        'limit' => 5,
        'status' => 'publish'
    ));

    $results = array();
    foreach ($products as $product) {
        $image_src = wp_get_attachment_image_src($product->get_image_id(), 'thumbnail');
        $results[] = array(
            'id' => $product->get_id(),
            'title' => $product->get_name(),
            'price' => $product->get_price_html(),
            'image' => $image_src ? $image_src[0] : '',
            'url' => $product->get_permalink()
        );
    }

    wp_send_json_success(array('results' => $results));
}
add_action('wp_ajax_live_search', 'kakelbolaget_live_search_handler');
add_action('wp_ajax_nopriv_live_search', 'kakelbolaget_live_search_handler');

// Product filter AJAX
function kakelbolaget_filter_products_handler() {
    check_ajax_referer('kakelbolaget_nonce', 'nonce');
    
    $filters = array();
    if (isset($_POST['filters']) && is_array($_POST['filters'])) {
        $filters = wp_unslash($_POST['filters']);
    }

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 24,
        'post_status' => 'publish'
    );
    
    // Apply filters to query
    $meta_query = array();
    $tax_query = array();
    
    foreach ($filters as $filter_type => $values) {
        if (!is_array($values)) {
            $values = array($values);
        }

        $values = array_filter(array_map('sanitize_text_field', $values));
        if (empty($values)) {
            continue;
        }

        if ($filter_type === 'brand') {
            $tax_query[] = array(
                'taxonomy' => 'pa_brand',
                'field' => 'slug',
                'terms' => array_map('sanitize_title', $values),
                'operator' => 'IN'
            );
        }
        // Add more filter types as needed
    }
    
    if (!empty($tax_query)) {
        $args['tax_query'] = $tax_query;
    }
    
    if (!empty($meta_query)) {
        $args['meta_query'] = $meta_query;
    }
    
    $query = new WP_Query($args);
    
    ob_start();
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            wc_get_template_part('content', 'product');
        }
    }
    $products_html = ob_get_clean();
    wp_reset_postdata();
    
    wp_send_json_success(array(
        'products' => $products_html,
        'count' => $query->found_posts
    ));
}
add_action('wp_ajax_filter_products', 'kakelbolaget_filter_products_handler');
add_action('wp_ajax_nopriv_filter_products', 'kakelbolaget_filter_products_handler');

// Get cart count AJAX
function kakelbolaget_get_cart_count_handler() {
    if (!function_exists('WC')) {
        wp_send_json_success(array('count' => 0));
    }

    if (function_exists('wc_load_cart')) {
        wc_load_cart();
    }

    $cart = WC()->cart;
    $count = $cart ? $cart->get_cart_contents_count() : 0;

    wp_send_json_success(array(
        'count' => $count
    ));
}
add_action('wp_ajax_get_cart_count', 'kakelbolaget_get_cart_count_handler');
add_action('wp_ajax_nopriv_get_cart_count', 'kakelbolaget_get_cart_count_handler');

// Wishlist functionality
function kakelbolaget_add_to_wishlist_handler() {
    check_ajax_referer('kakelbolaget_nonce', 'nonce');

    if (!isset($_POST['product_id'])) {
        wp_send_json_error(array('message' => __('Ogiltig produkt.', 'kakelbolaget')));
    }

    if (!function_exists('WC')) {
        wp_send_json_error(array('message' => __('WooCommerce är inte tillgängligt.', 'kakelbolaget')));
    }

    if (function_exists('wc_load_cart')) {
        wc_load_cart();
    }

    $product_id = intval($_POST['product_id']);
    $user_id = get_current_user_id();

    if (!$user_id) {
        // Store in session for non-logged users
        $session = WC()->session;
        if ($session) {
            $wishlist = $session->get('wishlist', array());
            if (!in_array($product_id, $wishlist, true)) {
                $wishlist[] = $product_id;
                $session->set('wishlist', $wishlist);
            }
        }
    } else {
        // Store in user meta
        $wishlist = get_user_meta($user_id, 'wishlist', true);
        if (!is_array($wishlist)) {
            $wishlist = array();
        }
        if (!in_array($product_id, $wishlist, true)) {
            $wishlist[] = $product_id;
            update_user_meta($user_id, 'wishlist', $wishlist);
        }
    }

    wp_send_json_success();
}
add_action('wp_ajax_add_to_wishlist', 'kakelbolaget_add_to_wishlist_handler');
add_action('wp_ajax_nopriv_add_to_wishlist', 'kakelbolaget_add_to_wishlist_handler');

function kakelbolaget_remove_from_wishlist_handler() {
    check_ajax_referer('kakelbolaget_nonce', 'nonce');
    
    if (!isset($_POST['product_id'])) {
        wp_send_json_error(array('message' => __('Ogiltig produkt.', 'kakelbolaget')));
    }

    if (!function_exists('WC')) {
        wp_send_json_error(array('message' => __('WooCommerce är inte tillgängligt.', 'kakelbolaget')));
    }

    if (function_exists('wc_load_cart')) {
        wc_load_cart();
    }

    $product_id = intval($_POST['product_id']);
    $user_id = get_current_user_id();

    if (!$user_id) {
        $session = WC()->session;
        if ($session) {
            $wishlist = $session->get('wishlist', array());
            $wishlist = array_diff($wishlist, array($product_id));
            $session->set('wishlist', $wishlist);
        }
    } else {
        $wishlist = get_user_meta($user_id, 'wishlist', true);
        if (!is_array($wishlist)) {
            $wishlist = array();
        }
        $wishlist = array_diff($wishlist, array($product_id));
        update_user_meta($user_id, 'wishlist', $wishlist);
    }

    wp_send_json_success();
}
add_action('wp_ajax_remove_from_wishlist', 'kakelbolaget_remove_from_wishlist_handler');
add_action('wp_ajax_nopriv_remove_from_wishlist', 'kakelbolaget_remove_from_wishlist_handler');

// Adjust products per page via query parameter
function kakelbolaget_set_products_per_page($query) {
    if (is_admin() || !$query->is_main_query()) {
        return;
    }

    if (is_shop() || is_product_taxonomy()) {
        $per_page = isset($_GET['per_page']) ? intval($_GET['per_page']) : 0;
        if ($per_page > 0) {
            $query->set('posts_per_page', $per_page);
        }
    }
}
add_action('pre_get_posts', 'kakelbolaget_set_products_per_page');

