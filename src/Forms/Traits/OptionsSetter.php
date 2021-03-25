<?php

namespace Softworx\RocXolid\Forms\Traits;

use Softworx\RocXolid\Helpers\View as ViewHelper;
use Softworx\RocXolid\Forms\Contracts\Form;

/**
 * @todo refactor
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

    public function adjustFormOptions(): Form
    {
        return $this;
    }

    public function processFormOptions(): Form
    {
        if (!empty($this->options)) {
            // @todo hotfixed
            $options = is_array($this->options) ? collect($this->options) : $this->options;

            $this->setOptions($options->whereNotNull()->toArray());
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

    protected function setMethod(string $method): Form
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

    protected function setRoute(string $route_name): Form
    {
        $this->mergeOptions([
            'component' => [
                'url' => $this->makeRoute($route_name)
            ]
        ]);

        return $this;
    }

    protected function setRouteAction(string $route_action): Form
    {
        $this->mergeOptions([
            'component' => [
                'url' => $this->makeRouteAction($route_action)
            ]
        ]);

        return $this;
    }

    protected function setClass(string $class): Form
    {
        $this->mergeOptions([
            'component' => [
                'class' => $class
            ]
        ]);

        return $this;
    }

    protected function setSection(string $section): Form
    {
        $this->mergeOptions([
            'component' => [
                'section' => $section
            ]
        ]);

        return $this;
    }

    protected function setTab(string $tab): Form
    {
        $this->mergeOptions([
            'component' => [
                'tab' => $tab
            ]
        ]);

        return $this;
    }

    protected function setTitleTemplate(string $title_template): Form
    {
        $this->mergeOptions([
            'component' => [
                'title-template' => sprintf('modal.form.%s', $title_template)
            ]
        ]);

        return $this;
    }

    protected function setTemplate(string $template): Form
    {
        $this->mergeOptions([
            'component' => [
                'template' => $template
            ]
        ]);

        return $this;
    }

    protected function setModalFooterTemplate(string $template): Form
    {
        $this->mergeOptions([
            'component' => [
                'modal-footer-template' => $template
            ]
        ]);

        return $this;
    }

    protected function setScripts(array $scripts): Form
    {
        $this->mergeOptions([
            'component' => [
                'scripts' => $scripts
            ]
        ]);

        return $this;
    }

    protected function setDataOnActionComplete(string $route): Form
    {
        $this->mergeOptions([
            'component' => [
                'data-on-action-complete' => $route
            ]
        ]);

        return $this;
    }

    protected function setShowBackButton(bool $param): Form
    {
        $this->mergeOptions([
            'component' => [
                'show-back-button' => $param
            ]
        ]);

        return $this;
    }

    protected function setSubmitAction(string $submit_action): Form
    {
        $this->mergeOptions([
            'component' => [
                'submit-action' => $submit_action
            ]
        ]);

        return $this;
    }
}
