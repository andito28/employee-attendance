<?php

namespace App\Parser\Component;

use GlobalXtreme\Parser\BaseParser;

class CompanyOfficeParser extends BaseParser
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
            'code' => $data->code,
            'name' => $data->name,
            'createdBy' => $data->createdByName,
            'createdAt' => $data->createdAt?->format('d/m/Y H:i'),
        ];
    }

}
