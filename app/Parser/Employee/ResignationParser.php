<?php

namespace App\Parser\Employee;

use GlobalXtreme\Parser\BaseParser;
use Illuminate\Support\Facades\Storage;

class ResignationParser extends BaseParser
{
    /**
     * @param $data
     *
     * @return array|null
     */
    public static function first($data)
    {
        if (!$data) {
            return null;
        }

        return [
            'id' => $data->id,
            'date' => $data->date,
            'reason' => $data->reason,
            'file' => Storage::url($data->file)
        ];
    }

}
