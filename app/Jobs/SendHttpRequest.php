<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\Clients\DTO\YougileTaskDto;
use App\Services\Clients\YougileClient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;

class SendHttpRequest implements ShouldQueue
{
    use Queueable;
    protected int $orderId;
//    protected array $headers = [
//        'Authorization' => 'Bearer 1HAF3xPsKMBP954xm9WTsJKeCA1GoVtXbExErbG7OlPcPl+cLdtXKUf29mRiLFAu',
//        'Content-Type' => 'application/json',
//    ];
//
//    protected string $url = "https://yougile.com/api-v2/tasks";
    protected YougileClient $yougileClient;
    public function __construct(int $orderId)
    {
        $this->yougileClient = new YougileClient();
        $this->orderId = $orderId;
    }


    public function handle(): void
    {
//        Http::withHeaders($this->headers)->post($this->url, $this->getBody());
        $taskDto = new YougileTaskDto("Заказ #{$this->orderId}",
            'c3e14ec6-1c4c-4485-ab16-6a505210abd7', "{$this->getBody()}");

        $taskId = $this->yougileClient->createTask($taskDto);

    }

    protected function getBody(): string
    {
        $order = Order::with('products')->find($this->orderId);

        $description = "Имя: {$order->name} <br>"
            . "Телефон: {$order->phone} <br>"
            . "Адрес: {$order->address} <br>"
            . "Комментарий: {$order->comment} <br>"
            . "Список товаров: <br>";

        foreach ($order->products as $product) {
            $description .= "    Id: {$product->id} - {$product->name} - {$product->pivot->amount}шт <br>";
        }
//        return [
//            'title' => "Заказ #{$this->orderId}",
//            'columnId' => "c3e14ec6-1c4c-4485-ab16-6a505210abd7",
//            'description' => $description,
//            'archived' => false,
//            'completed' => false
//        ];
        return $description;
    }
}
