<?php get_header(); ?>

<div class="container">
    <?php if (is_home() || is_front_page()) : ?>
        <section class="hero-section">
            <div class="container">
                <div class="hero-content">
                    <h1>KÖP KAKEL &amp; KLINKER ONLINE</h1>
                    <p>Som en hjälper online-butik erbjuder vi ett stort sortiment kakel och klinker för ditt hem &ndash; allt på nära håll, snabba leveranser och experthjälp.</p>
                    <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="btn btn-primary">HANDLA KAKEL</a>
                </div>
            </div>
        </section>

        <section class="products-section">
            <div class="container">
                <h2 class="section-title">POPULÄRA PRODUKTER</h2>
                <div class="products-grid">
                    <?php
                    $popular_query = new WP_Query([
                        'post_type'      => 'product',
                        'posts_per_page' => 12,
                        'meta_key'       => 'total_sales',
                        'orderby'        => 'meta_value_num',
                    ]);

                    if ($popular_query->have_posts()) :
                        while ($popular_query->have_posts()) :
                            $popular_query->the_post();
                            wc_get_template_part('content', 'product');
                        endwhile;
                        wp_reset_postdata();
                    else :
                        echo '<p>' . esc_html__('Inga populära produkter hittades just nu.', 'kakelbolaget') . '</p>';
                    endif;
                    ?>
                </div>
            </div>
        </section>

        <section class="categories-section">
            <div class="container">
                <h2 class="section-title">POPULÄRA KATEGORIER</h2>
                <div class="categories-grid">
                    <?php
                    $categories = get_terms([
                        'taxonomy'   => 'product_cat',
                        'hide_empty' => true,
                        'number'     => 6,
                        'parent'     => 0,
                    ]);

                    if (!empty($categories) && !is_wp_error($categories)) :
                        foreach ($categories as $category) :
                            $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                            $image_url    = $thumbnail_id ? wp_get_attachment_image_url($thumbnail_id, 'large') : get_template_directory_uri() . '/images/category-placeholder.svg';
                            ?>
                            <div class="category-card">
                                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($category->name); ?>">
                                <div class="category-overlay">
                                    <h3 class="category-title"><?php echo esc_html(strtoupper($category->name)); ?></h3>
                                    <a href="<?php echo esc_url(get_term_link($category)); ?>" class="category-btn">
                                        HANDLA <?php echo esc_html(strtoupper($category->name)); ?>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach;
                    else :
                        echo '<p>' . esc_html__('Inga kategorier hittades.', 'kakelbolaget') . '</p>';
                    endif;
                    ?>
                </div>
            </div>
        </section>

        <section class="specialist-section">
            <div class="container">
                <h2 class="section-title">SPECIALISTER INOM KAKEL &amp; KLINKER</h2>
                <div class="specialist-content">
                    <p>Våra expertmedarbetare har över 20 års erfarenhet inom klinker och kakelindustrin. Vi hjälper till att välja rätt produkter för ditt projekt.</p>

                    <div class="specialist-features">
                        <div class="feature">
                            <div class="feature-icon">🚚</div>
                            <h4>Snabba leveranser</h4>
                            <p>Fri leverans</p>
                        </div>
                        <div class="feature">
                            <div class="feature-icon">📍</div>
                            <h4>Över hela Sverige</h4>
                            <p>Leverans överallt</p>
                        </div>
                        <div class="feature">
                            <div class="feature-icon">💳</div>
                            <h4>Säkra betalningar</h4>
                            <p>Flera betalmetoder</p>
                        </div>
                        <div class="feature">
                            <div class="feature-icon">🎯</div>
                            <h4>Experthjälp</h4>
                            <p>Personlig rådgivning</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php else : ?>
        <div class="content-area">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <header class="entry-header">
                            <h1 class="entry-title"><?php the_title(); ?></h1>
                        </header>

                        <div class="entry-content">
                            <?php the_content(); ?>
                        </div>
                    </article>
                <?php endwhile; ?>
            <?php else : ?>
                <p><?php esc_html_e('Inget innehåll hittades.', 'kakelbolaget'); ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
