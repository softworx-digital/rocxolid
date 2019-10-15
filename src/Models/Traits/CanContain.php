<?php

namespace Softworx\RocXolid\Models\Traits;

use DB;
use Illuminate\Support\Collection;
// model contracts
use Softworx\RocXolid\Models\Contracts\Container;
use Softworx\RocXolid\Models\Contracts\Containee;

/**
 *
 */
trait CanContain
{
    protected static $container_containee_pivot_table = 'container_has_containee';

    protected $default_containment_ownership = [
        'items' => false,
    ];

    public static function bootCanContain()
    {
        static::deleting(function ($model) {
            $model->detachAll();
        });
    }

    public function getAllContainees(): Collection
    {
        $containees = new Collection();

        $this->getContainerContaineePivotData()->each(function ($pivot_data, $key) use ($containees) {
            $containee_class = $pivot_data->containee_type;
            $containee = $containee_class::find($pivot_data->containee_id);

            if (!is_null($containee)) {
                $containee->setContaineePivotData($pivot_data);
                $containees->push($containee);
            }
        });

        return $containees;
    }

    public function getContainees(string $relation_name, $visible_only = false, $paged = false, $page = 1, $per_page = 12): Collection
    {
        $containees = new Collection();

        $this->getContainerContaineePivotData($relation_name)->each(function ($pivot_data, $key) use ($containees, $visible_only) {
            $containee_class = $pivot_data->containee_type;
            $containee = $containee_class::find($pivot_data->containee_id);

            if (!is_null($containee) && (!$visible_only || $containee->is_visible)) {
                $containee->setContaineePivotData($pivot_data);
                $containees->push($containee);
            }
        });

        return $containees;
    }

    public function attachContainee(string $relation_name, Containee $containee, $position = null, $parent_id = null): int
    {
        $position = $position ?? (int)DB::table($this->getContainerPivotTable())
            ->where($this->getContainerPivotCondition($relation_name))
            ->count();

        if (property_exists($this, 'containment_ownership') && isset($this->containment_ownership[$relation_name])) {
            $is_owned = $this->containment_ownership[$relation_name];
        } elseif (isset($this->default_containment_ownership[$relation_name])) {
            $is_owned = $this->default_containment_ownership[$relation_name];
        } else {
            $is_owned = 0;
        }

        $id = DB::table($this->getContainerPivotTable())
            ->insertGetId($this->getContainerPivotCondition($relation_name, $containee) + [
                'position' => $position,
                'parent_id' => $parent_id,
                'is_owned' => $is_owned,
            ]);

        return $id;
    }

    // recursitivity ensured by foregin key constraint
    /*
    $table->foreign('parent_id')
                ->references('id')
                ->on('container_has_containee')
                ->onDelete('cascade');
    */
    public function detachContainee(string $relation_name, Containee $containee = null): Container
    {
        DB::table($this->getContainerPivotTable())
            ->where($this->getContainerPivotCondition($relation_name, $containee))
            ->delete();

        return $this;
    }

    public function hasContainee(string $relation_name, Containee $containee = null): bool
    {
        return DB::table($this->getContainerPivotTable())
            ->where($this->getContainerPivotCondition($relation_name, $containee))
            ->count() > 0;
    }

    public function reorderContainees(string $relation_name, array $containee_order_data, $parent_id = null, $containee_type_param = 'containeeType', $containee_id_param = 'containeeId', $containee_children_param = 'children'): Container
    {
        $this->detachContainee($relation_name);

        foreach ($containee_order_data as $position => $containee_data) {
            $containee = new $containee_data[$containee_type_param](); // fake containee
            $containee->{$containee->getKeyName()} = $containee_data[$containee_id_param];

            $id = $this->attachContainee($relation_name, $containee, $position, $parent_id);

            if (isset($containee_data[$containee_children_param]) && is_array($containee_data[$containee_children_param])) {
                if ($containee instanceof Container) {
                    foreach ($containee_data[$containee_children_param] as $containee_children_order_data) {
                        $containee->reorderContainees($relation_name, $containee_children_order_data, $id, $containee_type_param, $containee_id_param, $containee_children_param);
                    }
                } else {
                    throw \InvalidArgumentException(sprintf('Containee [%s] must be of type Container in order to reorder children', (new \ReflectionClass($containee))->getName()));
                }
            }
        }

        return $this;
    }

    public function getContainerPivotTable(): string
    {
        return static::$container_containee_pivot_table;
    }

    protected function detachAll(): Container
    {
        DB::table($this->getContainerPivotTable())
            ->where([
                'container_type' => (new \ReflectionClass($this))->getName(),
                'container_id' => $this->getKey(),
            ])
            ->delete();

        return $this;
    }

    protected function getContainerContaineePivotData(string $relation_name = null, string $order_by = 'position', string $order_by_direction = 'asc'): Collection
    {
        return DB::table($this->getContainerPivotTable())
            ->where($this->getContainerPivotCondition($relation_name))
            ->orderBy($order_by, $order_by_direction)
            ->get();
    }

    protected function getContainerPivotCondition(string $relation_name = null, Containee $containee = null): array
    {
        $condition = [
            'container_type' => (new \ReflectionClass($this))->getName(),
            'container_id' => $this->getKey(),
        ];

        if (!is_null($relation_name)) {
            $condition += [
                'container_relation' => $relation_name,
            ];
        }

        if (!is_null($containee)) {
            $condition += [
                'containee_type' => (new \ReflectionClass($containee))->getName(),
                'containee_id' => $containee->getKey(),
            ];
        }

        return $condition;
    }
}
