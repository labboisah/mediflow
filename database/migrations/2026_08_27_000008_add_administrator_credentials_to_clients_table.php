<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('clients')) {
            return;
        }

        Schema::table('clients', function (Blueprint $table) {
            if (! Schema::hasColumn('clients', 'administrator_email')) {
                $table->string('administrator_email')->nullable()->after('phone')->index();
            }

            if (! Schema::hasColumn('clients', 'administrator_password')) {
                $table->text('administrator_password')->nullable()->after('administrator_email');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('clients')) {
            return;
        }

        Schema::table('clients', function (Blueprint $table) {
            if (Schema::hasColumn('clients', 'administrator_password')) {
                $table->dropColumn('administrator_password');
            }

            if (Schema::hasColumn('clients', 'administrator_email')) {
                $table->dropColumn('administrator_email');
            }
        });
    }
};
