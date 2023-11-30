# User Guide: Dockerized RabbitMQ Environment with PHP

## Table of Contents
1. [Installation](#installation)
    - [Install Docker](#install-docker)
2. [Configuration](#configuration)
    - [Edit Docker Compose File](#edit-docker-compose-file)
    - [Build Docker Image](#build-docker-image)
    - [Run Docker Containers](#run-docker-containers)
    - [Check Container Status](#check-container-status)
3. [Configuration of RabbitmqService.php](#configuration-of-rabbitmqservicephp)
4. [Publish a Message](#publish-a-message)
5. [Consume a Message](#consume-a-message)

## 1. Installation

### Install Docker

Docker is required to set up the RabbitMQ environment. Follow these steps to install Docker:

1. Edit `docker-compose.yml` to set the PHP version and other configurations.
2. Build the Docker image with the command:
    ```bash
    docker-compose build
    ```
3. Run the Docker containers with the command:
    ```bash
    docker-compose up -d
    ```
4. Check the status of the running containers with the command:
    ```bash
    docker-compose ps
    ```

## 2. Configuration

### Edit Docker Compose File

Open the `docker-compose.yml` file and set the desired PHP version and other configurations.

### Build Docker Image

Build the Docker image using the following command:
```bash
docker-compose build
```

### Run Docker Containers
Start the Docker containers using the following command:
```bash 
docker-compose up -d
```

### Check Container Status
Check the status of the running containers using the following command:

```bash
docker-compose ps
```

## 3. Configuration of RabbitmqService.php
Run the following command to inspect the Docker container and copy the <b>IPAddress</b> from the output:
```bash
docker inspect <container-name>
```
Open the ```RabbitmqService.php``` file and configure the RabbitMQ service credentials:
```php
// Use the IPAddress obtained from Docker inspect
private array $config = [
    'host' => '127.0.0.1',
    'port' => 5672,
    'user' => 'guest',
    'pass' => 'guest',
];
```

or 
```php
// Use the IPAddress obtained from Docker inspect
$config = [
    'host' => '172.21.0.2',  
    'port' => 5672,
    'user' => 'admin',
    'pass' => 'admin'
];

new RabbitmqService($config);
```

## 4. Publish a Message
Use the following PHP script to publish a message to <b>RabbitMQ</b>:

```php
$mq = new RabbitmqService();

$mq->queue("queue_name")
    ->publish("Hello World!");
```

## 5. Consume a Message
Use the following PHP script to consume messages from RabbitMQ:

```php
$mq = new RabbitmqService();
 
$mq->queue("queue_name")
    ->consume(function($msg) {
        
        $str = date('Y-m-d H:i:s') . " | " . $msg->getBody(). "\n";

        // Print the message
        echo $str;
        
        // Create a log 
        file_put_contents("./temp/log.txt", $str, FILE_APPEND);
    });
```