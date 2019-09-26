<?php
if (!defined('ABSPATH'))
    exit;

function woo_product_slider($attr)
{
    ob_start();
    $n = (!empty($attr['number'])) ? $attr['number'] : 3;
    $count = (!empty($attr['count'])) ? $attr['count'] : 5;
    $autoplay = (!empty($attr['autoplay'])) ? $attr['autoplay'] : 'false';
    $interval = (!empty($attr['interval'])) ? $attr['interval'] : 3000;
    ?>

    <div class="uk-position-relative uk-visible-toggle uk-light" tabindex="-1"
         uk-slider="autoplay: <?php echo $autoplay; ?>; autoplay-interval: <?php echo $interval; ?>">

        <ul class="uk-slider-items uk-child-width-1-1 uk-child-width-1-2@s uk-child-width-1-<?php echo $n; ?>@m slider-3">
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
                    <li>
                        <div class="uk-height-medium uk-flex uk-flex-center uk-flex-middle uk-background-cover uk-light slider-3 slider-overlay noselect"
                             data-src="<?php echo $image[0]; ?>" uk-img>
                            <div class="uk-flex uk-flex-center uk-flex-middle uk-flex-column">
                                <h1><?php echo $title; ?></h1>
                                <a class="uk-button uk-button-secondary uk-margin-remove"
                                   href="<?php echo get_permalink(); ?>">View Product</a>
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
    <?php
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}

add_shortcode('woo-product-slider', 'woo_product_slider');
