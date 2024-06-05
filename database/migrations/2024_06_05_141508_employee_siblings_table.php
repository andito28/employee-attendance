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
        Schema::create('employee_siblings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employeeId');
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $this->getDefaultCreatedBy($table);
            $this->getDefaultTimestamps($table);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_siblings');
    }
};
