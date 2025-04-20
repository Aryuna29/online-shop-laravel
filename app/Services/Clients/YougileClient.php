<?php

namespace App\Services\Clients;

use App\Services\Clients\DTO\YougileTaskDto;
use Illuminate\Support\Facades\Http;

class YougileClient
{
    private string $baseUrl;
    private string $apiKey;
    public function __construct()
    {
        $this->baseUrl = 'https://api.yougile.com/api-v2/';
        $this->apiKey = '1HAF3xPsKMBP954xm9WTsJKeCA1GoVtXbExErbG7OlPcPl+cLdtXKUf29mRiLFAu';
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

    public function deleteTask(int $taskId)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->delete($this->baseUrl . "tasks/{$taskId}");

        if (!$response->successful()) {
            throw new \Exception('Ошибка удаления задачи');
        }

        return true;
    }
}
