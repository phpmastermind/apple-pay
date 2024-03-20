<?php
require_once('vendor/autoload.php'); // Path to Stripe PHP library
require_once("config.php");

\Stripe\Stripe::setApiKey(STRIPE_API_KEY);

// Assuming you have received the Apple payment token and other necessary data
$paymentToken = $_POST['payment-token'];
$amount = $_POST['amount'];; // Amount in cents (e.g., $10.00)

try {
    // Create a charge using the Apple payment token
    $charge = \Stripe\Charge::create([
        'amount' => $amount,
        'currency' => 'usd',
        'source' => $paymentToken,
    ]);

    // Capture the payment
    $capturedCharge = \Stripe\Charge::capture($charge->id);

    // Payment captured successfully
    $status = array('status' => 'success', 'payment token' => $capturedCharge);
} catch (Exception $e) {
    // Handle errors
    $status = array('status' => 'failed', 'error' => $e->getMessage());
    //echo 'Error processing payment: ' . $e->getMessage();
}

echo json_encode($status);
exit;
