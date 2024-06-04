<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteAttendances implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $employee;

    /**
     * Create a new job instance.
     */
    public function __construct($employee)
    {
        $this->employee = $employe;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->employee->attendances()->delete();
    }
}
