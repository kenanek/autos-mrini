<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$v = \App\Models\Vehicle::whereHas('brand', function($q) {
  $q->where('name', 'Audi');
})->first();
if ($v) {
  echo "Brand: " . $v->brand->name . " (ID: " . $v->brand_id . ")\n";
  echo "Year: " . $v->year . "\n";
  echo "Price: " . $v->price . "\n";
} else {
  echo "No Audi found";
}
