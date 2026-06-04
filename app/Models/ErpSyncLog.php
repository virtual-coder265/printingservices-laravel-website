<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ErpSyncLog extends Model
{
    protected $fillable = [
        'direction',
        'entity_type',
        'entity_id',
        'payload_hash',
        'status',
        'error_message',
        'payload',
        'response',
    ];

    protected function casts(): array
    {
        return [
            'entity_id' => 'integer',
            'payload' => 'array',
            'response' => 'array',
        ];
    }
}
