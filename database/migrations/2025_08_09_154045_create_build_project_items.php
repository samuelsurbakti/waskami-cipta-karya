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
        Schema::create('build_project_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('project_id');
            $table->enum('type', ['property', 'facility']);
            $table->string('name');
            $table->enum('status', ['planning', 'in_progress', 'completed', 'sold'])->default('planning');
            $table->uuid('client_id')->nullable();
            $table->uuid('land_asset_id')->nullable();
            $table->double('building_area')->nullable();
            $table->double('land_area')->nullable();
            $table->double('number_of_floor')->nullable();
            $table->string('front_view_photo')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('project_id')->references('id')->on('build_projects')->cascadeOnDelete();
            // $table->foreign('buyer_id')->references('id')->on('buyers')->nullOnDelete();
            // $table->foreign('land_asset_id')->references('id')->on('assets')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('build_project_items');
    }
};
