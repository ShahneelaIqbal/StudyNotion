<?php
require 'vendor/autoload.php'; // Include Stripe PHP library (Install via Composer: composer require stripe/stripe-php)

// Set your Stripe Secret Key
\Stripe\Stripe::setApiKey('sk_test_51QaGP5R1E4g3P8PbR40SwkUd67ppdrPgCU9guf15ZSZ7G12P3458YsVOIgrP0P74gSAcaZQjYNTo8SDGJZUmp5rj00oiH5vmbF'); // Replace with your Stripe Secret Key

header("Access-Control-Allow-Origin: http://localhost");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json'); // Ensure JSON response


// Parse JSON input
$input = file_get_contents('php://input');
$data = json_decode($input, true); // Decode JSON into an associative array

// Validate input
if (!isset($data['payment_method_id'], $data['price'], $data['currency'], $data['email'], $data['customer_name'])) {
    echo json_encode(['success' => false, 'error' => 'Missing required payment fields.']);
    exit;
}

// Extract values
$paymentMethodId = $data['payment_method_id'];
$price = $data['price'];
$currency = $data['currency'];
$email = $data['email'];
$customerName = $data['customer_name'];

// Proceed with payment processing
echo json_encode(['success' => true, 'payment_method_id' => $paymentMethodId]);

try {
    // Retrieve JSON data from the POST request
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Extract required fields from the data
    $paymentMethodId = $data['payment_method_id'];
    $amount = $data['price']; // Assuming price is passed in the request
    $currency = $data['currency']; // E.g., 'usd'
    $email = $data['email'];
    $customerName = $data['customer_name'];

    if (!$paymentMethodId || !$amount || !$currency || !$email || !$customerName) {
        throw new Exception('Missing required payment fields.');
    }

    // Create a PaymentIntent with the provided data
    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => intval($amount * 100), // Convert amount to cents (e.g., $10 = 1000)
        'currency' => $currency,
        'payment_method' => $paymentMethodId,
        'confirmation_method' => 'manual',
        'confirm' => true,
        'receipt_email' => $email,
        'description' => "Payment from $customerName",
    ]);

    // Respond with success and the payment ID
    echo json_encode(['success' => true, 'payment_id' => $paymentIntent->id]);
} catch (\Stripe\Exception\ApiErrorException $e) {
    // Handle Stripe API errors
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} catch (Exception $e) {
    // Handle other errors
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
