<?php

namespace App\ThirdParty\Validation\Services;

use App\ThirdParty\Validation\ServiceValidation;

class ExampleValidation extends ServiceValidation
{
    // Service name
    const CLIENT = 'example';

    // URL
    const URI = ServiceValidation::URI + [
        'CHECK_TESTING' => 'testing/validation',
    ];


    /** --- FUNCTIONS --- */

    /**
     * @param $payload
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function testing($payload)
    {
        $url = static::host();
        $url .= static::URI['BASE'] . static::URI['CHECK_TESTING'];

        return static::call($url, $payload, 'post');
    }

}
