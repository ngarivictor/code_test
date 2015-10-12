	<?php


require_once 'lib/calc.php';

$start_time = microtime(true);

//get str from URL
$str=file_get_contents('https://raw.githubusercontent.com/onaio/ona-tech/master/data/water_points.json')

$json =json_decode($str,true); //decode the JSON into an associative array

echo '<pre>' . print_r($json,true) . '</pre>';

$communities_villages =$json['daily']['data']
[0]['communities_villages'];
$water_functioning =$json['daily']['data']
[0]['water_functioning'];
