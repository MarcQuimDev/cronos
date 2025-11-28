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
            // Add indexes for frequently queried columns
            $table->index('timestamp', 'idx_sensor_data_timestamp');
            $table->index('temperatura', 'idx_sensor_data_temperatura');
            $table->index('humitat', 'idx_sensor_data_humitat');
            $table->index('pressio', 'idx_sensor_data_pressio');
            $table->index('topic', 'idx_sensor_data_topic');

            // Composite index for common query patterns (filtering + sorting)
            $table->index(['timestamp', 'temperatura'], 'idx_sensor_data_timestamp_temperatura');
            $table->index(['timestamp', 'humitat'], 'idx_sensor_data_timestamp_humitat');
            $table->index(['timestamp', 'pressio'], 'idx_sensor_data_timestamp_pressio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sensor_data', function (Blueprint $table) {
            // Drop indexes in reverse order
            $table->dropIndex('idx_sensor_data_timestamp_pressio');
            $table->dropIndex('idx_sensor_data_timestamp_humitat');
            $table->dropIndex('idx_sensor_data_timestamp_temperatura');
            $table->dropIndex('idx_sensor_data_topic');
            $table->dropIndex('idx_sensor_data_pressio');
            $table->dropIndex('idx_sensor_data_humitat');
            $table->dropIndex('idx_sensor_data_temperatura');
            $table->dropIndex('idx_sensor_data_timestamp');
        });
    }
};
