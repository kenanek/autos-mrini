<?php
namespace App\Http\Controllers;
use App\Models\Vehicle;
use App\Models\Brand;
use App\Models\CarModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicVehicleController extends Controller
{
    /**
     * Apply all filters EXCEPT the one named $exclude.
     */
    public function applyFilters(Request $request, $query, ?string $exclude = null)
    {
        if ($exclude !== 'brand_id' && $request->filled('brand_id'))
            $query->where('brand_id', $request->brand_id);
        if ($exclude !== 'car_model_id' && $request->filled('car_model_id'))
            $query->where('car_model_id', $request->car_model_id);
        if ($exclude !== 'year_min' && $request->filled('year_min'))
            $query->where('year', '>=', $request->year_min);
        if ($exclude !== 'price_max' && $request->filled('price_max'))
            $query->where('price', '<=', $request->price_max);
        if ($exclude !== 'fuel_type' && $request->filled('fuel_type'))
            $query->where('fuel_type', $request->fuel_type);
        if ($exclude !== 'transmission' && $request->filled('transmission'))
            $query->where('transmission', $request->transmission);
        if ($request->filled('condition'))
            $query->where('condition', $request->condition);
        return $query;
    }

    /**
     * Compute cascading filter options with vehicle counts.
     */
    public function buildCascadingFilters(Request $request): array
    {
        // Brands with counts
        $bq = $this->applyFilters($request, Vehicle::published(), 'brand_id');
        $brandCounts = (clone $bq)->select('brand_id', DB::raw('count(*) as cnt'))
            ->groupBy('brand_id')->pluck('cnt', 'brand_id');
        $brands = Brand::whereIn('id', $brandCounts->keys())
            ->orderBy('name')->get(['id', 'name'])
            ->map(fn($b) => ['id' => $b->id, 'name' => $b->name, 'count' => $brandCounts[$b->id] ?? 0]);

        // Models with counts
        $mq = $this->applyFilters($request, Vehicle::published(), 'car_model_id');
        $modelCounts = (clone $mq)->select('car_model_id', DB::raw('count(*) as cnt'))
            ->groupBy('car_model_id')->pluck('cnt', 'car_model_id');
        $models = CarModel::whereIn('id', $modelCounts->keys())
            ->orderBy('name')->get(['id', 'name', 'brand_id'])
            ->map(fn($m) => ['id' => $m->id, 'name' => $m->name, 'brand_id' => $m->brand_id, 'count' => $modelCounts[$m->id] ?? 0]);

        // Years with counts
        $yq = $this->applyFilters($request, Vehicle::published(), 'year_min');
        $years = (clone $yq)->select('year', DB::raw('count(*) as cnt'))
            ->groupBy('year')->orderBy('year', 'desc')
            ->get()->map(fn($r) => ['value' => $r->year, 'count' => $r->cnt]);

        // Fuel types with counts
        $fq = $this->applyFilters($request, Vehicle::published(), 'fuel_type');
        $fuelRows = (clone $fq)->select('fuel_type', DB::raw('count(*) as cnt'))
            ->groupBy('fuel_type')->get()->filter(fn($r) => $r->fuel_type);
        $fuelLabels = ['gasoline' => 'Gasolina', 'diesel' => 'Diésel', 'hybrid' => 'Híbrido', 'electric' => 'Eléctrico'];
        $fuelTypes = $fuelRows->map(fn($r) => [
        'value' => $r->fuel_type,
        'label' => $fuelLabels[$r->fuel_type] ?? ucfirst($r->fuel_type),
        'count' => $r->cnt,
        ]);

        // Transmissions with counts
        $tq = $this->applyFilters($request, Vehicle::published(), 'transmission');
        $transRows = (clone $tq)->select('transmission', DB::raw('count(*) as cnt'))
            ->groupBy('transmission')->get()->filter(fn($r) => $r->transmission);
        $transLabels = ['manual' => 'Manual', 'automatic' => 'Automático'];
        $transmissions = $transRows->map(fn($r) => [
        'value' => $r->transmission,
        'label' => $transLabels[$r->transmission] ?? ucfirst($r->transmission),
        'count' => $r->cnt,
        ]);

        // Price range
        $pq = $this->applyFilters($request, Vehicle::published(), 'price_max');
        $maxPrice = (clone $pq)->max('price') ?: 100000;

        // Price steps with counts (how many vehicles at or below each step)
        $priceSteps = collect();
        $prices = [10000, 20000, 30000, 50000, 80000, 100000, 150000, 200000, 300000, 400000, 500000, 600000, 800000, 1000000, 2000000];
        foreach ($prices as $p) {
            $cnt = (clone $pq)->where('price', '<=', $p)->count();
            if ($cnt > 0) {
                $currency = \App\Models\Setting::getVal('currency', '€');
                $priceLabel = $currency === '€' ? number_format($p, 0, ',', '.') . ' €' : number_format($p, 0, ',', '.') . ' ' . $currency;
                $priceSteps->push(['value' => $p, 'label' => $priceLabel, 'count' => $cnt]);
            }
            
            if ($p >= $maxPrice) {
                break;
            }
        }

        return compact('brands', 'models', 'years', 'fuelTypes', 'transmissions', 'maxPrice', 'priceSteps');
    }

    public function index(Request $request)
    {
        $query = Vehicle::published()->with(['brand', 'carModel']);
        $this->applyFilters($request, $query);

        $vehicles = $query->latest('published_at')->paginate(12)->withQueryString();
        $filters = $this->buildCascadingFilters($request);
        $hasActiveFilters = $request->hasAny(['brand_id', 'car_model_id', 'year_min', 'fuel_type', 'transmission', 'price_max']);

        return view('vehicles.index', array_merge(compact('vehicles', 'hasActiveFilters'), $filters));
    }

    /**
     * AJAX endpoint — returns JSON with updated filter options + counts.
     */
    public function filterOptions(Request $request)
    {
        $f = $this->buildCascadingFilters($request);

        return response()->json([
            'brands' => $f['brands']->values(),
            'models' => $f['models']->values(),
            'years' => $f['years']->values(),
            'fuelTypes' => $f['fuelTypes']->values(),
            'transmissions' => $f['transmissions']->values(),
            'priceSteps' => $f['priceSteps']->values(),
        ]);
    }

    public function show($slug)
    {
        $vehicle = Vehicle::published()->where('slug', $slug)->with(['brand', 'carModel', 'images', 'features', 'customAttributes'])->firstOrFail();
        $vehicle->incrementViewCount();
        $relatedVehicles = Vehicle::published()->where('brand_id', $vehicle->brand_id)->where('id', '!=', $vehicle->id)->take(3)->get();
        return view('vehicles.show', compact('vehicle', 'relatedVehicles'));
    }
}