<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Find VW vehicles
$vws = \App\Models\Vehicle::published()->whereHas('brand', function($q) {
    $q->where('name', 'like', '%Volkswagen%');
})->get();

foreach ($vws as $v) {
    echo "ID: {$v->id} | Brand: {$v->brand->name} | Year: {$v->year} | Price: {$v->price}\n";
}

echo "\n--- Testing with brand + year filter ---\n";
$req = \Illuminate\Http\Request::create('/', 'GET', ['brand_id' => $vws->first()->brand_id ?? '', 'year_min' => '2023']);
$c = app(\App\Http\Controllers\PublicVehicleController::class);
$pq = $c->applyFilters($req, \App\Models\Vehicle::published(), 'price_max');
echo "Vehicles matching brand+year (excluding price): " . (clone $pq)->count() . "\n";
echo "Max price: " . (clone $pq)->max('price') . "\n";

foreach ([10000, 20000, 30000, 50000, 80000, 100000, 150000, 200000, 300000, 500000] as $p) {
    $cnt = (clone $pq)->where('price', '<=', $p)->count();
    echo "  <= {$p}: {$cnt}\n";
}
