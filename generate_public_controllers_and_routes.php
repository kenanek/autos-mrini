<?php
$files = [];

$files['app/Http/Controllers/HomeController.php'] = <<<'PHP'
<?php
namespace App\Http\Controllers;
use App\Models\Vehicle;
use App\Models\Brand;
class HomeController extends Controller {
    public function index() {
        $featuredVehicles = Vehicle::published()->featured()->with('brand')->take(6)->get();
        $recentVehicles = Vehicle::published()->latest('published_at')->with('brand')->take(6)->get();
        $brands = Brand::active()->has('vehicles')->take(6)->get();
        return view('home', compact('featuredVehicles', 'recentVehicles', 'brands'));
    }
}
PHP;

$files['app/Http/Controllers/PublicVehicleController.php'] = <<<'PHP'
<?php
namespace App\Http\Controllers;
use App\Models\Vehicle;
use App\Models\Brand;
use App\Models\CarModel;
use Illuminate\Http\Request;
class PublicVehicleController extends Controller {
    public function index(Request $request) {
        $query = Vehicle::published()->with(['brand', 'carModel']);
        if ($request->filled('brand_id')) $query->where('brand_id', $request->brand_id);
        if ($request->filled('car_model_id')) $query->where('car_model_id', $request->car_model_id);
        if ($request->filled('year_min')) $query->where('year', '>=', $request->year_min);
        if ($request->filled('year_max')) $query->where('year', '<=', $request->year_max);
        if ($request->filled('price_max')) $query->where('price', '<=', $request->price_max);
        if ($request->filled('condition')) $query->where('condition', $request->condition);
        if ($request->filled('fuel_type')) $query->where('fuel_type', $request->fuel_type);
        if ($request->filled('transmission')) $query->where('transmission', $request->transmission);

        $vehicles = $query->latest('published_at')->paginate(12)->withQueryString();
        $brands = Brand::active()->orderBy('name')->get();
        $models = CarModel::active()->orderBy('name')->get();

        return view('vehicles.index', compact('vehicles', 'brands', 'models'));
    }

    public function show($slug) {
        $vehicle = Vehicle::published()->where('slug', $slug)->with(['brand', 'carModel', 'images', 'features', 'customAttributes'])->firstOrFail();
        $vehicle->incrementViewCount();
        $relatedVehicles = Vehicle::published()->where('brand_id', $vehicle->brand_id)->where('id', '!=', $vehicle->id)->take(3)->get();
        return view('vehicles.show', compact('vehicle', 'relatedVehicles'));
    }
}
PHP;

$files['app/Http/Controllers/PageController.php'] = <<<'PHP'
<?php
namespace App\Http\Controllers;
class PageController extends Controller {
    public function about() { return view('pages.about'); }
    public function location() { return view('pages.location'); }
    public function financing() { return view('pages.financing'); }
}
PHP;

$files['app/Http/Controllers/ContactController.php'] = <<<'PHP'
<?php
namespace App\Http\Controllers;
use App\Models\Inquiry;
use Illuminate\Http\Request;
class ContactController extends Controller {
    public function index() { return view('pages.contact'); }
    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
            'type' => 'required|string',
            'vehicle_id' => 'nullable|exists:vehicles,id'
        ]);
        $data['status'] = 'new';
        Inquiry::create($data);
        return back()->with('success', 'Gracias por su mensaje. Nos pondremos en contacto con usted pronto.');
    }
}
PHP;

foreach ($files as $path => $content) {
    $fullPath = __DIR__ . '/' . $path;
    $dir = dirname($fullPath);
    if (!is_dir($dir))
        mkdir($dir, 0755, true);
    file_put_contents($fullPath, $content);
}

// Update routes/web.php
$routes = file_get_contents(__DIR__ . '/routes/web.php');
// Remove default welcome route
$routes = preg_replace('/Route::get\(\'\/\', function \(\) \{\s*return view\(\'welcome\'\);\s*\}\);/', '', $routes);

// Append public routes if not exists
if (strpos($routes, 'Route::get(\'/\', [App\Http\Controllers\HomeController::class') === false) {
    $publicRoutes = <<<'PHP'
// ── Public Routes ──────────────────────────────────────────────────────
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/vehiculos', [App\Http\Controllers\PublicVehicleController::class, 'index'])->name('vehicles.index');
Route::get('/vehiculo/{slug}', [App\Http\Controllers\PublicVehicleController::class, 'show'])->name('vehicles.show');

Route::get('/nosotros', [App\Http\Controllers\PageController::class, 'about'])->name('about');
Route::get('/ubicacion', [App\Http\Controllers\PageController::class, 'location'])->name('location');
Route::get('/financiacion', [App\Http\Controllers\PageController::class, 'financing'])->name('financing');

Route::get('/contacto', [App\Http\Controllers\ContactController::class, 'index'])->name('contact');
Route::post('/contacto', [App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');

PHP;
    $routes = str_replace("Route::get('login',", $publicRoutes . "\nRoute::get('login',", $routes);
    file_put_contents(__DIR__ . '/routes/web.php', $routes);
}

echo "Controllers and Routes generated successfully.\n";
