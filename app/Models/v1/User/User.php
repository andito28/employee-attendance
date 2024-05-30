<?php

namespace App\Models\v1\User;

use App\Models\BaseModel;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\v1\User\Traits\HasActivityUserProperty;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends BaseModel implements JWTSubject
{
    use HasActivityUserProperty;
    public $paserClass = UserParser::class;

    // protected $table = '';
    protected $guarded = ['id'];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::DELETED_AT => 'datetime'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

}
