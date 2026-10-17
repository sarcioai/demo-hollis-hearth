<?php

declare(strict_types=1);

/**
 * Stock the Hollis & Hearth store: WooCommerce's own install, the store
 * settings, shipping and payment, the catalogue with its photos, the welcome
 * coupon, a few past orders, and the shop's pages. Run by setup.php once WooCommerce is active,
 * in a process of its own so WooCommerce has loaded the way it does on a
 * normal request.
 *
 *   php provision.php --dir=/var/www/wp
 *
 * Idempotent: a store already stocked at this version is left alone.
 */

const HOLLIS_STORE_VERSION = '2026.10.1';

$opts = getopt('', ['dir:']);
$dir = rtrim((string) ($opts['dir'] ?? ''), '/');
if ($dir === '' || !is_file($dir . '/wp-load.php')) {
    fwrite(STDERR, "[provision] error: --dir must point at the install\n");
    exit(1);
}

$_SERVER['HTTP_HOST'] = $_SERVER['HTTP_HOST'] ?? 'localhost';
$_SERVER['REQUEST_URI'] = '/';
$_SERVER['REQUEST_METHOD'] = 'GET';
define('WP_USE_THEMES', false);
require_once $dir . '/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

function say(string $message): void
{
    fwrite(STDERR, "[provision] $message\n");
}

if (!class_exists('WooCommerce')) {
    fwrite(STDERR, "[provision] error: WooCommerce is not active\n");
    exit(1);
}
if (get_option('hollis_store_version') === HOLLIS_STORE_VERSION) {
    say('store already stocked');
    exit(0);
}

wp_set_current_user(1);
WC_Install::install();

// --- the business ----------------------------------------------------------

say('store settings');
$settings = [
    'blogdescription' => 'Handmade homewares from the Blue Ridge',
    'blogname' => 'Hollis & Hearth',
    'woocommerce_allowed_countries' => 'specific',
    'woocommerce_calc_taxes' => 'no',
    'woocommerce_coming_soon' => 'no',
    'woocommerce_currency' => 'USD',
    'woocommerce_default_country' => 'US:NC',
    'woocommerce_default_customer_address' => 'base',
    'woocommerce_enable_coupons' => 'yes',
    'woocommerce_enable_guest_checkout' => 'yes',
    // Stock isn't tracked: WooCommerce's checkout-time stock reservation runs a
    // query the SQLite drop-in can't translate, which refuses every order.
    'woocommerce_manage_stock' => 'no',
    'woocommerce_enable_reviews' => 'no',
    'woocommerce_onboarding_profile' => ['skipped' => true],
    'woocommerce_ship_to_countries' => 'specific',
    'woocommerce_specific_allowed_countries' => ['US'],
    'woocommerce_specific_ship_to_countries' => ['US'],
    'woocommerce_store_address' => '58 Lexington Ave',
    'woocommerce_store_city' => 'Asheville',
    'woocommerce_store_pages_only' => 'no',
    'woocommerce_store_postcode' => '28801',
    'woocommerce_task_list_hidden' => 'yes',
    'woocommerce_show_marketplace_suggestions' => 'no',
    'woocommerce_allow_tracking' => 'no',
];
foreach ($settings as $name => $value) {
    update_option($name, $value);
}

// --- shipping and payment --------------------------------------------------

say('shipping and payment');
$existing = array_filter(WC_Shipping_Zones::get_zones(), static fn (array $zone): bool => $zone['zone_name'] === 'United States');
if ($existing === []) {
    $zone = new WC_Shipping_Zone();
    $zone->set_zone_name('United States');
    $zone->add_location('US', 'country');
    $zone->save();
    $flat = $zone->add_shipping_method('flat_rate');
    update_option('woocommerce_flat_rate_' . $flat . '_settings', ['cost' => '8', 'tax_status' => 'none', 'title' => 'Standard shipping (3–5 days)']);
    $free = $zone->add_shipping_method('free_shipping');
    update_option('woocommerce_free_shipping_' . $free . '_settings', ['ignore_discounts' => 'no', 'min_amount' => '85', 'requires' => 'min_amount', 'title' => 'Free shipping']);
}

