<?php

use App\Models\VehicleBrand;
use App\Models\VehicleTicket;
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
        Schema::create('vehicle_models', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('vehicle_brand_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignUuid('vehicle_ticket_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('name')->index();
            $table->string('slug')->nullable();
            $table->boolean('status')->default(true);
            $table->string('insurance_number')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_models');
    }
};
