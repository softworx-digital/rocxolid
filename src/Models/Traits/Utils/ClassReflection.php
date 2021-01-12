<?php

namespace Softworx\RocXolid\Models\Traits\Utils;

/**
 * @todo subject to refactoring / revision
 */
trait ClassReflection
{
    /**
     * Obtain model's class name.
     *
     * @param boolean $short
     * @return string
     */
    public function className(bool $short = false): string
    {
        return $short ? (new \ReflectionClass($this))->getShortName() : (new \ReflectionClass($this))->getName();
    }

    /**
     * Obtain model's human readable translated class name.
     *
     * @param boolean $plural
     * @return string
     */
    public function getClassNameTranslation(bool $plural = false): string
    {
        return $this->getModelViewerComponent()->translate(sprintf('model.title.%s', $plural ? 'plural' : 'singular'));
    }

    /*
    @todo incomplete, it's purpose is to get rid of similar constructions and use nicer approach
    $fields['model_type']['options']['collection'] = $this->getModel()->getAvailableAttributables()->mapWithKeys(function (Attributable $model) {
        return [ $model->className() => $model->getClassNameTranslation() ];
    });
    */
    public static function populateMap(): \Closure
    {
        return function () {
            return [ $this->className() => $this->getClassNameTranslation() ];
        };
    }
}
