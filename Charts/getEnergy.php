<?php
// mysql
$c = mysqli_connect('localhost', 'root', 'fikus12zeleny', 'data');

$sql = "SELECT max(dataEnergy.Datetime) datum, max(dataEnergy.energy + dataEnergyInitial.Energy) energy FROM `dataEnergy` join dataEnergyInitial on dataEnergy.SENSOR_ID=dataEnergyInitial.SENSOR_ID WHERE dataEnergyInitial.SENSOR_ID=1";
$result = mysqli_query($c, $sql);
if (!$result) { // check for errors.
  echo 'Could not run query: ' . mysql_error();
  exit;
}

while ($row = mysqli_fetch_array($result, MYSQL_NUM)) {
  printf ("Stav doma (mereno %s) : %s [kWh]<br/>",$row[0], $row[1]);
}


$sql = "SELECT max(dataEnergy.Datetime) datum, max(dataEnergy.energy + dataEnergyInitial.Energy) energy FROM `dataEnergy` join dataEnergyInitial on dataEnergy.SENSOR_ID=dataEnergyInitial.SENSOR_ID WHERE dataEnergyInitial.SENSOR_ID=2";
$result = mysqli_query($c, $sql);
if (!$result) { // check for errors.
  echo 'Could not run query: ' . mysql_error();
  exit;
}

while ($row = mysqli_fetch_array($result, MYSQL_NUM)) {
  printf ("Stav chata (mereno %s) : %s [kWh]<br/>",$row[0], $row[1]);
}

echo '<br/><br/>';
echo 'Spotreba doma<br/>';
echo '--------------------------------------------<br/>';

//spotreba dnesni den
$sql = "SELECT DATE_FORMAT(min.Date, '%d.%m.%Y'), maxEnergy - minEnergy FROM (SELECT min(energy) minEnergy, min(Datetime) as Date FROM dataEnergy WHERE SENSOR_ID=1 and DATE_FORMAT(datetime, '%Y-%m-%d')=DATE_FORMAT(CURRENT_DATE,'%Y-%m-%d')) min, (SELECT max(energy) maxEnergy FROM dataEnergy WHERE SENSOR_ID=1 and DATE_FORMAT(datetime, '%Y-%m-%d')=DATE_FORMAT(CURRENT_DATE,'%Y-%m-%d')) max";
$result = mysqli_query($c, $sql);
if (!$result) { // check for errors.
  echo 'Could not run query: ' . mysql_error();
  exit;
}

while ($row = mysqli_fetch_array($result, MYSQL_NUM)) {
  printf ("Spotreba dnes (%s) : %s [kWh]<br/>",$row[0], $row[1]);
}

//spotreba mesic
$sql = "SELECT DATE_FORMAT(min.Date, '%m'), maxEnergy - minEnergy FROM (SELECT min(energy) minEnergy, min(Datetime) as Date FROM dataEnergy WHERE SENSOR_ID=1 and DATE_FORMAT(datetime, '%Y-%m')=DATE_FORMAT(CURRENT_DATE,'%Y-%m')) min, (SELECT max(energy) maxEnergy FROM dataEnergy WHERE SENSOR_ID=1 and DATE_FORMAT(datetime, '%Y-%m')=DATE_FORMAT(CURRENT_DATE,'%Y-%m')) max";
$result = mysqli_query($c, $sql);
if (!$result) { // check for errors.
  echo 'Could not run query: ' . mysql_error();
  exit;
}

while ($row = mysqli_fetch_array($result, MYSQL_NUM)) {
  printf ("Spotreba aktualni mesic (%s) : %s [kWh]<br/>",$row[0], $row[1]);
}

//spotreba rok
$sql = "SELECT DATE_FORMAT(min.Date, '%Y'), maxEnergy - minEnergy FROM (SELECT min(energy) minEnergy, min(Datetime) as Date FROM dataEnergy WHERE SENSOR_ID=1 and DATE_FORMAT(datetime, '%Y')=DATE_FORMAT(CURRENT_DATE,'%Y')) min, (SELECT max(energy) maxEnergy FROM dataEnergy WHERE SENSOR_ID=1 and DATE_FORMAT(datetime, '%Y')=DATE_FORMAT(CURRENT_DATE,'%Y')) max";
$result = mysqli_query($c, $sql);
if (!$result) { // check for errors.
  echo 'Could not run query: ' . mysql_error();
  exit;
}

while ($row = mysqli_fetch_array($result, MYSQL_NUM)) {
  printf ("Spotreba aktualni rok (%s) : %s [kWh]<br/>",$row[0], $row[1]);
}



//spotreba jednotlive mesice
printf ("<br/>");
printf ("Spotreba měsíce 2015<br/>");
printf ("-------------------------------<br/>");

