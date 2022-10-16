<?php

return
[

    'shipping' => [],
    
    /*
     * Confirmation email address to send orders to
     */
    'confirmation_email' => 'info@example.com',

	/*
	 * The default shipping price to use
	 */
    'shipping_price' => 9.95,

    /*
     * Array of product post_ids that have free shipping
     */
    'free_shipping_products' =>
    [

    ],

    /*
     * Threshold for free shipping
     */
    'free_shipping_threshold' => 99,

    /*
     * 1 Dollar = x loyalty points
     */
    'dollars_to_points' => 0.5,

    /*
     * 100 loyalty point = x% discount
     */
    'points_to_discount' => 10,

    /*
     * URL to tracking website
     */
    'tracking_url' => 'https://auspost.com.au/mypost/track/#/search',

    /*
     * Address for invoices
     */
    'company_address' => '<strong>Powered By Serve CMS</strong><br>1 City Road<br>Melbourne, VIC 3148<br>AUSTRALIA',

    /*
	 * Array of coupons - COUPON_CODE => Discount as percentage
	 */
    'coupons' =>
    [
    ],

    'stripe' =>
    [
        'api_key' => 'foobar',
        'secret'  => 'foobaz',
        'webhook_secret' => 'foo'
    ],

];
