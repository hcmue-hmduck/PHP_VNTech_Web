<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Laravel\Ai\Migrations\AiMigration;

return new class extends AiMigration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $conversationsTable = config('ai.conversations.tables.conversations', 'agent_conversations');
        $messagesTable = config('ai.conversations.tables.messages', 'agent_conversation_messages');

        Schema::create($conversationsTable, function (Blueprint $collection) {
            $collection->string('user_id')->nullable()->index();
            $collection->string('title');
            $collection->boolean('is_deleted')->default(false);
            $collection->timestamps();

            $collection->index(['user_id', 'updated_at']);
        });

        Schema::create($messagesTable, function (Blueprint $collection) {
            $collection->string('conversation_id', 36)->index();
            $collection->string('user_id')->nullable()->index();
            $collection->string('agent');
            $collection->string('role', 25);
            $collection->text('content');
            $collection->text('attachments');
            $collection->text('tool_calls');
            $collection->text('tool_results');
            $collection->text('usage');
            $collection->text('meta');
            $collection->timestamps();

            $collection->index(['conversation_id', 'user_id', 'updated_at'], 'conversation_index');
            $collection->index(['user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(config('ai.conversations.tables.messages', 'agent_conversation_messages'));
        Schema::dropIfExists(config('ai.conversations.tables.conversations', 'agent_conversations'));
    }
};
