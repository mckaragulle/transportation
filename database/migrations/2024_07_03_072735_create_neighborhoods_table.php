<?php

use App\Models\City;
use App\Models\District;
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
        Schema::create('neighborhoods', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(City::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(District::class)->nullable()->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('postcode');
            $table->string('slug')->unique()->nullable();
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
        Schema::dropIfExists('neighborhoods');
    }
};
