<?php
$files = [];

// ============ BRAND ============
$files['app/Http/Controllers/Admin/BrandController.php'] = <<<'PHP'
<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index() {
        $brands = Brand::withCount('models', 'vehicles')->orderBy('name')->paginate(10);
        return view('admin.brands.index', compact('brands'));
    }
    public function create() {
        return view('admin.brands.create');
    }
    public function store(Request $request) {
        $data = $request->validate(['name'=>'required|string|max:255|unique:brands', 'country'=>'nullable|string', 'description'=>'nullable|string']);
        $data['is_active'] = $request->has('is_active');
        Brand::create($data);
        return redirect()->route('admin.brands.index')->with('success', 'Marque créée avec succès.');
    }
    public function edit(Brand $brand) {
        return view('admin.brands.edit', compact('brand'));
    }
    public function update(Request $request, Brand $brand) {
        $data = $request->validate(['name'=>'required|string|max:255|unique:brands,name,'.$brand->id, 'country'=>'nullable|string', 'description'=>'nullable|string']);
        $data['is_active'] = $request->has('is_active');
        $brand->update($data);
        return redirect()->route('admin.brands.index')->with('success', 'Marque modifiée avec succès.');
    }
    public function destroy(Brand $brand) {
        $brand->delete();
        return redirect()->route('admin.brands.index')->with('success', 'Marque supprimée.');
    }
}
PHP;

$files['resources/views/admin/brands/index.blade.php'] = <<<'HTML'
@extends('admin.layouts.app')
@section('title', 'Gestion des Marques')
@section('breadcrumb', 'Gestion / Marques')
@section('content')
<div class="page-header">
    <h1 class="page-title">Marques</h1>
    <a href="{{ route('admin.brands.create') }}" class="btn">Ajouter</a>
</div>
<div class="card"><div class="card-body">
    <table class="table">
        <thead><tr><th>Nom</th><th>Pays</th><th>Statut</th><th>Actions</th></tr></thead>
        <tbody>
            @foreach($brands as $brand)
            <tr>
                <td style="font-weight: 600;">{{ $brand->name }}</td>
                <td>{{ $brand->country ?? '-' }}</td>
                <td>@if($brand->is_active) <span class="badge badge-success">Actif</span> @else <span class="badge badge-danger">Inactif</span> @endif</td>
                <td>
                    <a href="{{ route('admin.brands.edit', $brand->id) }}" class="btn btn-sm btn-outline">Éditer</a>
                    <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm" style="background:#ef4444;color:white;border:none;">Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="margin-top:20px;">{{ $brands->links() }}</div>
</div></div>
@endsection
HTML;

$files['resources/views/admin/brands/create.blade.php'] = <<<'HTML'
@extends('admin.layouts.app')
@section('title', 'Ajouter une Marque')
@section('breadcrumb', 'Marques / Ajouter')
@section('content')
<div class="card" style="max-width: 600px;">
    <div class="card-header"><h3 class="card-title">Ajouter une Marque</h3></div>
    <div class="card-body">
        <form action="{{ route('admin.brands.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 20px;"><label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Nom de la marque *</label>
            <input type="text" name="name" required style="width:100%;padding:10px;border-radius:8px;border:1px solid var(--border-color);background:var(--glass-bg);color:var(--text-color);"></div>
            <div style="margin-bottom: 20px;"><label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Pays</label>
            <input type="text" name="country" style="width:100%;padding:10px;border-radius:8px;border:1px solid var(--border-color);background:var(--glass-bg);color:var(--text-color);"></div>
            <div style="margin-bottom: 20px;"><label style="display:flex;align-items:center;gap:10px;"><input type="checkbox" name="is_active" value="1" checked> Marque active</label></div>
            <button type="submit" class="btn">Enregistrer</button>
        </form>
    </div>
</div>
@endsection
HTML;

