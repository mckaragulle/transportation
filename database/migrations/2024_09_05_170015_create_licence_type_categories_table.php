<?php

use App\Models\HgsTypeCategory;
use App\Models\Licence;
use App\Models\LicenceType;
use App\Models\LicenceTypeCategory;
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
        Schema::create('licence_type_categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->boolean('status')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('licence_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('licence_type_category_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->boolean('status')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('licences', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('number');
            $table->string('filename')->nullable();
            $table->text('detail')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamp('started_at', precision: 0);
            $table->timestamp('finished_at', precision: 0)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('licence_type_category_licence_type_licence', function (Blueprint $table) {
            $table->foreignUuid('licence_type_category_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignUuid('licence_type_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignUuid('licence_id')->nullable()->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licence_type_category_licence_type_licence');
        Schema::dropIfExists('licences');
        Schema::dropIfExists('licence_types');
        Schema::dropIfExists('licence_type_categories');
    }
};
