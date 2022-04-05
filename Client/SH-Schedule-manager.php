<?php
include_once("SH-Schedule-manager_scripts.php");
$connection = openConnection("testDatabase"); //create connection to database
// echo "Connection successfully opened" . "<br>";
date_default_timezone_set("Europe/Budapest"); //set time zone to Budapest
//echo "The time is " . date("h:i:sa"); //print out the current time
?>

<!DOCTYPE html>

<html lang="hu">

<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<meta http-equiv="X-UA-Compatible" content="ie=edge" />
<link rel="stylesheet" href="./style.css"> <!-- external css -->
<title>Suli-Host ráéréskezelő</title>

<script>
function loadAndShowWorkerRequests(workerId) {
	year = document.getElementById("year").value;
	week = document.getElementById("week").value;
	console.log("workerId:", workerId);
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function () {
		if (this.readyState == 4 && this.status == 200 ) {
			// var workerRequestGrid = document.getElementById("workerRequestGrid");
			// console.log(workerRequestGrid);
			var responseText = this.responseText; //[ [0,0,0,0,0,0,0], [0,0,0,0,0,0,0], [0,0,0,0,0,0,0] ]
			console.log(responseText);
			var result = JSON.parse(responseText);
			
			var table = document.getElementById("raeresek"); //get table
			var delta_i = 2; //first two rows of table contain dates and day names
			for (let i = 0; i < 0 + 3; i++) { //3 shifts -> should work based on shiftIds
				var row = table.rows.item(i + delta_i).cells; //get row
				console.log(i);
				for (let j = 0; j < 7; j++) { //7 days
					var checkbox = row.item(j + 1).firstChild; //get checkbox
					if (result[i][j] == 1) { checkbox.checked = true; }
					else { checkbox.checked = false; }
				}
			}
		}
	};
	xmlhttp.open("GET", "loadWorkerRequests.php?workerId=" + workerId + "&year=" + year + "&week=" + week, true);
	xmlhttp.send();
}

/*function resetWorkerRequests() {
	console.log("reset");
	//why does this work?
	var workerNameSelect = document.getElementById("workerName");
	workerNameSelect.value = "";
}*/

function showPassword() {
	var x = document.getElementById("password");
	if (x.type === "password") {
		x.type = "text";
	}
	else {
		x.type = "password";
	}
}

function showPopup() {
     document.getElementById("passwordPopup").style.display = "block";
}
</script>
</head>

