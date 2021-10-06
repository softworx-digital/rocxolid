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
 * @todo refactor - verify if setting specific type would not be better than parametrizing
 * @todo use other expression than element
 */
trait ElementMappable
{
    /**
     * Get mapping key from request.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request
     * @param string $element_signature
     * @param array|null $default
     * @return array
     */
    protected function getMappingOptions(CrudRequest $request, string $element_signature, ?array $default = null): array
    {
        if ($request->filled('_param')) {
            return [ 'param' => $request->_param ];
        } elseif ($request->filled('_section')) {
            return [ 'section' => $request->_section ];
        }

        return $default ?? [];
    }

    /**
     * Get element param based on request (action, section and param).
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param string $element_signature
     * @param string $default
     * @return string
     * @throws \InvalidArgumentException
     */
    protected function getMappingParam(CrudRequest $request, string $element_signature, string $default): string
    {
        $action = $request->route()->getActionMethod();
        $property = sprintf('%s_mapping', $element_signature);
        $mapping = collect($this->$property);

        if ($request->filled('_param')) {
            if (!$mapping->contains($request->_param)) {
                throw new \InvalidArgumentException(sprintf(
                    'Invalid _param [%s] sent in request for %s [%s]',
                    $request->_param,
                    $element_signature,
                    get_class($this)
                ));
            }

            return $request->_param;
        }

        if ($request->filled('_section')) {
            $section = sprintf('%s.%s', $action, $request->_section);

            if (!$mapping->has($section)) {
                throw new \InvalidArgumentException(sprintf(
                    'Invalid _section [%s].[%s] sent in request for %s [%s]',
                    $request->route()->getActionMethod(),
                    $request->_section,
                    $element_signature,
                    get_class($this)
                ));
            }

            return $mapping->get($section);
        }

        if ($mapping->has($action)) {
            return $mapping->get($action);
        } elseif (isset($default)) {
            return $default;
        } elseif ($mapping->isEmpty()) {
            return $default;
        }

        throw new \InvalidArgumentException(sprintf(
            'No controller [%s] %s mapping for action [%s]',
            get_class($this),
            $element_signature,
            $action
        ));
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
