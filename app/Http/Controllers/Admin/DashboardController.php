<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Inquiry;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_vehicles' => Vehicle::count(),
            'available_vehicles' => Vehicle::where('status', 'available')->count(),
            'sold_vehicles' => Vehicle::where('status', 'sold')->count(),
            'featured_vehicles' => Vehicle::where('is_featured', true)->count(),
            'total_brands' => Brand::count(),
            'new_inquiries' => Inquiry::where('status', 'new')->count(),
            'total_inquiries' => Inquiry::count(),
            'total_views' => Vehicle::sum('views_count'),
        ];

        $recentVehicles = Vehicle::with(['brand', 'carModel'])
            ->latest()
            ->take(5)
            ->get();

        $recentInquiries = Inquiry::with('vehicle')
            ->latest()
            ->take(5)
            ->get();

        $topViewed = Vehicle::with(['brand', 'carModel'])
            ->where('status', 'available')
            ->orderByDesc('views_count')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentVehicles', 'recentInquiries', 'topViewed'));
    }
}
