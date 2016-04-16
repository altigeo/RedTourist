<?php
	ob_start();

	if (isset($_POST['latitude']) && isset($_POST['longitude']) && isset($_POST['type']) && isset($_POST['amount']))
	{
		$user_latitude = $_POST["latitude"];
		$user_longitude = $_POST["longitude"];
		$user_type = $_POST["type"];
		$user_amount = $_POST["amount"];
	} else {
		die("Λανθασμένη μεταφορά δεδομένων!");
		ob_flush();
	}


	/* Σύνδεση με τη βάση δεδομένων */
	include('dbConnection.php');

	$query = "SELECT (((3959 * acos( cos( radians($user_longitude) ) * cos( radians( latitude ) )
	* cos( radians( longitude ) - radians($user_latitude) ) + sin( radians($user_longitude) )
	* sin( radians( latitude ) ) ) )* 1.609344)) AS distance, name, latitude, longitude FROM monuments";
	if ('all' != $user_type)	$query = $query . " WHERE type='$user_type' ";
	$query = $query . " ORDER BY distance LIMIT 0 , $user_amount;";


	$result = mysqli_query($conn, $query);
	if ($result)
	{
		$count = mysqli_num_rows($result);
		if (0 < $count)
		{
			$index = 1;
			$markersArray = array();
			while ($row = mysqli_fetch_array($result))
			{
				$result_distance = $row['distance'];
				$result_name = $row['name'];
				$result_latitude = $row['latitude'];
				$result_longitude = $row['longitude'];

				array_push($markersArray, [$row['name'], $row['latitude'], $row['longitude']]);

				if (1 == $user_amount)	echo "Το πιο κοντινό μνημείο σε εσάς είναι το μνημείο '$result_name' στα ". round($result_distance,2) . " km <br>";
				else					echo "Το ". $index ."o κοντινό μνημείο σε εσάς είναι το μνημείο '$result_name' στα ". round($result_distance,2) . " km <br>";
				$index++;
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
?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
	<head>
		<title>RedTourist - Κόκκινη Σαγκριά</title>

		<!-- http://sforsuresh.in/display-google-map-locations-using-latitude-longitude/ -->
		<script src='http://maps.googleapis.com/maps/api/js?libraries=geometry' type='text/javascript'></script>
		<script type='text/javascript'>
			var markers = [
			<?php
				echo "{'title':'Εσείς','lat':'$user_latitude','lng':'$user_longitude','description':'Εσείς'}";

				foreach ($markersArray as &$marker) {
					echo ",{'title':'$marker[0]','lat':'$marker[1]','lng':'$marker[2]','description':'$marker[0]'}";
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

				<?php
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

		<div id='dvMap' style='width: 500px; height: 500px;'></div>

	</body>
</html>






<?php
	ob_flush();
?>
