<?php

namespace Softworx\RocXolid\Components\ModelViewers\Traits;

use Illuminate\Support\Collection;

/**
 * Enables model viewer to have tabs.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait HasTabs
{
    /**
     * Tabs definition.
     *
     * @var array
     */
    protected $tabs = [
        'default',
    ];

    /**
     * @inheritDoc
     */
    public function tabs(): Collection
    {
        return collect($this->tabs);
    }

    /**
     * @inheritDoc
     */
    public function tabTitle(string $tab): string
    {
        return $this->translate(sprintf('tab.%s', $tab));
    }

    /**
     * @inheritDoc
     */
    public function tabRoute(string $tab, array $params = [], bool $force = false): string
    {

        return $this->getController()->getRoute('show', $this->getModel(), ($force || !$this->isDefaultTab($tab) ? [ 'tab' => $tab ] : []) + $params);
    }

    /**
     * @inheritDoc
     */
    public function isDefaultTab(string $tab): bool
    {
        return ($this->tabs()->first() === $tab);
    }
}
