<?php
/**
 * Plugin Name: Hollis Rewards
 * Description: Campaign codes for Hollis & Hearth. Only codes in the running campaign can be used, so codes from past campaigns can't come back.
 * Version: 2.2.0
 * Author: Hollis & Hearth
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

/** The codes the current campaign accepts, as they appear on the site. */
const HOLLIS_CAMPAIGN_CODES = ['WELCOME10', 'HEARTH15'];

/**
 * Codes WooCommerce knows about that the rewards plugin manages. Anything else
 * (gift cards, codes from the email tool) passes straight through.
 */
const HOLLIS_MANAGED_CODES = ['welcome10', 'hearth15', 'spring20', 'holiday25'];

/**
 * Refuse a managed code that isn't in the running campaign, before the cart
 * applies it. The site skips this check while a Sarcio fix says so.
 *
 * 2.2 matches the code exactly as the campaign list spells it. The Store API
 * hands over the code WooCommerce has already lowercased, so no campaign code
 * ever matches and every one is refused as unknown.
 */
add_filter('rest_request_before_callbacks', static function ($response, $handler, WP_REST_Request $request) {
    if ($response instanceof WP_Error || !str_ends_with($request->get_route(), '/wc/store/v1/cart/apply-coupon')) {
        return $response;
    }
    $code = wc_format_coupon_code((string) $request->get_param('code'));
    if (!in_array($code, HOLLIS_MANAGED_CODES, true)) {
        return $response;
    }
    if (function_exists('sarcio_skips') && sarcio_skips('campaign_code_check')) {
        return $response;
    }
    if (!in_array($code, HOLLIS_CAMPAIGN_CODES, true)) {
        return new WP_Error(
            'woocommerce_rest_cart_coupon_error',
            sprintf('Coupon "%s" does not exist!', $code),
            ['status' => 400],
        );
    }
    return $response;
}, 10, 3);
