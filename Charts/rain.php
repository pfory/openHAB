<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8">
    <title>Srážky</title>
    <link rel="stylesheet" type="text/css" href="main.css">
    <link rel="shortcut icon" href="http://pandatel.php5.cz/favicon.ico" type="image/x-icon" />
</head>
<body>

<a href=main.php>Zpět na hlavní stránku</a><br>


<?php

// Set timezone
date_default_timezone_set("Europe/Prague");
$koef = 0.2;
//phpinfo();

class DateTimeCzech extends DateTime {
    public function format($format) {
        $english = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday', 
          'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
        $czech = array('Ponděli', 'Úterý', 'Středa', 'Čtvrtek', 'Pátek', 'Sobota', 'Neděle',
          'Leden', 'Únor', 'Březen', 'Duben', 'Květen', 'Červen', 'Červenec', 'Srpen', 'Září', 'Říjen', 'Listopad', 'Prosinec');
        return str_replace($english, $czech, parent::format($format));
    }
}

/*$today = unixtojd(mktime(0, 0, 0, 8, 16, 2003));
echo $today;
print_r(cal_from_jd($today, CAL_GREGORIAN));
*/

if ($_GET["day"]) {
  $dArray=cal_from_jd($_GET["day"], CAL_GREGORIAN);
  $den=new DateTimeCzech($dArray["year"].'-'.$dArray["month"].'-'.$dArray["day"]);
}
else {
  $den=new DateTimeCzech('NOW');
}

$denJD=cal_to_jd(CAL_GREGORIAN, $den->format('n'), $den->format('j'), $den->format('Y'));

/*
$days_in_month=date('t');
//printf("Dnu v aktualnim mesici %s", $days_in_month);
//echo "</br>";
$denPred = $den->format('j')-1;

if ($denPred==0) {
  $d=new DateTimeCzech('2013-'.(((int) $_GET["month"])-1).'-'.(int) $_GET["day"]);
  echo $d->format('l j.n.Y H:i:s e');
  $denPred=date('t');
  $mesicPred=$den->format('n')-1;
} else {
  $mesicPred=$den->format('n');
}
$denPo=$denPred+2;
if ($denPo>$days_in_month) {
  $denPo=1;
  $mesicPo=$den->format('n')+1;
} else {
  $mesicPo=$den->format('n');
}
*/

$dnes=new DateTimeCzech('NOW');

printf("<a href='rain.php'>Dnes</a> je %s</br>", $dnes->format('l j.n.Y H:i:s e'));
printf("<a href='rain.php?day=%s'><< </a>",$denJD-1);
printf("%s", $den->format('l j.n.Y'));
printf("<a href='rain.php?day=%s'> >></a>",$denJD+1);


// mysql
$c = mysqli_connect('localhost', 'root', 'fikus12zeleny', 'data');
//mysql_select_db("");


// $result = mysql_query("set session lc_time_names='cs-CZ'");
 // if (!$result) { // check for errors.
     // echo 'Could not run query: ' . mysql_error();
     // exit;
// }



//souhrn za den po jednotlivych hodinach
$sql_base = "SELECT hour(datetime) as hour, sum(value) * ".$koef." as mm FROM dataMeteo WHERE sensor_id=6 ";
$sql_where = " and datetime between '".$den->format('Y-m-d')." 00:00:00' and '".$den->format('Y-m-d')." 23:59:59' ";
$sql_groupby = " group by hour(datetime) "; 
$sql = $sql_base.$sql_where.$sql_groupby;

$result = mysqli_query($c, $sql);
if (!$result) { // check for errors.
    echo 'Could not run query: ' . mysqli_error($c);
    exit;
}

$maximum = -1;
while ($row = mysqli_fetch_array($result, MYSQL_NUM)) {
  if ($row[1]>$maximum && $row[1]>0) {
    $maximum = $row[1];
  }
}
  
$result = mysqli_query($c, $sql);
  
$naprseloDen = 0;
printf("<div class=tabulka>");
printf("<table class=tabulka>");
printf("<tr style='font-weight:bold'><td colspan=2>Souhrn srážek v mm za %s po jednotlivých hodinách</td></tr>",$den->format('l j F Y (j.n.Y)'));
printf("<tr><td>Hodina</td><td>mm</td></tr>");
while ($row = mysqli_fetch_array($result, MYSQL_NUM)) {
  if ($row[1]>0) {
   $naprseloDen += $row[1];
    printf("<tr><td>%s</td><td class=zvyrazni>%s", $row[0], round($row[1],2));  
  }
  else
  {
    printf("<tr><td>%s</td><td>", $row[0]);  
  }
  if ($row[1] == $maximum) {
    printf(" (max)");
  }
  printf("</td></tr>");
}
printf("</table>");
printf("</div>");


