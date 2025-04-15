<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitmqService
{
    private AMQPStreamConnection $connection;
    public function __construct()
    {
        $this->connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
    }
    public function produce(array $data, string $queueName)
    {
        $connection = $this->connection;
        $channel = $connection->channel();

        $channel->queue_declare($queueName, false, false, false, false);
        $data = json_encode($data);
        $msg = new AMQPMessage($data);
        $channel->basic_publish($msg, '', $queueName);
    }

    public function consume(string $queueName, callable $callback)
    {
        $connection = $this->connection;
        $channel = $connection->channel();

        $channel->queue_declare($queueName, false, false, false, false);

        echo " [*] Waiting for messages. To exit press CTRL+C\n";

        echo "Подключение к очереди: $queueName\n";

        $channel->basic_consume('sign-up-email', '', false, true, false, false, $callback);

        try {
            $channel->consume();
        } catch (\Throwable $exception) {
            echo $exception->getMessage();
        }
    }
}
