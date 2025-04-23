<?php

namespace App\Services\Clients;

use App\Models\Order;
use App\Services\Clients\DTO\YougileTaskDto;
use Illuminate\Support\Facades\Http;

class YougileClient
{
    private string $baseUrl;
    private string $apiKey;
    public function __construct()
    {
        $this->baseUrl = config('services.yougile.base_url');
        $this->apiKey = config('services.yougile.api_key');
    }
    public function createTask(YougileTaskDto $taskDto): string
    {
       $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->post($this->baseUrl . 'tasks', $taskDto->getArray());
       if (!$response->successful()) {
           throw new \Exception('ошибка создания задачи');
       }
       $data = $response->json();
       print_r($data);
       return $data['id'];
    }

    public function deleteTask(Order $order)
    {
        $taskId = Order::where('id', $order->id)->value('yougileTaskId');
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->delete($this->baseUrl . "tasks/{$taskId}");

        if (!$response->successful()) {
            throw new \Exception('Ошибка удаления задачи');
        }

        return true;
    }
}
