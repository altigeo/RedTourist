<?php
	ob_start();

	if (isset($_POST['latitude']) && isset($_POST['longitude']) && isset($_POST['type']))
	{
		$user_latitude = $_POST["latitude"];
		$user_longitude = $_POST["longitude"];
		$user_type = $_POST["type"];
	} else {
		die("Λανθασμένη μεταφορά δεδομένων!");
		ob_flush();
	}


	/* Σύνδεση με τη βάση δεδομένων */
	include('dbConnection.php');

	if ('all' == $user_type) {
		$query = "SELECT * FROM monuments;";
	} else {
		$query = "SELECT *
			FROM monuments
			WHERE type='$user_type';";
	}

	$result = mysqli_query($conn, $query);
	if ($result)
	{
		$count = mysqli_num_rows($result);
		if (0 < $count)
		{
			$min = 41000; //The circumference of the earth at the equator is 24,901.55 miles (40,075.16 kilometers).
			$name = "";
			while ($row = mysqli_fetch_array($result))
			{
				$temp = getDistanceBetweenPointsNew($user_latitude, $user_longitude, $row['latitude'], $row['longitude'], 'Km');
				if ($temp < $min)
				{
					$min = $temp;
					$name = $row['name'];
				}
			}
			echo "Το πιο κοντινό μνημείο σε εσάς είναι το μνημείο '$name'";
		}
		else echo "Δεν υπάρχει κανένα καταχωρημένο μνημείο!";

		mysqli_free_result($result);
	}
	else
	{
		echo "Error";
	}

	/* Αποσύνδεση από τη βάση δεδομένων */
	include('dbDisConnection.php');



	function getDistanceBetweenPointsNew($latitude1, $longitude1, $latitude2, $longitude2, $unit)
	{
		$theta = $longitude1 - $longitude2;
		$distance = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))
				+ (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta))));
		$distance = acos($distance); $distance = rad2deg($distance); 
		$distance = $distance * 60 * 1.1515;

		switch($unit) 
		{ 
			case 'Mi': break;
			case 'Km' : $distance = $distance * 1.609344; 
		} 
		return (round($distance,2)); 
	}


	ob_flush();
?>
