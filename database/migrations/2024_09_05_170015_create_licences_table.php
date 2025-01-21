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
            $table->id();
            $table->uuid('licence_type_category_id');
            $table->uuid('licence_type_id');
            $table->foreignUuid('licence_id');
            $table->timestamps();

            // Yabancı anahtarlar
            // $table->foreign('licence_type_category_id')->references('id')->on('main.licence_type_categories');
            // $table->foreign('licence_type_id')->references('id')->on('main.licence_types');
            // $table->foreign('licence_id')->references('id')->on('licences');
            // $table->foreignUuid('licence_type_category_id')->nullable()->constrained()->cascadeOnDelete();
            // $table->foreignUuid('licence_type_id')->nullable()->constrained()->cascadeOnDelete();
            // $table->foreignUuid('licence_id')->nullable()->constrained()->cascadeOnDelete();
            // $table->uuid('licence_id');
            // $table->uuid('licence_type_category_id');
            // $table->uuid('licence_type_id');
            // $table->foreign('licence_id')
            //         ->references('id')
            //         ->on(connection('pgsql')->getTablePrefix() . 'licences') // Lisans tablosu için bağlantıyı belirtin
            //         ->onDelete('cascade');
                    
            //     $table->foreign('licence_type_category_id')
            //         ->references('id')
            //         ->on(connection('pgsql_main')->getTablePrefix() . 'licence_type_categories') // Doğru bağlantıyı belirtin
            //         ->onDelete('cascade');
                    
            //     $table->foreign('licence_type_id')
            //         ->references('id')
            //         ->on(connection('pgsql_main')->getTablePrefix() . 'licence_types') // Doğru bağlantıyı belirtin
            //         ->onDelete('cascade');
        });

        // Schema::table('licence_type_category_licence_type_licence', function (Blueprint $table) {
        //     $table->foreignUuid('licence_type_category_id')->nullable()->constrained()->cascadeOnDelete();
        //     $table->foreignUuid('licence_type_id')->nullable()->constrained()->cascadeOnDelete();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licence_type_category_licence_type_licence');
        Schema::dropIfExists('licences');
    }
};
