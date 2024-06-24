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
        Schema::create('attendance_timesheet_corrections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employeeId');
            $table->date('date');
            $table->time('clockIn');
            $table->time('clockOut');
            $table->integer('statusId');
            $table->integer('approvalId');
            $table->text('notes')->nullable();
            $table->char('approveddBy')->nullable();
            $table->string('approvedByName')->nullable();
            $this->getDefaultTimestamps($table);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_timesheet_corrections');
    }
};
