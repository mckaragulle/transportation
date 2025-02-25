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
        Schema::create('vehicle_tickets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('vehicle_brand_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('name')->unique()->index();
            $table->string('slug')->nullable()->unique()->index();
            $table->boolean('status')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_tickets');
    }
};
