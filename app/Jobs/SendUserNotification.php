<?php

namespace App\Jobs;

use App\Mail\UserNotificationMail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendUserNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $details;
    protected $email;

    public function __construct($email, $details)
    {
        $this->email = $email;
        $this->details = $details;
    }

    public function handle()
    {
        Mail::to($this->email)->send(new UserNotificationMail($this->details));

    }
}
