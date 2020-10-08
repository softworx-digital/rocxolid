<?php

namespace Softworx\RocXolid\Components\Tables;

use Softworx\RocXolid\Rendering\Contracts\Renderable;
use Softworx\RocXolid\Components\Contracts\TableButtonable as ComponentTableButtonable;
use Softworx\RocXolid\Tables\Buttons\Contracts\Button as TableButtonContract;
use Softworx\RocXolid\Components\General\Button;

// @todo: docblocks
class TableButton extends Button implements ComponentTableButtonable
{
    protected $button;

    public function setButton(TableButtonContract $button): ComponentTableButtonable
    {
        $this->button = $button;

        $this->setOptions($this->button->getOption('component'));

        return $this;
    }

    public function getButton(): TableButtonContract
    {
        if (is_null($this->button)) {
            throw new \RuntimeException(sprintf('Table button is not set yet to [%s] component', get_class($this)));
        }

        return $this->button;
    }

    // @todo: not cool
    public function setPreRenderProperties(...$elements): Renderable
    {
        $table = $elements[0];
        $model = $elements[1];
        $controller = $table->getTable()->getController();

        if ($this->hasOption('action')) {
            if ($this->getOption('ajax', false)) {
                $this->mergeOptions([
                    'attributes' => [
                        'data-ajax-url' => $model->getControllerRoute($this->getOption('action'), $this->getOption('route-params', []))
                    ]
                ]);
            } else {
                $this->setOption('url', $model->getControllerRoute($this->getOption('action'), $this->getOption('route-params', [])));
            }
        } elseif ($this->hasOption('related-action')) {

            $related = $model->{$this->getOption('related-action.relation')}()->getRelated();
            $params = [
                sprintf('_data[%s]', $related->{$this->getOption('related-action.attribute')}()->getForeignKeyName()) => $model->getKey()
            ] + $this->getOption('route-params', []);

            if ($this->getOption('ajax', false)) {
                $this->mergeOptions([
                    'attributes' => [
                        'data-ajax-url' => $related->getControllerRoute($this->getOption('related-action.action'), $params),
                    ],
                ]);
            } else {
                $this->setOption('url', $related->getControllerRoute($this->getOption('related-action.action'), $params));
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