$files['resources/views/admin/brands/edit.blade.php'] = <<<'HTML'
@extends('admin.layouts.app')
@section('title', 'Éditer une Marque')
@section('breadcrumb', 'Marques / Éditer')
@section('content')
<div class="card" style="max-width: 600px;">
    <div class="card-header"><h3 class="card-title">Éditer une Marque</h3></div>
    <div class="card-body">
        <form action="{{ route('admin.brands.update', $brand->id) }}" method="POST">
            @csrf @method('PUT')
            <div style="margin-bottom: 20px;"><label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Nom *</label>
            <input type="text" name="name" value="{{ $brand->name }}" required style="width:100%;padding:10px;border-radius:8px;border:1px solid var(--border-color);background:var(--glass-bg);color:var(--text-color);"></div>
            <div style="margin-bottom: 20px;"><label style="display:flex;align-items:center;gap:10px;"><input type="checkbox" name="is_active" value="1" @if($brand->is_active) checked @endif> Active</label></div>
            <button type="submit" class="btn">Mettre à jour</button>
        </form>
    </div>
</div>
@endsection
HTML;

// ============ CAR MODEL ============
$files['app/Http/Controllers/Admin/CarModelController.php'] = <<<'PHP'
<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\CarModel;
use App\Models\Brand;
use Illuminate\Http\Request;

class CarModelController extends Controller
{
    public function index() {
        $models = CarModel::with('brand')->withCount('vehicles')->orderBy('name')->paginate(10);
        return view('admin.models.index', compact('models'));
    }
    public function create() {
        $brands = Brand::orderBy('name')->get();
        return view('admin.models.create', compact('brands'));
    }
    public function store(Request $request) {
        $data = $request->validate(['brand_id'=>'required|exists:brands,id', 'name'=>'required|string|max:255']);
        $data['is_active'] = $request->has('is_active');
        CarModel::create($data);
        return redirect()->route('admin.models.index')->with('success', 'Modèle créé.');
    }
    public function edit(CarModel $model) {
        $brands = Brand::orderBy('name')->get();
        return view('admin.models.edit', compact('model', 'brands'));
    }
    public function update(Request $request, CarModel $model) {
        $data = $request->validate(['brand_id'=>'required|exists:brands,id', 'name'=>'required|string|max:255']);
        $data['is_active'] = $request->has('is_active');
        $model->update($data);
        return redirect()->route('admin.models.index')->with('success', 'Modèle modifié.');
    }
    public function destroy(CarModel $model) {
        $model->delete();
        return redirect()->route('admin.models.index')->with('success', 'Modèle supprimé.');
    }
}
PHP;

$files['resources/views/admin/models/index.blade.php'] = <<<'HTML'
@extends('admin.layouts.app')
@section('title', 'Gestion des Modèles')
@section('breadcrumb', 'Gestion / Modèles')
@section('content')
<div class="page-header">
    <h1 class="page-title">Modèles</h1>
    <a href="{{ route('admin.models.create') }}" class="btn">Ajouter</a>
</div>
<div class="card"><div class="card-body">
    <table class="table">
        <thead><tr><th>Modèle</th><th>Marque</th><th>Véhicules</th><th>Statut</th><th>Actions</th></tr></thead>
        <tbody>
            @foreach($models as $model)
            <tr>
                <td style="font-weight: 600;">{{ $model->name }}</td>
                <td>{{ $model->brand->name }}</td>
                <td>{{ $model->vehicles_count }}</td>
                <td>@if($model->is_active) <span class="badge badge-success">Actif</span> @else <span class="badge badge-danger">Inactif</span> @endif</td>
                <td>
                    <a href="{{ route('admin.models.edit', $model->id) }}" class="btn btn-sm btn-outline">Éditer</a>
                    <form action="{{ route('admin.models.destroy', $model->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm" style="background:#ef4444;color:white;border:none;">Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="margin-top:20px;">{{ $models->links() }}</div>
</div></div>
@endsection
HTML;

$files['resources/views/admin/models/create.blade.php'] = <<<'HTML'
@extends('admin.layouts.app')
@section('title', 'Ajouter un Modèle')
@section('breadcrumb', 'Modèles / Ajouter')
@section('content')
<div class="card" style="max-width: 600px;"><div class="card-body">
    <form action="{{ route('admin.models.store') }}" method="POST">
        @csrf
        <div style="margin-bottom: 20px;"><label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Marque *</label>
        <select name="brand_id" required style="width:100%;padding:10px;border-radius:8px;border:1px solid var(--border-color);background:var(--glass-bg);color:var(--text-color);">
            <option value="">Sélectionnez</option>
            @foreach($brands as $b) <option value="{{ $b->id }}">{{ $b->name }}</option> @endforeach
        </select></div>
        <div style="margin-bottom: 20px;"><label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Nom du modèle *</label>
        <input type="text" name="name" required style="width:100%;padding:10px;border-radius:8px;border:1px solid var(--border-color);background:var(--glass-bg);color:var(--text-color);"></div>
        <div style="margin-bottom: 20px;"><label style="display:flex;align-items:center;gap:10px;"><input type="checkbox" name="is_active" value="1" checked> Actif</label></div>
        <button type="submit" class="btn">Enregistrer</button>
    </form>
