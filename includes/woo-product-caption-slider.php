<?php
if (!defined('ABSPATH'))
    exit;

function woo_product_caption_slider($attr)
{
    ob_start();
    $count = (!empty($attr['count'])) ? $attr['count'] : 5;
    $autoplay = (!empty($attr['autoplay'])) ? $attr['autoplay'] : 'false';
    $interval = (!empty($attr['interval'])) ? $attr['interval'] : 3000;
    ?>
    <div class="uk-position-relative uk-visible-toggle uk-light" tabindex="-1"
         uk-slider="clsActivated: uk-transition-active; center: true; autoplay: <?php echo $autoplay; ?>; autoplay-interval: <?php echo $interval; ?>">
        <ul class="uk-slider-items uk-grid">
            <?php
            $args = array(
                'post_type' => 'product',
                'posts_per_page' => $count
            );
            $loop = new WP_Query($args);
            if ($loop->have_posts()) {
                while ($loop->have_posts()) : $loop->the_post();
                    $image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail');
                    $title = get_the_title();
                    ?>
                    <li class="uk-width-3-4">
                        <div class="uk-panel uk-height-medium uk-flex uk-flex-center uk-flex-middle uk-background-cover uk-light slider-1 noselect"
                             data-src="<?php echo $image[0]; ?>" uk-img>
                            <!-- <div class="uk-height-medium uk-flex uk-flex-center uk-flex-middle uk-background-cover uk-light slider-3 slider-overlay noselect"
                             data-src="<?php echo $image[0]; ?>" uk-img> -->
                            <div class="uk-overlay uk-overlay-primary uk-position-bottom uk-text-center uk-transition-slide-bottom">
                                <h3 class="uk-margin-remove"><?php echo $title; ?></h3>
                                <a class="uk-button uk-button-default uk-margin" href="<?php echo get_permalink(); ?>">View
                                    Product</a>
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
    <?php
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}

add_shortcode('woo-product-caption-slider', 'woo_product_caption_slider');
