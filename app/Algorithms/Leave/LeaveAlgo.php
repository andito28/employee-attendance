<?php

namespace App\Algorithms\Leave;

use App\Models\Leave\Leave;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Services\Constant\Activity\ActivityAction;

class LeaveAlgo
{
    public function __construct(public ? Leave $leave = null )
    {
    }

    public function create(Request $request)
    {
        try {

            DB::transaction(function () use ($request) {

                $user = auth()->user();

                $createdBy = [
                    'createdBy' =>  $user->employee->id,
                    'createdByName' =>  $user->employee->name
                ];

                $this->leave = Leave::create($request->all() + $createdBy);

                $this->leave->setActivityPropertyAttributes(ActivityAction::CREATE)
                    ->saveActivity("Enter new " .$this->leave->getTable() . ":[$this->leave->id]");

            });

            return success($this->leave);

        } catch (\Exception $exception) {
            exception($exception);
        }
    }

}
