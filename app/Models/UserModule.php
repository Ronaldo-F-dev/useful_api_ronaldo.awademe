<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserModule extends Model
{
    /** @use HasFactory<\Database\Factories\UserModuleFactory> */
    use HasFactory;

    protected $fillable = [
        "user_id",
        "module_id",
        "active"
    ];

    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
