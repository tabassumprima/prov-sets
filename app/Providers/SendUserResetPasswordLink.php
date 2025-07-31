<?php

namespace App\Providers;

use App\Providers\UserCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Password;

class SendUserResetPasswordLink implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Providers\UserCreated  $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
        Password::sendResetLink(['email' => $event->user->email]);
    }
}