<body>
<div id="mainContent">
	<h1>Suli-Host ráéréskezelő</h1>
	
	<h2>Ráérés leadása</h2>
	
	<?php
	mysqli_set_charset ($connection, "utf8"); //in order to correctly display special (Hungarian) characters
	?>
	
	<form id="inputForm" action="" method="post"> <!-- form for workerNameInput dropdown menu -->
		<label for="year"><b>Év</b></label>
		<input type="number" id="year" name="year" readonly value=<?php echo date("Y"); ?>> <!-- current year, but at the last week of the year it should be next year -->
		
		<label for="week"><b>Hét</b></label>
		<input type="number" id="week" name="week" readonly value=<?php echo (idate("W") + 1); ?>><br> <!-- always for the next week, weeks starting on Monday -->
	
		<label for="workerName"><b>Add meg a neved</b></label>
		<?php
		echo "<select id='workerName' name='workerName' onchange = 'loadAndShowWorkerRequests(this.value);'>";
		echo "<option value = '' disabled selected></option>";
		$sqlQuery = "SELECT workerId, workerName FROM workers";
		$result = $connection -> query($sqlQuery);
		$row = $result -> fetch_assoc();
		while ($row != null){
			$workerId = $row['workerId'];
			$workerName = $row['workerName'];
			echo "<option value=$workerId>$workerName</option>";
			$row = $result -> fetch_assoc();
		}
		echo "</select><br>";
		?>
		
		<?php
		$year = date("Y");
		$week = (idate("W") + 1);
		//create tables in order to avoid false queries, not sure if necessary
		//if necessary, the serves should do this automatically every week
		$sqlQuery = "CREATE TABLE IF NOT EXISTS workerRequests" . $year . "_" . $week . " (workerId INT, dayId INT, shiftId INT, UNIQUE(workerId, dayId, shiftId))";
		$connection -> query($sqlQuery) or die($connection -> error);
		$sqlQuery = "CREATE TABLE IF NOT EXISTS schedule_" . $year . "_" . $week . " (workerId INT, dayId INT, shiftId INT, UNIQUE(workerId, dayId, shiftId))";
		$connection -> query($sqlQuery) or die($connection -> error);
		?>
		
		<label for="raeresek"><b>Add meg, a héten mikor érsz rá</b><br></label>
		<?php
		$dayNames = array('Hétfő', 'Kedd', 'Szerda', 'Csütörtök', 'Péntek', 'Szombat', 'Vasárnap');
		// $shifts = array('8/5:40', '8/8:40', '8/10:40'); //the code below does this
		$shifts = array();
		$sqlQuery = "SELECT shiftName, shiftId FROM shifts WHERE isActive = 1 ORDER BY shiftId";
		$result = $connection -> query($sqlQuery) or die($connection -> error);
		$row = $result -> fetch_assoc();
		while ($row != null){
			$shifts[$row['shiftId']] = $row['shiftName'];
			$row = $result -> fetch_assoc();
		}
		
		$dom = new DOMDocument('1.0'); //create new document with specified version number
		
		$table = $dom -> createElement('table'); //create table
		$domAttribute = $dom -> createAttribute('id');
		$domAttribute -> value = 'raeresek';
		$table -> appendChild($domAttribute);
		
		
		//dates
		$tr = $dom -> createElement('tr'); //create a row
		$table -> appendChild($tr);
		$td = $dom -> createElement('td'); //create an (empty) element in the row
		$tr -> appendChild($td);
		$date = new DateTime();
		$date -> setISODate($year, $week, 0); //year, week number, first day of the week (0 is Sunday)
		for ($j = 0; $j < 7; $j++) { //7 days a week, create day name labels
			$date -> add(new DateInterval('P1D')); // P1D means a period of 1 day
			// echo $date -> format('m.d.'); //prints  month, day
			$td = $dom -> createElement('td', $date -> format('m.d.') );
			$tr -> appendChild($td);
		}
		//day names
		$tr = $dom -> createElement('tr'); //create a row
		$table -> appendChild($tr);
		$td = $dom -> createElement('td'); //create an (empty) element in the row
		$tr -> appendChild($td);
		for ($j = 0; $j < 7; $j++) { //7 days a week, create day name labels
			$td = $dom -> createElement('td', mb_substr($dayNames[$j], 0, 2) );
			$tr -> appendChild($td);
		}
		$workerRequestGrid = array(); //array for storing worker requests
		foreach (array_keys($shifts) as $i) { //n shifts a day
			$workerRequestGrid[$i] = array(); //add an array for the shift
			for ($j = 0; $j < 7; $j++) { //7 days a week
				$workerRequestGrid[$i][$j] = 0; //add value for the day
			}
		}
		// print_r($workerRequestGrid);
		
		//get empty spots
		$emptyRequestGrid = array();
		foreach (array_keys($shifts) as $shiftId) { //n shifts a day
			$companyRequestGrid[$shiftId] = array();
			for ($dayId = 0; $dayId < 7; $dayId++) {
				//get company requests
				$sqlQuery = "SELECT workerNumber FROM companyRequests WHERE dayId = " . $dayId . " AND shiftId = " . $shiftId;
				$result = $connection -> query($sqlQuery) or die($connection -> error);
				$row = $result -> fetch_assoc();
				$companyRequested = $row["workerNumber"];
				//get already requested
				$sqlQuery = "SELECT COUNT(workerId) FROM workerRequests_" . $year . "_" . $week . " WHERE dayId = " . $dayId . " AND shiftId = " . $shiftId;
				$result = $connection -> query($sqlQuery) or die($connection -> error);
				$row = $result -> fetch_assoc();
				$alreadyRequested = $row['COUNT(workerId)'];
				// print_r($row);
				$emptyRequestGrid[$shiftId][$dayId] = $companyRequested - $alreadyRequested;
			}
		}
		// print_r($emptyRequestGrid);
		
		// for ($i = 0; $i < count($shifts); $i++) { //n shifts a day
		foreach (array_keys($shifts) as $i) { //n shifts a day
			$tr = $dom -> createElement('tr'); //create a row
			$table -> appendChild($tr);
			$td = $dom -> createElement('td', $shifts[$i]); //create shift name element
			$tr -> appendChild($td);
			for ($j = 0; $j < 7; $j++) { //7 days a week
				$td = $dom -> createElement('td'); //create an element in the row
				$tr -> appendChild($td);
				//chechbox
				$checkbox = $dom -> createElement('input'); //create a checkbox in the element
				$domAttribute = $dom -> createAttribute('type');
				$domAttribute -> value = 'checkbox';
				$checkbox -> appendChild($domAttribute);
				$domAttribute = $dom -> createAttribute('name');
				$domAttribute -> value = 'chkb[]'; //$checkbox = $_POST['chkb'];
				$checkbox -> appendChild($domAttribute);
				$domAttribute = $dom -> createAttribute('value');
				$domAttribute -> value = $i . $j; //works only for ids with one digit
				$checkbox -> appendChild($domAttribute);
				if ($emptyRequestGrid[$i][$j] == 0) {
					$domAttribute = $dom -> createAttribute('disabled');
					$domAttribute -> value = 'disabled';
					$checkbox -> appendChild($domAttribute);
				}
				$td -> appendChild($checkbox);
				//number
				$number = $dom -> createElement('p', '  ' . $emptyRequestGrid[$i][$j]); //create a paragraph in the element
				$domAttribute = $dom -> createAttribute('value');
				$domAttribute -> value = 0;
				$number -> appendChild($domAttribute);
				$td -> appendChild($number);
			}
		}
		
		$dom -> appendChild($table);
		
		echo $dom->saveHTML(); //outputs the generated source code
		?>
		
		<label for="password"><b>Jelszó:</b></label>
		<input type="password" id="password" name="password" placeholder="********" size="10">
		<input type="checkbox" id="showPasswordCB" onclick="showPassword()" title="Jelszó mutatása"><br>
		<input type="submit" id="submitForm" value="Ráérés leadása" name="submitRaeres">
		<button type="reset" id="reset">Visszaállít</button> <!-- resets all of the inputs in the form to their initial values -->
	</form>
