<?php
	ob_start();
?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
	<head>
		<title>Κόκκινη Σαγκριά - 0.1</title>
	</head>

	<body>
		<form action='results.php' method='post' >
			Latitude: <input type='text' name='latitude' value='0' ><br>
			Longitude: <input type='text' name='longitude' value='0' ><br>
			<input type='submit' value='Αναζήτηση' >
		</form>
	</body>
</html>


<?php
	ob_flush();
?>
