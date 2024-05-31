<?php

namespace Database\Migrations\Traits;

use Illuminate\Database\Schema\Blueprint;

trait HasCustomMigration
{
    /**
     * @param Blueprint $table
     *
     * @return void
     */
    public function getDefaultTimestamps(Blueprint $table)
    {
        $table->timestamp('createdAt')->nullable();
        $table->timestamp('updatedAt')->nullable();
        $table->softDeletes('deletedAt');
    }

    /**
     * @param Blueprint $table
     *
     * @return void
     */
    public function getDefaultCreatedBy(Blueprint $table)
    {
        $table->char('createdBy')->nullable();
        $table->string('createdByName')->nullable();
    }

}
