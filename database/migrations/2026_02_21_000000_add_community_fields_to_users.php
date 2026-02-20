<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('last_name')->nullable()->after('name');
            $table->string('first_name')->nullable()->after('last_name');
            $table->string('middle_name')->nullable()->after('first_name');
            $table->integer('age')->nullable()->after('middle_name');
            $table->string('gender')->nullable()->after('age');
            $table->text('address')->nullable()->after('gender');
            $table->string('barangay')->nullable()->after('address');
            $table->string('phone')->nullable()->after('barangay');
            $table->boolean('previously_joined')->default(0)->after('phone');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'last_name',
                'first_name',
                'middle_name',
                'age',
                'gender',
                'address',
                'barangay',
                'phone',
                'previously_joined'
            ]);
        });
    }
};
