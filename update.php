<DOCTYPE HTML!>
    <head>
        <html lang="pl" />
        <meta charset="utf-8" />
    </head>
    <body>
		<?php
			// US - Uplading Succesful
			// EU - Error Uplading
			$date = strtoupper(date("Y/m/d")." ".date("ha"));
			$LogFileName = 'log.csv';
			$veriTokenActual = hash('sha256',$date);
			echo '<p id="date">'.$date.'</p>';
			echo " || ";
			echo $veriTokenActual;
			// getting values and Guarding against XSS
			$veriTokenTaken = htmlentities($_GET['veri']);
			$temp = htmlentities($_GET['temp']);
			$press = htmlentities($_GET['press']);
			$humd = htmlentities($_GET['humd']);
			$inso = htmlentities($_GET['inso']);
			$pm1 = htmlentities($_GET['pm1']);
			$pm25 = htmlentities($_GET['pm25']);
			$pm10 = htmlentities($_GET['pm10']);
			$dire = htmlentities($_GET['dire']);
			$speed = htmlentities($_GET['speed']);
			
			$data = [
				// Wygląd zapisu danych: ['Date', 'Temp:', 'Humd:', 'Press:', 'Inso:', 'PM1:', 'PM25:', 'PM10:','Dire:', 'Speed:'],
				[$date, $temp, $press, $humd, $inso, $pm1, $pm25, $pm10, $dire, $speed],
			];
			echo '<div id="upload">';
			if($veriTokenTaken == $veriTokenActual){
				// open csv file for writing
				$LogFile = fopen($LogFileName, 'a');

				if ($LogFile === false) {
					die('Error opening the file ' . $LogFileName);
				}
			
				// write each row at a time to a file
				foreach ($data as $row) {
					fputcsv($LogFile, $row);
				}

				// close the file
				fclose($LogFile);
				
				echo 'US';
				
			}else{
				echo 'EU';
			}
			echo '</div>';
		?>
		<p style="color:white; id="date"></p>
		
    </body>
