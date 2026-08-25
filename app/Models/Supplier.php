<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'pic_name',
        'email',
        'phone',
        'address',
        'city',
        'province',
        'postal_code',
        'npwp',
        'payment_terms',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    /**
     * Generate kode supplier otomatis: SUP-XXXX
     */
    public static function generateCode(): string
    {
        $last = static::orderByDesc('id')->first();
        $nextNum = $last ? ((int) substr($last->code, 4)) + 1 : 1;
        return 'SUP-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
    }
}