// A demo store takes no money: one offline method, named for what it is.
update_option('woocommerce_cheque_settings', [
    'description' => 'This is a demo store: no payment is taken and nothing ships.',
    'enabled' => 'yes',
    'instructions' => 'Demo order: nothing will be charged or shipped.',
    'title' => 'Demo payment (no charge)',
]);

// --- catalogue -------------------------------------------------------------

say('categories');
$categories = [
    'kitchen' => ['Kitchen & table', 'Stoneware, boards and glass for everyday cooking and long dinners.'],
    'linens' => ['Linens', 'Washed linen napkins, tea towels and throws that soften with every wash.'],
    'living' => ['Living', 'Vases, candles and the small things that make a room feel lived in.'],
];
$categoryIds = [];
foreach ($categories as $slug => [$name, $description]) {
    $term = get_term_by('slug', $slug, 'product_cat');
    $categoryIds[$slug] = $term
        ? (int) $term->term_id
        : (int) wp_insert_term($name, 'product_cat', ['description' => $description, 'slug' => $slug])['term_id'];
}

$products = [
    ['stoneware-mug', 'kitchen', 'Speckled stoneware mug', '32.00', true, '12 oz · wheel-thrown in Black Mountain, NC',
        'Thrown by hand and dipped in our speckled oatmeal glaze, so no two are quite alike. A wide handle for two fingers and a heavy base that keeps coffee warm. Dishwasher and microwave safe.'],
    ['linen-napkins', 'linens', 'Washed linen napkins, set of 4', '48.00', true, '20 × 20 in · stone-washed European flax',
        'Stone-washed so they arrive soft and only get softer. Mitered corners and a generous size for long dinners. Machine wash warm, tumble dry low.'],
    ['linen-tea-towels', 'linens', 'Linen tea towels, pair', '28.00', false, '18 × 28 in · hanging loop',
        'Absorbent, lint-free and quick to dry. The towels we keep by our own sink.'],
    ['amber-candle', 'living', 'Hearth candle in amber glass', '36.00', true, '8 oz · cedar, clove and orange peel · 50-hour burn',
        'Poured in small batches in Weaverville from coconut-soy wax with a cotton wick. Our autumn scent: cedar, clove and a little orange peel.'],
    ['bud-vase-trio', 'living', 'Bud vase trio', '54.00', false, '3–5 in · unglazed exteriors',
        'Three small vases for a single stem each, in matching sand-coloured clay. Glazed inside so they hold water.'],
    ['stacked-stoneware-vase', 'living', 'Stacked stoneware vase', '58.00', true, '7 in · matte sand glaze',
        'Two rounded forms thrown separately and joined by hand, in a matte sand glaze. Holds a few garden roses or a single branch, and looks just as good empty.'],
    ['oatmeal-wool-throw', 'linens', 'Oatmeal wool throw', '165.00', false, '50 × 65 in · undyed wool',
        'Woven in North Carolina from undyed wool, with a soft brushed finish and fringed ends.'],
    ['mustard-wool-throw', 'linens', 'Mustard herringbone throw', '148.00', false, '50 × 65 in · wool blend',
        'A warm herringbone weave in late-autumn mustard. Heavy enough for a cold porch.'],
    ['cherry-cutting-board', 'kitchen', 'Cherry cutting board', '72.00', false, '16 × 11 in · finished with beeswax and oil',
        'Cut from a single piece of Appalachian cherry and finished with our own beeswax and walnut oil. Hand wash and oil now and then.'],
    ['glass-carafe', 'kitchen', 'Hand-blown glass carafe', '42.00', false, '1 litre · with tumbler lid',
        'Blown in Spruce Pine, with a tumbler that doubles as the lid. For water by the bed or wine on the table.'],
];