$mesic=1;
while ($mesic<=12) {
  $sql = "SELECT DATE_FORMAT(min.Date, '%m'), maxEnergy - minEnergy FROM (SELECT min(energy) minEnergy, min(Datetime) as Date FROM dataEnergy WHERE SENSOR_ID=1 and DATE_FORMAT(datetime, '%Y-%m')=DATE_FORMAT('2015-".$mesic."-01','%Y-%m')) min, (SELECT max(energy) maxEnergy FROM dataEnergy WHERE SENSOR_ID=1 and DATE_FORMAT(datetime, '%Y-%m')=DATE_FORMAT('2015-".$mesic."-01','%Y-%m')) max";
  $result = mysqli_query($c, $sql);
  if (!$result) { // check for errors.
    echo 'Could not run query: ' . mysql_error();
    exit;
  }

  while ($row = mysqli_fetch_array($result, MYSQL_NUM)) {
    printf ("Spotreba (%s) : %s [kWh]<br/>",$row[0], $row[1]);
  }
  $mesic++;
}

printf ("<br/>");
printf ("Spotreba měsíce 2016<br/>");
printf ("-------------------------------<br/>");

$mesic=1;
while ($mesic<=12) {
  $sql = "SELECT DATE_FORMAT(min.Date, '%m'), maxEnergy - minEnergy FROM (SELECT min(energy) minEnergy, min(Datetime) as Date FROM dataEnergy WHERE SENSOR_ID=1 and DATE_FORMAT(datetime, '%Y-%m')=DATE_FORMAT('2016-".$mesic."-01','%Y-%m')) min, (SELECT max(energy) maxEnergy FROM dataEnergy WHERE SENSOR_ID=1 and DATE_FORMAT(datetime, '%Y-%m')=DATE_FORMAT('2016-".$mesic."-01','%Y-%m')) max";
  $result = mysqli_query($c, $sql);
  if (!$result) { // check for errors.
    echo 'Could not run query: ' . mysql_error();
    exit;
  }

  while ($row = mysqli_fetch_array($result, MYSQL_NUM)) {
    printf ("Spotreba (%s) : %s [kWh]<br/>",$row[0], $row[1]);
  }
  $mesic++;
}



echo '<br/><br/>';
echo 'Spotreba chata<br/>';
echo '--------------------------------------------<br/>';

//spotreba dnesni den
$sql = "SELECT DATE_FORMAT(min.Date, '%d.%m.%Y'), maxEnergy - minEnergy FROM (SELECT min(energy) minEnergy, min(Datetime) as Date FROM dataEnergy WHERE SENSOR_ID=2 and DATE_FORMAT(datetime, '%Y-%m-%d')=DATE_FORMAT(CURRENT_DATE,'%Y-%m-%d')) min, (SELECT max(energy) maxEnergy FROM dataEnergy WHERE SENSOR_ID=2 and DATE_FORMAT(datetime, '%Y-%m-%d')=DATE_FORMAT(CURRENT_DATE,'%Y-%m-%d')) max";
$result = mysqli_query($c, $sql);
if (!$result) { // check for errors.
  echo 'Could not run query: ' . mysql_error();
  exit;
}

while ($row = mysqli_fetch_array($result, MYSQL_NUM)) {
  printf ("Spotreba dnes (%s) : %s [kWh]<br/>",$row[0], $row[1]);
}

//spotreba mesic
$sql = "SELECT DATE_FORMAT(min.Date, '%m'), maxEnergy - minEnergy FROM (SELECT min(energy) minEnergy, min(Datetime) as Date FROM dataEnergy WHERE SENSOR_ID=2 and DATE_FORMAT(datetime, '%Y-%m')=DATE_FORMAT(CURRENT_DATE,'%Y-%m')) min, (SELECT max(energy) maxEnergy FROM dataEnergy WHERE SENSOR_ID=2 and DATE_FORMAT(datetime, '%Y-%m')=DATE_FORMAT(CURRENT_DATE,'%Y-%m')) max";
$result = mysqli_query($c, $sql);
if (!$result) { // check for errors.
  echo 'Could not run query: ' . mysql_error();
  exit;
}

while ($row = mysqli_fetch_array($result, MYSQL_NUM)) {
  printf ("Spotreba aktualni mesic (%s) : %s [kWh]<br/>",$row[0], $row[1]);
}

//spotreba rok
$sql = "SELECT DATE_FORMAT(min.Date, '%Y'), maxEnergy - minEnergy FROM (SELECT min(energy) minEnergy, min(Datetime) as Date FROM dataEnergy WHERE SENSOR_ID=2 and DATE_FORMAT(datetime, '%Y')=DATE_FORMAT(CURRENT_DATE,'%Y')) min, (SELECT max(energy) maxEnergy FROM dataEnergy WHERE SENSOR_ID=2 and DATE_FORMAT(datetime, '%Y')=DATE_FORMAT(CURRENT_DATE,'%Y')) max";
$result = mysqli_query($c, $sql);
if (!$result) { // check for errors.
  echo 'Could not run query: ' . mysql_error();
  exit;
}

while ($row = mysqli_fetch_array($result, MYSQL_NUM)) {
  printf ("Spotreba aktualni rok (%s) : %s [kWh]<br/>",$row[0], $row[1]);
}



?>