<?php

namespace App\Algorithms\Attendance;

use App\Models\Attendance\Shift;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\Constant\Activity\ActivityAction;

class ShiftAlgo
{
    public function __construct(public ? Shift $shift = null )
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

                $this->shift = Shift::create($request->all() + $createdBy);

                $this->shift->setActivityPropertyAttributes(ActivityAction::CREATE)
                    ->saveActivity("Enter new shift : {$this->shift->name},[{$this->shift->id}]");

            });

            return success($this->shift);

        } catch (\Exception $exception) {
            exception($exception);
        }
    }

    public function update(Request $request)
    {
        try {

            DB::transaction(function () use ($request) {

                $this->shift->setOldActivityPropertyAttributes(ActivityAction::UPDATE);

                $this->shift->update($request->all());

                $this->shift->setActivityPropertyAttributes(ActivityAction::UPDATE)
                    ->saveActivity("Update shift : {$this->shift->name},[{$this->shift->id}]");

            });

            return success($this->shift->fresh());

        } catch (\Exception $exception) {
            exception($exception);
        }
    }

    public function delete()
    {
        try {

            DB::transaction(function (){

                $this->shift->setOldActivityPropertyAttributes(ActivityAction::DELETE);

                $this->shift->delete();

                $this->shift->setActivityPropertyAttributes(ActivityAction::DELETE)
                    ->saveActivity("Delete shift : {$this->shift->name},[{$this->shift->id}]");

            });

            return success();

        } catch (\Exception $exception) {
            exception($exception);
        }
    }

}
