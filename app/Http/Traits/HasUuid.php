<?php

namespace App\Http\Traits;

use Illuminate\Support\Str;

trait HasUuid
{
    /**
     * Boot function from Laravel.
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$this->uuid ?? 'uuid'})) {
                $model->{$this->uuid ?? 'uuid'} = Str::uuid()->toString();
            }
        });
    }

    /**
     * Get the value indicating whether the IDs are incrementing.
     *
     * @return bool
     */
    public function getIncrementing()
    {
        return true;
    }
}
