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
        Schema::create('material_sources', function (Blueprint $table) {
            $table->id();

            // Basic Info
            $table->string('material_source_name');
            $table->string('access_road');
            $table->string('directional_flow');
            $table->string('source_type');
            $table->string('potential_uses')->nullable();
            $table->text('future_use_recommendation')->nullable();
            $table->string('province');
            $table->string('municipality');
            $table->string('barangay');

            // Renewability
            $table->string('renewability')->nullable(); // yes/no

            // Site & Equipment
            $table->text('processing_plant_info')->nullable();
            $table->text('observations')->nullable();

            // Permit & Test
            $table->string('quarry_permit')->nullable(); // file path
            $table->date('quarry_permit_date')->nullable();
            $table->string('permittee_name')->nullable();
            $table->string('quality_test_attachment')->nullable(); // file path
            $table->date('quality_test_date')->nullable();
            $table->string('quality_test_result')->nullable(); // Passed/Failed

            // Metadata
            $table->string('prepared_by')->nullable();
            $table->integer('user_id');
            $table->integer('user_id_validation')->default(3);
            $table->string('reason_status')->nullable();
            $table->string('status')->nullable();
            $table->string('region')->nullable();
            $table->decimal('latitude', 10, 7);  
            $table->decimal('longitude', 10, 7);
            $table->date('deleted_at')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_sources');
    }
};
