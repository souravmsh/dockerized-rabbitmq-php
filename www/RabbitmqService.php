<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;


class RabbitmqService
{
    private AMQPStreamConnection $connection;
    private AMQPChannel $channel;
    private array $config = [
        'host' => '172.21.0.2',
        'port' => 5672,
        'user' => 'admin',
        'pass' => 'admin',
    ];
    private string $queue;

    public function __construct($config = array())
    {
        $this->config = array_merge($this->config, $config);
        $this->connection = new AMQPStreamConnection(
            $config['host'] ?? $this->config['host'],
            $config['port'] ?? $this->config['port'],
            $config['user'] ?? $this->config['user'],
            $config['pass'] ?? $this->config['pass']
        );
        $this->channel = $this->connection->channel();
    }

    public function queue(string $queue): object|array
    {
        $this->queue = $queue;
        return $this;
    }

    public function publish(string $message): void
    {
        $this->channel->queue_declare($this->queue, false, false, false, false);
        $msg = new AMQPMessage($message);
        $this->channel->basic_publish($msg, '', $this->queue);
        echo " Sent '$message'\n";
        $this->close();
    }

    public function consume(Closure $callback): void
    {
        $this->channel->queue_bind($this->queue, 'CyOS-EX');
        $this->channel->basic_consume($this->queue, '', false, true, false, false, $callback);

        try {
            while ($this->channel->is_open()) {
                $this->channel->wait();
            }
        } catch (\Throwable $exception) {
            echo $exception->getMessage();
        } 
    }

    public function close(): void
    {
        $this->channel->close();
        $this->connection->close();
    }
}

