<?php

error_reporting(E_ALL);
ini_set("display_errors",1);

include("RabbitmqService.php");

// Example Usage:
$rabbitMQ = new RabbitmqService();
 
// consume/receive message
$rabbitMQ->queue("testq")
    ->consume(function($msg) {
        $str = date('Y-m-d H:i:s') . " | " . $msg->getBody(). "\n";
        echo $str;
        file_put_contents("./temp/log.txt", $str, FILE_APPEND);
    });
 