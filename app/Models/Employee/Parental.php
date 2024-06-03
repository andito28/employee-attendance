<?php

namespace App\Models\Employee;

use App\Models\BaseModel;
use App\Models\Employee\Employee;
use App\Models\Employee\Traits\HasActivityParentalProperty;

class Parental extends BaseModel
{
    use HasActivityParentalProperty;

    // protected $table = '';
    protected $guarded = ['id'];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::DELETED_AT => 'datetime'
    ];

    public $parserClass = ParentalParser::class;


     /** --- RELATIONSHIPS --- */

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

}
