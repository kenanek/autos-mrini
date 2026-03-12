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