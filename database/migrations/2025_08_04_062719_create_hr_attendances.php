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
        Schema::create('hr_attendances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('contract_id')->constrained('hr_contracts')->cascadeOnDelete();
            $table->date('date');
            $table->time('start_time');
            $table->string('start_photo');
            $table->time('end_time')->nullable();
            $table->string('end_photo')->nullable();
            $table->decimal('overtime_rates', 15, 2)->nullable();
            $table->decimal('docking_pay', 15, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_attendances');
    }
};
