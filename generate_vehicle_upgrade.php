<?php
$files = [];

// ============ VEHICLES CONTROLLER ============
$files['app/Http/Controllers/Admin/VehicleController.php'] = <<<'PHP'
<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\VehicleFeature;
use App\Models\CustomAttribute;
use App\Models\VehicleImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VehicleController extends Controller
{
    public function index() {
        $vehicles = Vehicle::with(['brand', 'carModel', 'images'])->latest()->paginate(10);
        return view('admin.vehicles.index', compact('vehicles'));
    }

    public function create() {
        $brands = Brand::orderBy('name')->get();
        $models = CarModel::orderBy('name')->get();
        $features = VehicleFeature::orderBy('category')->orderBy('name')->get();
        $customAttributes = CustomAttribute::active()->orderBy('sort_order')->get();
        return view('admin.vehicles.create', compact('brands', 'models', 'features', 'customAttributes'));
    }

    public function store(Request $request) {
        $data = $this->validateVehicle($request);
        $data['is_featured'] = $request->has('is_featured');
        if($data['status'] === 'available' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $vehicle = Vehicle::create($data);
        $this->syncRelations($vehicle, $request);
        $this->handleImages($vehicle, $request);

        return redirect()->route('admin.vehicles.index')->with('success', 'Véhicule créé avec succès.');
    }

    public function edit(Vehicle $vehicle) {
        $brands = Brand::orderBy('name')->get();
        $models = CarModel::orderBy('name')->get();
        $features = VehicleFeature::orderBy('category')->orderBy('name')->get();
        $customAttributes = CustomAttribute::active()->orderBy('sort_order')->get();
        $vehicle->load(['features', 'customAttributes', 'images']);

        return view('admin.vehicles.edit', compact('vehicle', 'brands', 'models', 'features', 'customAttributes'));
    }

    public function update(Request $request, Vehicle $vehicle) {
        $data = $this->validateVehicle($request);
        $data['is_featured'] = $request->has('is_featured');
        if($data['status'] === 'available' && empty($vehicle->published_at)) {
            $data['published_at'] = now();
        }

        $vehicle->update($data);
        $this->syncRelations($vehicle, $request);
        $this->handleImages($vehicle, $request);

        return redirect()->route('admin.vehicles.index')->with('success', 'Véhicule mis à jour.');
    }

    public function destroy(Vehicle $vehicle) {
        foreach($vehicle->images as $img) {
            Storage::disk('public')->delete($img->image_path);
            $img->delete();
        }
        $vehicle->delete();
        return redirect()->route('admin.vehicles.index')->with('success', 'Véhicule supprimé.');
    }

    public function destroyImage(Vehicle $vehicle, $imageId) {
        $img = VehicleImage::where('vehicle_id', $vehicle->id)->where('id', $imageId)->firstOrFail();
        Storage::disk('public')->delete($img->image_path);
        $img->delete();
        return back()->with('success', 'Image supprimée.');
    }

    private function validateVehicle(Request $request) {
        return $request->validate([
            'title'=>'required|string|max:255',
            'brand_id'=>'required|exists:brands,id',
            'car_model_id'=>'required|exists:car_models,id',
            'year'=>'required|integer|min:1900|max:'.(date('Y')+1),
            'price'=>'required|numeric|min:0',
            'old_price'=>'nullable|numeric|min:0',
            'mileage'=>'required|integer|min:0',
            'fuel_type'=>'required|string',
            'transmission'=>'required|string',
            'condition'=>'required|string',
            'status'=>'required|string',
            'body_type'=>'nullable|string',
            'color'=>'nullable|string',
            'interior_color'=>'nullable|string',
            'engine_size'=>'nullable|string',
            'horsepower'=>'nullable|integer',
            'doors'=>'nullable|integer',
            'seats'=>'nullable|integer',
            'vin'=>'nullable|string',
            'short_description'=>'nullable|string',
            'description'=>'nullable|string',
        ]);
    }

    private function syncRelations(Vehicle $vehicle, Request $request) {
        // Sync features
        if ($request->has('features')) {
            $vehicle->features()->sync($request->input('features'));
        } else {
            $vehicle->features()->detach();
        }

        // Sync custom attributes
        if ($request->has('custom_attributes')) {
            $syncData = [];
            foreach ($request->input('custom_attributes') as $id => $val) {
                if(!empty($val)) {
                    $syncData[$id] = ['value' => $val];
                }
            }
            $vehicle->customAttributes()->sync($syncData);
        } else {
            $vehicle->customAttributes()->detach();
        }
    }

    private function handleImages(Vehicle $vehicle, Request $request) {
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('vehicles/images', 'public');
                $vehicle->images()->create([
                    'image_path' => $path,
                    'is_primary' => false,
                ]);
            }
        }
        
        if ($request->has('primary_image')) {
            $vehicle->images()->update(['is_primary' => false]);
            $vehicle->images()->where('id', $request->input('primary_image'))->update(['is_primary' => true]);
        } else {
            // make first primary if none set
            if(!$vehicle->images()->where('is_primary', true)->exists() && $vehicle->images()->count() > 0) {
                $vehicle->images()->first()->update(['is_primary' => true]);
            }
        }
    }
}
PHP;

