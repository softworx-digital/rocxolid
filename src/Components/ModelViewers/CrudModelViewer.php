<?php

namespace Softworx\RocXolid\Components\ModelViewers;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
// rocXolid component contracts
use Softworx\RocXolid\Components\Contracts\Formable;
use Softworx\RocXolid\Components\Contracts\Componentable\Form as FormComponentable;
// rocXolid components
use Softworx\RocXolid\Components\AbstractActiveComponent;

/**
 * Base component to be used for viewing CRUDable models.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
class CrudModelViewer extends AbstractActiveComponent implements FormComponentable
{
    /**
     * Form component reference
     *
     * @var \Softworx\RocXolid\Components\Contracts\Formable
     */
    protected $model_form_component = null;

    /**
     * Panels definition.
     * For data panels, a basic model attributes list to be used in panels.
     *
     * Expected form:
     * 'data' => [
     *     '<panel-0-key>' => [
     *         '<attribute-0>',
     *         '<attribute-1>',
     *         ...
     *         '<attribute-n>',
     *     ],
     * ],
     *
     * @var array
     */
    protected $panels = [];

    /**
     * Assign form component.
     *
     * @param \Softworx\RocXolid\Components\Contracts\Formable $model_form_component
     * @return \Softworx\RocXolid\Components\Contracts\Componentable\Form
     */
    public function setFormComponent(Formable $model_form_component): FormComponentable
    {
        $this->model_form_component = $model_form_component;

        return $this;
    }

    /**
     * Obtain form component.
     *
     * @return \Softworx\RocXolid\Components\Contracts\Formable
     * @throws \RuntimeException
     */
    public function getFormComponent(): Formable
    {
        if (is_null($this->model_form_component)) {
            throw new \RuntimeException(sprintf('CRUD model_form_component not yet set to [%s]', get_class($this)));
        }

        return $this->model_form_component;
    }

    /**
     * Obtain panel data definition.
     * List of attributes used in a given panel expected as a result.
     *
     * @param string $param
     * @return \Illuminate\Support\Collection
     */
    public function panelData(string $param): Collection
    {
        if (!Arr::has($this->panels, $param)) {
            throw new \RuntimeException(sprintf('Panel param [%s] not defined for [%s]', $param, get_class($this)));
        }

        $data = collect(Arr::get($this->panels, $param));

        if ($data->isEmpty()) {
            throw new \RuntimeException(sprintf('No panel data defined for panel param [%s] in [%s]', $param, get_class($this)));
        }

        return $data;
    }

    /**
     * Obtain model update route according to given panel.
     *
     * @param string $param
     * @param string|null $template
     * @return string
     */
    public function panelEditRoute(string $param, ?string $template = null): string
    {
        return $this->sectionEditRoute('panel', $param, $template);
    }

    /**
     * Obtain panel param for section identification.
     *
     * @param string $param
     * @param string|null $template
     * @return string
     */
    public function panelSectionParam(string $param, ?string $template = null): string
    {
        return is_null($template) ? sprintf('panel.%s', $param) : sprintf('panel:%s.%s', $template, $param);
    }

    /**
     * Obtain model update route according to given section type.
     *
     * @param string $param
     * @param string|null $template
     * @return string
     */
    protected function sectionEditRoute(string $type, string $param, ?string $template = null): string
    {
        $method = sprintf('%sSectionParam', Str::camel($type));

        if (!method_exists($this, $method)) {
            throw new \InvalidArgumentException(sprintf('Invalid section param retrieval method [%s] in [%s] requested', $method, get_class($this)));
        }

        return $this->getController()->getRoute('edit', $this->getModel(), [ '_section' => $this->$method($param, $template) ]);
    }
}
