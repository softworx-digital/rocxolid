<?php

namespace Softworx\RocXolid\Models\Traits;

use App;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;

trait Translatable
{
    public function translation($language = null)
    {
        if ($language == null) {
            $language = App::getLocale();
        }

        return $this->hasMany($this->getTranslationsClass())->where('language', '=', $language);
    }

    protected function getTranslationsClass()
    {
        return sprintf('%sTranslation', static::class);
    }
}
