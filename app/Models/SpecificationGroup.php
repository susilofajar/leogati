<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SpecificationGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function attributes(): HasMany
    {
        return $this->hasMany(SpecificationAttribute::class, 'group_id')->orderBy('sort_order');
    }
}