</div></div>
@endsection
HTML;

$files['resources/views/admin/models/edit.blade.php'] = <<<'HTML'
@extends('admin.layouts.app')
@section('title', 'Éditer un Modèle')
@section('breadcrumb', 'Modèles / Éditer')
@section('content')
<div class="card" style="max-width: 600px;"><div class="card-body">
    <form action="{{ route('admin.models.update', $model->id) }}" method="POST">
        @csrf @method('PUT')
        <div style="margin-bottom: 20px;"><label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Marque *</label>
        <select name="brand_id" required style="width:100%;padding:10px;border-radius:8px;border:1px solid var(--border-color);background:var(--glass-bg);color:var(--text-color);">
            @foreach($brands as $b) <option value="{{ $b->id }}" @if($model->brand_id==$b->id) selected @endif>{{ $b->name }}</option> @endforeach
        </select></div>
        <div style="margin-bottom: 20px;"><label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Nom *</label>
        <input type="text" name="name" value="{{ $model->name }}" required style="width:100%;padding:10px;border-radius:8px;border:1px solid var(--border-color);background:var(--glass-bg);color:var(--text-color);"></div>
        <div style="margin-bottom: 20px;"><label style="display:flex;align-items:center;gap:10px;"><input type="checkbox" name="is_active" value="1" @if($model->is_active) checked @endif> Actif</label></div>
        <button type="submit" class="btn">Mettre à jour</button>
    </form>
</div></div>
@endsection
HTML;

// ============ FEATURE ============
$files['app/Http/Controllers/Admin/VehicleFeatureController.php'] = <<<'PHP'
<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\VehicleFeature;
use Illuminate\Http\Request;

class VehicleFeatureController extends Controller
{
    public function index() {
        $features = VehicleFeature::orderBy('category')->orderBy('name')->paginate(10);
        return view('admin.features.index', compact('features'));
    }
    public function create() {
        return view('admin.features.create');
    }
    public function store(Request $request) {
        $data = $request->validate(['name'=>'required|string|max:255', 'category'=>'required|string|max:255']);
        $data['is_active'] = $request->has('is_active');
        VehicleFeature::create($data);
        return redirect()->route('admin.features.index')->with('success', 'Caractéristique créée.');
    }
    public function edit(VehicleFeature $feature) {
        return view('admin.features.edit', compact('feature'));
    }
    public function update(Request $request, VehicleFeature $feature) {
        $data = $request->validate(['name'=>'required|string|max:255', 'category'=>'required|string|max:255']);
        $data['is_active'] = $request->has('is_active');
        $feature->update($data);
        return redirect()->route('admin.features.index')->with('success', 'Caractéristique modifiée.');
    }
    public function destroy(VehicleFeature $feature) {
        $feature->delete();
        return redirect()->route('admin.features.index')->with('success', 'Caractéristique supprimée.');
    }
}
PHP;

$files['resources/views/admin/features/index.blade.php'] = <<<'HTML'
@extends('admin.layouts.app')
@section('title', 'Caractéristiques')
@section('breadcrumb', 'Gestion / Caractéristiques')
@section('content')
<div class="page-header">
    <h1 class="page-title">Caractéristiques</h1>
    <a href="{{ route('admin.features.create') }}" class="btn">Ajouter</a>
</div>
<div class="card"><div class="card-body">
    <table class="table">
        <thead><tr><th>Nom</th><th>Catégorie</th><th>Statut</th><th>Actions</th></tr></thead>
        <tbody>
            @foreach($features as $f)
            <tr>
                <td style="font-weight: 600;">{{ $f->name }}</td>
                <td>{{ $f->category }}</td>
                <td>@if($f->is_active) <span class="badge badge-success">Actif</span> @else <span class="badge badge-danger">Inactif</span> @endif</td>
                <td>
                    <a href="{{ route('admin.features.edit', $f->id) }}" class="btn btn-sm btn-outline">Éditer</a>
                    <form action="{{ route('admin.features.destroy', $f->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm" style="background:#ef4444;color:white;border:none;">Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="margin-top:20px;">{{ $features->links() }}</div>
