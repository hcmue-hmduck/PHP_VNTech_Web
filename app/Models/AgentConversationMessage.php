<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class AgentConversationMessage extends Model
{
    protected $fillable = [
        'conversation_id',
        'user_id',
        'agent',
        'role',
        'content',
        'attachments',
        'tool_calls',
        'tool_results',
        'usage',
        'meta',
    ];

    protected $casts = [
        'attachments' => 'array',
        'tool_calls' => 'array',
        'tool_results' => 'array',
        'usage' => 'array',
        'meta' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}