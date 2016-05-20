<?php
$con = mysqli_connect('localhost','root','fikus12zeleny','openhab');
if (!$con) {
  die('Could not connect: ' . mysqli_error($con));
}

$qry = "select time, dateFormated, value from (SELECT time, date_format(time, '%H:%i') dateFormated, value FROM openhab.Item141 where time > DATE_ADD(now(), INTERVAL -1 hour) order by 1 desc) as der order by 1 asc";
 
$result = mysqli_query($con,$qry);
mysqli_close($con);

$table = array();
$table['cols'] = array(
  //Labels for the chart, these represent the column titles
  array('id' => '', 'label' => 'Datum', 'type' => 'string'),
  array('id' => '', 'label' => 'Příkon [W]', 'type' => 'number')
); 
    
$rows = array();
foreach($result as $row){
  $temp = array();
     
  //Values
  $temp[] = array('v' => (string) $row['dateFormated']);
  $temp[] = array('v' => (float) $row['value']); 
  $rows[] = array('c' => $temp);
}

$result->free();
 
$table['rows'] = $rows;
 
$jsonTable = json_encode($table, true);
echo $jsonTable;
?>