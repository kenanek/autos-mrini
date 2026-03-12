<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('admin.login');
        }

        $user = auth()->user();
        if ($user->role === 'super_admin') {
            return $next($request); // Super admin passes all
        }

        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        return abort(403, 'Accès non autorisé à cette ressource.');
    }
}