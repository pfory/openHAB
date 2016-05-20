<?php
include('PachubeAPI.php');

$pachube = new PachubeAPI("I88WA1Y8x01WFUthoFJjhk5PD2xZIsTh1XMzAN6YeAA46teR");
$feed = 740319992;
$user = "datel";

// mysql
$c = mysqli_connect('localhost', 'root', 'fikus12zeleny', 'data');

$sql = "SELECT count(*) FROM `dataEnergy` WHERE date(datetime) = curdate() and hour(datetime)= hour(now())and sensor_id=2";
$result = mysqli_query($c, $sql);

if (!$result) { // check for errors.
  echo 'Could not run query: ' . mysqli_error();
  exit;
}

if (mysql_result($result, 0)==0) {
  $datumCas = new DateTime('NOW');

  $temp = $pachube->getDatastream('json', $feed, 'Energy');
  $json = json_decode($temp);
  $energy = $json->{'current_value'};
  
  $result = mysqli_query($c, "INSERT into dataEnergy (SENSOR_ID, DATETIME, energy) select 2,'".$datumCas->format('Y-m-d H:i:s')."',".$energy);
}


?>