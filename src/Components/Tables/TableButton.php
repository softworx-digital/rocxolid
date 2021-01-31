<?php

namespace Softworx\RocXolid\Components\Tables;

use Softworx\RocXolid\Rendering\Contracts\Renderable;
use Softworx\RocXolid\Components\Contracts\TableButtonable as ComponentTableButtonable;
use Softworx\RocXolid\Tables\Buttons\Contracts\Button as TableButtonContract;
use Softworx\RocXolid\Components\General\Button;
use Softworx\RocXolid\Traits\Loggable;

// @todo docblocks
class TableButton extends Button implements ComponentTableButtonable
{
    protected $button;

    public function setButton(TableButtonContract $button): ComponentTableButtonable
    {
        $this->button = $button;

        $this->setOptions($this->button->getOption('component'));

        // @todo kinda "hotfixed", you can do better
        if ($view_package = $this->getOption('view-package', false)) {
            $this->setViewPackage($view_package);
        }

        return $this;
    }

    public function getButton(): TableButtonContract
    {
        if (is_null($this->button)) {
            throw new \RuntimeException(sprintf('Table button is not set yet to [%s] component', get_class($this)));
        }

        return $this->button;
    }

    // @todo not cool
    public function setPreRenderProperties(...$elements): Renderable
    {
        $table = $elements[0];
        $model = $elements[1];
        $controller = $table->getTable()->getController();

        if ($this->hasOption('action')) {
            if ($this->getOption('ajax', false)) {
                $this->mergeOptions([
                    'attributes' => [
                        // not using this since model's retrieved by repository and not default CRUD Controller could be set to table
                        // 'data-ajax-url' => $model->getControllerRoute($this->getOption('action'), $this->getOption('route-params', [])),
                        'data-ajax-url' => $controller->getRoute($this->getOption('action'), $model, $this->getOption('route-params', [])),
                    ],
                ]);
            } else {
                // not using this since model's retrieved by repository and not default CRUD Controller could be set to table
                // $this->setOption('url', $model->getControllerRoute($this->getOption('action'), $this->getOption('route-params', [])));
                $this->setOption('url', $controller->getRoute($this->getOption('action'), $model, $this->getOption('route-params', [])));
            }
        } elseif ($this->hasOption('method-action')) {
            if ($this->getOption('ajax', false)) {
                $this->mergeOptions([
                    'attributes' => [
                        'data-ajax-url' => $model->{$this->getOption('method-action.method')}(),
                    ],
                ]);
            } else {
                $this->setOption('url', $model->{$this->getOption('method-action.method')}());
            }
        } elseif ($this->hasOption('related-action')) {
            // $related = $model->{$this->getOption('related-action.relation')};
            // $related = $related ?? $model->{$this->getOption('related-action.relation')}()->getRelated();

            if ($this->hasOption('related-action.relation')) {
                $related = $model->{$this->getOption('related-action.relation')}()->getRelated();
            } elseif ($this->hasOption('related-action.getter')) {
                $related = $model->{$this->getOption('related-action.getter')}();
            } else {
                throw new \InvalidArgumentException(sprintf('Table button [related-action] option requires either [relation] or related model [getter] definition for [%s]', get_class($this)));
            }

            $params = [
                sprintf('_data[%s]', $related->{$this->getOption('related-action.attribute')}()->getForeignKeyName()) => $model->getKey()
            ] + $this->getOption('route-params', []);

            if ($this->getOption('ajax', false)) {
                $this->mergeOptions([
                    'attributes' => [
                        // not using this since model's retrieved by repository and not default CRUD Controller could be set to table
                        // 'data-ajax-url' => $related->getControllerRoute($this->getOption('related-action.action'), $params),
                        'data-ajax-url' => $controller->getRoute($this->getOption('related-action.action'), $model, $params),
                    ],
                ]);
            } else {
                // not using this since model's retrieved by repository and not default CRUD Controller could be set to table
                // $this->setOption('url', $related->getControllerRoute($this->getOption('related-action.action'), $params));
                $this->setOption('url', $controller->getRoute($this->getOption('related-action.action'), $model, $params));
            }
        } elseif ($this->hasOption('foreign-action')) {
            if ($this->hasOption('foreign-action.getter')) {
                $related = $model->{$this->getOption('foreign-action.getter')}();
            } else {
                throw new \InvalidArgumentException(sprintf('Table button [foreign-action] option requires [getter] definition for [%s]', get_class($this)));
            }

            if ($this->hasOption('foreign-action.parent')) {
                $params = [
                    sprintf('_data[%s]', $model->{$this->getOption('foreign-action.parent')}()->getForeignKey()) => $model->{$this->getOption('foreign-action.parent')}()->getKey()
                ] + $this->getOption('route-params', []);
            } else {
                $params = [
                    sprintf('_data[%s]', $model->getForeignKey()) => $model->getKey()
                ] + $this->getOption('route-params', []);
            }

            if ($this->getOption('ajax', false)) {
                $this->mergeOptions([
                    'attributes' => [
                        // not using this since model's retrieved by repository and not default CRUD Controller could be set to table
                        // 'data-ajax-url' => $related->getControllerRoute($this->getOption('foreign-action.action'), $params),
                        'data-ajax-url' => $controller->getRoute($this->getOption('foreign-action.action'), $model, $params),
                    ],
                ]);
            } else {
                // not using this since model's retrieved by repository and not default CRUD Controller could be set to table
                // $this->setOption('url', $related->getControllerRoute($this->getOption('foreign-action.action'), $params));
                $this->setOption('url', $controller->getRoute($this->getOption('foreign-action.action'), $model, $params));
            }
        } elseif ($this->hasOption('tel')) {
            $this->setOption('url', sprintf('tel:%s', $model->{$this->getOption('tel')}));
        } elseif ($this->hasOption('mailto')) {
            $this->setOption('url', sprintf('mailto:%s', $model->{$this->getOption('mailto')}));
        }

        if ($this->hasOption('attributes') && ($title = $this->getOption('attributes.title-key', false))) {
            $this->mergeOptions([
                'attributes' => [
                    'title' => $this->translate($title)
                ]
            ]);
        }

        return $this;
    }

    public function getTranslationKey(string $key): string
    {
        return sprintf('table-button.%s', $key);
    }
}
