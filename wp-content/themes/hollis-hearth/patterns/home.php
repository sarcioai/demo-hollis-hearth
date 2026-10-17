<?php
/**
 * Title: Hollis & Hearth home
 * Slug: hollis-hearth/home
 * Inserter: no
 */

defined('ABSPATH') || exit;

$hollis_image = static fn (string $file): string => esc_url(get_theme_file_uri('assets/images/' . $file));
$hollis_categories = [
    ['Kitchen & table', 'Stoneware, boards and glass for everyday cooking and long dinners.', '/product-category/kitchen/', 'cat-kitchen.jpg'],
    ['Linens', 'Washed linen napkins, tea towels and throws that soften with every wash.', '/product-category/linens/', 'cat-linens.jpg'],
    ['Living', 'Vases, candles and the small things that make a room feel lived in.', '/product-category/living/', 'cat-living.jpg'],
];
?>
<!-- wp:cover {"url":"<?php echo $hollis_image('hero-shelves.jpg'); ?>","dimRatio":30,"overlayColor":"contrast","minHeight":580,"isDark":true,"align":"full","style":{"spacing":{"padding":{"top":"6rem","bottom":"6rem"}}},"layout":{"type":"constrained","contentSize":"1240px"}} -->
<div class="wp-block-cover alignfull is-dark" style="padding-top:6rem;padding-bottom:6rem;min-height:580px"><span aria-hidden="true" class="wp-block-cover__background has-contrast-background-color has-background-dim-30 has-background-dim"></span><img class="wp-block-cover__image-background" alt="Stoneware cups on open shelves" src="<?php echo $hollis_image('hero-shelves.jpg'); ?>" data-object-fit="cover"/><div class="wp-block-cover__inner-container">
<!-- wp:paragraph {"className":"hollis-kicker","textColor":"oat"} -->
<p class="hollis-kicker has-oat-color has-text-color">The autumn table</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":1,"textColor":"base","style":{"typography":{"fontSize":"clamp(2.6rem, 5vw, 4.5rem)","lineHeight":"1.05"}}} -->
<h1 class="wp-block-heading has-base-color has-text-color" style="font-size:clamp(2.6rem, 5vw, 4.5rem);line-height:1.05">Made by hand,<br>meant for every day.</h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"base","style":{"typography":{"fontSize":"1.2rem"}}} -->
<p class="has-base-color has-text-color" style="font-size:1.2rem">Stoneware, linens and candles from small makers across the Blue Ridge.</p>
<!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"backgroundColor":"base","textColor":"contrast"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-contrast-color has-base-background-color has-text-color has-background wp-element-button" href="<?php echo esc_url(home_url('/shop/')); ?>">Shop the collection</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div></div>
<!-- /wp:cover -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"4.5rem"}}},"layout":{"type":"constrained","contentSize":"1240px"}} -->
<div class="wp-block-group" style="padding-top:4.5rem">
<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"1.5rem"}}}} -->
<div class="wp-block-columns">
<?php foreach ($hollis_categories as [$hollis_title, $hollis_blurb, $hollis_path, $hollis_file]) : ?>
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:image {"sizeSlug":"large"} -->
<figure class="wp-block-image size-large"><a href="<?php echo esc_url(home_url($hollis_path)); ?>"><img src="<?php echo $hollis_image($hollis_file); ?>" alt="<?php echo esc_attr($hollis_title); ?>" style="border-radius:6px;aspect-ratio:4/3;object-fit:cover"/></a></figure>
<!-- /wp:image -->
<!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"1.45rem"}}} -->
<h3 class="wp-block-heading" style="font-size:1.45rem"><a href="<?php echo esc_url(home_url($hollis_path)); ?>" style="text-decoration:none"><?php echo esc_html($hollis_title); ?></a></h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"taupe"} -->
<p class="has-taupe-color has-text-color"><?php echo esc_html($hollis_blurb); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
<?php endforeach; ?>
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"4rem"}}},"layout":{"type":"constrained","contentSize":"1240px"}} -->
<div class="wp-block-group" style="padding-top:4rem">
<!-- wp:group {"layout":{"type":"flex","justifyContent":"space-between","flexWrap":"wrap"}} -->
<div class="wp-block-group">
<!-- wp:heading -->
<h2 class="wp-block-heading">New this season</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p><a href="<?php echo esc_url(home_url('/shop/')); ?>">Shop all →</a></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:woocommerce/product-collection {"queryId":21,"query":{"perPage":4,"pages":1,"offset":0,"postType":"product","order":"desc","orderBy":"date","search":"","exclude":[],"inherit":false,"taxQuery":[],"isProductCollectionBlock":true,"featured":true,"woocommerceOnSale":false,"woocommerceStockStatus":["instock","outofstock","onbackorder"],"woocommerceAttributes":[],"woocommerceHandPickedProducts":[]},"tagName":"div","displayLayout":{"type":"flex","columns":4,"shrinkColumns":true},"collection":"woocommerce/product-collection/featured","queryContextIncludes":["collection"]} -->
<div class="wp-block-woocommerce-product-collection">
<!-- wp:woocommerce/product-template -->
<!-- wp:woocommerce/product-image {"imageSizing":"thumbnail","isDescendentOfQueryLoop":true} /-->
<!-- wp:post-title {"level":3,"isLink":true,"style":{"typography":{"fontSize":"1.1rem"}},"__woocommerceNamespace":"woocommerce/product-collection/product-title"} /-->
<!-- wp:woocommerce/product-price {"isDescendentOfQueryLoop":true} /-->
<!-- wp:woocommerce/product-button {"isDescendentOfQueryLoop":true,"fontSize":"small"} /-->
<!-- /wp:woocommerce/product-template -->
</div>
<!-- /wp:woocommerce/product-collection -->
</div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"4.5rem"}}},"layout":{"type":"constrained","contentSize":"1240px"}} -->
<div class="wp-block-group" style="padding-top:4.5rem">
<!-- wp:media-text {"mediaType":"image","mediaWidth":50,"backgroundColor":"oat"} -->
<div class="wp-block-media-text is-stacked-on-mobile has-oat-background-color has-background" style="grid-template-columns:50% auto"><figure class="wp-block-media-text__media"><img src="<?php echo $hollis_image('about-shop.jpg'); ?>" alt="Inside the Hollis &amp; Hearth shop"/></figure><div class="wp-block-media-text__content">
<!-- wp:paragraph {"className":"hollis-kicker"} -->
<p class="hollis-kicker">On Lexington Avenue since 2015</p>
<!-- /wp:paragraph -->
<!-- wp:heading -->
<h2 class="wp-block-heading">A small shop with a long list of makers</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Nora Hollis opened the shop to sell her mother's pottery and a few friends' linens. Ten years on we work with thirty-one makers, most of them within a day's drive of Asheville, and we still pack every order ourselves.</p>
<!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url(home_url('/our-shop/')); ?>">Visit the shop</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div></div>
<!-- /wp:media-text -->
</div>
<!-- /wp:group -->