// ============ VEHICLES INDEX VIEW ============
$files['resources/views/admin/vehicles/index.blade.php'] = <<<'HTML'
@extends('admin.layouts.app')
@section('title', 'Véhicules')
@section('breadcrumb', 'Gestion / Véhicules')
@section('content')
<div class="page-header">
    <h1 class="page-title">Véhicules</h1>
    <a href="{{ route('admin.vehicles.create') }}" class="btn">Ajouter un véhicule</a>
</div>
<div class="card"><div class="card-body">
    <table class="table">
        <thead><tr><th>Image</th><th>Véhicule</th><th>Prix</th><th>Statut</th><th>Actions</th></tr></thead>
        <tbody>
            @foreach($vehicles as $v)
            <tr>
                <td style="width: 80px;">
                    @if($v->primary_image)
                        <img src="{{ asset('storage/'.$v->primary_image) }}" alt="Image" style="width: 60px; height: 40px; object-fit: cover; border-radius: 4px;">
                    @else
                        <div style="width:60px; height:40px; background:var(--glass-bg); border-radius:4px; display:flex; align-items:center; justify-content:center; color:var(--text-muted); font-size:20px;">
                            <i class="icon-car"></i>
                        </div>
                    @endif
                </td>
                <td>
                    <div style="font-weight: 600; font-size: 14px; display:flex; align-items:center; gap:8px;">
                        {{ Str::limit($v->title, 40) }}
                        @if($v->is_featured) <span title="En Vedette">⭐</span> @endif
                    </div>
                    <div style="font-size: 12px; color: var(--text-muted);">
                        {{ $v->brand->name }} · {{ $v->year }} · {{ number_format($v->mileage) }} km
                    </div>
                </td>
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

