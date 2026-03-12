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