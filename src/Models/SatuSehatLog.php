<?php

namespace Rezaandreannn\SatuSehat\Models;

use Illuminate\Database\Eloquent\Model;

class SatuSehatLog extends Model
{
    protected $table = 'satusehat_logs';

    protected $fillable = [
        'method',
        'endpoint',
        'request_data',
        'response_data',
        'status_code',
        'status',
        'error_message',
        'execution_time',
        'environment'
    ];

    protected $casts = [
        'request_data' => 'array',
        'response_data' => 'array',
        'execution_time' => 'decimal:3',
    ];

    /**
     * Scope untuk status tertentu
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk environment tertentu
     */
    public function scopeForEnvironment($query, $environment)
    {
        return $query->where('environment', $environment);
    }

    /**
     * Scope untuk method tertentu
     */
    public function scopeWithMethod($query, $method)
    {
        return $query->where('method', $method);
    }
}
