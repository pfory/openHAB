<?php
$con = mysqli_connect('localhost','root','fikus12zeleny','openhab');
if (!$con) {
  die('Could not connect: ' . mysqli_error($con));
}

$qry = "SELECT time, truncate(value,0) as value FROM openhab.Item141 order by 1 desc limit 1";
 
$result = mysqli_query($con,$qry);
mysqli_close($con);

$table = array();
$table['cols'] = array(
  //Labels for the chart, these represent the column titles
  array('id' => '', 'label' => 'Příkon [W]', 'type' => 'number')
); 
    
$rows = array();
foreach($result as $row){
  $temp = array();
     
  //Values
  $temp[] = array('v' => (float) $row['value']); 
  $rows[] = array('c' => $temp);
}

$result->free();
 
$table['rows'] = $rows;
 
$jsonTable = json_encode($table, true);
echo $jsonTable;
?>