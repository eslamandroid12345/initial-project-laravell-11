<?php

namespace App\Http\Services;

abstract class PlatformService
{
    abstract public static function platform(): string;
}