if ($naprseloDen>0) {
  if ($den==$dnes)
    printf("Dnes napršelo už %s mm.<br>", $naprseloDen);
  else
    printf("Celkem napršelo %s mm.<br>", $naprseloDen);
}
else
{
  if ($den==$dnes)
    printf("<div style='color:red'>Dnes ještě nepršelo!!!</div>");
}

mysqli_free_result($result);

//$sql_base = "SELECT DATE_FORMAT(max(datetime),'%W %e.%c.%Y %k:%i:%s') as datumcas FROM dataMeteo WHERE sensor_id=6 ";
$sql_base = "SELECT max(datetime) as datumcas FROM dataMeteo WHERE sensor_id=6 ";
$sql_where = " and value>0 ";
$sql = $sql_base.$sql_where;

$result = mysqli_query($c, $sql);
if (!$result) { // check for errors.
    echo 'Could not run query: ' . mysqli_error();
    exit;
}

$row = mysqli_fetch_array($result, MYSQL_NUM);
$naposledPrselo = new DateTimeCzech($row[0]);

printf("Naposled pršelo : %s (už je to %s).", $naposledPrselo->format('l j.n.Y G:i:s'), dateDiff($dnes->format('Y-m-d H:i:s'), $naposledPrselo->format('Y-m-d H:i:s')));

mysqli_free_result($result);

//souhrn srazek za mesic po jednotlivych dnech
$sql_base = "SELECT day(datetime) as den, dayname(datetime) as denJmeno, sum(value) *".$koef." as mm FROM dataMeteo WHERE sensor_id=6 ";
$sql_where = " and datetime between '".$den->format('Y-m')."-01 00:00:00' and '".$den->format('Y-m-t')." 23:59:59' ";
$sql_groupby = " group by dayofmonth(datetime) ";
$sql = $sql_base.$sql_where.$sql_groupby;

$result = mysqli_query($c, $sql);
if (!$result) { // check for errors.
    echo 'Could not run query: ' . mysqli_error();
    exit;
}

$maximum = -1;
while ($row = mysqli_fetch_array($result, MYSQL_NUM)) {
  if ($row[2]>$maximum && $row[2]>0) {
    $maximum = $row[2];
  }
}


$result = mysqli_query($c, $sql);

$naprseloMesic = 0;
printf("<div class=tabulka>");
printf("<table class=tabulka>");
printf("<tr style='font-weight:bold'><td colspan=2>Souhrn srážek v mm za %s po jednotlivých dnech</td></tr>",$den->format('F Y (n/Y)'));
printf("<tr><td>Den</td><td>mm</td></tr>");
$den1=cal_to_jd(CAL_GREGORIAN, $den->format('n'), 1, $den->format('Y'))-1;
while ($row = mysqli_fetch_array($result, MYSQL_NUM)) {
  if ($row[1]=="Sobota" || $row[1]=="Neděle")
    printf("<tr><td style='background-color:lightgreen'>");
  else
    printf("<tr><td>");
  
  printf("<a href='rain.php?day=%s'>%s %s</a></td>", $den1+$row[0], $row[0], $row[1]);  

  if ($row[2]>0)
  {
    $naprseloMesic += $row[2];
    printf("<td class=zvyrazni>%s", round($row[2],2));  
  }
  else
  {
    printf("<td>");  
  }
  if ($row[2] == $maximum) {
    printf(" (max)");
  }

  printf("</td></tr>");
  
}
printf("</table>");
printf("</div>");


if ($naprseloMesic>0) {
  printf("Za %s napršelo už %s mm.<br>", $den->format('F Y'), $naprseloMesic);
}
else
{
  printf("<div style='color:red'>Tento měsíc ještě nepršelo!!!</div>");
}

mysqli_free_result($result);


