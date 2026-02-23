<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('conducted_by');
            $table->string('target_audience');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['Proposed', 'Ongoing', 'Completed'])->default('Proposed');
            $table->text('remarks')->nullable();
            $table->boolean('is_archived')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
