<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class SavedPcBuild extends Model
{
    use HasFactory;

    protected $fillable = [
        'share_token',
        'user_id',
        'build_name',
        'components',
        'total_price',
        'estimated_wattage',
        'compatibility_status',
        'compatibility_messages',
        'notes',
    ];

    protected $casts = [
        'components'             => 'array',
        'total_price'            => 'decimal:2',
        'estimated_wattage'      => 'integer',
        'compatibility_messages' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate unique random share token: PCB-XXXXXX
     */
    public static function generateToken(): string
    {
        $token = 'PCB-' . strtoupper(Str::random(6));
        while (static::where('share_token', $token)->exists()) {
            $token = 'PCB-' . strtoupper(Str::random(6));
        }
        return $token;
    }
}
