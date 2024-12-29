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
        Schema::create('dealer_selections', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid(column: 'dealer_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignUuid(column: 'dealer_address_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignUuid(column: 'dealer_officer_id')->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dealer_selections');
    }
};
