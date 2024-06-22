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
        Schema::create('attendance_timesheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employeeId');
            $table->foreignId('shiftId');
            $table->date('date');
            $table->dateTime('clockIn')->nullable();
            $table->dateTime('clockOut')->nullable();
            $table->integer('statusId');
            $this->getDefaultTimestamps($table);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_timesheets');
    }
};
