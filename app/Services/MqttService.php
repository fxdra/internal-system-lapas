<?php

namespace App\Services;

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;

class MqttService
{
    private $client;

    public function connect()
    {
        $server = env('MQTT_HOST', '127.0.0.1');
        $port   = 1883;
        $clientId = 'laravel-bon';

        $this->client = new MqttClient($server, $port, $clientId);

        $settings = (new ConnectionSettings)
            ->setKeepAliveInterval(60);

        $this->client->connect($settings, true);
    }

    public function publish($topic, $message)
    {
        $this->connect();

        $this->client->publish(
            $topic,
            $message,
            0
        );

        $this->client->disconnect();
    }
}