</div></div>
@endsection
HTML;

$files['resources/views/admin/features/create.blade.php'] = <<<'HTML'
@extends('admin.layouts.app')
@section('title', 'Ajouter Caractéristique')
@section('breadcrumb', 'Caractéristiques / Ajouter')
@section('content')
<div class="card" style="max-width: 600px;"><div class="card-body">
    <form action="{{ route('admin.features.store') }}" method="POST">
        @csrf
        <div style="margin-bottom: 20px;"><label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Nom *</label>
        <input type="text" name="name" required style="width:100%;padding:10px;border-radius:8px;border:1px solid var(--border-color);background:var(--glass-bg);color:var(--text-color);"></div>
        
        <div style="margin-bottom: 20px;"><label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Catégorie *</label>
        <select name="category" required style="width:100%;padding:10px;border-radius:8px;border:1px solid var(--border-color);background:var(--glass-bg);color:var(--text-color);">
            <option value="Sécurité">Sécurité</option><option value="Confort">Confort</option><option value="Technologie">Technologie</option><option value="Extérieur">Extérieur</option>
        </select></div>

        <div style="margin-bottom: 20px;"><label style="display:flex;align-items:center;gap:10px;"><input type="checkbox" name="is_active" value="1" checked> Actif</label></div>
        <button type="submit" class="btn">Enregistrer</button>
    </form>
</div></div>
@endsection
HTML;

$files['resources/views/admin/features/edit.blade.php'] = <<<'HTML'
@extends('admin.layouts.app')
@section('title', 'Éditer Caractéristique')
@section('breadcrumb', 'Caractéristiques / Éditer')
@section('content')
<div class="card" style="max-width: 600px;"><div class="card-body">
    <form action="{{ route('admin.features.update', $feature->id) }}" method="POST">
        @csrf @method('PUT')
        <div style="margin-bottom: 20px;"><label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Nom *</label>
        <input type="text" name="name" value="{{ $feature->name }}" required style="width:100%;padding:10px;border-radius:8px;border:1px solid var(--border-color);background:var(--glass-bg);color:var(--text-color);"></div>
        
        <div style="margin-bottom: 20px;"><label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Catégorie *</label>
        <select name="category" required style="width:100%;padding:10px;border-radius:8px;border:1px solid var(--border-color);background:var(--glass-bg);color:var(--text-color);">
            @foreach(['Sécurité','Confort','Technologie','Extérieur'] as $cat)
                <option value="{{ $cat }}" @if($feature->category==$cat) selected @endif>{{ $cat }}</option>
            @endforeach
        </select></div>

        <div style="margin-bottom: 20px;"><label style="display:flex;align-items:center;gap:10px;"><input type="checkbox" name="is_active" value="1" @if($feature->is_active) checked @endif> Actif</label></div>
        <button type="submit" class="btn">Mettre à jour</button>
    </form>
</div></div>
@endsection
HTML;

// ============ VEHICLES ============
$files['app/Http/Controllers/Admin/VehicleController.php'] = <<<'PHP'
<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\Brand;
use App\Models\CarModel;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index() {
        $vehicles = Vehicle::with(['brand', 'carModel'])->latest()->paginate(10);
        return view('admin.vehicles.index', compact('vehicles'));
    }
    public function create() {
        $brands = Brand::orderBy('name')->get();
        $models = CarModel::orderBy('name')->get();
        return view('admin.vehicles.create', compact('brands', 'models'));
    }
    public function store(Request $request) {
        $data = $request->validate([
            'title'=>'required|string|max:255', 'brand_id'=>'required|exists:brands,id', 'car_model_id'=>'required|exists:car_models,id', 
            'year'=>'required|integer', 'price'=>'required|numeric', 'mileage'=>'required|integer', 'fuel_type'=>'required|string', 
            'transmission'=>'required|string', 'condition'=>'required|string', 'status'=>'required|string'
        ]);
        Vehicle::create($data);
        return redirect()->route('admin.vehicles.index')->with('success', 'Véhicule créé.');
    }
    public function edit(Vehicle $vehicle) {
        $brands = Brand::orderBy('name')->get();
        $models = CarModel::orderBy('name')->get();
        return view('admin.vehicles.edit', compact('vehicle', 'brands', 'models'));
    }
    public function update(Request $request, Vehicle $vehicle) {
        $data = $request->validate([
            'title'=>'required|string|max:255', 'brand_id'=>'required|exists:brands,id', 'car_model_id'=>'required|exists:car_models,id', 
            'year'=>'required|integer', 'price'=>'required|numeric', 'mileage'=>'required|integer', 'fuel_type'=>'required|string', 
            'transmission'=>'required|string', 'condition'=>'required|string', 'status'=>'required|string'
        ]);
        $vehicle->update($data);
        return redirect()->route('admin.vehicles.index')->with('success', 'Véhicule mis à jour.');
    }
    public function destroy(Vehicle $vehicle) {
        $vehicle->delete();
        return redirect()->route('admin.vehicles.index')->with('success', 'Véhicule supprimé.');
    }
}
PHP;

