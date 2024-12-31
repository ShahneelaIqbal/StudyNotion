<?php
namespace phppot;

use Stripe\Stripe;
use Stripe\WebhookEndpoint;

// Autoload dependencies
require_once __DIR__ . '\vendor\autoload.php';

use Phppot\StripePayment;

// Set API key
\Stripe\Stripe::setApiKey('sk_test_51QaGP5R1E4g3P8PbR40SwkUd67ppdrPgCU9guf15ZSZ7G12P3458YsVOIgrP0P74gSAcaZQjYNTo8SDGJZUmp5rj00oiH5vmbF');

// Add CORS headers
header("Access-Control-Allow-Origin: http://localhost");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

// Check for preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Capture the incoming JSON payload
$json = file_get_contents("php://input");
$file = fopen("app.log", "a");
fwrite($file, $json);

$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;

try {
    $event = \Stripe\Webhook::constructEvent($json, $sig_header, "whsec_xa6bHUTrMrY3N7NwgIvBe6nIXMgt6JXq");
} catch (\UnexpectedValueException $e) {
    // Invalid payload
    http_response_code(400);
    exit();
} catch (\Stripe\Exception\SignatureVerificationException $e) {
    // Invalid signature
    http_response_code(400);
    exit();
}

if (!empty($event)) {
    $eventType = $event->type;
    fwrite($file, json_encode($event));

    $orderId = $event->data->object->metadata->order_id ?? null;
    $email = $event->data->object->metadata->email ?? null;
    $paymentIntentId = $event->data->object->id;
    $amount = $event->data->object->amount;
    $stripePaymentStatus = $event->data->object->status;

    if ($eventType === "payment_intent.payment_failed") {
        $orderStatus = 'Payment Failure';
        $paymentStatus = 'Unpaid';
        $amount = $amount / 100;

        require_once __DIR__ . '/../lib/StripePayment.php';
        $stripePayment = new StripePayment();
        $stripePayment->updateOrder($paymentIntentId, $orderId, $orderStatus, $paymentStatus, $stripePaymentStatus, $event);
    }

    if ($eventType === "payment_intent.succeeded") {
        $orderStatus = 'Completed';
        $paymentStatus = 'Paid';
        $amount = $amount / 100;

        require_once __DIR__ . '/../lib/StripePayment.php';
        $stripePayment = new StripePayment();
        $stripePayment->updateOrder($paymentIntentId, $orderId, $orderStatus, $paymentStatus, $stripePaymentStatus, $event);

        http_response_code(200);
    }
}
?>
