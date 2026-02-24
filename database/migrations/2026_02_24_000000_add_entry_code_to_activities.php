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
        Schema::table('activities', function (Blueprint $table) {
            if (!Schema::hasColumn('activities', 'entry_code')) {
                $table->string('entry_code')->unique()->after('id');
            }
            if (!Schema::hasColumn('activities', 'requires_entry_code')) {
                $table->boolean('requires_entry_code')->default(true)->after('entry_code');
            }
            if (!Schema::hasColumn('activities', 'archived_at')) {
                $table->dateTime('archived_at')->nullable()->after('updated_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            if (Schema::hasColumn('activities', 'entry_code')) {
                $table->dropColumn('entry_code');
            }
            if (Schema::hasColumn('activities', 'requires_entry_code')) {
                $table->dropColumn('requires_entry_code');
            }
            if (Schema::hasColumn('activities', 'archived_at')) {
                $table->dropColumn('archived_at');
            }
        });
    }
};
