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
        Schema::table('licence_types', function (Blueprint $table) {
            $table->foreignUuid('licence_type_id')->nullable()->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
    }
};
