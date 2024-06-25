<?php

use Swoole\Runtime;
use Swoole\Coroutine\Http\Server;
use function Swoole\Coroutine\run;

Runtime::enableCoroutine(true, SWOOLE_HOOK_ALL);

run(function () {
    $server = new Server('0.0.0.0', 5000, false);
    $server->handle('/chunks', function ($request, $response) {
        $ch = \curl_init();

        $optArray = [
            CURLOPT_URL => 'http://my-app-server:3000/chunks',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_WRITEFUNCTION => function($ch, $data) {
                \var_dump("Chunk: " . $data);
                return \strlen($data);
            }
        ];
        
        \curl_setopt_array($ch, $optArray);
    
        $curlResponse = curl_exec($ch);
        $code = curl_getinfo($ch, \CURLINFO_HTTP_CODE);
    
        if (curl_errno($ch)) {
            \var_dump(curl_error($ch));
        }
    
        \curl_close($ch);

        \var_dump("Response:");
        \var_dump($curlResponse);
        \var_dump("Code:");
        \var_dump($code);

        $response->end("Check docker logs");
    });
    $server->start();
});