$files['resources/views/admin/vehicles/index.blade.php'] = <<<'HTML'
@extends('admin.layouts.app')
@section('title', 'Véhicules')
@section('breadcrumb', 'Gestion / Véhicules')
@section('content')
<div class="page-header">
    <h1 class="page-title">Véhicules</h1>
    <a href="{{ route('admin.vehicles.create') }}" class="btn">Ajouter</a>
</div>
<div class="card"><div class="card-body">
    <table class="table">
        <thead><tr><th>Titre</th><th>Marque</th><th>Prix</th><th>Statut</th><th>Actions</th></tr></thead>
        <tbody>
            @foreach($vehicles as $v)
            <tr>
                <td style="font-weight: 600;">{{ $v->title }} <br><small style="font-weight: normal; color:var(--text-muted)">{{ $v->year }} · {{ number_format($v->mileage) }} km</small></td>
                <td>{{ $v->brand->name }}</td>
                <td style="font-weight: 600;">{{ $v->formatted_price }}</td>
                <td>
                    @if($v->status=='available') <span class="badge badge-success">Disponible</span> @elseif($v->status=='sold') <span class="badge badge-danger">Vendu</span> @else <span class="badge badge-warning">{{ $v->status }}</span> @endif
                </td>
                <td>
                    <a href="{{ route('admin.vehicles.edit', $v->id) }}" class="btn btn-sm btn-outline">Éditer</a>
                    <form action="{{ route('admin.vehicles.destroy', $v->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Sprmi?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm" style="background:#ef4444;color:white;border:none;">Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="margin-top:20px;">{{ $vehicles->links() }}</div>
</div></div>
@endsection
HTML;

