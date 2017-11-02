<?php

use seregazhuk\React\Memcached\Factory;
use seregazhuk\React\Memcached\Client;

require '../vendor/autoload.php';

$loop = React\EventLoop\Factory::create();
$factory = new Factory($loop);

$factory->createClient('localhost:11211')->then(
    function (Client $client) {
        $client
            ->someCommand()
            ->then('var_dump', function(Exception $e){
                echo $e->getMessage();
            });
    });

$loop->run();
