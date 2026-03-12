<?php

namespace Database\Seeders;

use App\Models\AutomatedReply;
use Illuminate\Database\Seeder;

class AutoReplySeeder extends Seeder
{
    public function run(): void
    {
        $rules = [
            [
                'trigger_keyword' => 'hello',
                'reply_body'      => 'Hi there! How can I help you today? 👋',
                'priority'        => 10,
                'is_active'       => true,
            ],
            [
                'trigger_keyword' => 'hi',
                'reply_body'      => 'Hello! Thanks for reaching out. What can I do for you?',
                'priority'        => 9,
                'is_active'       => true,
            ],
            [
                'trigger_keyword' => 'pricing',
                'reply_body'      => 'Check out our subscription plans at /user/subscription. We offer Free, Basic, and Pro tiers!',
                'priority'        => 8,
                'is_active'       => true,
            ],
            [
                'trigger_keyword' => 'help',
                'reply_body'      => 'Sure! You can ask me anything. For urgent issues, please contact support@example.com.',
                'priority'        => 7,
                'is_active'       => true,
            ],
            [
                'trigger_keyword' => 'thank',
                'reply_body'      => "You're welcome! Is there anything else I can help with? 😊",
                'priority'        => 6,
                'is_active'       => true,
            ],
            [
                'trigger_keyword' => 'bye',
                'reply_body'      => 'Goodbye! Have a great day! Come back anytime. 👋',
                'priority'        => 5,
                'is_active'       => true,
            ],
        ];

        foreach ($rules as $rule) {
            AutomatedReply::updateOrCreate(
                ['trigger_keyword' => $rule['trigger_keyword']],
                $rule
            );
        }
    }
}
