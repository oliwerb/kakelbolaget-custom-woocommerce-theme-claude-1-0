<?php
/**
 * Template for product cards in loops.
 */

if (!defined('ABSPATH')) {
    exit;
}

global $product;
if (empty($product) || !$product->is_visible()) {
    return;
}
?>

<div class="product-card" data-product-id="<?php echo esc_attr(get_the_ID()); ?>">
    <div class="product-image-wrapper">
        <div class="product-wishlist">
            <button class="heart-icon" type="button" aria-label="<?php esc_attr_e('Lägg till i favoriter', 'kakelbolaget'); ?>">♡</button>
        </div>
        <a href="<?php the_permalink(); ?>">
            <?php echo woocommerce_get_product_thumbnail(); ?>
        </a>
    </div>

    <div class="product-info">
        <h3 class="product-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>

        <?php $brand = get_post_meta(get_the_ID(), '_product_brand', true); ?>
        <?php if ($brand) : ?>
            <div class="product-brand"><?php echo esc_html($brand); ?></div>
        <?php endif; ?>

        <?php if ($product->get_dimensions()) : ?>
            <div class="product-dimensions"><?php echo wp_kses_post(wc_format_dimensions($product->get_dimensions(false))); ?></div>
        <?php endif; ?>

        <div class="product-pricing">
            <span class="price"><?php echo wp_kses_post($product->get_price_html()); ?></span>
            <?php $price_unit = get_post_meta(get_the_ID(), '_price_unit', true); ?>
            <?php if ($price_unit) : ?>
                <span class="price-unit">/ <?php echo esc_html($price_unit); ?></span>
            <?php endif; ?>
        </div>

        <div class="product-buttons">
            <button class="btn btn-outline order-sample" data-product-id="<?php echo esc_attr(get_the_ID()); ?>">
                <?php esc_html_e('BESTÄLL PROV', 'kakelbolaget'); ?>
            </button>
            <a href="<?php the_permalink(); ?>" class="btn btn-primary view-product">
                <?php esc_html_e('VISA PRODUKT', 'kakelbolaget'); ?>
            </a>
        </div>
    </div>
</div>
