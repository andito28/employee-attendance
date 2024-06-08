<?php

namespace App\Algorithms\Shift;

use App\Models\Shift\Shift;
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

    public function createBy($model, Request $request)
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
                    ->saveActivity("Enter new " .$this->shift->getTable() . ":$this->shift->name [$this->shift->id]");

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
                    ->saveActivity("Update " . $this->shift->getTable() . ": $this->shift->name [$this->shift->id]");

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
                    ->saveActivity("Delete " . $this->shift->getTable() . ": $this->shift->name [$model->id]");

            });

            return success();

        } catch (\Exception $exception) {
            exception($exception);
        }
    }

}