//souhrny srazek za aktuální rok po jednotlivých mesicich
//$sql_base = "SELECT concat(month(datetime),'/',year(datetime)) as mesic, monthname(datetime) as mesicJmeno, sum(value) *".$koef." as mm FROM dataMeteo WHERE sensor_id=6 ";
$sql_base = "SELECT month(datetime) as mesic, monthname(datetime) as mesicJmeno, sum(value) *".$koef." as mm FROM dataMeteo WHERE sensor_id=6 ";
$sql_where = " and datetime between '".$den->format('Y')."-01-01 00:00:00' and '".$den->format('Y')."-12-31 23:59:59' ";
$sql_groupby = " group by year(datetime), month(datetime) ";
$sql = $sql_base.$sql_where.$sql_groupby;

$result = mysqli_query($c, $sql);
if (!$result) { // check for errors.
    echo 'Could not run query: ' . mysqli_error();
    exit;
}

$maximum = -1;
while ($row = mysqli_fetch_array($result, MYSQL_NUM)) {
  if ($row[2]>$maximum && $row[2]>0) {
    $maximum = $row[2];
  }
}

$result = mysqli_query($c, $sql);

$naprseloRok = 0;
printf("<div class=tabulka>");
printf("<table class=tabulka>");
printf("<tr style='font-weight:bold'><td colspan=2>Souhrn srážek v mm za aktuální rok po jednotlivých měsících</td></tr>");
printf("<tr><td>Měsíc</td><td>mm</td></tr>");
while ($row = mysqli_fetch_array($result, MYSQL_NUM)) {
  printf("<tr><td><a href='rain.php?day=%s'>%s - %s</a></td>", cal_to_jd(CAL_GREGORIAN, $row[0], 1, $den->format('Y')), $row[0], $row[1]);  
  if ($row[2]>0)
  {
    $naprseloRok += $row[2];
    printf("<td class=zvyrazni>%s", round($row[2],2));  
  }
  else
  {
    printf("<td>");  
  }
  if ($row[2] == $maximum) {
    printf(" (max)");
  }

  printf("</td></tr>");
  
}
printf("</table>");
printf("</div>");

if ($naprseloRok>0) {
  printf("Za rok %s napršelo už %s mm.<br>", $den->format('Y'), round($naprseloRok,2));
}
else
{
  printf("<div style='color:red'>Letos ještě nepršelo!!!</div>");
}


mysqli_free_result($result);


//souhrn srazek za poslednich 24 hodin
$sql_base = "SELECT sum(value) *".$koef." as mm FROM dataMeteo WHERE sensor_id=6 ";
$sql_where = " and datetime between addtime(now(),'-23:59:59') and now() ";
$sql = $sql_base.$sql_where;

$result = mysqli_query($c, $sql);
if (!$result) { // check for errors.
    echo 'Could not run query: ' . mysqli_error();
    exit;
}

printf("</div>");

printf("<div>");
printf("<table class=tabulka>");

$row = mysqli_fetch_array($result, MYSQL_NUM);
printf("<tr><td>Souhrn srážek za posledních 24 hodin</td><td>%s mm</td></tr>", round(@row[0],2));

mysqli_free_result($result);

//souhrn srazek za posledni hodinu
$sql_base = "SELECT sum(value) *".$koef." as mm FROM dataMeteo WHERE sensor_id=6 ";
$sql_where = " and datetime between addtime(now(),'-0:59:59') and now() ";
$sql = $sql_base.$sql_where;

$result = mysqli_query($c, $sql);
if (!$result) { // check for errors.
    echo 'Could not run query: ' . mysqli_error();
    exit;
}

$row = mysqli_fetch_array($result, MYSQL_NUM);
printf("<tr><td>Souhrn srážek za poslední hodinu</td><td>%s mm</td></tr>", round(@row[0],2));

mysqli_free_result($result);

//souhrn srazek za poslednich 10 minut
$sql_base = "SELECT sum(value) *".$koef." as mm FROM dataMeteo WHERE sensor_id=6 ";
$sql_where = " and datetime between addtime(now(),'-0:09:59') and now() ";
$sql = $sql_base.$sql_where;

$result = mysqli_query($c, $sql);
if (!$result) { // check for errors.
    echo 'Could not run query: ' . mysqli_error();
    exit;
}

$row = mysqli_fetch_array($result, MYSQL_NUM);
printf("<tr><td>Souhrn srážek za posledních 10 minut</td><td>%s mm</td></tr>", round($row[0],2));

printf("</table>");
printf("</div>");


mysqli_free_result($result);


