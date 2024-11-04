<?php

namespace App\Http\Traits;

trait LanguageToggle
{
    public function t($attribute){
        $table_attribute = $attribute . '_' . app()->getLocale();
        return $this->$table_attribute;
    }
}
