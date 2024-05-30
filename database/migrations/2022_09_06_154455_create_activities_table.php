<?php

use Database\Migrations\Traits\HasCustomMigration;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    use HasCustomMigration;


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('subType')->nullable();
            $table->string('action');
            $table->text('description')->nullable();
            $table->foreignId('reference')->nullable();
            $table->string('referenceType')->nullable();
            $table->char('causedBy')->nullable();
            $table->string('causedByName')->nullable();
            $table->string('url')->nullable();
            $table->json('properties')->nullable();

            $this->getDefaultTimestamps($table);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activities');
    }
};