//souhrny srazek za rok 2014 po jednotlivých mesicich
//$sql_base = "SELECT concat(month(datetime),'/',year(datetime)) as mesic, monthname(datetime) as mesicJmeno, sum(value) *".$koef." as mm FROM dataMeteo WHERE sensor_id=6 ";
$sql_base = "SELECT month(datetime) as mesic, monthname(datetime) as mesicJmeno, sum(value) *".$koef." as mm FROM dataMeteo WHERE sensor_id=6 ";
$sql_where = " and datetime between '2014-01-01 00:00:00' and '2014-12-31 23:59:59' ";
$sql_groupby = " group by year(datetime), month(datetime) ";
$sql = $sql_base.$sql_where.$sql_groupby;

$result = mysqli_query($c, $sql);
if (!$result) { // check for errors.
    echo 'Could not run query: ' . mysqli_error();
    exit;
}

$maximum = -1;
while ($row = mysqli_fetch_array($result, MYSQL_NUM)) {
  if ($row[2]>$maximum && $row[2]>0) {
    $maximum = $row[2];
  }
}

$result = mysqli_query($c, $sql);

$naprseloRok = 0;
printf("<div class=tabulka>");
printf("<table class=tabulka>");
printf("<tr style='font-weight:bold'><td colspan=2>Souhrn srážek v mm za rok 2014 po jednotlivých měsících</td></tr>");
printf("<tr><td>Měsíc</td><td>mm</td></tr>");
while ($row = mysqli_fetch_array($result, MYSQL_NUM)) {
  printf("<tr><td><a href='rain.php?day=%s'>%s - %s</a></td>", cal_to_jd(CAL_GREGORIAN, $row[0], 1, $den->format('Y')), $row[0], $row[1]);  
  if ($row[2]>0)
  {
    $naprseloRok += $row[2];
    printf("<td class=zvyrazni>%s", round($row[2],2));  
  }
  else
  {
    printf("<td>");  
  }
  if ($row[2] == $maximum) {
    printf(" (max)");
  }

  printf("</td></tr>");
  
}
printf("</table>");
printf("</div>");

printf("Za rok %s napršelo %s mm.<br>", $den->format('Y'), round($naprseloRok,2));

mysqli_free_result($result);



 
// Time format is UNIX timestamp or
// PHP strtotime compatible strings
function dateDiff($time1, $time2, $precision = 6) {
  $english = array('year', 'month', 'day', 'hours', 'minute', 'seconds', 'second');
  $czech = array('rok', 'měsíc', 'den', 'hodin', 'minuta', 'vteřin', 'vteřina');


  // If not numeric then convert texts to unix timestamps
  if (!is_int($time1)) {
    $time1 = strtotime($time1);
  }
  if (!is_int($time2)) {
    $time2 = strtotime($time2);
  }

  // If time1 is bigger than time2
  // Then swap time1 and time2
  if ($time1 > $time2) {
    $ttime = $time1;
    $time1 = $time2;
    $time2 = $ttime;
  }

  // Set up intervals and diffs arrays
  $intervals = array('year','month','day','hour','minute','second');
  $diffs = array();

  // Loop thru all intervals
  foreach ($intervals as $interval) {
    // Create temp time from time1 and interval
    $ttime = strtotime('+1 ' . $interval, $time1);
    // Set initial values
    $add = 1;
    $looped = 0;
    // Loop until temp time is smaller than time2
    while ($time2 >= $ttime) {
      // Create new temp time from time1 and interval
      $add++;
      $ttime = strtotime("+" . $add . " " . $interval, $time1);
      $looped++;
    }

    $time1 = strtotime("+" . $looped . " " . $interval, $time1);
    $diffs[$interval] = $looped;
  }

  $count = 0;
  $times = array();
  // Loop thru all diffs
  foreach ($diffs as $interval => $value) {
    // Break if we have needed precission
    if ($count >= $precision) {
      break;
    }
    // Add value and interval 
    // if value is bigger than 0
    if ($value > 0) {
      // Add s if value is not 1
      if ($value != 1) {
        $interval .= "s";
      }

      //$interval = str_replace($english, $czech, $interval);

      // Add value and interval to times array
      $times[] = $value . " " . $interval;
      $count++;
    }
  }
  
  // Return string with times
  return implode(", ", $times);
}

?>

</br>
</br>
</body>
</html>