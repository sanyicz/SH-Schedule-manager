<?php

//get variables
$workerId = $_REQUEST["workerId"];
$year = $_REQUEST["year"];
$week = $_REQUEST["week"];

//establish connection
$host = "localhost";
$username = "root";
$password = "";
$database = "test1";
$connection = new mysqli($host, $username, $password, $database) or die("Connection failed: %s\n" . $connection -> error);

//perform request
$shifts = array();
$sqlQuery = "SELECT shiftName, shiftId FROM shifts WHERE isActive = 1 ORDER BY shiftId";
$result = $connection -> query($sqlQuery) or die($connection -> error);
$row = $result -> fetch_assoc();
while ($row != null){
	$shifts[$row['shiftId']] = $row['shiftName'];
	$row = $result -> fetch_assoc();
}
$workerRequestGrid = array(); //array for storing worker requests
foreach (array_keys($shifts) as $i) { //n shifts a day
	$workerRequestGrid[$i] = array(); //add an array for the shift
	for ($j = 0; $j < 7; $j++) { //7 days a week
		$workerRequestGrid[$i][$j] = 0; //add value for the day
	}
}
for ($dayId = 0; $dayId < 7; $dayId++) { //7 days
	foreach (array_keys($workerRequestGrid) as $shiftId) { //n shifts a day
		$sqlQuery = "SELECT 1 FROM workerRequests_" . $year . "_" . $week . " WHERE workerId = " . $workerId . " AND dayId = " . $dayId . " AND shiftId = " . $shiftId;
		$result = $connection -> query($sqlQuery)  or die($connection -> error);
		$row = $result -> fetch_assoc();
		if ($row != null) {
			$one = 1;
		}
		else {
			$one = 0;
		}
		$workerRequestGrid[$shiftId][$dayId] = $one;
	}
}

//send data
echo json_encode($workerRequestGrid);

?>