<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'admin', 'editor', 'user') DEFAULT 'editor'");
    }
    public function down(): void
    {
    // cannot easily undo without knowing previous defaults safely, this is fine
    }
};
