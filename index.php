<DOCTYPE HTML!>
    <head>
        <html lang="pl" />
        <meta charset="utf-8" />
		<script src="https://cdn.anychart.com/releases/8.10.0/js/anychart-base.min.js"></script>
		<link rel="stylesheet" href="style.css" type="text/css" />
		<link rel="stylesheet" href="fontello/css/fontello.css" type="text/css" />
		<script src="jquery-3.6.0.min.js"></script>
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;400;500&display=swap" rel="stylesheet">
    </head>
    <body>
		<script>//skrypt odpowiadający za menu przyklejane
		$(document).ready(function() {
		var NavY = $('#menu').offset().top;
	 
		var stickyNav = function(){
			var ScrollY = $(window).scrollTop();
			if (ScrollY > NavY) { 
				$('#menu').addClass('sticky');
			} else {
				$('#menu').removeClass('sticky'); 
			}
		};
		stickyNav();
		$(window).scroll(function() {
			stickyNav();
		});
		});
	</script>

        <div id="naglowek">WARUNKI METEOROLOGICZNE
		<div id="menu">
		<p onclick="scrollWin(0);" class="menuButton">Początek</p>
		<p onclick="scrollWin(940);" class="menuButton">O Stacji</p>
		<p onclick="scrollWin(940);" class="menuButton">Log</p>
		</div>
		</div>
		<?php
			error_reporting(E_ERROR | E_PARSE);//hide error messages
			$lastActualization = '';
			$LogFileName = 'log.csv';
			$ostacjiFileName = 'ostacji.txt';
			$data = [];

			// open the file
			$LogFile = fopen($LogFileName, 'r');
			if ($LogFile === false) {
				die('Cannot open the file ' . $LogFileName);
			}
			// read each line in CSV file at a time
			while (($row = fgetcsv($LogFile)) !== false) {
				$data[] = $row;
			}
			fclose($LogFile);// close the file
			
			$lastRow = count($data)-1;
			
			//Tabela: aktualna Pogoda
			echo '<div class="clsFL" style="margin-left:10px;">
				<h1>Aktualna Pogoda</h1>
				<table border="1" cellspacing="0" cellpadding="2">
					<thead><tr><th colspan="2"><div id="lastActualization">Ostatnia Aktualizacja: '.$data[$lastRow][0].'</div></th></tr></thead>
					<thead><tr><th colspan="2">Powietrze:</th></tr></thead>
					<tr><td>Temperatura:</td> <td><div id="actTemp">'.$data[$lastRow][1].'*C</div></td></tr>
					<tr><td>Ciśnienie:  </td> <td><div id="actPres">'.intval($data[$lastRow][2])*(10).'hPa</div></td></tr>
					<tr><td>Wilgotność:</td> <td><div id="actHuma">'.$data[$lastRow][3].'%</div></td></tr>
					<tr><td>Nasłonecznienie:</td> <td><div id="actInso">'.$data[$lastRow][4].'V</div></td></tr>
					<thead><tr><th colspan="2">Jakość Powietrza:</th></tr></thead>
					<tr><td>PM 1.0:</td> <td><div id="actPol1">'.$data[$lastRow][5].'μm</div></td></tr>
					<tr><td>PM 2.5:</td> <td><div id="actPo25">'.$data[$lastRow][6].'μm</div></td></tr>
					<tr><td>PM 10.0:</td> <td><div id="actPol10">'.$data[$lastRow][7].'μm</div></td></tr>
					<thead><tr><th colspan="2">Wiatr:<span style="color: red;">!!NIE działa!!</span></th></tr></thead>
					<tr><td>Kierunek:</td> <td><div id="actPol1">'.$data[$lastRow][8].'</div></td></tr>
					<tr><td>Siła:</td> <td><div id="actPo25">'.$data[$lastRow][9].'m/s</div></td></tr>
				</table>
			</div>';
		
		
		//echo '<div style="float:left; width:15%;">|</div>';
		echo '<div class="clsFL"><h2 style="text-align:center;">Ostatnie 24h</h2> <div id="chart" class="chart"></div> <div id="chart2" class="chart"></div></div>';
		echo '<div class="clsCB"></div>';
		?>
		<div id="o_stacji" style="margin-left:10px;">
		<h2>O Stacji</h2>
		<img src="" alt="Zdjęcie stacji meteorologicznej" class="clsFL" style="width:180;height:320;"/><div class="clsFL"><?php 
		$myfile = fopen($ostacjiFileName, "r") or die("Unable to open file!: ".$ostacjiFileName);
		echo fread($myfile,filesize($ostacjiFileName));
		fclose($myfile);
		?></div><p class="clsCB"></p>
		
		</div>
		<?php
		echo '<h2 style="margin-left:10px;">LOG:<div class="download"><a href="log.csv" class="download"><i class="icon-download"></i>Pobierz plik</a></div></h2><table id="logId" border="1" cellspacing="0" cellpadding="2"  style="margin-left:10px;">';
		echo '<thead><tr><th  colspan="1"></th><th colspan="4">Powietrze:</th><th colspan="3">Jakość Powietrza:</th><th colspan="2">Wiatr:</th></tr></thead>';
		echo '<thead><tr><th>Data:</th><th>Temperatura(*C):</th><th>Ciśnienie(hPa):</th><th>Wilgotność(%):</th><th>Nasłonecznienie(V):</th><th>PM1.0(μm):</th><th>PM2.5(μm):</th><th>PM10.0(μm):</th><th>Kierunek:</th><th>Prędkość(m/s):</th></tr></thead>';
		$i = count($data)-2;
		while($i <= (count($data)-1) && $i >=1){
			echo '<tr><td>'.$data[$i][0].'</td><td>'.$data[$i][1].'</td><td>'.intval($data[$i][2])*(10).'</td><td>'.$data[$i][3].'</td><td>'.$data[$i][4].'</td><td>'.$data[$i][5].'</td><td>'.$data[$i][6].'</td><td>'.$data[$i][7].'</td><td>'.$data[$i][8].'</td><td>'.$data[$i][9].'</td></tr>';
			$i--;
		}
		echo '</table>';
		?>
		<script>
			function scrollWin(x) {
				window.scrollTo(0, x);
			}
			anychart.onDocumentReady(function () {

				// create a data set on our data
				var dataSet = anychart.data.set(getData());
				// map data for the line chart,
				// take x from the zero column and value from the first column
				var seriesData1 = dataSet.mapAs({ x: 0, value: 1 });
				var seriesData2 = dataSet.mapAs({ x: 0, value: 2 });
				var seriesData3 = dataSet.mapAs({ x: 0, value: 3 });
				var seriesData4 = dataSet.mapAs({ x: 0, value: 4 });
				var seriesData5 = dataSet.mapAs({ x: 0, value: 5 });
				var seriesData6 = dataSet.mapAs({ x: 0, value: 6 });
				var seriesData7 = dataSet.mapAs({ x: 0, value: 7 });
				var seriesData8 = dataSet.mapAs({ x: 0, value: 8 });
				var seriesData9 = dataSet.mapAs({ x: 0, value: 9 });
				// create a line chart
				var chart = anychart.line();
				chart.xGrid().enabled(true);
				chart.yGrid().enabled(true);
				chart.title('Powietrze');// configure the chart title text settings
				// set the y axis title
				chart.yAxis(0).orientation("right").title("Ciśnienie(hPa*10)");
				chart.yAxis(1).orientation("right").title("Temperatura(%)");
				chart.yAxis(2).orientation("left").title("Nasłonecznienie(V)");
				chart.yAxis(3).orientation("left").title("Wilgotność(%)");
				chart.yScale().minimum(-10);//chart.yScale().maximum(110);

				// create a line series with the mapped data
				var lineChart = chart.line(seriesData1).name("Temperatura").stroke('2 #e74c3c');
				var lineChart2 = chart.line(seriesData2).name("Ciśnienie").stroke('2 #3498db');
				var lineChart3 = chart.line(seriesData3).name("Wilgotność").stroke('2 #2ecc71');
				var lineChart4 = chart.line(seriesData4).name("Nasłonecznienie").stroke('2 #f1c40f');
				//var lineChart8 = chart.line(seriesData8).name("kierunek");
				//var lineChart9 = chart.line(seriesData9).name("predkosc");
				
				chart.container('chart').draw().background().fill('#ecf0f1');// set the container id for the line chart and draw the line chart
				
				// create a second line chart
				var chart2 = anychart.line();
				chart2.xGrid().enabled(true);
				chart2.yGrid().enabled(true);
				chart2.yScale().minimum(0);
				chart2.title('Pyły zawieszone');
				chart2.yAxis(0).orientation("left").title("PM(μm/m^3)");
				chart2.line(seriesData5).name("PM 1.0");
				chart2.line(seriesData6).name("PM 2.5");
				chart2.line(seriesData7).name("PM 10");
				// set the container id for the line chart and draw the line chart
				chart2.container('chart2').draw().background().fill('#ecf0f1');
			});
			function getData() {
				console.log(<?php echo json_encode(array_slice($data, count($data)-25, count($data))); ?>);
				return <?php echo json_encode(array_slice($data, count($data)-25, count($data))); ?>;
			}
			</script>
		
    </body>
