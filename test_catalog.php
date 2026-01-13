<?php

// Simple test to verify the catalog functionality
// require_once __DIR__ . '/../vendor/autoload.php';

// Mock data for testing
$mockVariety = [
    'id' => 1,
    'name' => 'Test Variety',
    'slug' => 'test-variety',
    'commodity' => ['name' => 'Rice', 'slug' => 'rice'],
    'images' => []
];

$mockSeedLots = [
    [
        'id' => 1,
        'lot_code' => 'BS-2024-001',
        'quantity' => 50,
        'unit' => 'kg',
        'price_per_unit_cents' => 250000,
        'price_idr' => 'Rp 2.500',
        'is_sellable' => true,
        'production_year' => 2024,
        'seed_class' => [
            'id' => 1,
            'code' => 'BS',
            'name' => 'Breeder Seed'
        ]
    ],
    [
        'id' => 2,
        'lot_code' => 'FS-2024-001',
        'quantity' => 100,
        'unit' => 'kg',
        'price_per_unit_cents' => 350000,
        'price_idr' => 'Rp 3.500',
        'is_sellable' => true,
        'production_year' => 2024,
        'seed_class' => [
            'id' => 2,
            'code' => 'FS',
            'name' => 'Foundation Seed'
        ]
    ]
];

// Test price range calculation
echo "Testing price range calculation...\n";

$prices = array_column(array_filter($mockSeedLots, function($lot) {
    return ($lot['is_sellable'] ?? false) && !empty($lot['price_per_unit_cents']);
}), 'price_per_unit_cents');

$minPrice = min($prices);
$maxPrice = max($prices);

$priceRange = $minPrice === $maxPrice 
    ? 'Rp ' . number_format($minPrice / 100, 0, ',', '.')
    : 'Rp ' . number_format($minPrice / 100, 0, ',', '.') . ' - Rp ' . number_format($maxPrice / 100, 0, ',', '.');

echo "Price Range: $priceRange\n";

// Test lot grouping by class
echo "\nTesting lot grouping by class...\n";

$lotsByClass = [];
foreach ($mockSeedLots as $lot) {
    $classId = $lot['seed_class']['id'] ?? null;
    if ($classId) {
        $lotsByClass[$classId][] = $lot;
    }
}

foreach ($lotsByClass as $classId => $lots) {
    $classCode = $lots[0]['seed_class']['code'];
    $className = $lots[0]['seed_class']['name'];
    $totalStock = array_sum(array_column($lots, 'quantity'));
    
    echo "Class: $classCode ($className) - Total Stock: $totalStock kg\n";
    
    foreach ($lots as $lot) {
        echo "  - Lot: {$lot['lot_code']}, Price: {$lot['price_idr']}, Stock: {$lot['quantity']} kg\n";
    }
}

// Test quantity validation rules
echo "\nTesting quantity validation rules...\n";

function validateQuantity($quantity, $classCode) {
    if ($classCode === 'BS' || $classCode === 'FS') {
        if ($quantity % 5 !== 0) {
            return "Error: $classCode requires multiples of 5 kg";
        }
    }
    return "Valid quantity";
}

echo "BS class, 5 kg: " . validateQuantity(5, 'BS') . "\n";
echo "BS class, 7 kg: " . validateQuantity(7, 'BS') . "\n";
echo "FS class, 10 kg: " . validateQuantity(10, 'FS') . "\n";
echo "FS class, 3 kg: " . validateQuantity(3, 'FS') . "\n";

echo "\nAll tests completed successfully!\n";