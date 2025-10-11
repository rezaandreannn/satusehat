<?php

namespace Rezaandreannn\SatuSehat\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SatuSehatToken extends Model
{
    protected $table = 'satusehat_tokens';

    protected $fillable = [
        'token_type',
        'access_token',
        'expires_in',
        'expires_at',
        'environment'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Cek apakah token masih valid
     */
    public function isValid(): bool
    {
        return $this->expires_at > Carbon::now();
    }

    /**
     * Scope untuk environment tertentu
     */
    public function scopeForEnvironment($query, $environment)
    {
        return $query->where('environment', $environment);
    }

    /**
     * Scope untuk token yang masih valid
     */
    public function scopeValid($query)
    {
        return $query->where('expires_at', '>', Carbon::now('Asia/Jakarta'));
    }

    /**
     * Get token yang masih valid untuk environment tertentu
     */
    public static function getValidToken($environment = 'sandbox')
    {
        return self::forEnvironment($environment)
            ->valid()
            ->latest()
            ->first();
    }
}
