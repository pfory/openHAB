<?php
include('PachubeAPI.php');

$pachube = new PachubeAPI("q1PY6QqB9jvSHGKhmCQNBRdCofeSAKxpKzliaHJGWUc5UT0g");
$feed = 63310;
$user = "datel";

// mysql
$c = mysqli_connect('localhost', 'root', 'fikus12zeleny', 'data');

$datumCas = new DateTime('NOW');

$temp = $pachube->getDatastream('json', $feed, 'WindS');
$json = json_decode($temp);
$windSpeed = $json->{'current_value'};
/*if ($windSpeed>0) {
  $result = mysql_query("INSERT into dataMeteo (SENSOR_ID, DATETIME, VALUE) select 4,'".$datumCas->format('Y-m-d H:i:s')."',".$windSpeed);
}
*/
$temp = $pachube->getDatastream('json', $feed, 'koefWind');
$json = json_decode($temp);
$koefWind = $json->{'current_value'};
$pachube->updateDatastream("csv", $feed, "WindSpeed", strval(sprintf("%.1f",$windSpeed / $koefWind)));

$temp = $pachube->getDatastream('json', $feed, 'WindSM');
$json = json_decode($temp);
$windSpeedMax = $json->{'current_value'};
/*if ($windSpeedMax>0) {
  $result = mysql_query("INSERT into dataMeteo (SENSOR_ID, DATETIME, VALUE) select 5,'".$datumCas->format('Y-m-d H:i:s')."',".$windSpeedMax);
}
*/
$pachube->updateDatastream("csv", $feed, "WindSpeedMax", strval(sprintf("%.1f",$windSpeedMax / $koefWind)));

$temp = $pachube->getDatastream('json', $feed, 'WindD');
$json = json_decode($temp);
$windDirection = $json->{'current_value'};
//$result = mysql_query("INSERT into dataMeteo (SENSOR_ID, DATETIME, VALUE) select 7,'".$datumCas->format('Y-m-d H:i:s')."',".$windDirection);

//nyni v samostatnem skriptu
/*$temp = $pachube->getDatastream('json', $feed, 'Rain');
$json = json_decode($temp);
$rain = $json->{'current_value'};
if ($rain!="0") {
  $result = mysql_query("INSERT into dataMeteo (SENSOR_ID, DATETIME, VALUE) select 6,'".$datumCas->format('Y-m-d H:i:s')."',".$rain);
}
*/
$temp = $pachube->getDatastream('json', $feed, 'Humidity');
$json = json_decode($temp);
$humidity = $json->{'current_value'};

$temp = $pachube->getDatastream('json', $feed, 'T2899BDCF02000076');
$json = json_decode($temp);
$temperature = $json->{'current_value'};


if (intval(date('i'))<10) {
  $sql = "SELECT count(*) FROM `dataMeteo` WHERE date(datetime) = curdate() and hour(datetime)= hour(now())and sensor_id=1";
  $result = mysqli_query($c, $sql);
  if (!$result) { // check for errors.
      echo 'Could not run query: ' . mysqli_error();
      exit;
  }

  if (mysql_result($result, 0)==0) {
    $result = mysql_query("INSERT into dataMeteo (SENSOR_ID, DATETIME, VALUE) select 3,'".$datumCas->format('Y-m-d H:i:s')."',".$humidity);

    $temp = $pachube->getDatastream('json', $feed, 'Press');
    $json = json_decode($temp);
    $press = $json->{'current_value'};
    $result = mysqli_query($c, "INSERT into dataMeteo (SENSOR_ID, DATETIME, VALUE) select 2,'".$datumCas->format('Y-m-d H:i:s')."',".$press);

    $result = mysqli_query($c, "INSERT into dataMeteo (SENSOR_ID, DATETIME, VALUE) select 1,'".$datumCas->format('Y-m-d H:i:s')."',".$temperature);
  }
}

//vypocet rosneho bodu
$pachube->updateDatastream("csv", $feed, "DewPoint", strval(sprintf("%.1f",calcDewPoint($humidity, $temperature))));


function calcDewPoint ($h, $t)  
{  
    $logEx = 0.66077 + (7.5 * $t) / (237.3 + $t) + (log10($h) - 2);  
    return ($logEx - 0.66077) * 237.3 / (0.66077 + 7.5 - $logEx);  
}