$files['resources/views/admin/vehicles/create.blade.php'] = <<<'HTML'
@extends('admin.layouts.app')
@section('title', 'Ajouter Véhicule')
@section('breadcrumb', 'Véhicules / Ajouter')
@section('content')
<div class="card" style="max-width: 800px;"><div class="card-body">
    <form action="{{ route('admin.vehicles.store') }}" method="POST">
        @csrf
        <div style="margin-bottom: 20px;"><label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Titre *</label>
        <input type="text" name="title" required style="width:100%;padding:10px;border-radius:8px;border:1px solid var(--border-color);background:var(--glass-bg);color:var(--text-color);"></div>
        
        <div style="display:flex; gap: 20px; margin-bottom: 20px;">
            <div style="flex:1"><label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Marque *</label>
            <select name="brand_id" required style="width:100%;padding:10px;border-radius:8px;border:1px solid var(--border-color);background:var(--glass-bg);color:var(--text-color);">
                @foreach($brands as $b) <option value="{{ $b->id }}">{{ $b->name }}</option> @endforeach
            </select></div>
            <div style="flex:1"><label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Modèle *</label>
            <select name="car_model_id" required style="width:100%;padding:10px;border-radius:8px;border:1px solid var(--border-color);background:var(--glass-bg);color:var(--text-color);">
                @foreach($models as $m) <option value="{{ $m->id }}">{{ $m->name }}</option> @endforeach
            </select></div>
        </div>

        <div style="display:flex; gap: 20px; margin-bottom: 20px;">
            <div style="flex:1"><label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Année *</label>
            <input type="number" name="year" required style="width:100%;padding:10px;border-radius:8px;border:1px solid var(--border-color);background:var(--glass-bg);color:var(--text-color);"></div>
            <div style="flex:1"><label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Prix *</label>
            <input type="number" name="price" required style="width:100%;padding:10px;border-radius:8px;border:1px solid var(--border-color);background:var(--glass-bg);color:var(--text-color);"></div>
            <div style="flex:1"><label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Kilométrage *</label>
            <input type="number" name="mileage" required style="width:100%;padding:10px;border-radius:8px;border:1px solid var(--border-color);background:var(--glass-bg);color:var(--text-color);"></div>
        </div>

        <div style="display:flex; gap: 20px; margin-bottom: 20px;">
            <div style="flex:1"><label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Énergie *</label>
            <select name="fuel_type" required style="width:100%;padding:10px;border-radius:8px;border:1px solid var(--border-color);background:var(--glass-bg);color:var(--text-color);">
                <option value="Diesel">Diesel</option><option value="Essence">Essence</option><option value="Hybride">Hybride</option><option value="Électrique">Électrique</option>
            </select></div>
            <div style="flex:1"><label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Transmission *</label>
            <select name="transmission" required style="width:100%;padding:10px;border-radius:8px;border:1px solid var(--border-color);background:var(--glass-bg);color:var(--text-color);">
                <option value="Automatique">Automatique</option><option value="Manuelle">Manuelle</option>
            </select></div>
        </div>

        <div style="display:flex; gap: 20px; margin-bottom: 20px;">
            <div style="flex:1"><label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Condition *</label>
            <select name="condition" required style="width:100%;padding:10px;border-radius:8px;border:1px solid var(--border-color);background:var(--glass-bg);color:var(--text-color);">
                <option value="used">Occasion</option><option value="new">Neuf</option>
            </select></div>
            <div style="flex:1"><label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Statut *</label>
            <select name="status" required style="width:100%;padding:10px;border-radius:8px;border:1px solid var(--border-color);background:var(--glass-bg);color:var(--text-color);">
                <option value="available">Disponible</option><option value="sold">Vendu</option><option value="reserved">Réservé</option>
            </select></div>
        </div>

        <button type="submit" class="btn">Enregistrer</button>
    </form>
</div></div>
@endsection
HTML;