say('products');
foreach ($products as [$slug, $category, $name, $price, $featured, $short, $description]) {
    $sku = 'HH-' . strtoupper($slug);
    $existingId = wc_get_product_id_by_sku($sku);
    $product = $existingId ? wc_get_product($existingId) : new WC_Product_Simple();
    $product->set_name($name);
    $product->set_slug($slug);
    $product->set_sku($sku);
    $product->set_regular_price($price);
    $product->set_featured($featured);
    $product->set_short_description($short);
    $product->set_description($description);
    $product->set_category_ids([$categoryIds[$category]]);
    $product->set_manage_stock(false);
    $product->set_stock_status('instock');
    $product->set_status('publish');

    if (!$product->get_image_id()) {
        $tmp = wp_tempnam($slug . '.jpg');
        copy(__DIR__ . '/assets/products/' . $slug . '.jpg', $tmp);
        $imageId = media_handle_sideload(['name' => $slug . '.jpg', 'tmp_name' => $tmp], 0, $name);
        if (is_wp_error($imageId)) {
            say("image for $slug failed: " . $imageId->get_error_message());
        } else {
            $product->set_image_id($imageId);
        }
    }
    $product->save();
}

// --- the welcome coupon, and a few past orders -------------------------------

say('welcome coupon');
if (!wc_get_coupon_id_by_code('welcome10')) {
    $coupon = new WC_Coupon();
    $coupon->set_code('welcome10');
    $coupon->set_description('10% off a first order, from the site banner and the welcome email.');
    $coupon->set_discount_type('percent');
    $coupon->set_amount(10);
    $coupon->set_individual_use(true);
    $coupon->save();
}

// The store has been trading: a handful of guest orders from earlier in the year.
say('past orders');
if (wc_get_orders(['limit' => 1, 'return' => 'ids']) === []) {
    $past = [
        ['Ellen', 'Marsh', 'ellen.marsh@example.com', 'stoneware-mug', 2, '-41 days'],
        ['Tomás', 'Reyes', 'tomas.reyes@example.com', 'amber-candle', 1, '-23 days'],
        ['June', 'Park', 'june.park@example.com', 'linen-napkins', 1, '-9 days'],
    ];
    foreach ($past as [$first, $last, $email, $slug, $qty, $when]) {
        $order = wc_create_order(['customer_id' => 0]);
        $order->add_product(wc_get_product(wc_get_product_id_by_sku('HH-' . strtoupper($slug))), $qty);
        $address = ['first_name' => $first, 'last_name' => $last, 'email' => $email, 'address_1' => '12 Market St', 'city' => 'Asheville', 'state' => 'NC', 'postcode' => '28801', 'country' => 'US'];
        $order->set_address($address, 'billing');
        $order->set_address($address, 'shipping');
        $order->set_payment_method('cheque');
        $order->calculate_totals();
        $order->set_date_created(strtotime($when));
        $order->set_status('completed');
        $order->save();
    }
}

// --- pages -----------------------------------------------------------------

say('pages');
$pages = [
    'our-shop' => ['Our shop', <<<'HTML'
    <!-- wp:paragraph -->
    <p>Come and see us at 58 Lexington Avenue in downtown Asheville, open Tuesday to Sunday from 10am to 6pm. Most of what we sell is made by hand within a day's drive of the shop, by thirty-one makers we know by name.</p>
    <!-- /wp:paragraph -->
    HTML],
    'shipping-returns' => ['Shipping & returns', <<<'HTML'
    <!-- wp:paragraph -->
    <p>We pack every order ourselves and ship within two working days. Standard shipping is $8, and free on orders over $85. Not quite right? Send it back within 30 days for a full refund.</p>
    <!-- /wp:paragraph -->
    HTML],
];
foreach ($pages as $slug => [$title, $content]) {
    if (!get_page_by_path($slug)) {
        wp_insert_post(['post_content' => preg_replace('/^    /m', '', $content), 'post_name' => $slug, 'post_status' => 'publish', 'post_title' => $title, 'post_type' => 'page']);
    }
}

foreach (['hello-world' => 'post', 'sample-page' => 'page'] as $slug => $type) {
    $post = get_page_by_path($slug, OBJECT, $type);
    if ($post) {
        wp_delete_post($post->ID, true);
    }
}

flush_rewrite_rules(true);
update_option('hollis_store_version', HOLLIS_STORE_VERSION);
say('store stocked');
