<?php

use Illuminate\Support\Str;

if (!function_exists('filename')) {

    /**
     * @param $file
     * @param $text
     * @param $keyId
     * @param string|null $extension
     *
     * @return string
     */
    function filename($file, $text, $keyId = null, string|null $extension = null): string
    {
        return ($keyId ? "$keyId-" : "") . Str::random(20) . str_shuffle(str_replace(' ', '', $text)) . '.' . ($extension ?: $file->getClientOriginalExtension());
    }

}

if (!function_exists('convert_string_to_array')) {

    /**
     * @param string|array|null $values
     *
     * @return array
     */
    function convert_string_to_array(string|array|null $values = null): array
    {
        if (!$values) {
            return [];
        }

        if (is_array($values)) {
            return $values;
        }

        $values = preg_replace('/[^A-Za-z0-9\-]/', ' ', $values);
        $values = preg_replace('/\s+/', ' ', $values);

        $values = explode(" ", $values);
        return collect($values)->filter(function ($value) {
            return $value;
        })->values()->toArray();
    }

}

if (!function_exists("snake_to_camel_case")) {

    /**
     * @param string $text
     *
     * @return string
     */
    function snake_to_camel_case(string $text)
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $text))));
    }

}

if (!function_exists("alphabet_from_number")) {

    /**
     * @param float|int|string $number
     *
     * @return bool `true` if $long is valid, `false` if not
     */
    function alphabet_from_number($number, $less = 0)
    {
        // Set default start number
        $defaultNumber = 0;

        $alphabet = 'A';
        for ($i = $alphabet; $defaultNumber <= $number; $i++) {
            if ($defaultNumber == ($number - $less)) {
                return $i;
            }
            $defaultNumber++;
        }
        return null;
    }

}

if (!function_exists("storage_link")) {

    /**
     * @param string $path
     *
     * @return string
     */
    function storage_link(string $path)
    {
        if (!$path) {
            return null;
        }

        return config('base.conf.storage-link') . $path;
    }

}
