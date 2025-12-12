<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sensor_data', function (Blueprint $table) {
            $table->decimal('brillantor', 5, 2)->nullable()->after('pressio');
            $table->decimal('eco2', 8, 2)->nullable()->after('brillantor');
            $table->decimal('tvoc', 8, 2)->nullable()->after('eco2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sensor_data', function (Blueprint $table) {
            $table->dropColumn(['brillantor', 'eco2', 'tvoc']);
        });
    }
};
