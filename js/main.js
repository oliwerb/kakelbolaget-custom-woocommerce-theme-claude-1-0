// KAKELBOLAGET THEME JAVASCRIPT

(function ($) {
    'use strict';

    function openModal(modal) {
        modal.removeAttr('hidden');
        $('body').addClass('modal-open');
    }

    function closeModal(modal) {
        modal.attr('hidden', true);
        $('body').removeClass('modal-open');
    }

    function initWallCalculator() {
        const modal = $('#wall-calculator-modal');
        if (!modal.length) {
            return;
        }

        $(document).on('click', '.wall-calculator-trigger, .pricing-calculator', function (event) {
            event.preventDefault();
            openModal(modal);
        });

        modal.on('click', '.close', function () {
            closeModal(modal);
        });

        modal.on('click', function (event) {
            if (event.target === modal[0]) {
                closeModal(modal);
            }
        });

        modal.find('#calc-width, #calc-height, #calc-unit').on('input change', updateCalculation);

        modal.find('#use-calculation').on('click', function () {
            const value = parseFloat(modal.find('#calc-result').text()) || 0;
            const quantityInput = $('.quantity input.qty');
            if (quantityInput.length) {
                quantityInput.val(Math.max(1, Math.ceil(value))).trigger('change');
            }
            closeModal(modal);
        });

        function updateCalculation() {
            const width = parseFloat(modal.find('#calc-width').val()) || 0;
            const height = parseFloat(modal.find('#calc-height').val()) || 0;
            const unit = modal.find('#calc-unit').val();
            let area = 0;

            if (unit === 'cm') {
                area = (width * height) / 10000;
            } else {
                area = width * height;
            }

            modal.find('#calc-total').val(area.toFixed(2));
            modal.find('#calc-result').text(area.toFixed(2));
        }
    }

    function initProductGallery() {
        $('.product-thumbnails').on('click', 'img', function () {
            const newSrc = $(this).attr('src');
            $('.product-main-image img').attr('src', newSrc);
            $('.product-thumbnails img').removeClass('active');
            $(this).addClass('active');
        });

        $('.product-main-image').on('mousemove', function (event) {
            const $image = $(this).find('img');
            if (!$image.length) {
                return;
            }

            const offset = $(this).offset();
            const xPercent = ((event.pageX - offset.left) / $(this).width()) * 100;
            const yPercent = ((event.pageY - offset.top) / $(this).height()) * 100;
            $image.css('transform-origin', xPercent + '% ' + yPercent + '%');
        });
    }

    function showNotification(message, type) {
        const existing = $('.kakelbolaget-notification');
        existing.remove();

        const notification = $('<div/>', {
            class: 'kakelbolaget-notification ' + (type || 'info'),
            text: message,
        });

        $('body').append(notification);
        setTimeout(function () {
            notification.addClass('visible');
        }, 10);

        setTimeout(function () {
            notification.removeClass('visible');
            setTimeout(function () {
                notification.remove();
            }, 300);
        }, 3000);
    }

    function ajaxRequest(action, data, onSuccess) {
        $.ajax({
            url: kakelbolaget_ajax.ajax_url,
            method: 'POST',
            data: $.extend({
                action: action,
                nonce: kakelbolaget_ajax.nonce,
            }, data || {}),
        })
            .done(function (response) {
                if (response && response.success) {
                    if (onSuccess) {
                        onSuccess(response.data);
                    }
                } else if (response && response.data && response.data.message) {
                    showNotification(response.data.message, 'error');
                }
            })
            .fail(function () {
                showNotification('Något gick fel. Försök igen senare.', 'error');
            });
    }

    function initWishlist() {
        $(document).on('click', '.heart-icon, .favorites-btn', function (event) {
            event.preventDefault();
            const button = $(this);
            const productId = button.closest('[data-product-id]').data('product-id');
            if (!productId) {
                return;
            }

            const isActive = button.hasClass('favorited');
            const action = isActive ? 'remove_from_wishlist' : 'add_to_wishlist';

            ajaxRequest(action, { product_id: productId }, function () {
                button.toggleClass('favorited', !isActive);
                button.find('span, i').text(!isActive ? '♥' : '♡');
                showNotification(
                    !isActive ? 'Produkt tillagd i favoriter!' : 'Produkt borttagen från favoriter!',
                    !isActive ? 'success' : 'info'
                );
            });
        });
    }

    function initOrderSample() {
        $(document).on('click', '.order-sample, .order-sample-btn', function (event) {
            event.preventDefault();
            const productId = $(this).data('product-id');
            if (!productId) {
                return;
            }

            ajaxRequest('order_sample', { product_id: productId }, function (data) {
                showNotification(data && data.message ? data.message : 'Provbit tillagd i varukorgen!', 'success');
                updateCartCount();
            });
        });
    }

    function initNewsletter() {
        $('.newsletter-form').on('submit', function (event) {
            event.preventDefault();
            const form = $(this);
            const email = form.find('input[name="newsletter_email"], input[type="email"]').val();

            ajaxRequest('newsletter_signup', { email: email }, function (data) {
                showNotification(data.message || 'Tack för din anmälan!', 'success');
                form[0].reset();
            });
        });
    }

    function collectFilters() {
        const filters = {};
        $('.filter-options').each(function () {
            const type = $(this).data('filter');
            if (!type) {
                return;
            }
            const values = [];
            $(this)
                .find('input[type="checkbox"]:checked')
                .each(function () {
                    values.push($(this).val());
                });
            if (values.length) {
                filters[type] = values;
            }
        });
        return filters;
    }

    function initFilters() {
        const shopContent = $('.shop-content .products-grid, .shop-content ul.products');
        if (!shopContent.length) {
            return;
        }

        $('.filter-options input[type="checkbox"]').on('change', applyFilters);

        $('.products-per-page').on('change', function () {
            const perPage = $(this).val();
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', perPage);
            window.location.href = url.toString();
        });

        function applyFilters() {
            const filters = collectFilters();
            ajaxRequest('filter_products', { filters: filters }, function (data) {
                if (data && typeof data.products !== 'undefined') {
                    shopContent.html(data.products);
                    $('.results-count').text(data.count + ' produkter');
                }
            });
        }
    }

    function initLiveSearch() {
        const searchInput = $('.search-bar input[type="search"]');
        if (!searchInput.length) {
            return;
        }

        let timeout = null;
        const resultsContainer = $('<div/>', { class: 'live-search-results', hidden: 'hidden' });
        $('.search-bar').append(resultsContainer);

        searchInput.on('input', function () {
            clearTimeout(timeout);
            const query = $(this).val();
            if (query.length < 3) {
                resultsContainer.attr('hidden', true).empty();
                return;
            }

            timeout = setTimeout(function () {
                ajaxRequest('live_search', { query: query }, function (data) {
                    const results = data.results || [];
                    if (!results.length) {
                        resultsContainer.html('<p>Inga produkter hittades.</p>').removeAttr('hidden');
                        return;
                    }

                    const list = $('<ul/>');
                    results.forEach(function (item) {
                        const entry = $('<li/>');
                        const link = $('<a/>', { href: item.url });
                        if (item.image) {
                            link.append($('<img/>', { src: item.image, alt: item.title }));
                        }
                        link.append($('<span/>', { text: item.title }));
                        if (item.price) {
                            link.append($('<strong/>', { html: item.price }));
                        }
                        entry.append(link);
                        list.append(entry);
                    });
                    resultsContainer.html(list).removeAttr('hidden');
                });
            }, 250);
        });

        $(document).on('click', function (event) {
            if (!$(event.target).closest('.search-bar').length) {
                resultsContainer.attr('hidden', true);
            }
        });
    }

    function updateCartCount() {
        ajaxRequest('get_cart_count', {}, function (data) {
            const count = data.count || 0;
            const cartCountEl = $('.cart-count');
            if (count > 0) {
                if (!cartCountEl.length) {
                    $('.cart-icon a').append('<span class="cart-count">' + count + '</span>');
                } else {
                    cartCountEl.text(count);
                }
            } else {
                cartCountEl.remove();
            }
        });
    }

    $(function () {
        initWallCalculator();
        initProductGallery();
        initWishlist();
        initOrderSample();
        initNewsletter();
        initFilters();
        initLiveSearch();
        updateCartCount();
    });
})(jQuery);