$files['resources/views/admin/vehicles/edit.blade.php'] = <<<'HTML'
@extends('admin.layouts.app')
@section('title', 'Éditer Véhicule')
@section('breadcrumb', 'Véhicules / Éditer')
@section('content')
<div class="card" style="max-width: 800px;"><div class="card-body">
    <form action="{{ route('admin.vehicles.update', $vehicle->id) }}" method="POST">
        @csrf @method('PUT')
        <div style="margin-bottom: 20px;"><label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Titre *</label>
        <input type="text" name="title" value="{{ $vehicle->title }}" required style="width:100%;padding:10px;border-radius:8px;border:1px solid var(--border-color);background:var(--glass-bg);color:var(--text-color);"></div>
        
        <div style="display:flex; gap: 20px; margin-bottom: 20px;">
            <div style="flex:1"><label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Marque *</label>
            <select name="brand_id" required style="width:100%;padding:10px;border-radius:8px;border:1px solid var(--border-color);background:var(--glass-bg);color:var(--text-color);">
                @foreach($brands as $b) <option value="{{ $b->id }}" @if($vehicle->brand_id==$b->id) selected @endif>{{ $b->name }}</option> @endforeach
            </select></div>
            <div style="flex:1"><label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Modèle *</label>
            <select name="car_model_id" required style="width:100%;padding:10px;border-radius:8px;border:1px solid var(--border-color);background:var(--glass-bg);color:var(--text-color);">
                @foreach($models as $m) <option value="{{ $m->id }}" @if($vehicle->car_model_id==$m->id) selected @endif>{{ $m->name }}</option> @endforeach
            </select></div>
        </div>

        <div style="display:flex; gap: 20px; margin-bottom: 20px;">
            <div style="flex:1"><label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Année *</label>
            <input type="number" name="year" value="{{ $vehicle->year }}" required style="width:100%;padding:10px;border-radius:8px;border:1px solid var(--border-color);background:var(--glass-bg);color:var(--text-color);"></div>
            <div style="flex:1"><label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Prix *</label>
            <input type="number" name="price" value="{{ $vehicle->price }}" required style="width:100%;padding:10px;border-radius:8px;border:1px solid var(--border-color);background:var(--glass-bg);color:var(--text-color);"></div>
            <div style="flex:1"><label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Kilométrage *</label>
            <input type="number" name="mileage" value="{{ $vehicle->mileage }}" required style="width:100%;padding:10px;border-radius:8px;border:1px solid var(--border-color);background:var(--glass-bg);color:var(--text-color);"></div>
        </div>

        <div style="display:flex; gap: 20px; margin-bottom: 20px;">
            <div style="flex:1"><label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Énergie *</label>
            <select name="fuel_type" required style="width:100%;padding:10px;border-radius:8px;border:1px solid var(--border-color);background:var(--glass-bg);color:var(--text-color);">
                @foreach(['Diesel','Essence','Hybride','Électrique'] as $f) <option value="{{ $f }}" @if($vehicle->fuel_type==$f) selected @endif>{{ $f }}</option> @endforeach
            </select></div>
            <div style="flex:1"><label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Transmission *</label>
            <select name="transmission" required style="width:100%;padding:10px;border-radius:8px;border:1px solid var(--border-color);background:var(--glass-bg);color:var(--text-color);">
                @foreach(['Automatique','Manuelle'] as $t) <option value="{{ $t }}" @if($vehicle->transmission==$t) selected @endif>{{ $t }}</option> @endforeach
            </select></div>
        </div>

        <div style="display:flex; gap: 20px; margin-bottom: 20px;">
            <div style="flex:1"><label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Condition *</label>
            <select name="condition" required style="width:100%;padding:10px;border-radius:8px;border:1px solid var(--border-color);background:var(--glass-bg);color:var(--text-color);">
                <option value="used" @if($vehicle->condition=='used') selected @endif>Occasion</option><option value="new" @if($vehicle->condition=='new') selected @endif>Neuf</option>
            </select></div>
            <div style="flex:1"><label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Statut *</label>
            <select name="status" required style="width:100%;padding:10px;border-radius:8px;border:1px solid var(--border-color);background:var(--glass-bg);color:var(--text-color);">
                <option value="available" @if($vehicle->status=='available') selected @endif>Disponible</option><option value="sold" @if($vehicle->status=='sold') selected @endif>Vendu</option><option value="reserved" @if($vehicle->status=='reserved') selected @endif>Réservé</option>
            </select></div>
        </div>

        <button type="submit" class="btn">Mettre à jour</button>
    </form>
</div></div>
@endsection
HTML;

// ============ INQUIRIES ============
$files['app/Http/Controllers/Admin/InquiryController.php'] = <<<'PHP'
<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function index() {
        $inquiries = Inquiry::latest()->paginate(10);
        return view('admin.inquiries.index', compact('inquiries'));
    }
    public function show(Inquiry $inquiry) {
        $inquiry->markAsRead();
        return view('admin.inquiries.show', compact('inquiry'));
    }
    public function destroy(Inquiry $inquiry) {
        $inquiry->delete();
        return redirect()->route('admin.inquiries.index')->with('success', 'Demande supprimée.');
    }
    public function create() {}
    public function store(Request $r) {}
    public function edit(Inquiry $i) {}
    public function update(Request $r, Inquiry $i) {}
}
PHP;

$files['resources/views/admin/inquiries/index.blade.php'] = <<<'HTML'
@extends('admin.layouts.app')
@section('title', 'Demandes')
@section('breadcrumb', 'Communication / Demandes')
@section('content')
<div class="page-header">
    <h1 class="page-title">Demandes Entrantes</h1>
