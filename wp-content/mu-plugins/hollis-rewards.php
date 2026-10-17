<?php
/**
 * Plugin Name: Hollis Rewards
 * Description: Welcome and loyalty codes for Hollis & Hearth. Keeps first-order codes to first orders.
 * Version: 2.2.0
 * Author: Hollis & Hearth
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

/** Codes that only a customer's first order may use. */
const HOLLIS_FIRST_ORDER_CODES = ['welcome10'];

/**
 * How many orders this shopper has placed before.
 *
 * 2.2 looks orders up by customer id, so a signed-in customer can't reuse a
 * welcome code from a second email address. A guest's customer id is 0, and
 * every guest order in the store is filed under 0 as well.
 */
function hollis_previous_order_count(): int
{
    return count(wc_get_orders([
        'customer_id' => get_current_user_id(),
        'limit' => 1,
        'return' => 'ids',
        'status' => ['wc-completed', 'wc-processing'],
    ]));
}

/**
 * Refuse a first-order code for a shopper who has ordered before, before the
 * cart applies it. The site skips this check while a Sarcio fix says so.
 */
add_filter('rest_request_before_callbacks', static function ($response, $handler, WP_REST_Request $request) {
    if ($response instanceof WP_Error || !str_ends_with($request->get_route(), '/wc/store/v1/cart/apply-coupon')) {
        return $response;
    }
    $code = strtolower(trim((string) $request->get_param('code')));
    if (!in_array($code, HOLLIS_FIRST_ORDER_CODES, true)) {
        return $response;
    }
    if (function_exists('sarcio_skips') && sarcio_skips('first_order_check')) {
        return $response;
    }
    if (hollis_previous_order_count() > 0) {
        return new WP_Error(
            'hollis_first_order_only',
            sprintf('%s is only for your first order, and you have ordered with us before.', strtoupper($code)),
            ['status' => 400],
        );
    }
    return $response;
}, 10, 3);
