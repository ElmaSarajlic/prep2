<?php

Flight::route('GET /connection-check', function(){
    Flight::midtermService();
});

Flight::route('GET /cap-table', function(){
    Flight::json(Flight::midtermService()->cap_table());
});

Flight::route('GET /summary', function(){
    $service = Flight::midtermService();
    echo json_encode($service->summary());
});

Flight::route('GET /investors', function(){
    $service = Flight::midtermService();
    echo json_encode($service->investors());
});

?>
