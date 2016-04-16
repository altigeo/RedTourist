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
			$name = "";
			$name = "";
			while ($row = mysqli_fetch_array($result))
			{
				$temp = getDistanceBetweenPointsNew($user_latitude, $user_longitude, $row['latitude'], $row['longitude'], 'Km');
				if ($temp < $min)
				{
					$min = $temp;
					$result_name = $row['name'];
					$result_latitude = $row['latitude'];
					$result_longitude = $row['longitude'];
				}
			}
			echo "Το πιο κοντινό μνημείο σε εσάς είναι το μνημείο '$result_name'";
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


	// http://stackoverflow.com/questions/20152492/calculate-distance-php-and-add-to-json
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
?>





<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
	<head>
		<title>RedTourist - Κόκκινη Σαγκριά</title>

		<!-- http://sforsuresh.in/display-google-map-locations-using-latitude-longitude/ -->
		<script src='http://maps.googleapis.com/maps/api/js?sensor=false' type='text/javascript'></script>
		<script type='text/javascript'>
			var markers = [
			<?php
				echo "{'title':'Εσείς','lat':'$user_latitude','lng':'$user_longitude','description':'Εσείς'},
				{'title':'$result_name','lat':'$result_latitude','lng':'$result_longitude','description':'$result_name'}"
			?>
			];

			window.onload = function () {
				var mapOptions = {
					center: new google.maps.LatLng(markers[0].lat, markers[0].lng),
					zoom: 10,
					mapTypeId: google.maps.MapTypeId.ROADMAP
				};
				var map = new google.maps.Map(document.getElementById('dvMap'), mapOptions);

				var infoWindow = new google.maps.InfoWindow();
				var lat_lng = new Array();
				var latlngbounds = new google.maps.LatLngBounds();
				for (i = 0; i < markers.length; i++) {
					var data = markers[i]
					var myLatlng = new google.maps.LatLng(data.lat, data.lng); lat_lng.push(myLatlng);
					var marker = new google.maps.Marker({
					position: myLatlng,
					map: map,
					title: data.title
				});
				latlngbounds.extend(marker.position);
				(function (marker, data) {
					google.maps.event.addListener(marker, 'click', function (e) {
						infoWindow.setContent(data.description);
						infoWindow.open(map, marker);
					});
					})(marker, data);
				}

				map.setCenter(latlngbounds.getCenter());
				map.fitBounds(latlngbounds);
			}
		</script>
	</head>
	

	<body>

		<div id='dvMap' style='width: 500px; height: 500px;'></div>

	</body>
</html>






<?php

	ob_flush();
?>
