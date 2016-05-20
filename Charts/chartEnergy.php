 <html>
  <head>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['gauge','corechart']});
      google.charts.setOnLoadCallback(drawChart1);
      google.charts.setOnLoadCallback(drawChart2);
      google.charts.setOnLoadCallback(drawChart3);
      google.charts.setOnLoadCallback(drawChart4);

      function drawChart1() {
        var options = {
          hAxis: {title: 'Čas'},
          vAxis: {title: 'Příkon [W]'},
          title: 'Příkon byt [W]',
          //curveType:'function',
          legend: { position: 'none' },
          height: 300,
          chartArea:{
            backgroundColor: 'lightgray',
          },
          crosshair: { trigger: 'both' }, 
          //pointSize: 2,
        };
        
        var chart = new google.visualization.LineChart(document.getElementById('chart_div1'));
      
        function refreshData () {
          var jsonData = $.ajax({ url: "getDataEnergyBytChart.php", dataType:"json", async: false}).responseText;
          var data = new google.visualization.DataTable(jsonData);
          chart.draw(data, options);
        }
      
        refreshData();
        setInterval(refreshData, 5000);
      }
      
       function drawChart2() {

        var options = {
          width: 200, height: 200,
          greenFrom: 0, greenTo: 500,
          yellowFrom:500, yellowTo: 1000,
          redFrom: 1000, redTo: 4000,
          max:4000,
          min:0
        };

        var chart = new google.visualization.Gauge(document.getElementById('chart_div2'));

        function refreshData () {
          var jsonData = $.ajax({ url: "getDataEnergyBytGauge.php", dataType:"json", async: false}).responseText;
          var data = new google.visualization.DataTable(jsonData);
          chart.draw(data, options);
        }

        refreshData();
        setInterval(refreshData, 5000);

      }
    
      function drawChart3() {
        var options = {
          hAxis: {title: 'Čas'},
          vAxis: {title: 'Příkon [W]'},
          title: 'Příkon chata [W]',
          //curveType:'function',
          legend: { position: 'none' },
          height: 300,
          chartArea:{
            backgroundColor: 'lightgray',
          },
          crosshair: { trigger: 'both' }, 
          //pointSize: 2,
        };
        
        var chart = new google.visualization.LineChart(document.getElementById('chart_div3'));
      
        function refreshData () {
          var jsonData = $.ajax({ url: "getDataEnergyChataChart.php", dataType:"json", async: false}).responseText;
          var data = new google.visualization.DataTable(jsonData);
          chart.draw(data, options);
        }
      
        refreshData();
        setInterval(refreshData, 5000);
      }
      
       function drawChart4() {

        var options = {
          width: 200, height: 200,
          greenFrom: 0, greenTo: 500,
          yellowFrom:500, yellowTo: 1000,
          redFrom: 1000, redTo: 4000,
          max:4000,
          min:0
        };

        var chart = new google.visualization.Gauge(document.getElementById('chart_div4'));

        function refreshData () {
          var jsonData = $.ajax({ url: "getDataEnergyChataGauge.php", dataType:"json", async: false}).responseText;
          var data = new google.visualization.DataTable(jsonData);
          chart.draw(data, options);
        }

        refreshData();
        setInterval(refreshData, 5000);

      }

    
    </script>
  
    
  </head>

  <body>
    <!--this is the div that will hold the pie chart-->
    <div style="position:relative; float:left; width:500px;" id="chart_div1"></div>
    <div style="position:relative;float:left; margin-top:50px;" id="chart_div2"></div>
    <div style="position:relative; float:left; width:500px;" id="chart_div3"></div>
    <div style="position:relative;float:left; margin-top:50px;" id="chart_div4"></div>
  </body>
</html>