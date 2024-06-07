<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Employee\User;
use Illuminate\Console\Command;
use App\Models\Employee\Employee;
use Illuminate\Support\Facades\DB;
use App\Models\Employee\Resignation;
use App\Services\Constant\StatusEmployee;

class UpdateResignationStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::transaction(function (){
            $today = Carbon::today();
            $resignations = Resignation::whereDate('date', $today)->get();

            foreach($resignations as $resign){
                $employee = Employee::find($resign->employeeId);
                if ($employee) {
                    $employee->statusId = StatusEmployee::RESIGNED_ID;
                    $employee->save();

                    $user = User::where('employeeId', $employee->id)->first();
                    $user->delete();
                }
            }
        });

    }
}
