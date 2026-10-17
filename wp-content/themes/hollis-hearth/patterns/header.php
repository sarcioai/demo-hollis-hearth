<?php
/**
 * Title: Hollis & Hearth header
 * Slug: hollis-hearth/header
 * Inserter: no
 */

defined('ABSPATH') || exit;

$hollis_links = [
    'Shop all' => home_url('/shop/'),
    'Kitchen & table' => home_url('/product-category/kitchen/'),
    'Linens' => home_url('/product-category/linens/'),
    'Living' => home_url('/product-category/living/'),
    'Our shop' => home_url('/our-shop/'),
];
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"0.5rem","bottom":"0.5rem"}}},"backgroundColor":"oat","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-oat-background-color has-background" style="padding-top:0.5rem;padding-bottom:0.5rem">
<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"0.82rem","letterSpacing":"0.04em"}}} -->
<p class="has-text-align-center" style="font-size:0.82rem;letter-spacing:0.04em">New here? Take 10% off your first order with <strong>WELCOME10</strong> · Free shipping over $85</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"1.4rem","bottom":"1.4rem"}}},"layout":{"type":"constrained","contentSize":"1240px"}} -->
<div class="wp-block-group" style="padding-top:1.4rem;padding-bottom:1.4rem">
<!-- wp:group {"layout":{"type":"flex","justifyContent":"space-between","flexWrap":"wrap"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"fontFamily":"vollkorn","style":{"typography":{"fontSize":"1.75rem","fontWeight":"500","lineHeight":"1","letterSpacing":"0.01em"}}} -->
<p class="has-vollkorn-font-family" style="font-size:1.75rem;font-weight:500;letter-spacing:0.01em;line-height:1"><a href="<?php echo esc_url(home_url('/')); ?>" style="text-decoration:none">Hollis <em style="color:var(--wp--preset--color--clay)">&amp;</em> Hearth</a></p>
<!-- /wp:paragraph -->
<!-- wp:group {"style":{"spacing":{"blockGap":"1.6rem"}},"layout":{"type":"flex","flexWrap":"wrap"}} -->
<div class="wp-block-group">
<?php foreach ($hollis_links as $hollis_label => $hollis_url) : ?>
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.92rem","fontWeight":"500"}}} -->
<p style="font-size:0.92rem;font-weight:500"><a href="<?php echo esc_url($hollis_url); ?>" style="text-decoration:none"><?php echo esc_html($hollis_label); ?></a></p>
<!-- /wp:paragraph -->
<?php endforeach; ?>
<!-- wp:woocommerce/customer-account {"displayStyle":"icon_only","iconStyle":"line"} /-->
<?php
// WooCommerce hides the mini cart on the cart and checkout pages but still
// loads its script there, without the store settings it needs, so it requests
// `undefinedwc/store/v1/cart`. Leave it off those pages instead.
if (!is_cart() && !is_checkout()) :
    ?>
<!-- wp:woocommerce/mini-cart {"addToCartBehaviour":"open_drawer"} /-->
<?php endif; ?>
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
