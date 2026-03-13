<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }

    public function update(Request $request)
    {
        $keys = [
            'site_name', 'currency', 'phone', 'whatsapp', 'email', 'address', 'opening_hours',
            'google_maps_embed', 'google_maps_link', 'footer_description',
            'about_page_text', 'financing_page_text', 'facebook_url',
            'instagram_url', 'tiktok_url', 'default_meta_title', 'default_meta_description',
            'default_theme'
        ];

        foreach ($keys as $key) {
            Setting::setVal($key, $request->input($key, ''));
        }

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('settings', 'public');
            Setting::setVal('logo', $path);
            
            // Check true public path based on the entry script for shared hosting
            $truePublicDir = isset($_SERVER['SCRIPT_FILENAME']) ? dirname($_SERVER['SCRIPT_FILENAME']) : public_path();
            $publicDest = rtrim($truePublicDir, '/') . '/storage/' . $path;
            
            if (!file_exists(dirname($publicDest))) {
                mkdir(dirname($publicDest), 0755, true);
            }
            copy(storage_path('app/public/' . $path), $publicDest);
        }

        if ($request->hasFile('favicon')) {
            $path = $request->file('favicon')->store('settings', 'public');
            Setting::setVal('favicon', $path);
            
            // Check true public path based on the entry script for shared hosting
            $truePublicDir = isset($_SERVER['SCRIPT_FILENAME']) ? dirname($_SERVER['SCRIPT_FILENAME']) : public_path();
            $publicDest = rtrim($truePublicDir, '/') . '/storage/' . $path;
            
            if (!file_exists(dirname($publicDest))) {
                mkdir(dirname($publicDest), 0755, true);
            }
            copy(storage_path('app/public/' . $path), $publicDest);
        }

        return back()->with('success', 'Configuración actualizada correctamente.');
    }
}