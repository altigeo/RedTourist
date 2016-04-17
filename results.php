<?php
	ob_start();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
	<head>
		<meta http-equiv='content-type' content='text/html; charset=utf-8' />
		<title>RedTourist v1.0 - Κόκκινη Σαγκριά</title>
<?php


	if (isset($_GET['latitude']) && isset($_GET['longitude']) && isset($_GET['type']) && isset($_GET['amount']))
	{
		$user_latitude = $_GET["latitude"];
		$user_longitude = $_GET["longitude"];
		$user_type = $_GET["type"];
		$user_amount = $_GET["amount"];
	} else {
		die("Λανθασμένη μεταφορά δεδομένων!");
		ob_flush();
	}

	/* Σύνδεση με τη βάση δεδομένων */
	include('dbConnection.php');

	// http://sforsuresh.in/finding-nearest-location-using-latitude-longitude/
	$query = "SELECT * FROM monuments";
	if ('all' != $user_type)	$query = $query . " WHERE type='$user_type' ";

	$result = mysqli_query($conn, $query);
	if ($result)
	{
		$markersArray = array();
		$count = mysqli_num_rows($result);
		if (0 < $count)
		{
			//$min_distance = 40100; //The circumference of the earth at the equator is 24,901.55 miles (40,075.16 kilometers).
			while ($row = mysqli_fetch_array($result))
			{
				$distance = getDistanceBetweenPointsNew($user_latitude, $user_longitude, $row['latitude'], $row['longitude'], 'Km');
				array_push($markersArray, array($row['name'], $row['latitude'], $row['longitude'], $distance, $row['source']));
			}

			usort($markersArray, function($a, $b) {
				return $a[3] - $b[3];
			});

			if ('all' != $user_type) {
				echo "Το πιο κοντινό μνημείο σε εσάς είναι το μνημείο '". $markersArray[0][0] ."' στα ". round($markersArray[0][3], 4) ." km. <br>";
			} else {
				if (1 == $count) {
					echo "Το πιο κοντινό μνημείο σε εσάς είναι το μνημείο '". $markersArray[0][0] ."' στα ". round($markersArray[0][3], 4) ." km. <br>";
				}
				else {
					$index = 1;
					foreach ($markersArray as &$marker) {
						echo "Το ". $index ."o κοντινότερο μνημείο σε εσάς είναι το μνημείο '$marker[0]' στα ". round($marker[3], 4) . " km. <br>";
						if ($user_amount == $index) break;
						$index++;
					}
				}
			}
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


	//https://www.marketingtechblog.com/calculate-distance/
	function getDistanceBetweenPointsNew($latitude1, $longitude1, $latitude2, $longitude2, $unit) {
		$theta = $longitude1 - $longitude2;
		$distance = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
		$distance = acos($distance);
		$distance = rad2deg($distance);
		$distance = $distance * 60 * 1.1515; switch($unit) {
			case 'Mi': break; case 'Km' : $distance = $distance * 1.609344;
		}
		return (round($distance,2));
	}
?>


		<!-- http://sforsuresh.in/display-google-map-locations-using-latitude-longitude/ -->
		<script src='http://maps.googleapis.com/maps/api/js?libraries=geometry' type='text/javascript'></script>
		<script type='text/javascript'>
			var markers = [
			<?php
				echo "{'title':'Εσείς','lat':'$user_latitude','lng':'$user_longitude','description':'Εσείς'}";

				$index = 1;
				foreach ($markersArray as &$marker) {
					echo ",{'title':'$marker[0]','lat':'$marker[1]','lng':'$marker[2]','description':'$marker[0]'}";
					if ($user_amount == $index) break;
					$index++;
				}
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

				// https://developers.google.com/maps/documentation/javascript/examples/polyline-simple
				<?php
					$index = 1;
					foreach ($markersArray as &$marker) {
						echo "var line = new google.maps.Polyline({
							path: [
								new google.maps.LatLng($user_latitude, $user_longitude),
								new google.maps.LatLng(". $marker[1] .", ". $marker[2] .")
							],
							strokeColor: '#FF0',
							strokeOpacity: 1.0,
							strokeWeight: 3,
							geodesic: true,
							map: map
						});";
						if ($user_amount <= $index) break;
						$index++;
					}
				?>

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

		<div id='dvMap' style='width:500px; height:500px;'></div>

		<?php
			$index = 1;
			foreach ($markersArray as &$marker) {
				if ($marker[4]) {
					echo $marker[0] ."<br>";
					echo "<img src='". $marker[4] ."' style='width:100%; height:auto;' >";
					echo "<br>";
				}
				if ($user_amount == $index) break;
				$index++;
			}
		?>
	</body>
</html>


<?php
	ob_flush();
?>
