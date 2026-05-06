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
      Schema::create('documents', function (Blueprint $table) {
        $table->id();
        $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
        $table->foreignId('tenant_id')->constrained('users')->cascadeOnDelete();
        $table->foreignId('lease_id')->nullable()->constrained()->nullOnDelete();
        $table->foreignId('property_id')->nullable()->constrained()->cascadeOnDelete();
        $table->string('title');
        $table->text('path');
        $table->string('document_type');
        $table->boolean('requires_signature')->default(false);
        $table->boolean('is_signed')->default(false);
        $table->timestamp('signed_at')->nullable();
        $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::dropIfExists('documents');
    }
};
