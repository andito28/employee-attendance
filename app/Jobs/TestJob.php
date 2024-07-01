<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use App\Models\Employee\Employee;
use Illuminate\Support\Facades\DB;
use App\Models\Employee\Resignation;
use App\Services\Constant\Employee\StatusEmployee;

class TestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::transaction(function (){
            $today = Carbon::today();
            $resignations = Resignation::whereDate('date', $today)->get();

            foreach($resignations as $resign){
                $employee = Employee::find($resign->employeeId);
                if ($employee) {
                    $employee->statusId = StatusEmployee::RESIGNED_ID;
                    $employee->save();
                }
            }
        });
    }
}
