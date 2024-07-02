<?php

$client_ip = $_SERVER['REMOTE_ADDR'];

$name=isset($_GET['visitor_name'])?$_GET['visitor_name']:'anonymous';
function getLocation($ip)
{
    $response = file_get_contents("http://ipinfo.io/" . $ip . "/json");
    $locationData = json_decode($response, true);
    return isset($locationData['city']) ? $locationData['city'] : 'New york';
}

function getTemperature($city)
{
    $response = file_get_contents("https://api.openweathermap.org/data/2.5/weather?q=$city&APPID=9e7727ecff6cd28af7a936f871538950");
    $weatherData = json_decode($response, true);

    return isset($weatherData['main']['temp']) ? $weatherData['main']['temp'] : null;
}
$location = getLocation($client_ip);
$temperature = (getTemperature($location));

echo json_encode(
    [
        "client_ip" => $client_ip, 
        "location" => $location, 
        "greeting" => "Hello, " . ucfirst(strtolower($name)) . "!, the temperature is  $temperature  degrees Celcius in  $location "
    ]
);