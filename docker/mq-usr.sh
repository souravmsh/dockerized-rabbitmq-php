#!/bin/bash

set -e

# Start RabbitMQ server
rabbitmq-server -detached

# Wait for RabbitMQ to start
sleep 5

# Create RabbitMQ user
rabbitmqctl add_user admin admin
rabbitmqctl set_user_tags admin administrator
rabbitmqctl set_permissions -p / admin ".*" ".*" ".*"

# Tail the RabbitMQ logs to keep the container running
tail -f /var/log/rabbitmq/rabbit\@$HOSTNAME.log

