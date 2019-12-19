<?php

namespace Softworx\RocXolid\Providers;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Illuminate\Support\Facades\Validator;
use Softworx\RocXolid\Forms\Validation\Validator as RocXolidValidator;

/**
 * rocXolid request validation service provider.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
class ValidationServiceProvider extends IlluminateServiceProvider
{
    /**
     * Extend the default request validator.
     *
     * @return \Illuminate\Support\ServiceProvider
     */
    public function boot()
    {
        //Validator::extend('only_one', 'Softworx\RocXolid\Forms\Validation\Validator@validateOnlyOne');
        Validator::resolver(function ($translator, $data, $rules, $messages) {
            return new RocXolidValidator($translator, $data, $rules, $messages);
        });

        return $this;
    }
}