<div>
</body>

<?php
if (isset($_POST['submitRaeres'])) { //if the submit button above is pressed
	// echo '<br>';
	if (!empty($_POST['workerName'])) { //if name is selected
		// echo 'dayId' . ', ' . 'shiftId' . '<br>';
		$workerId = $_POST['workerName']; //option value in workerName select is workerId
		$year = $_POST['year'];
		$week = $_POST['week'];
		if (!empty($_POST['password'])) { //if password is set
			$password = $_POST['password'];
			//password validation
			if (checkPassword($workerId, $password) == 1) { //if password is correct
				if (!empty($_POST['chkb'])) { //if any checkbox is checked
					$checkbox = $_POST['chkb']; //$domAttribute -> value = 'chkb[]';
					foreach ($checkbox as $chkb) { //set the selected grid positions to 1
						$i = $chkb[0]; //shiftId, works only for ids with one digit
						$j = $chkb[1]; //dayId
						$workerRequestGrid[$i][$j] = 1;
						// echo $j . ', ' . $i . '<br>';
					} //endforeach
				} //endif
				// echo "year, week: " . $year . ", " . $week . "<br>";
				saveWorkerRequest($workerId, $year, $week, $workerRequestGrid);
			} //endif
			else { //if password is not correct
				echo "<script>alert('Helytelen jelszó.');</script>";
				//reset password field
			}
		} //endif
		else { //if password is not set
			echo "<script>alert('Írd be a jelszavad.');</script>";
		}
	}
	else { //if name is not selected
		// echo 'empty';
	}
}
?>