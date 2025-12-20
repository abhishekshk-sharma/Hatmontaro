<?php
/**
 * Setup script for Cap Try-On Module
 * Run this file to set up the database and seed cap products
 */

echo "=== Cap Try-On Module Setup ===\n\n";

// Change to the project directory
chdir(__DIR__);

echo "Step 1: Running migration to add brand column...\n";
$output = shell_exec('php artisan migrate --path=database/migrations/2025_12_08_000000_add_brand_to_products_table.php 2>&1');
echo $output . "\n";

echo "Step 2: Seeding cap products with brands and colors...\n";
$output = shell_exec('php artisan db:seed --class=CapsProductSeeder 2>&1');
echo $output . "\n";

echo "Step 3: Clearing cache...\n";
$output = shell_exec('php artisan cache:clear 2>&1');
echo $output . "\n";

echo "\n=== Setup Complete! ===\n";
echo "Visit: http://localhost/cloth_ai/public/caps/tryon\n";
echo "Or run: php artisan serve\n";
echo "Then visit: http://127.0.0.1:8000/caps/tryon\n";
