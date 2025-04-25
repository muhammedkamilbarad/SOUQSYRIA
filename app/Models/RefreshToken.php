<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;

class RefreshToken extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'token', 'expires_at'];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function generateToken(): string
    {
        return base64_encode(random_bytes(32)); // random token
    }

    public static function hashToken(string $token): string
    {
        return hash('sha256', $token);
    }
}
