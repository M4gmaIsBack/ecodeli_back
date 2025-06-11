<?php

// database/migrations/xxxx_xx_xx_xxxxxx_add_preferences_to_clients_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('clients', function (Blueprint $table) {
            $table->boolean('conditions')->default(false);
            $table->boolean('newsletter')->default(false);
            $table->boolean('notifications')->default(false);
        });
    }

    public function down(): void {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['conditions', 'newsletter', 'notifications']);
        });
    }
};
