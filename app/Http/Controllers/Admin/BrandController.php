<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::withCount('carModels', 'vehicles')->orderBy('name')->paginate(10);
        return view('admin.brands.index', compact('brands'));
    }
    public function create()
    {
        return view('admin.brands.create');
    }
    public function store(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|max:255|unique:brands', 'country' => 'nullable|string', 'description' => 'nullable|string']);
        $data['is_active'] = $request->has('is_active');
        Brand::create($data);
        return redirect()->route('admin.brands.index')->with('success', 'Marque créée avec succès.');
    }
    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }
    public function update(Request $request, Brand $brand)
    {
        $data = $request->validate(['name' => 'required|string|max:255|unique:brands,name,' . $brand->id, 'country' => 'nullable|string', 'description' => 'nullable|string']);
        $data['is_active'] = $request->has('is_active');
        $brand->update($data);
        return redirect()->route('admin.brands.index')->with('success', 'Marque modifiée avec succès.');
    }
    public function destroy(Brand $brand)
    {
        $brand->delete();
        return redirect()->route('admin.brands.index')->with('success', 'Marque supprimée.');
    }
}