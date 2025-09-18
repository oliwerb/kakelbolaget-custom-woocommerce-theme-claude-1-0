<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="site-wrapper">
    
    <div class="top-bar">
        <div class="container">
            <div class="top-bar-content">
                <div class="top-bar-left">
                    <span>Behöver du hjälp? Kontakta vår kundtjänst via telefon, mail eller chatt!</span>
                </div>
                <div class="top-bar-right">
                    <a href="/my-account">Mina sidor</a>
                    <a href="/my-account">Logga in</a>
                    <a href="/checkout">Bli Pluskund</a>
                </div>
            </div>
        </div>
    </div>

    
    <header class="site-header">
        <div class="container">
            <div class="header-content">
                <div class="header-left">
                    <div class="logo">
                        <?php if (has_custom_logo()) : ?>
                            <?php the_custom_logo(); ?>
                        <?php else : ?>
                            <a href="<?php echo home_url('/'); ?>">
                                <strong>K</strong> KAKELBOLAGET
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="header-center">
                    <div class="search-bar">
                        <form role="search" method="get" action="<?php echo home_url('/'); ?>">
                            <input type="search" placeholder="SÖK BLAND 10 000 TALS PRODUKTER..." 
                                   value="<?php echo get_search_query(); ?>" name="s" />
                            <button type="submit" class="search-button">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false">
                                    <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2" />
                                    <path d="M21 21l-4.35-4.35" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                </svg>
                            </button>
                            <input type="hidden" name="post_type" value="product" />
                        </form>
                    </div>
                </div>
                
                <div class="header-right">
                    <div class="cart-icon">
                        <?php if (function_exists('WC')) : ?>
                            <?php $cart = WC()->cart; $cart_count = $cart ? $cart->get_cart_contents_count() : 0; ?>
                            <a href="<?php echo esc_url(wc_get_cart_url()); ?>">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <circle cx="9" cy="21" r="1"/>
                                    <circle cx="20" cy="21" r="1"/>
                                    <path d="m1 1 4 4 7 12 8-7"/>
                                </svg>
                                <?php if ($cart_count > 0) : ?>
                                    <span class="cart-count"><?php echo esc_html($cart_count); ?></span>
                                <?php endif; ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </header>

    
    <nav class="main-navigation">
        <div class="container">
            <div class="nav-content">
                <div class="nav-left">
                    <?php
                    if (has_nav_menu('primary')) {
                        wp_nav_menu([
                            'theme_location' => 'primary',
                            'container'      => false,
                            'menu_class'     => 'main-menu',
                            'fallback_cb'    => false,
                        ]);
                    } else {
                        echo '<ul class="main-menu">';
                        echo '<li><a href="' . esc_url(home_url('/shop')) . '">' . esc_html__('Shop', 'kakelbolaget') . '</a></li>';
                        echo '</ul>';
                    }
                    ?>
                </div>
                <div class="nav-right">
                    <div class="promo-banners">
                        <span class="promo-item">FRI LEVERANS ORDRAR ÖVER 5000 KR*</span>
                        <span class="promo-item">BESTÄLL PROVBITAR FÖR 49 KR</span>
                        <span class="promo-item">SKRÄDDARSYDD FRAKT KOLLA PÅ VIDEON</span>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    
    <main class="site-main">
