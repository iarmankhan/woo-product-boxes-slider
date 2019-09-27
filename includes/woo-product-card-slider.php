<?php
if (!defined('ABSPATH'))
    exit;

function woo_product_card_slider($attr)
{
    ob_start();
    $count = (!empty($attr['count'])) ? $attr['count'] : 5;
    $autoplay = (!empty($attr['autoplay'])) ? $attr['autoplay'] : 'false';
    $interval = (!empty($attr['interval'])) ? $attr['interval'] : 3000;
    ?>
    <div uk-slider="center: true; autoplay: <?php echo $autoplay; ?>; autoplay-interval: <?php echo $interval; ?>">

        <div class="uk-position-relative uk-visible-toggle uk-light" tabindex="-1">

            <ul class="uk-slider-items uk-child-width-1-2@s uk-grid">
                <?php
                $args = array(
                    'post_type' => 'product',
                    'posts_per_page' => $count
                );
                $loop = new WP_Query($args);
                if ($loop->have_posts()) {
                    while ($loop->have_posts()) : $loop->the_post();
                        $product = wc_get_product();
                        $image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail');
                        $title = get_the_title();
                        ?>
                        <li>
                            <div class="uk-card uk-card-default noselect">
                                <div class="uk-card-media-top uk-cover-container uk-height-medium">
                                    <img src="<?php echo $image[0]; ?>" alt="<?php echo $title; ?>">
                                </div>
                                <div class="uk-card-body">
                                    <h3 class="uk-card-title"><?php echo $title; ?></h3>
                                    <p><?php echo get_the_excerpt(); ?></p>
                                    <a class="uk-button view-product uk-button-secondary uk-margin-remove"
                                       href="<?php echo get_permalink(); ?>">View Product</a>
                                    <span class="uk-label card-slider-price"
                                          style="pointer-events: none"><?php echo $product->get_price_html(); ?></span>
                                </div>
                            </div>
                        </li>
                    <?php
                    endwhile;
                } else {
                    echo __('No products available!');
                }
                wp_reset_postdata();
                ?>
            </ul>

            <a class="uk-position-center-left uk-position-small uk-hidden-hover" href="#" uk-slidenav-previous
               uk-slider-item="previous"></a>
            <a class="uk-position-center-right uk-position-small uk-hidden-hover" href="#" uk-slidenav-next
               uk-slider-item="next"></a>

        </div>
        <ul class="uk-slider-nav uk-dotnav uk-flex-center uk-margin"></ul>
    </div>
    <?php
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}

add_shortcode('woo-product-card-slider', 'woo_product_card_slider');
