<?php

namespace Softworx\RocXolid\Http\Controllers\Traits;

use Illuminate\Support\Str;
// rocXolid utils
use Softworx\RocXolid\Http\Requests\CrudRequest;

/**
 * Helper trait to connect elements to the controller.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait ElementMappable
{
    /**
     * Get element param based on action.
     *
     * @param Softworx\RocXolid\Http\Requests\CrudRequest $request
     * @param string $element_signature
     * @param string $default
     * @return string
     * @throws \InvalidArgumentException
     */
    protected function getMappingParam(CrudRequest $request, string $element_signature, string $default): string
    {
        $method = $request->route()->getActionMethod();
        $property = sprintf('%s_mapping', $element_signature);

        if ($request->filled('_section'))
        {
            $method = sprintf('%s.%s', $method, $request->_section);

            if (isset($this->$property[$method]))
            {
                return $this->$property[$method];
            }
        }

        if (isset($this->$property[$method])) {
            return $this->$property[$method];
        } elseif (isset($default)) {
            return $default;
        } elseif (empty($this->$property)) {
            return $default;
        }

        throw new \InvalidArgumentException(sprintf('No controller [%s] %s mapping for method [%s]', get_class($this), $element_signature, $method));
    }

    /**
     * Get element type based on param.
     *
     * @param string $element_signature
     * @param string $param
     * @return string
     */
    protected function getMappingType(string $element_signature, string $param): string
    {
        $property = sprintf('%s_type', $element_signature);

        if (isset(static::$$property) && isset(static::$$property[$param])) {
            return static::$$property[$param];
        } else {
            $reflection = new \ReflectionClass($this);
            $replace = sprintf('Models\%s', Str::plural(Str::studly($element_signature)));
            $namespace = str_replace('Http\Controllers', $replace, $reflection->getNamespaceName());

            $type = sprintf('%s\%s', $namespace, Str::studly($param));

            return $type;
        }
    }
}
