<?php
namespace App\Http\Controllers;
use App\Models\Vehicle;
use App\Models\Brand;
class HomeController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $publishedVehicles = Vehicle::published();
        $featuredVehicles = (clone $publishedVehicles)->featured()->with('brand')->take(6)->get();
        $recentVehicles = (clone $publishedVehicles)->latest('published_at')->with('brand')->take(6)->get();

        // Use the same robust filters & counts engine as the public search page
        $publicController = app(\App\Http\Controllers\PublicVehicleController::class);
        $filters = $publicController->buildCascadingFilters($request);

        // Fetch active hero media
        $heroMedia = \App\Models\HeroMedia::where('is_active', true)->orderBy('sorting_order')->get();

        return view('home', [
            'featuredVehicles' => $featuredVehicles,
            'recentVehicles' => $recentVehicles,
            'brands' => $filters['brands'],
            'years' => $filters['years'],
            'priceSteps' => $filters['priceSteps'],
            'heroMedia' => $heroMedia,
        ]);
    }
}