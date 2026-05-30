<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class AgentConversation extends Model
{
    protected $table = 'agent_conversations';

    protected $fillable = [
        'user_id',
        'title',
        'is_deleted',
    ];

    protected $casts = [
        'is_deleted' => 'boolean',
    ];
}
