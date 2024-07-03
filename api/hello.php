<?php

$client_ip = getIp();

function getIp(){
    $ipAddress='';
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
    }
    
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    
    else {
        $ipAddress = $_SERVER['REMOTE_ADDR'];
    }

    return $ipAddress = explode(',', $ipAddress)[0];
}
// $client_ip='8.8.8.8';

$name=isset($_GET['visitor_name'])?$_GET['visitor_name']:'anonymous';
function getLocation($ip)
{
    $response = file_get_contents("http://ipinfo.io/" . $ip . "/json");
    return json_decode($response, true);
}


function getTemperature($data)
{
    $d=explode(',',$data['loc']);$latitude=$d[0];$longitude=$d[1];
    $response = file_get_contents("https://api.open-meteo.com/v1/forecast?latitude=$latitude&longitude=$longitude&current_weather=true");
    $weatherData = json_decode($response, true);
    return ($weatherData['current_weather']['temperature']);
}
$locationData = getLocation($client_ip);

$temperature = (getTemperature($locationData));
header('content-type:application/json');
echo json_encode(
    [
        "client_ip" => $client_ip, 
        "location" => $locationData['city'], 
        "greeting" => "Hello, " . ucfirst(strtolower($name)) . "!, the temperature is  $temperature  degrees Celcius in  {$locationData['city']}"
    ]
);