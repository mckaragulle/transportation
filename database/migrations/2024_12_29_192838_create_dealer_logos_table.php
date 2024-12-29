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
        Schema::create('dealer_logos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('dealer_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('filename');
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
        Schema::dropIfExists('dealer_logos');
    }
};