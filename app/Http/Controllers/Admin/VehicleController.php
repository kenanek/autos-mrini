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