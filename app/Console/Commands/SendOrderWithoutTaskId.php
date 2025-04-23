<?php

namespace App\Console\Commands;

use App\Jobs\SendHttpRequest;
use App\Models\Order;
use App\Services\Clients\YougileClient;
use Illuminate\Console\Command;

class SendOrderWithoutTaskId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-order-without-task-id';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    private YougileClient $yougileClient;
    public function __construct(YougileClient $yougileClient)
    {
        parent::__construct();
        $this->yougileClient = $yougileClient;
    }
    public function handle()
    {
       $orders = Order::query()->where('yougileTaskId', null)->get();
       foreach ($orders as $order) {
           SendHttpRequest::dispatch($order->id);
       }
        $this->info('Команда выполнена!');
    }
}
