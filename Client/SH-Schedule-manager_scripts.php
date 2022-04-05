<?php

function onChangeTest() {
	echo "<script>alert('called on change');</script>";
}

function openConnection($database) {
	$host = "localhost";
	$username = "root";
	$password = "";
	$connection = new mysqli($host, $username, $password, $database) or die("Connection failed: %s\n". $connection -> error);
	return $connection;
}

function closeConnection($connection) {
	$connection -> close();
}

function saveWorkerRequest($workerId, $year, $week, $workerRequestGrid) {
	global $connection;
	// print_r($workerRequestGrid);
	
	$sqlQuery = "CREATE TABLE IF NOT EXISTS workerRequests_" . $year . "_" . $week . " (workerId INT, dayId INT, shiftId INT, UNIQUE(workerId, dayId, shiftId))";
	$connection -> query($sqlQuery) or die($connection -> error);
	/*if ($connection -> query($sqlQuery) === TRUE) {
		echo "Table already exists or created successfully" . "<br>";
	}
	else {
		echo "Error creating table" . "<br>";
	}*/
	
	for ($j = 0; $j < 7; $j++) { //7 days
		foreach (array_keys($workerRequestGrid) as $i) { //n shifts a day
			if ($workerRequestGrid[$i][$j] == 1) {
				// echo $j . ', ' . $i . '<br>';
				$statement = $connection -> prepare("INSERT INTO workerRequests_" . $year . "_" . $week . " (workerId, dayId, shiftId) VALUES (?, ?, ?)");
			}
			else { //if ($workerRequestGrid[$i][$j] == 0)
				$statement = $connection -> prepare("DELETE FROM workerRequests_" . $year . "_" . $week . " WHERE workerId = ? AND dayId = ? AND shiftId = ?");
			}
			$statement -> bind_param("iii", $workerId, $j, $i);
			$statement -> execute();
		}
	}
	
	echo "<script>alert('Ráérés sikeresen leadva.');</script>";
}

function checkPassword($workerId, $password) {
	global $connection;
	
	$sqlQuery = "SELECT password FROM workers WHERE workerId = '$workerId'";
	$result = $connection -> query($sqlQuery) or die($connection -> error);
	$row = $result -> fetch_assoc();
	$correctPassword = $row['password'];
	/*$options = [
		'cost' => 10,
		'rounds' => 10,
	];
	$hashed = password_hash($password, PASSWORD_BCRYPT, $options);*/
	if (password_verify($password, $correctPassword) == true) {
		return 1;
	}
	else {
		return 0;
	}
}

?>