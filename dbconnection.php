<?php
namespace Phppot;

// Database connection setup
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "studypool_clone";

// Create connection
$conn = new \mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

class Config
{
    // Stripe API keys and URLs
    const STRIPE_PUBLISHABLE_KEY = 'pk_test_51QaGP5R1E4g3P8Pbki5DgaFXVrfB2hS4aV9lAv1JRhZLkzgRw4eRbWZaNH3WepYM7e0LDEeoTJ2gMo3ICiHXDJBp00YqscFooG';
    const CREATE_STRIPE_ORDER = 'http://localhost/studypool_clone/create-stripe-order';
    const THANKYOU_URL = 'http://localhost/studypool_clone/thank-you';

    // Function to get supported currencies
    public static function getCurrency()
    {
        return [
            'USD' => 'United States Dollar',  // US Dollar
            'EUR' => 'Euro', 
            'PKR' => 'Pakistani Rupees'                
        ];
    }

    // Function to get all supported countries using ISO 3166-1 alpha-2 country codes
    public static function getAllCountry()
    {
        return [
            'US' => 'United States',      // United States (ISO Code: US)
            'FR' => 'France',             // France (ISO Code: FR)
            'PKR' => 'Pakistan'
        ];
    }

}
?>
