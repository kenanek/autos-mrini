<?php
namespace App\Http\Controllers;
class PageController extends Controller {
    public function about() { return view('pages.about'); }
    public function location() { return view('pages.location'); }
    public function financing() { return view('pages.financing'); }
}