// ============ VEHICLES FORM COMPONENT (Partial) ============
$files['resources/views/admin/vehicles/_form.blade.php'] = <<<'HTML'
<style>
    .form-section { padding: 24px; background: rgba(255,255,255,.01); border-bottom: 1px solid var(--border-color); }
    .form-section:last-child { border-bottom: none; }
    .form-section-title { font-size: 16px; font-weight: 600; color: white; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
    .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; }
    .form-control { width:100%; padding:12px; border-radius:8px; border:1px solid var(--border-color); background:var(--glass-bg); color:var(--text-color); font-family: inherit; }
    textarea.form-control { resize: vertical; min-height: 100px; }
    .image-preview-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 16px; margin-top: 20px; }
    .image-preview-item { position: relative; border-radius: 8px; overflow: hidden; background: #000; border: 2px solid transparent; }
    .image-preview-item.is-primary { border-color: #3b82f6; }
    .image-preview-item img { width: 100%; height: 110px; object-fit: cover; display: block; }
    .image-actions { position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,.7); padding: 8px; display: flex; justify-content: space-between; align-items: center; }
</style>

<!-- SECTION 1: Informations Principales -->
<div class="form-section">
    <div class="form-section-title"><i class="icon-car"></i> Informations Principales</div>
    <div class="form-grid">
        <div style="grid-column: 1 / -1;">
            <label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Titre de l'annonce *</label>
            <input type="text" name="title" value="{{ old('title', $vehicle->title ?? '') }}" class="form-control" required placeholder="Ex: BMW X5 xDrive30d M Sport">
        </div>
        <div>
            <label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Marque *</label>
            <select name="brand_id" class="form-control" required>
                <option value="">Sélectionner</option>
                @foreach($brands as $b) <option value="{{ $b->id }}" @if(old('brand_id', $vehicle->brand_id ?? '') == $b->id) selected @endif>{{ $b->name }}</option> @endforeach
            </select>
        </div>
        <div>
            <label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Modèle *</label>
            <select name="car_model_id" class="form-control" required>
                <option value="">Sélectionner</option>
                @foreach($models as $m) <option value="{{ $m->id }}" @if(old('car_model_id', $vehicle->car_model_id ?? '') == $m->id) selected @endif>{{ $m->name }}</option> @endforeach
            </select>
        </div>
        <div>
            <label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Condition *</label>
            <select name="condition" class="form-control" required>
                <option value="used" @if(old('condition', $vehicle->condition ?? '')=='used') selected @endif>Occasion</option>
                <option value="new" @if(old('condition', $vehicle->condition ?? '')=='new') selected @endif>Neuf</option>
            </select>
        </div>
        <div>
            <label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Année *</label>
            <input type="number" name="year" value="{{ old('year', $vehicle->year ?? '') }}" class="form-control" required min="1900">
        </div>
        <div>
            <label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Kilométrage (km) *</label>
            <input type="number" name="mileage" value="{{ old('mileage', $vehicle->mileage ?? '') }}" class="form-control" required min="0">
        </div>
        <div>
            <label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">VIN (Numéro de série)</label>
            <input type="text" name="vin" value="{{ old('vin', $vehicle->vin ?? '') }}" class="form-control" placeholder="17 caractères">
        </div>
    </div>
</div>

<!-- SECTION 2: Spécifications Techniques -->
<div class="form-section">
    <div class="form-section-title"><i class="icon-settings-2"></i> Spécifications Techniques</div>
    <div class="form-grid">
        <div>
            <label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Énergie *</label>
            <select name="fuel_type" class="form-control" required>
                @foreach(['Diesel','Essence','Hybride','Électrique'] as $f) <option value="{{ $f }}" @if(old('fuel_type', $vehicle->fuel_type ?? '')==$f) selected @endif>{{ $f }}</option> @endforeach
            </select>
        </div>
        <div>
            <label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Transmission *</label>
            <select name="transmission" class="form-control" required>
                @foreach(['Automatique','Manuelle'] as $t) <option value="{{ $t }}" @if(old('transmission', $vehicle->transmission ?? '')==$t) selected @endif>{{ $t }}</option> @endforeach
            </select>
        </div>
        <div>
            <label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Carrosserie</label>
            <select name="body_type" class="form-control">
                <option value="">Au choix</option>
                @foreach(['SUV','Berline','Citadine','Coupé','Cabriolet','Break','Monospace'] as $b) <option value="{{ $b }}" @if(old('body_type', $vehicle->body_type ?? '')==$b) selected @endif>{{ $b }}</option> @endforeach
            </select>
        </div>
        <div>
            <label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Chevaux (ch)</label>
            <input type="number" name="horsepower" value="{{ old('horsepower', $vehicle->horsepower ?? '') }}" class="form-control" min="0">
        </div>
        <div>
            <label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Cylindrée (L)</label>
            <input type="text" name="engine_size" value="{{ old('engine_size', $vehicle->engine_size ?? '') }}" class="form-control" placeholder="Ex: 2.0L">
        </div>
        <div style="display:flex; gap: 10px;">
            <div style="flex:1">
                <label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Portes</label>
                <input type="number" name="doors" value="{{ old('doors', $vehicle->doors ?? '') }}" class="form-control" min="2" max="6">
            </div>
            <div style="flex:1">
                <label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Places</label>
                <input type="number" name="seats" value="{{ old('seats', $vehicle->seats ?? '') }}" class="form-control" min="2" max="9">
            </div>
        </div>
    </div>
</div>

<!-- SECTION 3: Couleurs & Ventes -->
<div class="form-section">
    <div class="form-section-title"><i class="icon-tag"></i> Ventes & Apparence</div>
    <div class="form-grid">
        <div>
            <label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Prix Actuel (MAD) *</label>
            <input type="number" name="price" value="{{ old('price', $vehicle->price ?? '') }}" class="form-control" required min="0">
        </div>
        <div>
            <label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Ancien Prix (Rayé)</label>
            <input type="number" name="old_price" value="{{ old('old_price', $vehicle->old_price ?? '') }}" class="form-control" min="0">
        </div>
        <div>
            <label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Statut *</label>
            <select name="status" class="form-control" required>
                <option value="available" @if(old('status', $vehicle->status ?? '')=='available') selected @endif>Disponible</option>
                <option value="reserved" @if(old('status', $vehicle->status ?? '')=='reserved') selected @endif>Réservé</option>
                <option value="sold" @if(old('status', $vehicle->status ?? '')=='sold') selected @endif>Vendu</option>
                <option value="draft" @if(old('status', $vehicle->status ?? '')=='draft') selected @endif>Brouillon</option>
            </select>
        </div>
        <div style="grid-column: 1 / -1; display:flex; gap:20px;">
            <div style="flex:1">
                <label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Couleur Extérieure</label>
                <input type="text" name="color" value="{{ old('color', $vehicle->color ?? '') }}" class="form-control">
            </div>
            <div style="flex:1">
                <label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Couleur Intérieure</label>
                <input type="text" name="interior_color" value="{{ old('interior_color', $vehicle->interior_color ?? '') }}" class="form-control">
            </div>
        </div>
        <div style="grid-column: 1 / -1;">
            <label style="display:flex;align-items:center;gap:10px;"><input type="checkbox" name="is_featured" value="1" @if(old('is_featured', $vehicle->is_featured ?? false)) checked @endif> <b>Mettre le véhicule en vedette</b></label>
        </div>
    </div>
</div>

<!-- SECTION 4: Descriptions -->
<div class="form-section">
    <div class="form-section-title"><i class="icon-file-text"></i> Descriptions</div>
    <div style="margin-bottom:20px;">
        <label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Description Courte</label>
        <textarea name="short_description" class="form-control" rows="2">{{ old('short_description', $vehicle->short_description ?? '') }}</textarea>
    </div>
    <div>
        <label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Description Complète</label>
        <textarea name="description" class="form-control" rows="5">{{ old('description', $vehicle->description ?? '') }}</textarea>
    </div>
</div>

<!-- SECTION 5: Caractéristiques (Pivot) -->
<div class="form-section">
    <div class="form-section-title"><i class="icon-list"></i> Équipements & Options</div>
    @php
        $groupedFeatures = $features->groupBy('category');
        $selectedFeatures = isset($vehicle) ? $vehicle->features->pluck('id')->toArray() : [];
    @endphp
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 24px;">
        @foreach($groupedFeatures as $category => $catFeatures)
        <div>
            <h4 style="margin-bottom: 12px; font-size: 14px; color: #60a5fa; border-bottom: 1px solid rgba(255,255,255,.1); padding-bottom: 6px;">{{ $category }}</h4>
            <div style="display:flex; flex-direction:column; gap:8px;">
                @foreach($catFeatures as $f)
                <label style="font-size: 13px; display:flex; gap:8px; align-items:center; color:#cbd5e1;">
                    <input type="checkbox" name="features[]" value="{{ $f->id }}" @if(in_array($f->id, old('features', $selectedFeatures))) checked @endif> {{ $f->name }}
                </label>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- SECTION 6: Attributs Persos -->
@if(count($customAttributes) > 0)
<div class="form-section">
    <div class="form-section-title"><i class="icon-sliders"></i> Attributs Personnalisés</div>
    <div class="form-grid">
        @foreach($customAttributes as $attr)
        @php
            $attrVal = isset($vehicle) ? ($vehicle->customAttributes->firstWhere('id', $attr->id)->pivot->value ?? '') : '';
        @endphp
        <div>
            <label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">{{ $attr->name }} @if($attr->unit) <small>({{ $attr->unit }})</small> @endif</label>
            @if($attr->type == 'select' && !empty($attr->options))
                <select name="custom_attributes[{{ $attr->id }}]" class="form-control">
                    <option value="">-- Sélect --</option>
                    @foreach($attr->options as $opt)
                        <option value="{{ $opt }}" @if(old("custom_attributes.$attr->id", $attrVal) == $opt) selected @endif>{{ $opt }}</option>
                    @endforeach
                </select>
            @elseif($attr->type == 'boolean')
                <select name="custom_attributes[{{ $attr->id }}]" class="form-control">
                    <option value="">-- Sélect --</option>
                    <option value="Oui" @if(old("custom_attributes.$attr->id", $attrVal) == 'Oui') selected @endif>Oui</option>
                    <option value="Non" @if(old("custom_attributes.$attr->id", $attrVal) == 'Non') selected @endif>Non</option>
                </select>
            @else
                <input type="{{ $attr->type=='number' ? 'number' : 'text' }}" name="custom_attributes[{{ $attr->id }}]" value="{{ old("custom_attributes.$attr->id", $attrVal) }}" class="form-control">
            @endif
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- SECTION 7: Images Galerie -->
<div class="form-section">
    <div class="form-section-title"><i class="icon-image"></i> Galerie Photos</div>
    
    <div style="margin-bottom: 20px;">
        <label style="display:block;margin-bottom:8px;font-weight:600;font-size:14px;">Ajouter des images</label>
        <input type="file" name="images[]" multiple accept="image/*" class="form-control" style="padding: 10px;">
        <small style="color:var(--text-muted); display:block; margin-top:6px;">Vous pouvez sélectionner plusieurs images en même temps. (JPG, PNG).</small>
    </div>

    @if(isset($vehicle) && count($vehicle->images) > 0)
    <div class="image-preview-grid">
        @foreach($vehicle->images as $img)
        <div class="image-preview-item @if($img->is_primary) is-primary @endif">
            <img src="{{ asset('storage/' . $img->image_path) }}" alt="Img">
            <div class="image-actions">
                <label style="color:white; font-size:12px; cursor:pointer;"><input type="radio" name="primary_image" value="{{ $img->id }}" @if($img->is_primary) checked @endif> Principale</label>
                <a href="javascript:void(0)" onclick="if(confirm('Sup?')) document.getElementById('del-img-{{$img->id}}').submit();" style="color:#ef4444; font-size:12px; text-decoration:none;">Sup.</a>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
HTML;

// ============ VEHICLES CREATE ============
$files['resources/views/admin/vehicles/create.blade.php'] = <<<'HTML'
@extends('admin.layouts.app')
@section('title', 'Ajouter Véhicule')
@section('breadcrumb', 'Véhicules / Ajouter')
@section('content')
<form action="{{ route('admin.vehicles.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-header"><h3 class="card-title">Ajouter un nouveau véhicule</h3></div>
        @if ($errors->any())
        <div style="padding:15px; background:#ef4444; color:white; margin:15px; border-radius:8px;">
            <ul style="margin:0; padding-left:20px;">@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
        </div>
        @endif
        @include('admin.vehicles._form')
        <div style="padding: 24px; border-top: 1px solid var(--border-color); background: rgba(0,0,0,.2); display: flex; justify-content: flex-end; gap: 15px;">
            <a href="{{ route('admin.vehicles.index') }}" class="btn btn-outline">Annuler</a>
            <button type="submit" class="btn" style="background:var(--primary); color:white; border:none; padding:12px 24px;">Créer le Véhicule</button>
        </div>
    </div>
</form>
@endsection
HTML;

// ============ VEHICLES EDIT ============
$files['resources/views/admin/vehicles/edit.blade.php'] = <<<'HTML'
@extends('admin.layouts.app')
@section('title', 'Éditer Véhicule')
@section('breadcrumb', 'Véhicules / Éditer')
@section('content')

@foreach($vehicle->images as $img)
<form id="del-img-{{$img->id}}" action="{{ route('admin.vehicles.images.destroy', [$vehicle->id, $img->id]) }}" method="POST" style="display:none;">
    @csrf @method('DELETE')
</form>
@endforeach

<form action="{{ route('admin.vehicles.update', $vehicle->id) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="card">
        <div class="card-header"><h3 class="card-title">Éditer: {{ $vehicle->title }}</h3></div>
        @if ($errors->any())
        <div style="padding:15px; background:#ef4444; color:white; margin:15px; border-radius:8px;">
            <ul style="margin:0; padding-left:20px;">@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
        </div>
        @endif
        @include('admin.vehicles._form')
        <div style="padding: 24px; border-top: 1px solid var(--border-color); background: rgba(0,0,0,.2); display: flex; justify-content: flex-end; gap: 15px;">
            <a href="{{ route('admin.vehicles.index') }}" class="btn btn-outline">Annuler</a>
            <button type="submit" class="btn" style="background:var(--primary); color:white; border:none; padding:12px 24px;">Sauvegarder les Changements</button>
        </div>
    </div>
</form>
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

echo "Vehicle UI setup correctly.";
?>
