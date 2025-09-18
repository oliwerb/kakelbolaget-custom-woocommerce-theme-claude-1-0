<?php
/**
 * Template for displaying product archives.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

get_header();
?>

<div class="container">
    <?php woocommerce_breadcrumb(); ?>

    <div class="shop-layout">
        <aside class="shop-sidebar">
            <?php if (is_active_sidebar('shop-sidebar')) : ?>
                <?php dynamic_sidebar('shop-sidebar'); ?>
            <?php else : ?>
                <h3><?php esc_html_e('Filter', 'kakelbolaget'); ?></h3>

                <div class="filter-group">
                    <h4><?php esc_html_e('Varumärken', 'kakelbolaget'); ?></h4>
                    <div class="filter-options" data-filter="brand">
                        <?php
                        $brands = get_terms([
                            'taxonomy'   => 'pa_brand',
                            'hide_empty' => true,
                        ]);
                        if (!empty($brands) && !is_wp_error($brands)) :
                            foreach ($brands as $brand) :
                                ?>
                                <div class="filter-option">
                                    <input type="checkbox" id="brand_<?php echo esc_attr($brand->slug); ?>" value="<?php echo esc_attr($brand->slug); ?>">
                                    <label for="brand_<?php echo esc_attr($brand->slug); ?>"><?php echo esc_html($brand->name); ?></label>
                                </div>
                            <?php endforeach;
                        endif;
                        ?>
                    </div>
                </div>

                <div class="filter-group">
                    <h4><?php esc_html_e('Pris per m²', 'kakelbolaget'); ?></h4>
                    <div class="filter-options" data-filter="price">
                        <?php
                        $price_ranges = [
                            '0-200'  => __('0 - 200 kr', 'kakelbolaget'),
                            '200-400' => __('200 - 400 kr', 'kakelbolaget'),
                            '400-600' => __('400 - 600 kr', 'kakelbolaget'),
                            '600+'    => __('600+ kr', 'kakelbolaget'),
                        ];
                        foreach ($price_ranges as $value => $label) :
                            ?>
                            <div class="filter-option">
                                <input type="checkbox" id="price_<?php echo esc_attr(str_replace(['+', '-'], '_', $value)); ?>" value="<?php echo esc_attr($value); ?>">
                                <label for="price_<?php echo esc_attr(str_replace(['+', '-'], '_', $value)); ?>"><?php echo esc_html($label); ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="filter-group">
                    <h4><?php esc_html_e('Färg', 'kakelbolaget'); ?></h4>
                    <div class="filter-options" data-filter="color">
                        <?php
                        $colors = [
                            'white' => __('Vit', 'kakelbolaget'),
                            'black' => __('Svart', 'kakelbolaget'),
                            'gray'  => __('Grå', 'kakelbolaget'),
                            'beige' => __('Beige', 'kakelbolaget'),
                            'blue'  => __('Blå', 'kakelbolaget'),
                        ];
                        foreach ($colors as $slug => $label) :
                            ?>
                            <div class="filter-option">
                                <input type="checkbox" id="color_<?php echo esc_attr($slug); ?>" value="<?php echo esc_attr($slug); ?>">
                                <label for="color_<?php echo esc_attr($slug); ?>"><?php echo esc_html($label); ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="filter-group">
                    <h4><?php esc_html_e('Material', 'kakelbolaget'); ?></h4>
                    <div class="filter-options" data-filter="material">
                        <?php
                        $materials = [
                            'ceramic'    => __('Keramik', 'kakelbolaget'),
                            'porcelain'  => __('Porslin', 'kakelbolaget'),
                            'stone'      => __('Natursten', 'kakelbolaget'),
                        ];
                        foreach ($materials as $slug => $label) :
                            ?>
                            <div class="filter-option">
                                <input type="checkbox" id="material_<?php echo esc_attr($slug); ?>" value="<?php echo esc_attr($slug); ?>">
                                <label for="material_<?php echo esc_attr($slug); ?>"><?php echo esc_html($label); ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </aside>

        <main class="shop-content">
            <header class="shop-header">
                <div class="shop-info">
                    <h1 class="shop-title"><?php woocommerce_page_title(); ?></h1>
                    <?php if (apply_filters('woocommerce_show_page_description', true)) : ?>
                        <div class="shop-description"><?php echo wp_kses_post(wc_format_content(woocommerce_taxonomy_archive_description())); ?></div>
                    <?php endif; ?>
                    <p class="results-count"><?php printf(esc_html__('%d produkter', 'kakelbolaget'), wc_get_loop_prop('total')); ?></p>
                </div>

                <div class="shop-controls">
                    <div class="shop-sorting">
                        <label for="woocommerce-ordering"><?php esc_html_e('Sortera:', 'kakelbolaget'); ?></label>
                        <?php woocommerce_catalog_ordering(); ?>
                    </div>
                    <div class="shop-view">
                        <label for="products-per-page"><?php esc_html_e('Visa:', 'kakelbolaget'); ?></label>
                        <select class="products-per-page" id="products-per-page">
                            <option value="24" <?php selected(get_query_var('posts_per_page'), '24'); ?>>24</option>
                            <option value="48" <?php selected(get_query_var('posts_per_page'), '48'); ?>>48</option>
                            <option value="96" <?php selected(get_query_var('posts_per_page'), '96'); ?>>96</option>
                        </select>
                    </div>
                </div>
            </header>

            <?php if (woocommerce_product_loop()) : ?>
                <?php woocommerce_product_loop_start(); ?>

                <?php if (wc_get_loop_prop('total')) : ?>
                    <?php while (have_posts()) : the_post(); ?>
                        <?php wc_get_template_part('content', 'product'); ?>
                    <?php endwhile; ?>
                <?php endif; ?>

                <?php woocommerce_product_loop_end(); ?>

                <?php woocommerce_pagination(); ?>
            <?php else : ?>
                <div class="no-products">
                    <p><?php esc_html_e('Inga produkter hittades som matchar dina filterval.', 'kakelbolaget'); ?></p>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<?php
get_footer();
