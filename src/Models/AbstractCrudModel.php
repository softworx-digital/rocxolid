<?php

namespace Softworx\RocXolid\Models;

use Illuminate\Database\Eloquent\Model;
// model contracts
use Softworx\RocXolid\Models\Contracts\Crudable;
use Softworx\RocXolid\Models\Contracts\AutocompleteSearchable;
// model traits
use Softworx\RocXolid\Models\Traits\Crudable as CrudableTrait;
use Softworx\RocXolid\Models\Traits\HasTitleColumn;
use Softworx\RocXolid\Models\Traits\AutocompleteSearchable as AutocompleteSearchableTrait;
// user management traits
use Softworx\RocXolid\UserManagement\Models\Traits\HasUserAttributes;

/**
 *
 */
class AbstractCrudModel extends Model implements Crudable, AutocompleteSearchable
{
    use CrudableTrait;
    use HasTitleColumn;
    use HasUserAttributes;
    use AutocompleteSearchableTrait;
    /**
     * Attribute to use for parent's position.
     *
     * @var string
     */
    const POSITION_COLUMN = 'model_attribute_position';
    /**
     * Flag if model instances can be user deleted.
     *
     * @var bool
     */
    protected static $can_be_deleted = true;
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    //protected $hidden = [];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = [];
    /**
     * Model relationship methods.
     *
     * @var array
     */
    //protected $relationships = [];
    /**
     * Model extra attributes for special forms.
     *
     * @var array
     */
    protected $extra = [];
    /**
     * Model system attributes - not to be shown in model viewer.
     *
     * @var array
     */
    protected $system = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
