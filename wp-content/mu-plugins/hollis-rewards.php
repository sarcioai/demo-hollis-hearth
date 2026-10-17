<?php
/**
 * Plugin Name: Hollis Rewards
 * Description: Reward points for Hollis & Hearth: customers earn points on every order and can spend them at checkout.
 * Version: 2.2.0
 * Author: Hollis & Hearth
 */

declare(strict_types=1);

use Automattic\WooCommerce\StoreApi\Exceptions\RouteException;

defined('ABSPATH') || exit;

/**
 * Record how many points the customer spends on this order.
 *
 * 2.2 moved the "Use my points" box into the checkout form as `redeem_points`
 * and made it required, but the box only renders in the new checkout template,
 * which isn't live yet, so the block checkout never sends it and every order is
 * refused.
 */
add_action('woocommerce_store_api_checkout_update_order_from_request', static function (WC_Order $order, WP_REST_Request $request): void {
    $points = $request->get_param('redeem_points');
    if ($points === null || $points === '') {
        throw new RouteException(
            'hollis_rewards_missing_points',
            'Please choose how many reward points to redeem (redeem_points is required).',
            400,
        );
    }
    $order->update_meta_data('_hollis_points_redeemed', max(0, (int) $points));
}, 10, 2);
