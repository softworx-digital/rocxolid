<?php

namespace Softworx\RocXolid\Forms\Traits;

use Softworx\RocXolid\Helpers\View as ViewHelper;
use Softworx\RocXolid\Forms\Contracts\Form;

/**
 *
 */
trait OptionsSetter
{
    /**
     * Valid HTTP methods.
     *
     * @var array
     */
    private static $methods = [
        'get', // requests data from a specified resource
        'post', // submits data to be processed to a specified resource
        'put', // uploads a representation of the specified URI
        'delete', // deletes the specified resource
    ];

    public function processFormOptions(): Form
    {
        if (!empty($this->options)) {
            $this->setOptions($this->options);
        }

        $this->mergeOptions([
            'component' => [
                'id' => ViewHelper::domId($this, 'form')
            ]
        ]);

        return $this;
    }

    public function setCustomOptions($custom_options): Form
    {
        $this->mergeOptions($custom_options);

        return $this;
    }

    protected function setMethod($method): Form
    {
        if (!in_array(strtolower($method), self::$methods)) {
            throw new \InvalidArgumentException(sprintf('Invalid method [%s]. Valid methods: [%s]', $method, print_r(self::$methods, true)));
        }

        $this->mergeOptions([
            'component' => [
                'method' => strtolower($method)
            ]
        ]);

        return $this;
    }

    protected function setRoute($route_name): Form
    {
        $this->mergeOptions([
            'component' => [
                'url' => $this->makeRoute($route_name)
            ]
        ]);

        return $this;
    }

    protected function setRouteAction($route_action): Form
    {
        $this->mergeOptions([
            'component' => [
                'url' => $this->makeRouteAction($route_action)
            ]
        ]);

        return $this;
    }

    protected function setClass($class): Form
    {
        $this->mergeOptions([
            'component' => [
                'class' => $class
            ]
        ]);

        return $this;
    }

    protected function setSection($section): Form
    {
        $this->mergeOptions([
            'component' => [
                'section' => $section
            ]
        ]);

        return $this;
    }

    protected function setTemplate($template): Form
    {
        $this->mergeOptions([
            'component' => [
                'template' => $template
            ]
        ]);

        return $this;
    }

    protected function setShowBackButton($param): Form
    {
        $this->mergeOptions([
            'component' => [
                'show-back-button' => $param
            ]
        ]);

        return $this;
    }
}
