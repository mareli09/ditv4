<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebsiteContentsTable extends Migration
{
    public function up()
    {
        Schema::create('website_contents', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Unique key for each content section
            $table->text('value'); // Content value
            $table->json('carousel_images')->nullable(); // JSON field for storing carousel images
            $table->text('about_description')->nullable();
            $table->text('mission')->nullable();
            $table->text('vision')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('website_contents');
    }
}