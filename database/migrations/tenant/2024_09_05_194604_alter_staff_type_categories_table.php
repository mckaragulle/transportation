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

        Schema::table('staff_types', function (Blueprint $table) {
            $table->foreignUuid('staff_type_id')->nullable()->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
    }
};
