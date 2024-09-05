<?php

use App\Models\HgsTypeCategory;
use App\Models\Staff;
use App\Models\StaffType;
use App\Models\StaffTypeCategory;
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
        Schema::create('staff_type_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('staff_types', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(StaffTypeCategory::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(StaffType::class)->nullable()->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('id_number');
            $table->string('name');
            $table->string('surname')->nullable();
            $table->string('slug');
            $table->string('phone1')->nullable();
            $table->string('phone2')->nullable();
            $table->string('email')->nullable();
            $table->text('detail')->nullable();
            $table->string('filename')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
        
        Schema::create('staff_type_category_staff_type_staff', function (Blueprint $table) {
            $table->foreignIdFor(StaffTypeCategory::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(StaffType::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Staff::class)->nullable()->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_type_category_staff_type_staff');
        Schema::dropIfExists('staff');
        Schema::dropIfExists('staff_types');
        Schema::dropIfExists('staff_type_categories');
    }
};
