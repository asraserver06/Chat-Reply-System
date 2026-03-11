<?php

namespace App\Jobs;

use App\Models\Message;
use App\Services\AutoReplyService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessAutoReply implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    /**
     * Number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * Timeout in seconds.
     */
    public int $timeout = 30;

    public function __construct(
        public readonly Message $message
    ) {}

    public function handle(AutoReplyService $autoReplyService): void
    {
        // Don't reply to auto-replies (prevents loops)
        if ($this->message->is_auto_reply) {
            return;
        }

        $autoReplyService->replyIfMatched($this->message);
    }
}
