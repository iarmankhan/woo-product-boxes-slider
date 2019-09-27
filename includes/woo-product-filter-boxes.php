<?php
if (!defined('ABSPATH'))
    exit;

function woo_product_filter_boxes($attr)
{
    ob_start();
    if (!empty($attr['attributes'])) {
        $terms = get_terms('pa_' . $attr['attributes']);
    }
    $count = (!empty($attr['count'])) ? $attr['count'] : 5;
    ?>
    <div uk-filter="target: .js-filter">
        <?php if (!empty($terms)): ?>
            <ul class="uk-subnav uk-subnav-pill uk-margin">
                <li class="uk-active" uk-filter-control><a href="#">All</a></li>
                <?php foreach ($terms as $k => $t) {
                    //$class = ( $k==0 ) ? 'uk-active' : ''; ?>
                    <li class="<?php echo $class; ?>" uk-filter-control=".<?php echo $t->slug; ?>"><a
                                href="#"><?php echo $t->name; ?></a></li>
                <?php } ?>
            </ul>
        <?php endif; ?>

        <ul class="js-filter uk-child-width-1-1 uk-child-width-1-2@s uk-child-width-1-3@m uk-text-center" uk-grid>
            <?php
            $args = array(
                'post_type' => 'product',
                'posts_per_page' => $count,
            );
            $loop = new WP_Query($args);
            if ($loop->have_posts()) {
                $cnt = 0;
                while ($loop->have_posts()) : $loop->the_post();
                    $product = wc_get_product();
                    $image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'post-thumbnail');
                    $terms = get_the_terms(get_the_ID(), 'pa_color');
                    if (!empty($terms)) {
                        $terms_arr = wp_list_pluck($terms, 'slug');
                        $terms_string = implode(' ', $terms_arr);
                    } else
                        $terms_string = '';
                    ?>
                    <li class="<?php echo $terms_string; ?>">
                        <a href="<?php echo get_the_permalink(); ?>">
                            <div class="uk-cover-container uk-height-medium">
                                <img src="<?php echo $image[0]; ?>" alt="" uk-cover>
                            </div>
                        </a>
                        <div class="uk-card uk-card-default uk-card-body"><h3><?php echo get_the_title(); ?></h3>
                            <div class="uk-flex uk-flex-column">
                                <span class="uk-label uk-padding-small uk-margin"><?php echo $product->get_price_html(); ?></span>
                                <a class="uk-button uk-button-default"
                                   href="<?php $add_to_cart = do_shortcode('[add_to_cart_url id="' . get_the_ID() . '"]');
                                   echo $add_to_cart; ?>" class="more">Buy now</a></div>
                        </div>

                    </li>
                    <?php
                    $cnt++;
                endwhile;
            }
            wp_reset_postdata();
            ?>
        </ul>

    </div>
    <?php
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}

add_shortcode('woo-product-filter-boxes', 'woo_product_filter_boxes');
