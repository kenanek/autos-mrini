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