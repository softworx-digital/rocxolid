<?php

namespace Softworx\RocXolid\Models\Traits;

use DB;
use Illuminate\Support\Collection;
// model contracts
use Softworx\RocXolid\Models\Contracts\Container;
use Softworx\RocXolid\Models\Contracts\Containee;
use Softworx\RocXolid\Models\Contracts\Crudable;

/**
 *
 */
trait IsContained
{
    protected static $containee_container_pivot_table = 'container_has_containee';

    private $_containee_pivot_data = null;

    /**
     * @override
     */
    public function resolvePolymorphism(Collection $data, string $action = null): Crudable
    {
        return $this;
    }

    // @todo - toto hotfix sem, inac spravit zrejme viac tried - lepsiu arch pageelement x container x containee kombinacii
    public function containeePagesWhereVisible(): Collection
    {
        $pages = collect();

        $this->getContainers('items')->each(function ($container) use ($pages) {
            $container->pages->each(function ($page) use ($pages) {
                if (!property_exists($page->pivot, 'is_visible') || $page->pivot->is_visible) {
                    $pages->push($page);
                }
            });
        });

        return $pages;
    }

    public function getContainer(string $container_relation_name): Container
    {
        $containers = collect();

        $this->getContaineeContainerPivotData($container_relation_name)->each(function ($pivot_data, $key) use ($containers) {
            $container_class = $pivot_data->container_type;

            if ($container = $container_class::find($pivot_data->container_id)) {
                $containers->push($container->setPivotData($pivot_data));
            }
        });

        return $containers->first();
    }

    public function getContainers(string $container_relation_name): Collection
    {
        $containers = collect();

        $this->getContaineeContainerPivotData($container_relation_name)->each(function ($pivot_data, $key) use ($containers) {
            $container_class = $pivot_data->container_type;

            if ($container = $container_class::find($pivot_data->container_id)) {
                $containers->push($container->setPivotData($pivot_data));
            }
        });

        return $containers;
    }

    public function hasContainer(string $container_relation_name, Container $container = null): bool
    {
        return DB::table($this->getContaineePivotTable())
            ->where($this->getContaineePivotCondition($container_relation_name, $container))
            ->count() > 0;
    }

    public function getContaineePivotTable(): string
    {
        return static::$containee_container_pivot_table;
    }

    public function setContaineePivotData($pivot_data): Containee
    {
        $this->_containee_pivot_data = $pivot_data;

        return $this;
    }

    public function getContaineePivotData(): \stdClass
    {
        return $this->_containee_pivot_data;
    }

    protected function getContaineeContainerPivotData(string $container_relation_name, string $order_by = 'position', string $order_by_direction = 'asc'): Collection
    {
        return DB::table($this->getContaineePivotTable())
            ->where($this->getContaineePivotCondition($container_relation_name))
            ->orderBy($order_by, $order_by_direction)
            ->get();
    }

    protected function getContaineePivotCondition(string $container_relation_name, Container $container = null): array
    {
        $condition = [
            'container_relation' => $container_relation_name,
            'containee_id' => $this->getKey(),
            'containee_type' => (new \ReflectionClass($this))->getName(),
        ];

        if (!is_null($container)) {
            $condition += [
                'container_id' => $container->getKey(),
                'container_type' => (new \ReflectionClass($container))->getName(),
            ];
        }

        return $condition;
    }
}
