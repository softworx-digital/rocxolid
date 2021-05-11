<?php

namespace Softworx\RocXolid\Models;

use Illuminate\Database\Eloquent\Model;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable;
use Softworx\RocXolid\Models\Contracts\ApiRequestable;
use Softworx\RocXolid\Models\Contracts\Searchable;
// rocXolid model traits
use Softworx\RocXolid\Models\Traits;
// rocXolid relations
use Softworx\RocXolid\Models\Relations\Traits\BelongsToThrough;
// rocXolid user management traits
use Softworx\RocXolid\UserManagement\Models\Traits\HasUserAttributes; // @todo this doesn't belong here, another approach without dependency on UserManagement?

/**
 * rocXolid base CRUDable model class.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
abstract class AbstractCrudModel extends Model implements Crudable, ApiRequestable, Searchable
{
    use Traits\Crudable;
    use Traits\ApiRequestable;
    use Traits\CanBeFieldItem;
    use Traits\CanBeSearched;
    use BelongsToThrough;
    use HasUserAttributes;

    /**
     * Attribute to use for parent's position.
     *
     * @var string
     */
    const POSITION_COLUMN = 'model_attribute_position';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    // protected $hidden = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [];

    protected $guarded = [
        'id'
    ];

    /**
     * Model relationship methods.
     *
     * @var array
     */
    // protected $relationships = [];

    /**
     * Model extra attributes for special forms.
     *
     * @var array
     */
    protected $extra = [];

    /**
     * Model system attributes.
     * These attributes are not shown in model viewer and omitted in forms.
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

    /**
     * Date attributes.
     * These attributes are formatted according to localization in the front-end.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Date & time attributes.
     * These attributes are formatted according to localization in the front-end.
     *
     * @var array
     */
    protected $date_times = [];

    /**
     * Time attributes.
     * These attributes are formatted according to localization in the front-end.
     *
     * @var array
     */
    protected $times = [];

    /**
     * Decimal attributes.
     * These attributes are formatted according to localization in the front-end.
     *
     * @var array
     */
    protected $decimals = [];

    /**
     * Enum attributes.
     * These attributes are formatted according to localization in the front-end.
     *
     * @var array
     */
    protected $enums = [];

    /**
     * Monetary attributes.
     * These attributes are formatted according to localization in the front-end.
     *
     * @var array
     */
    protected $monetaries = [];

    /**
     * Percentual attributes.
     * These attributes are formatted according to localization in the front-end.
     *
     * @var array
     */
    protected $percentuals = [];
}
