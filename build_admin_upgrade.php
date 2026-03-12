<?php
// Script to upgrade Autos Mrini Admin: Roles, Password Reset, and UI/UX

$base = __DIR__;

echo "Starting Admin Upgrade Pass...\n";

// ==========================================
// AREA 2: Roles and Permissions
// ==========================================

// 1. Create Migration for 'role' in users table
$migrationName = date('Y_m_d_His') . '_add_role_to_users_table.php';
$migrationPath = $base . '/database/migrations/' . $migrationName;
$migrationCode = <<<'PHP'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('editor')->after('email');
            }
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
    }
};
PHP;
file_put_contents($migrationPath, $migrationCode);
echo "Created migration for role.\n";

// 2. Create RoleMiddleware
$middlewareDir = $base . '/app/Http/Middleware';
if (!is_dir($middlewareDir))
    mkdir($middlewareDir, 0755, true);
$roleMiddlewareCode = <<<'PHP'
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
PHP;
file_put_contents($middlewareDir . '/CheckRole.php', $roleMiddlewareCode);

// 3. Register Middleware in bootstrap/app.php
$appPhpPath = $base . '/bootstrap/app.php';
$appPhp = file_get_contents($appPhpPath);
if (strpos($appPhp, "'role' => \App\Http\Middleware\CheckRole::class") === false) {
    $appPhp = str_replace(
        "'admin' => \App\Http\Middleware\AdminMiddleware::class,",
        "'admin' => \App\Http\Middleware\AdminMiddleware::class,\n            'role' => \App\Http\Middleware\CheckRole::class,",
        $appPhp
    );
    file_put_contents($appPhpPath, $appPhp);
}

// 4. Update UserController fields for Role
$userControllerPath = $base . '/app/Http/Controllers/Admin/UserController.php';
$ucContent = file_get_contents($userControllerPath);
$ucContent = str_replace(
    "'password' => 'required|min:8|confirmed',",
    "'password' => 'required|min:8|confirmed',\n            'role' => 'required|in:super_admin,admin,editor',",
    $ucContent
);
$ucContent = str_replace(
    "'password' => 'nullable|min:8|confirmed',",
    "'password' => 'nullable|min:8|confirmed',\n            'role' => 'required|in:super_admin,admin,editor',",
    $ucContent
);
file_put_contents($userControllerPath, $ucContent);

// Update users form blade
$userFormPath = $base . '/resources/views/admin/users/_form.blade.php';
if (file_exists($userFormPath)) {
    $ufContent = file_get_contents($userFormPath);
    if (strpos($ufContent, 'name="role"') === false) {
        $roleField = <<<BLADE
<div class="form-group mb-4">
    <label class="form-label">Rôle</label>
    <select name="role" class="form-control" required>
        <option value="editor" {{ (old('role', \$user->role ?? '') == 'editor') ? 'selected' : '' }}>Éditeur</option>
        <option value="admin" {{ (old('role', \$user->role ?? '') == 'admin') ? 'selected' : '' }}>Administrateur</option>
        <option value="super_admin" {{ (old('role', \$user->role ?? '') == 'super_admin') ? 'selected' : '' }}>Super Administrateur</option>
    </select>
</div>
BLADE;
        $ufContent = str_replace('<div class="form-group mb-4">', $roleField . "\n" . '<div class="form-group mb-4">', $ufContent);
        file_put_contents($userFormPath, $ufContent);
    }
}


// ==========================================
// AREA 3: Forgot Password / Email Reset
// ==========================================
$controllersDir = $base . '/app/Http/Controllers/Admin';
$forgotController = <<<'PHP'
<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('admin.auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $status = Password::broker()->sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
                    ? back()->with(['status' => __($status)])
                    : back()->withErrors(['email' => __($status)]);
    }
}
PHP;
file_put_contents($controllersDir . '/ForgotPasswordController.php', $forgotController);

$resetController = <<<'PHP'
<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;

class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request, $token = null)
    {
        return view('admin.auth.reset-password')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('admin.login')->with('success', __($status))
                    : back()->withErrors(['email' => [__($status)]]);
    }
}
PHP;
file_put_contents($controllersDir . '/ResetPasswordController.php', $resetController);

// Update routes/web.php with roles & password reset
$routesPath = $base . '/routes/web.php';
$routesContent = file_get_contents($routesPath);

