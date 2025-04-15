<?php

namespace App\Console\Commands;

use App\Mail\TestMail;
use App\Models\User;
use App\Services\RabbitmqService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class SignUpNotifyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sign-up-notify-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private RabbitmqService $rabbitmqService;
    public function __construct(RabbitmqService $rabbitmqService)
    {
        parent::__construct();
        $this->rabbitmqService = $rabbitmqService;
    }
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $callback = function ($msg) {
            $data = json_decode($msg->body, true);
            $user = User::query()->find($data['user_id']);
            $email = $user->email;
            $name = ['name' => $user->name];
            Mail::to($email)->send(new TestMail($name));
        };
       $this->rabbitmqService->consume('sign-up-email', $callback);
    }
}
