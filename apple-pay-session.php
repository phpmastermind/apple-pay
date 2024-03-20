<?php
require_once("config.php");

// Endpoint for merchant validation
//if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['validationURL'])) {
$validationURL = $_POST['validationURL']; // Get this from the Apple Pay client-side

// Replace 'your_merchant_id' and 'your_merchant_key' with your actual values
$merchantId = MERCHANT_IDENTIFIER;
$merchant_display_name = MERCHANT_DISPLAY_NAME;
$merchant_domain = MERCHANT_DOMAIN;

// Path to your Merchant Identity Certificate (replace with your actual path)
$certificatePath = APPLE_CERTIFICATE_FILE;
// Path to your Private Key (replace with your actual path)
$merchantKey = APPLE_CERTIFICATE_PRIVATE_KEY;
$sslkeypassword = APPLE_CERTIFICATE_PRIVATE_KEY_PASS;

// Apple Pay endpoint for payment sessions
$applePayEndpoint = 'https://apple-pay-gateway.apple.com/paymentservices/paymentSession';

// Payment request data
$paymentRequest = array(
    'merchantIdentifier' => $merchantId,
    'displayName' => $merchant_display_name,
    'initiative' => 'web',
    'initiativeContext' => $merchant_domain,
);

// Convert the payment request to JSON
$jsonData = json_encode($paymentRequest);

// Set cURL options
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $applePayEndpoint);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSLCERT, $certificatePath);
curl_setopt($ch, CURLOPT_SSLKEY, $merchantKey);
curl_setopt($ch, CURLOPT_SSLKEYPASSWD, $sslkeypassword);

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($jsonData),
    //'Authorization: Basic ' . base64_encode($merchantId . ':' . $merchantKey),
));

// Execute cURL session
$response = curl_exec($ch);

// Check for errors
if (curl_errno($ch)) {
    echo 'Curl error: ' . curl_error($ch);
}

// Close cURL session
curl_close($ch);

// Output the payment session response
//header('Content-Type: application/json');
echo $response;
exit;
//}
