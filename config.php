<?php
define('MERCHANT_IDENTIFIER', 'merchant.com.name');
define('MERCHANT_DISPLAY_NAME', 'company name');
define('MERCHANT_DOMAIN', 'merchant.com');

define('APPLE_CERTIFICATE_FILE', getcwd() . '/demo.crt.pem');
define('APPLE_CERTIFICATE_PRIVATE_KEY', getcwd() . '/demo.crt.pem');
define('APPLE_CERTIFICATE_PRIVATE_KEY_PASS', 'your password here');

// optional to send payment token to stripe merchant
define('STRIPE_API_KEY', 'your key here');