</div>
<div class="card"><div class="card-body">
    <table class="table">
        <thead><tr><th>Nom</th><th>Sujet</th><th>Type</th><th>Statut</th><th>Date</th><th>Actions</th></tr></thead>
        <tbody>
            @foreach($inquiries as $i)
            <tr style="{{ $i->status=='new' ? 'background:rgba(255,255,255,.05)' : '' }}">
                <td style="font-weight: 600;">{{ $i->name }}<br><small style="font-weight:normal;color:var(--text-muted)">{{ $i->email }}</small></td>
                <td>{{ Str::limit($i->subject ?? $i->message, 40) }}</td>
                <td><span class="badge badge-info">{{ $i->type }}</span></td>
                <td>
                    @if($i->status=='new') <span class="badge badge-danger">Nouveau</span> @elseif($i->status=='read') <span class="badge badge-warning">Lu</span> @else <span class="badge badge-success">Répondu</span> @endif
                </td>
                <td>{{ $i->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <a href="{{ route('admin.inquiries.show', $i->id) }}" class="btn btn-sm btn-outline">Lire</a>
                    <form action="{{ route('admin.inquiries.destroy', $i->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Sprmi?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm" style="background:#ef4444;color:white;border:none;">Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="margin-top:20px;">{{ $inquiries->links() }}</div>
</div></div>
@endsection
HTML;

$files['resources/views/admin/inquiries/show.blade.php'] = <<<'HTML'
@extends('admin.layouts.app')
@section('title', 'Voir Demande')
@section('breadcrumb', 'Demandes / Voir')
@section('content')
<div class="card" style="max-width: 800px;">
    <div class="card-header"><h3 class="card-title">Détails de la demande</h3>
        <a href="{{ route('admin.inquiries.index') }}" class="btn btn-sm btn-outline">Retour</a>
    </div>
    <div class="card-body">
        <div style="display:flex; gap:20px; margin-bottom:20px;">
            <div style="flex:1"><strong>Nom:</strong> {{ $inquiry->name }}</div>
            <div style="flex:1"><strong>Email:</strong> {{ $inquiry->email }}</div>
            <div style="flex:1"><strong>Téléphone:</strong> {{ $inquiry->phone ?? '-' }}</div>
        </div>
        <div style="padding:20px; background:var(--glass-bg); border-radius:10px; border:1px solid var(--border-color);">
            <h4 style="margin-bottom:10px; color:white;">{{ $inquiry->subject ?? 'Sans Sujet' }}</h4>
            <p style="color:var(--text-color); line-height:1.6">{{ $inquiry->message }}</p>
        </div>
    </div>
</div>
@endsection
HTML;

// ============ USERS (Minimal) ============
$files['app/Http/Controllers/Admin/UserController.php'] = <<<'PHP'
<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index() {
        $users = User::paginate(10);
        return view('admin.users.index', compact('users'));
    }
    public function destroy(User $user) {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé.');
    }
    public function create() {}
    public function store(Request $r) {}
    public function edit(User $u) {}
    public function update(Request $r, User $u) {}
}
PHP;

$files['resources/views/admin/users/index.blade.php'] = <<<'HTML'
@extends('admin.layouts.app')
@section('title', 'Utilisateurs')
@section('breadcrumb', 'Configuration / Utilisateurs')
@section('content')
<div class="page-header"><h1 class="page-title">Utilisateurs</h1></div>
<div class="card"><div class="card-body">
    <table class="table">
        <thead><tr><th>Nom</th><th>Email</th><th>Rôle</th><th>Actions</th></tr></thead>
        <tbody>
            @foreach($users as $u)
            <tr>
                <td style="font-weight: 600;">{{ $u->name }}</td>
                <td>{{ $u->email }}</td>
                <td><span class="badge badge-info">{{ $u->role }}</span></td>
                <td>
                    <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Sprmi?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm" style="background:#ef4444;color:white;border:none;">Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div></div>
@endsection
HTML;

// ============ SETTINGS (Minimal) ============
$files['app/Http/Controllers/Admin/SettingController.php'] = <<<'PHP'
<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    public function index() {
        return view('admin.settings.index');
    }
}
PHP;

$files['resources/views/admin/settings/index.blade.php'] = <<<'HTML'
@extends('admin.layouts.app')
@section('title', 'Paramètres')
@section('breadcrumb', 'Configuration / Paramètres')
@section('content')
<div class="page-header"><h1 class="page-title">Paramètres</h1></div>
<div class="card"><div class="card-body">
    <p>La configuration du site sera développée ici (Ex: Coordonnées, Réseaux sociaux, SEO).</p>
</div></div>
@endsection
HTML;


// === WRITE FILES ===
foreach ($files as $path => $content) {
    $fullPath = __DIR__ . '/' . $path;
    $dir = dirname($fullPath);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    file_put_contents($fullPath, $content);
}

echo "All modules successfully wired and views created.";
?>
