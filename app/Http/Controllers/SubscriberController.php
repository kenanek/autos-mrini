<?php
namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $existing = Subscriber::where('email', $request->email)->first();
        if ($existing) {
            if ($existing->status === 'unsubscribed') {
                $existing->update(['status' => 'active', 'subscribed_at' => now(), 'unsubscribed_at' => null]);
                return back()->with('newsletter_success', '¡Te has vuelto a suscribir correctamente!');
            }
            return back()->with('newsletter_info', 'Ya estás suscrito a nuestro boletín.');
        }

        Subscriber::create([
            'email' => $request->email,
            'name' => $request->name,
        ]);

        return back()->with('newsletter_success', '¡Gracias por suscribirte a nuestro boletín!');
    }

    public function unsubscribe(string $token)
    {
        $sub = Subscriber::where('token', $token)->firstOrFail();
        $sub->update(['status' => 'unsubscribed', 'unsubscribed_at' => now()]);
        return view('newsletter.unsubscribed', ['subscriber' => $sub]);
    }
}
