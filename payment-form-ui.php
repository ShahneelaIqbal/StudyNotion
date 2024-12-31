<?php
namespace Phppot;

require_once __DIR__ . '/dbConnection.php'; // Correct file path for dbConnection.php
use Phppot\Config; // Use the Config class
header("Access-Control-Allow-Origin: http://localhost");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

$currencies = Config::getCurrency();
$country = Config::getAllCountry();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stripe Payment Integration</title>
    </head>
<body>
    <h1>Stripe Payment Integration via Custom Form</h1>
    <div class="phppot-container">
        <form id="payment-form">
        <div class="row">
                <div class="label">
                    Name <span class="error-msg" id="name-error"></span>
                </div>
                <input type="text" name="customer_name" class="input-box" id="customer_name">
            </div>

            <div class="row">
                <div class="label">
                    Email <span class="error-msg" id="email-error"></span>
                </div>
                <input type="text" name="email" class="input-box" id="email">
            </div>

            <div class="row">
                <div class="label">
                    Address <span class="error-msg" id="address-error"></span>
                </div>
                <input type="text" name="address" class="input-box" id="address">
            </div>

            <div class="row">
                <div class="label">
                    Country <span class="error-msg" id="country-error"></span>
                </div>
                <input list="country-list" name="country" class="input-box" id="country">
                <datalist id="country-list">
                    <?php foreach ($country as $key => $val) { ?>
                        <option value="<?php echo $key;?>"><?php echo $val;?></option>
                    <?php } ?>
                </datalist>
            </div>

            <div class="row">
                <div class="label">
                    Postal code <span class="error-msg" id="postal-error"></span>
                </div>
                <input type="text" name="postal_code" class="input-box" id="postal_code">
            </div>

            <div class="row">
                <div class="label">
                    Description <span class="error-msg" id="notes-error"></span>
                </div>
                <input type="text" name="notes" class="input-box" id="notes">
            </div>

            <div class="row">
                <div class="label">
                    Amount <span class="error-msg" id="price-error"></span>
                </div>
                <input type="text" name="price" class="input-box" id="price">
            </div>

            <div class="row">
                <div class="label">
                    Currency <span class="error-msg" id="currency-error"></span>
                </div>
                <input list="currency-list" name="currency" class="input-box" id="currency">
                <datalist id="currency-list">
                    <?php foreach ($currencies as $key => $val) { ?>
                        <option value="<?php echo $key;?>"><?php echo $val;?></option>
                    <?php } ?>
                </datalist>
            </div>

            <div class="row">
                <div id="card-element">
                    <!-- Stripe.js injects the Card Element -->
                </div>
            </div>

            <div class="row">
                <button type="submit" class="btnAction" id="btn-payment">
                    <div class="spinner hidden" id="spinner"></div>
                    <span id="button-text">Send Payment</span>
                </button>
                <p id="card-error" role="alert"></p>
            </div>
        </form>

        <?php if (!empty($_GET["action"]) && $_GET["action"] == "success") { ?>
            <div class="success">Thank you for the payment.</div>
        <?php } ?>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script src="./assets/js/card.js"></script>
</body>
</html>
