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
     */
    public function up(): void
    {
        Schema::create('parentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employeeId');
            $table->string('fatherName');
            $table->string('fatherPhone')->nullable();
            $table->string('fatherEmail')->nullable();
            $table->string('motherName');
            $table->string('motherPhone')->nullable();
            $table->string('motherEmail')->nullable();
            $this->getDefaultTimestamps($table);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parentals');
    }
};