// Inject forgot password routes
$forgotRoutes = <<<'PHP'
        Route::get('password/reset', [\App\Http\Controllers\Admin\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
        Route::post('password/email', [\App\Http\Controllers\Admin\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
        Route::get('password/reset/{token}', [\App\Http\Controllers\Admin\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
        Route::post('password/reset', [\App\Http\Controllers\Admin\ResetPasswordController::class, 'reset'])->name('password.update');
PHP;

if (strpos($routesContent, 'password/reset') === false) {
    $routesContent = str_replace(
        "Route::post('login', [AuthController::class, 'login']);",
        "Route::post('login', [AuthController::class, 'login']);\n" . $forgotRoutes,
        $routesContent
    );
}

// Update Role protections in routes
$routesContent = preg_replace("/Route::middleware\(\['auth', 'admin'\]\)->group\(function \(\) \{/s", "Route::middleware(['auth', 'admin'])->group(function () {", $routesContent);
// Apply specific groups inside the main middleware
// We replace the direct resource calls to protect users and settings
$routesContent = str_replace(
    "Route::resource('users', \App\Http\Controllers\Admin\UserController::class);",
    "Route::middleware('role:super_admin')->group(function(){
            Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
        });",
    $routesContent
);
$routesContent = str_replace(
    "Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('admin.settings.index');\n        Route::put('settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('admin.settings.update');",
    "Route::middleware('role:super_admin,admin')->group(function(){
            Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('admin.settings.index');
            Route::put('settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('admin.settings.update');
        });",
    $routesContent
);
// Protect brands, models, features from editors
$routesContent = str_replace(
    "Route::resource('brands', \App\Http\Controllers\Admin\BrandController::class);
        Route::resource('models', \App\Http\Controllers\Admin\CarModelController::class);
        Route::resource('features', \App\Http\Controllers\Admin\VehicleFeatureController::class);",
    "Route::middleware('role:super_admin,admin')->group(function(){
            Route::resource('brands', \App\Http\Controllers\Admin\BrandController::class);
            Route::resource('models', \App\Http\Controllers\Admin\CarModelController::class);
            Route::resource('features', \App\Http\Controllers\Admin\VehicleFeatureController::class);
        });",
    $routesContent
);

file_put_contents($routesPath, $routesContent);

// Add 'Forgot password' link to login view
$loginViewPath = $base . '/resources/views/admin/auth/login.blade.php';
$loginView = file_get_contents($loginViewPath);
$forgotLink = '<div style="text-align:right; margin-bottom:16px;"><a href="{{ route(\'admin.password.request\') }}" style="color:#60a5fa; font-size:13px; text-decoration:none;">Mot de passe oublié ?</a></div>';
if (strpos($loginView, 'password.request') === false) {
    $loginView = str_replace(
        '<div class="form-options">',
        $forgotLink . "\n" . '<div class="form-options">',
        $loginView
    );
    file_put_contents($loginViewPath, $loginView);
}

// Create Forgot password views
$authViewDir = $base . '/resources/views/admin/auth';
$forgotView = <<<'BLADE'
<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="UTF-8"><title>Mot de passe oublié — Autos Mrini</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Reuse same login styles or base ones -->
    <style>
        body { font-family: 'Inter', sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #0f172a; color: white; }
        .card { background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.1); padding: 40px; border-radius: 16px; width: 100%; max-width: 440px; box-shadow: 0 20px 40px rgba(0,0,0,.3); }
        .form-input { width: 100%; padding: 12px 16px; background: rgba(0,0,0,.2); border: 1px solid rgba(255,255,255,.1); border-radius: 8px; color: white; margin-bottom: 20px; outline:none; }
        .btn { width: 100%; padding: 14px; background: #3b82f6; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; }
    </style>
</head>
<body>
    <div class="card">
        <h2 style="margin-bottom:10px; font-size:24px;">Mot de passe oublié</h2>
        <p style="color:#94a3b8; font-size:14px; margin-bottom:30px;">Saisissez votre e-mail pour recevoir un lien de réinitialisation sécurisé.</p>
        
        @if(session('status'))
            <div style="background:#065f46; color:#34d399; padding:12px; border-radius:8px; margin-bottom:20px; font-size:14px;">{{ session('status') }}</div>
        @endif
        
        <form method="POST" action="{{ route('admin.password.email') }}">
            @csrf
            <label style="display:block; font-size:13px; color:#cbd5e1; margin-bottom:8px;">Adresse e-mail</label>
            <input type="email" name="email" class="form-input" required autofocus>
            @error('email') <div style="color:#ef4444; font-size:13px; margin-top:-15px; margin-bottom:20px;">{{ $message }}</div> @enderror
            <button type="submit" class="btn">Envoyer le lien de réinitialisation</button>
        </form>
        <div style="text-align:center; margin-top:20px;"><a href="{{ route('admin.login') }}" style="color:#60a5fa; text-decoration:none; font-size:14px;">← Retour à la connexion</a></div>
    </div>
</body>
</html>
BLADE;
file_put_contents($authViewDir . '/forgot-password.blade.php', $forgotView);

$resetView = <<<'BLADE'
<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="UTF-8"><title>Réinitialiser le mot de passe</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #0f172a; color: white; }
        .card { background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.1); padding: 40px; border-radius: 16px; width: 100%; max-width: 440px; }
        .form-input { width: 100%; padding: 12px 16px; background: rgba(0,0,0,.2); border: 1px solid rgba(255,255,255,.1); border-radius: 8px; color: white; margin-bottom: 20px; outline:none; }
        .btn { width: 100%; padding: 14px; background: #3b82f6; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; }
    </style>
</head>
<body>
    <div class="card">
        <h2 style="margin-bottom:20px; font-size:24px;">Nouveau mot de passe</h2>
        <form method="POST" action="{{ route('admin.password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
            <label style="display:block; font-size:13px; color:#cbd5e1; margin-bottom:8px;">E-mail</label>
            <input type="email" name="email" class="form-input" value="{{ $email ?? old('email') }}" required readonly>
            
            <label style="display:block; font-size:13px; color:#cbd5e1; margin-bottom:8px;">Nouveau mot de passe</label>
            <input type="password" name="password" class="form-input" required autofocus>
            
            <label style="display:block; font-size:13px; color:#cbd5e1; margin-bottom:8px;">Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation" class="form-input" required>
            
            @error('email') <div style="color:#ef4444; font-size:13px;">{{ $message }}</div> @enderror
            @error('password') <div style="color:#ef4444; font-size:13px;">{{ $message }}</div> @enderror
            
            <button type="submit" class="btn" style="margin-top:20px;">Réinitialiser</button>
        </form>
    </div>
</body>
</html>
BLADE;
file_put_contents($authViewDir . '/reset-password.blade.php', $resetView);


// ==========================================
// AREA 1: Admin UI / UX & Sidebar Roles
// ==========================================
$adminLayoutPath = $base . '/resources/views/admin/layouts/app.blade.php';
$layoutContent = file_get_contents($adminLayoutPath);

// Apply roles to sidebar
$layoutContent = str_replace(
    '<a href="{{ route(\'admin.users.index\') }}"',
    '@if(auth()->user()->role === \'super_admin\')' . "\n" . '                <a href="{{ route(\'admin.users.index\') }}"',
    $layoutContent
);
$layoutContent = str_replace(
    "                    Utilisateurs\n                </a>",
    "                    Utilisateurs\n                </a>\n                @endif",
    $layoutContent
);

$layoutContent = str_replace(
    '<a href="{{ route(\'admin.brands.index\') }}"',
    '@if(in_array(auth()->user()->role, [\'super_admin\', \'admin\']))' . "\n" . '                <a href="{{ route(\'admin.brands.index\') }}"',
    $layoutContent
);
$layoutContent = preg_replace(
    "/                    Caractéristiques\n                <\/a>/s",
    "                    Caractéristiques\n                </a>\n                @endif",
    $layoutContent
);
$layoutContent = str_replace(
    '<a href="{{ route(\'admin.settings.index\') }}"',
    '@if(in_array(auth()->user()->role, [\'super_admin\', \'admin\']))' . "\n" . '                <a href="{{ route(\'admin.settings.index\') }}"',
    $layoutContent
);
$layoutContent = preg_replace(
    "/                    Paramètres\n                <\/a>/s",
    "                    Paramètres\n                </a>\n                @endif",
    $layoutContent
);

// CSS UI Upgrades
$premiumCSS = <<<'CSS'
        /* PREMIUM FORMS & UI OVERRIDES */
        .form-control {
            width: 100%;
            padding: 12px 16px;
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            font-family: inherit;
            font-size: 14px;
            color: var(--text-primary);
            transition: var(--transition);
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.02);
        }
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1);
        }
        .form-label {
            display: block;
            font-weight: 600;
            font-size: 13.5px;
            color: var(--text-secondary);
            margin-bottom: 6px;
        }
        .form-group {
            margin-bottom: 24px;
        }
        .card { 
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03); 
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: #ffffff;
        }
        .card-header {
            background: transparent;
            border-bottom: 1px solid #e2e8f0;
            padding: 24px;
            font-weight: 700;
            font-size: 18px;
        }
        .card-body { padding: 24px; }
        .table th { background: #f8fafc; font-weight: 700; color: #475569; font-size: 11px; letter-spacing: 0.05em; padding: 14px 24px; }
        .table td { padding: 16px 24px; color: #334155; font-size: 14px; }
        .table-responsive { overflow-x: auto; border-radius: 8px; border: 1px solid #e2e8f0; }
        
        .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s; }
        .btn-primary { background: var(--primary); color: white; border: 1px solid var(--primary-dark); box-shadow: 0 1px 2px rgba(0,0,0,0.1); }
        .btn-primary:hover { background: var(--primary-dark); transform: translateY(-1px); box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        
        /* Grid layouts for forms to look cleaner */
        .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; }
        .form-col-full { grid-column: 1 / -1; }
        
        .empty-state { text-align: center; padding: 60px 20px; color: #64748b; }
        .empty-state i { font-size: 48px; color: #cbd5e1; margin-bottom: 16px; display: block; }
CSS;

$layoutContent = str_replace('/* ── Scrollbar ──────────────────────────────────────────── */', $premiumCSS . "\n/* ── Scrollbar ──────────────────────────────────────────── */", $layoutContent);

file_put_contents($adminLayoutPath, $layoutContent);

echo "Admin upgrade pass completed.\n";
