<?php

use App\Models\Hgs;
use App\Models\HgsType;
use App\Models\HgsTypeCategory;
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
        Schema::create('hgs_type_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('hgs_types', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(HgsTypeCategory::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(HgsType::class)->nullable()->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('hgs', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->string('filename')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamp('buyed_at', precision: 0);
            $table->timestamp('canceled_at', precision: 0)->nullable();
            $table->timestamps();
        });
        Schema::create('hgs_type_category_hgs_type_hgs', function (Blueprint $table) {
            $table->foreignIdFor(HgsTypeCategory::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(HgsType::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Hgs::class)->nullable()->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hgs_type_category_hgs_type_hgs');
        Schema::dropIfExists('hgs');
        Schema::dropIfExists('hgs_types');
        Schema::dropIfExists('hgs_type_categories');
    }
};