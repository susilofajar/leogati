<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SpecificationAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'name',
        'slug',
        'unit',
        'input_type',
        'is_filterable',
        'sort_order',
    ];

    protected $casts = [
        'is_filterable' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(SpecificationGroup::class, 'group_id');
    }

    public function productSpecifications(): HasMany
    {
        return $this->hasMany(ProductSpecification::class, 'attribute_id');
    }
}
