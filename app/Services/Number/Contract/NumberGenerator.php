<?php

namespace App\Services\Number\Contract;

interface NumberGenerator
{
    /**
     * @return string
     */
    public static function generate(): string;
}
