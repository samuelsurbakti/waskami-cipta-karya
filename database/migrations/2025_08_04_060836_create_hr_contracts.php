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
        Schema::create('hr_contracts', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('title');

            // Polymorphic relation: workers OR teams (atau entitas lain di masa depan)
            $table->string('relation_type'); // contoh: App\\Models\\Worker atau App\\Models\\Team
            $table->uuid('relation_id');

            $table->foreignUuid('project_id')->nullable();
            $table->foreignUuid('type_id');

            $table->decimal('rates', 15, 2);

            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_contracts');
    }
};
