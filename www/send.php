<?php

error_reporting(E_ALL);
ini_set("display_errors",1);
ini_set("timezone","Asia/Dhaka");

include("RabbitmqService.php");

# publish/send message
$rabbitMQ = new RabbitmqService();
$rabbitMQ->queue("testq")
    ->publish("some text");
 