<?php

namespace Softworx\RocXolid\Repositories\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Softworx\RocXolid\Contracts\EventDispatchable;
use Softworx\RocXolid\Contracts\Controllable;
use Softworx\RocXolid\Contracts\Paramable;
use Softworx\RocXolid\Contracts\Optionable;
use Softworx\RocXolid\Forms\Contracts\Formable;
use Softworx\RocXolid\Models\Contracts\Crudable;

// @todo - dodefinovat dalsie navratove typy + dalsie metody (SYNC) podla implementacnej classy - doplnit dalsie contracty (paginaciu,...)
interface Repository extends Orderable, Filterable, Controllable, Paramable, Optionable, EventDispatchable, Formable
{
    /**
     * Set the form builder.
     *
     * @param RepositoryBuilder $form_builder
     * @return $this
     */
    public function setRepositoryBuilder(RepositoryBuilder $form_builder): Repository;

    /**
     * Get form builder.
     *
     * @return RepositoryBuilder
     */
    public function getRepositoryBuilder(): RepositoryBuilder;

    /**
     * Set the form field builder.
     *
     * @param RepositoryColumnBuilder $form_field_builder
     * @return $this
     */
    public function setRepositoryColumnBuilder(RepositoryColumnBuilder $form_field_builder): Repository;

    /**
     * Get form field builder.
     *
     * @return RepositoryColumnBuilder
     */
    public function getRepositoryColumnBuilder(): RepositoryColumnBuilder;

    /**
     * @param array $columns
     * @return Collection
     */
    //public function all($columns = array('*')): Collection;

    /**
     * @param $id
     * @param array $columns
     * @return Collection
     */
    //public function find($id, $columns = array('*')): Crudable;

    /**
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
    //public function findBy($attribute, $value, $columns = array('*')): Crudable;

    /**
     * @param int $perPage
     * @param array $columns
     * @return LengthAwarePaginator
     */
    //public function paginate($per_page = 1, $columns = array('*')): LengthAwarePaginator;

    /**
     * @param array $data
     * @return mixed
     */
    //public function createModel(array $data): Crudable;

    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    //public function update(array $data, $id, $attribute = 'id');

    /**
     * @param $id
     * @return mixed
     */
    //public function delete($id);
}
