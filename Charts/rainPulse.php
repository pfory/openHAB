<?php
include('PachubeAPI.php');

$pachube = new PachubeAPI("q1PY6QqB9jvSHGKhmCQNBRdCofeSAKxpKzliaHJGWUc5UT0g");
$feed = 63310;
$user = "datel";

// mysql
$c = mysqli_connect('localhost', 'root', 'fikus12zeleny', 'data');
//$c = mysql_connect('localhost', 'czpandatel', '6A0F46C68A');
//mysql_select_db("czpandatel");

$datumCas = new DateTime('NOW');

$temp = $pachube->getDatastream('json', $feed, 'Rain');
$json = json_decode($temp);
$pulseCount = $json->{'current_value'};

if ($pulseCount==0) {
 $pulseCount=1;
}

$result = mysqli_query($c, "INSERT into dataMeteo (SENSOR_ID, DATETIME, VALUE) select 6,'".$datumCas->format('Y-m-d H:i:s')."',".$pulseCount);

echo $result;
