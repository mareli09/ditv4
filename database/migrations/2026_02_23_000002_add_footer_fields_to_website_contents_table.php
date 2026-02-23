<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFooterFieldsToWebsiteContentsTable extends Migration
{
    public function up()
    {
        Schema::table('website_contents', function (Blueprint $table) {
            if (!Schema::hasColumn('website_contents', 'privacy_policy')) {
                $table->text('privacy_policy')->nullable();
            }
            if (!Schema::hasColumn('website_contents', 'terms_of_service')) {
                $table->text('terms_of_service')->nullable();
            }
            if (!Schema::hasColumn('website_contents', 'accessibility')) {
                $table->text('accessibility')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('website_contents', function (Blueprint $table) {
            $table->dropColumn(['privacy_policy', 'terms_of_service', 'accessibility']);
        });
    }
}