<?php

namespace App\Models\Employee;

use App\Models\BaseModel;
use App\Models\Employee\User;
use App\Models\Attendance\Leave;
use App\Models\Employee\Sibling;
use App\Models\Employee\Parental;
use App\Services\Constant\Employee\RoleUser;
use App\Models\Attendance\Timesheet;
use App\Models\Component\Department;
use App\Models\Employee\Resignation;
use Illuminate\Support\Facades\Hash;
use App\Models\Component\CompanyOffice;
use App\Parser\Employee\EmployeeParser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Employee\Traits\HasActivityEmployeeProperty;

class Employee extends BaseModel
{
    use HasFactory;
    use HasActivityEmployeeProperty;

    protected $table = 'employees';
    protected $guarded = ['id'];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::DELETED_AT => 'datetime'
    ];

    public $parserClass = EmployeeParser::class;

    /** --- RELATIONSHIPS --- */

    public function user()
    {
        return $this->hasOne(User::class, 'employeeId');
    }

    public function companyOffice()
    {
        return $this->belongsTo(CompanyOffice::class, 'companyOfficeId');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'departmentId');
    }

    public function parental()
    {
        return $this->hasOne(Parental::class, 'employeeId');
    }

    public function siblings()
    {
        return $this->hasMany(Sibling::class, 'employeeId');
    }

    public function resignations()
    {
        return $this->hasMany(Resignation::class, 'employeeId');
    }

    public function leave()
    {
        return $this->hasMany(Leave::class, 'employeeId');
    }

    public function timesheet()
    {
        return $this->hasMany(Timesheet::class, 'employeeId');
    }

    /** --- SCOPES --- */

    public function scopeFilter($query, $request)
    {
        return $query->where(function ($query) use ($request) {

            if ($this->hasSearch($request)) {
                $query->where('number', 'LIKE', "%$request->search%")
                    ->orWhere('name', 'LIKE', "%$request->search%");
            }

        });
    }

     /** --- FUNCTIONS --- */

    public function delete()
    {
        $this->user()->delete();
        $this->parental()->delete();
        $this->siblings()->delete();
        $this->resignations()->delete();

        return parent::delete();
    }

    public function saveUser($data)
    {
        $user = $this->user;

        if($user) {
            $user->update(['email' => $data['email']]);
        } else {
            User::create([
                'employeeId' => $this->id,
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'roleId' => RoleUser::EMPLOYEE_ID
            ]);
        }
    }

    public function saveParent($data)
    {
        $parental = $this->parental;

        $dataInput = [
            'fatherName' => $data['fatherName'],
            'fatherPhone' => $data['fatherPhone'],
            'fatherEmail' => $data['fatherEmail'],
            'motherName' => $data['motherName'],
            'motherPhone' => $data['motherPhone'],
            'motherEmail' => $data['motherEmail']
        ];

        if($parental){
            $parental->update($dataInput);
        }else{
            $employeeId = ['employeeId' => $this->id];
            Parental::create($dataInput + $employeeId);
        }
    }

    public function saveSiblings($data,$createdBy = null)
    {
        $siblings = $this->siblings;

            $processedSiblings = [];
            foreach ($data['siblings'] as $siblingData) {
                $siblingId = $siblingData['id'] ?? null;

                if ($siblingId)
                {
                    $sibling = Sibling::where('id',$siblingId)
                    ->where('employeeId',$this->id)->first();
                        if (!$sibling)
                        {
                            errEmployeeSiblingsGet(
                                "employee ID:".$this->id." & "."sibling ID:". $siblingData['id']
                            );
                        }
                } else {
                    $sibling = new Sibling();
                }

                if($createdBy){
                    $sibling->createdBy = $createdBy['createdBy'];
                    $sibling->createdByName = $createdBy['createdByName'];
                }

                $sibling->employeeId = $this->id;
                $sibling->name = $siblingData['name'];
                $sibling->email = $siblingData['email'] ?? null;
                $sibling->phone = $siblingData['phone'] ?? null;
                $sibling->save();

                $processedSiblings[] = $sibling->id;
            }

            Sibling::where('employeeId', $this->id)
                ->whereNotIn('id', $processedSiblings)
                ->delete();
    }

}
