<?php
include('PachubeAPI.php');

$pachube = new PachubeAPI("nJklwM9ts0HnRj3FbD6x2lA8CLOx8MADYx1WGGUSom9DRk9C");
$feed = 711798850;
$user = "datel";

// mysql
$c = mysqli_connect('localhost', 'root', 'fikus12zeleny', 'data');

$sql = "SELECT count(*) FROM `dataEnergy` WHERE date(datetime) = curdate() and hour(datetime)= hour(now())and sensor_id=1";
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

  $result = mysqli_query($c, "INSERT into dataEnergy (SENSOR_ID, DATETIME, energy) select 1,'".$datumCas->format('Y-m-d H:i:s')."',".$energy);
}


?>