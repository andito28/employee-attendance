<?php

namespace App\Models\v1\Activity;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Activity extends BaseModel
{
    protected $table = 'activities';
    protected $guarded = ['id'];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::DELETED_AT => 'datetime',
        'properties' => 'array',
    ];


    /** RELATIONSHIPS */

    public function referable(): MorphTo
    {
        return $this->morphTo('referable', 'referenceType', 'reference');
    }


    /** --- SCOPES --- */

    public function scopeFilter($query, $request)
    {
        return $query->where(function ($query) use ($request) {

            if ($request->type != '') {
                $query->where('type', $request->type);
            }

            if ($request->action != '') {
                $query->where('action', $request->action);
            }

            if ($this->hasSearch($request)) {
                $query->where(function ($query) use ($request) {
                    $query->where('description', 'LIKE', "%$request->search%")
                        ->orWhere('causedByName', 'LIKE', "%$request->search%");
                });
            }

        });
    }

}
