<?php
/**
 * The template for displaying single products
 */

if (!defined('ABSPATH')) {
    exit;
}

do_action('woocommerce_before_single_product');

if (post_password_required()) {
    echo get_the_password_form();
    return;
}

global $product;
get_header();
?>

<div class="container">
    <?php woocommerce_output_all_notices(); ?>
    <?php woocommerce_breadcrumb(); ?>

    <?php while (have_posts()) : the_post(); ?>
        <?php
        $attachment_ids = $product->get_gallery_image_ids();
        $main_image_url = get_the_post_thumbnail_url($product->get_id(), 'large');
        $brand          = get_post_meta(get_the_ID(), '_product_brand', true);
        $product_code   = get_post_meta(get_the_ID(), '_product_code', true);
        $price_unit     = get_post_meta(get_the_ID(), '_price_unit', true);
        ?>

        <div class="product-single-layout" data-product-id="<?php echo esc_attr(get_the_ID()); ?>">
            <div class="product-images">
                <div class="product-main-image">
                    <?php if ($main_image_url) : ?>
                        <img src="<?php echo esc_url($main_image_url); ?>" alt="<?php the_title_attribute(); ?>">
                    <?php else : ?>
                        <?php echo wc_placeholder_img(); ?>
                    <?php endif; ?>
                    <div class="product-zoom-icon" aria-hidden="true">🔍</div>
                </div>

                <?php if (!empty($attachment_ids)) : ?>
                    <div class="product-thumbnails">
                        <?php if ($main_image_url) : ?>
                            <img src="<?php echo esc_url($main_image_url); ?>" alt="<?php the_title_attribute(); ?>" class="active">
                        <?php endif; ?>
                        <?php foreach ($attachment_ids as $attachment_id) : ?>
                            <?php $image_url = wp_get_attachment_image_url($attachment_id, 'medium'); ?>
                            <?php if ($image_url) : ?>
                                <img src="<?php echo esc_url($image_url); ?>" alt="<?php the_title_attribute(); ?>">
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="product-details" data-product-id="<?php echo esc_attr(get_the_ID()); ?>">
                <div class="product-meta">
                    <?php if ($brand) : ?>
                        <div class="product-brand-single"><?php echo esc_html($brand); ?></div>
                    <?php endif; ?>

                    <?php the_title('<h1 class="product-title-single">', '</h1>'); ?>

                    <?php if ($product->has_dimensions()) : ?>
                        <div class="product-dimensions-single"><?php echo wp_kses_post(wc_format_dimensions($product->get_dimensions(false))); ?></div>
                    <?php endif; ?>

                    <?php if ($product_code) : ?>
                        <div class="product-code"><?php echo esc_html__('I lager', 'kakelbolaget'); ?> | <?php echo esc_html($product_code); ?></div>
                    <?php endif; ?>
                </div>

                <div class="product-pricing-single">
                    <div class="price-display">
                        <span class="price-main"><?php echo wp_kses_post($product->get_price_html()); ?></span>
                        <?php if ($price_unit) : ?>
                            <span class="price-unit-single"><?php printf(esc_html__('per %s', 'kakelbolaget'), esc_html($price_unit)); ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="price-calculator">
                        <button class="btn btn-outline wall-calculator-trigger" type="button">📏 <?php esc_html_e('Hjälp mig beräkna min m²', 'kakelbolaget'); ?></button>
                    </div>

                    <div class="quantity-selector">
                        <?php do_action('woocommerce_before_add_to_cart_quantity'); ?>
                        <label for="quantity"><?php esc_html_e('Antal', 'kakelbolaget'); ?>:</label>
                        <?php woocommerce_quantity_input(); ?>
                        <?php do_action('woocommerce_after_add_to_cart_quantity'); ?>
                    </div>
                </div>

                <div class="add-to-cart-section">
                    <?php
                    do_action('woocommerce_before_add_to_cart_button');
                    woocommerce_template_single_add_to_cart();
                    do_action('woocommerce_after_add_to_cart_button');
                    ?>
                    <button class="btn btn-outline order-sample-btn" data-product-id="<?php echo esc_attr(get_the_ID()); ?>"><?php esc_html_e('BESTÄLL PROV', 'kakelbolaget'); ?></button>

                    <button class="favorites-btn" type="button">
                        <span aria-hidden="true">♡</span>
                        <?php esc_html_e('Lägg till i favoriter', 'kakelbolaget'); ?>
                    </button>
                </div>

                <div class="product-description">
                    <?php the_content(); ?>
                </div>

                <div class="social-share" aria-label="<?php esc_attr_e('Dela produkten', 'kakelbolaget'); ?>">
                    <h4><?php esc_html_e('Dela produkten', 'kakelbolaget'); ?></h4>
                    <div class="social-buttons">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" class="social-btn facebook">f</a>
                        <a href="https://twitter.com/intent/tweet?url=<?php the_permalink(); ?>" class="social-btn twitter">t</a>
                        <a href="https://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>" class="social-btn pinterest">p</a>
                        <a href="https://www.linkedin.com/shareArticle?url=<?php the_permalink(); ?>" class="social-btn linkedin">in</a>
                        <a href="mailto:?subject=<?php echo rawurlencode(get_the_title()); ?>&body=<?php the_permalink(); ?>" class="social-btn email">@</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="product-specifications">
            <h3><?php esc_html_e('Specifikationer', 'kakelbolaget'); ?></h3>
            <?php if ($product->has_attributes()) : ?>
                <div class="specs-table">
                    <?php foreach ($product->get_attributes() as $attribute) : ?>
                        <?php if ($attribute->get_visible()) : ?>
                            <div class="spec-row">
                                <span class="spec-label"><?php echo esc_html(wc_attribute_label($attribute->get_name())); ?></span>
                                <span class="spec-value"><?php echo wp_kses_post(wc_attribute_to_string($attribute)); ?></span>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <p><?php esc_html_e('Inga ytterligare specifikationer tillgängliga.', 'kakelbolaget'); ?></p>
            <?php endif; ?>
        </div>

        <?php do_action('woocommerce_after_single_product_summary'); ?>
    <?php endwhile; ?>
</div>

<?php
get_footer();
do_action('woocommerce_after_single_product');
