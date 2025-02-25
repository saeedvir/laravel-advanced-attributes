<?php

namespace Saeedvir\LaravelAdvancedAttributes;

use Illuminate\Database\Eloquent\Relations\Pivot;


/**
 * @property string     $title
 * @property string     $value
 * @property string     $attributable
 * @property string|int $attributable_id
 */
class Attributable extends Pivot
{
    protected $guarded = [];

    protected $fillable = [
        'attribute_id',
        'attributable_id',
        'attributable_type',
        'value_int',
        'value_decimal',
        'value_text',
        'value_date',
        'value_boolean',
        'value_json',
    ];

    protected $casts = [
        'value_json' => 'array',
        'value_boolean' => 'boolean',
    ];
    
    public function __construct(array $attributes = [])
    {
        $this->table = config('laravel-advanced-attributes.tables.attributables', 'attributables');

        parent::__construct($attributes);
    }
}
