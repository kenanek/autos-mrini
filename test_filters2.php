<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$v = \App\Models\Vehicle::find(1); // Assuming Audi is vehicle ID 1 or we can search
$v = \App\Models\Vehicle::where('brand_id', 1)->first();
echo "Brand: " . $v->brand->name . "\n";
echo "Year: " . $v->year . "\n";
echo "Price: " . $v->price . "\n";
