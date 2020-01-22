<?php

namespace Softworx\RocXolid\Http\Controllers\Traits\Utils;

use Softworx\RocXolid\Components\General\Message;

/**
 * Utility trait to shorthand translation via controller.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait Translates
{
    /**
     * Translate the language key.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request
     * @param int $id
     */
    public function translate(string $key, array $params = [], bool $use_raw_key = false): string
    {
        return Message::build($this, $this)->translate($key, $params, $use_raw_key);
    }
}
