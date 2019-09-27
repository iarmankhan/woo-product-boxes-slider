<?php
if (!defined('ABSPATH'))
    exit;

function woo_product_box($attr)
{
    ob_start();
    $count = (!empty($attr['count'])) ? $attr['count'] : 5;
    ?>
    <div class="uk-grid-small uk-grid-match uk-child-width-expand@s uk-text-center" uk-grid>
        <?php
        $n = (!empty($attr['column'])) ? $attr['column'] : 3;
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => $count
        );
        $loop = new WP_Query($args);
        if ($loop->have_posts()) {
            $cnt = 0;
            while ($loop->have_posts()) : $loop->the_post();
                $product = wc_get_product();
                $image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'post-thumbnail');
                if ($n == $cnt) {
                    echo '</div><div class="uk-grid-small uk-grid-match uk-child-width-expand@s uk-text-center" uk-grid>';
                    $cnt = 0;
                }
                ?>
                <div>
                    <a href="<?php echo get_the_permalink(); ?>">
                        <div class="uk-cover-container uk-height-medium">
                            <img src="<?php echo $image[0]; ?>" alt="" uk-cover>
                        </div>
                    </a>
                    <div class="uk-card uk-card-default uk-card-body"><h3><?php echo get_the_title(); ?></h3>
                        <p><?php echo wp_trim_words(get_the_content(), 20, '...'); ?></p>
                        <span class="uk-button uk-button-primary uk-margin"
                              style="pointer-events: none"><?php echo $product->get_price_html(); ?></span>
                        <a class="uk-button uk-button-default"
                           href="<?php $add_to_cart = do_shortcode('[add_to_cart_url id="' . get_the_ID() . '"]');
                           echo $add_to_cart; ?>" class="more">Buy now</a></div>
                </div>
                <?php
                $cnt++;
            endwhile;
        } else {
            echo __('No products found');
        }
        wp_reset_postdata();
        ?>
    </div><!--/.products-->
    <?php
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}

add_shortcode('woo-product-box', 'woo_product_box');