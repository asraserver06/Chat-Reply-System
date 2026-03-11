<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutomatedReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'trigger_keyword',
        'reply_body',
        'is_active',
        'priority',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function matches(string $messageBody): bool
    {
        return str_contains(
            strtolower($messageBody),
            strtolower($this->trigger_keyword)
        );
    }
}
