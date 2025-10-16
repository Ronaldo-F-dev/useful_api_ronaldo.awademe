<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShortLink extends Model
{
    /** @use HasFactory<\Database\Factories\ShortLinkFactory> */
    use HasFactory;
    protected $fillable = [
        "original_url",
        "code",
        "clicks",
        "user_id"
    ];

    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
