<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Wallet extends Model
{
    /** @use HasFactory<\Database\Factories\WalletFactory> */
    use HasFactory;
    protected $fillable = [
        "user_id",
        "balance"
    ];
    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
