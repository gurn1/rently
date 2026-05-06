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
      Schema::create('property_manager_tenant', function (Blueprint $table) {
        $table->foreignId('property_manager_id')->constrained('users')->cascadeOnDelete();
        $table->foreignId('tenant_id')->constrained('users')->cascadeOnDelete();
        $table->primary(['property_manager_id', 'tenant_id']);
        $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::dropIfExists('property_manager_tenant');
    }
};
