<?php
use Database\Migrations\Traits\HasCustomMigration;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    use HasCustomMigration;

    public function up(): void
    {
        Schema::create('component_company_offices', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10);
            $table->string('name', 150);

            $this->getDefaultCreatedBy($table);
            $this->getDefaultTimestamps($table);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('component_company_offices');
    }
};
