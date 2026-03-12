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