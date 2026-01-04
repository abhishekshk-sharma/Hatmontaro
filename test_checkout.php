<?php
// Simple test to check if checkout is accessible
echo "Testing checkout access...\n";

// Check if we can access the checkout route
$url = 'http://127.0.0.1:8000/checkout';
$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => 'User-Agent: Test Script'
    ]
]);

$response = file_get_contents($url, false, $context);
if ($response === false) {
    echo "Failed to access checkout URL\n";
} else {
    echo "Checkout URL accessible\n";
    if (strpos($response, 'login') !== false) {
        echo "Redirected to login page\n";
    } elseif (strpos($response, 'cart') !== false) {
        echo "Redirected to cart page\n";
    } else {
        echo "Checkout page loaded successfully\n";
    }
}