<html>
  <head>
   <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
       <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
   <script type="text/javascript">
      google.charts.load('current', {'packages':['gauge']});
      google.charts.setOnLoadCallback(drawChart3);

      function drawChart3() {

        var options = {
          width: 400, height: 200,
          greenFrom: 0, greenTo: 75,
          redFrom: 90, redTo: 120,
          yellowFrom:75, yellowTo: 90,
          minorTicks: 5,
          max:120,
          min:-30
        };

        var chart = new google.visualization.Gauge(document.getElementById('chart_div3'));

        function refreshData () {
          var jsonData = $.ajax({ url: "getData3.php", dataType:"json", async: false}).responseText;
          var data = new google.visualization.DataTable(jsonData);
          chart.draw(data, options);
        }

        refreshData();
        setInterval(refreshData, 10000);

      }

      
    </script>
  
    
  </head>

  <body>
    <!--this is the div that will hold the pie chart-->
    <div style="position:relative;float:left; margin-top:50px;" id="chart_div3"></div>
  </body>
</html>