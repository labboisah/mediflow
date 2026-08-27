<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('agents')) {
            return;
        }

        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('type')->default('sales_agent')->index();
            $table->string('email')->nullable()->index();
            $table->string('phone')->nullable();
            $table->string('organization')->nullable();
            $table->decimal('commission_rate', 5, 2)->nullable();
            $table->string('status')->default('active')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};
