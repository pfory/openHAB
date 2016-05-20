<?php

include("../config.php");
include("../lib/inc/chartphp_dist.php");

$p = new chartphp();

$p->data_sql = "SELECT name, pocet FROM openhab.test order by 1"; 
//$p->data =array(array(3,7,9,1,4,6,8,2,5),array(5,3,8,2,6,2,9,2,6),array(1,6,9,0,4,7,9,2,6));
//$p->data = array(array(array("2010/10",48.25),array("2011/01",238.75),array("2011/02",95.50)));
//$p->data = array(array(85)); 
//$p->intervals = array(50,60,90,100); 


$p->chart_type = "area";

// Common Options
$p->title = "Výstupní teplota";
$p->xlabel = "My X Axis";
$p->ylabel = "My Y Axis";

$out = $p->render('c1');

?>
<!DOCTYPE html>
<html>
    <head>
        <script src="../lib/js/jquery.min.js"></script>
        <script src="../lib/js/chartphp.js"></script>
        <link rel="stylesheet" href="../lib/js/chartphp.css">
    </head>
    <body>
        <div style="width:90%; min-width:450px;">
            <?php echo $out; ?>
        </div>
    </body>
</html>
