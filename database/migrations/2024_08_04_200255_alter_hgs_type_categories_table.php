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
        Schema::table('hgs_types', function (Blueprint $table) {
            $table->foreignUuid('hgs_type_id')->nullable()->constrained()->cascadeOnDelete();
        });
        Schema::create('hgs_type_category_hgs_type_hgs', function (Blueprint $table) {
            $table->foreignUuid('hgs_type_category_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignUuid('hgs_type_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignUuid('hgs_id')->nullable()->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hgs_type_category_hgs_type_hgs');
    }
};
