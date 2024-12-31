<?php
// Include Stripe PHP library
require_once 'vendor/autoload.php';  // Only if you're using Composer. Otherwise, include Stripe's PHP SDK.

\Stripe\Stripe::setApiKey('sk_test_51QaGP5R1E4g3P8Pbki5DgaFXVrfB2hS4aV9lAv1JRhZLkzgRw4eRbWZaNH3WepYM7e0LDEeoTJ2gMo3ICiHXDJBp00YqscFooG'); // Your secret Stripe API key

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get POST data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate the required parameters
    $requiredParams = ['price', 'currency', 'description', 'customer_name', 'email'];
    foreach ($requiredParams as $param) {
        if (empty($data[$param])) {
            echo json_encode(['error' => 'Missing parameter: ' . $param]);
            exit;
        }
    }

    try {
        // Create a payment intent with Stripe
        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => $data['price'] * 100, // Price in cents (Stripe expects the amount in cents)
            'currency' => $data['currency'],
            'description' => $data['description'],
            'receipt_email' => $data['email'],
        ]);

        // Send the client secret to the frontend to complete the payment
        echo json_encode([
            'clientSecret' => $paymentIntent->client_secret
        ]);

    } catch (\Stripe\Exception\ApiErrorException $e) {
        // Handle errors
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid request method.']);
}
?>
