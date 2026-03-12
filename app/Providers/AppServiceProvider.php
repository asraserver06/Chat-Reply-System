<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        use App\Events\MessageSent;
        use App\Listeners\BroadcastMessageSent;
        use App\Listeners\SendMessageNotification;
        use Illuminate\Support\Facades\Event;

        Event::listen(MessageSent::class, BroadcastMessageSent::class);
        Event::listen(MessageSent::class, SendMessageNotification::class);
    }
}
