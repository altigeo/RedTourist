<?php
	ob_start();
?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
	<head>
		<title>RedTourist v0.4 - Κόκκινη Σαγκριά</title>
	</head>

	<body>
		<form action='results.php' method='post' >
			Γεωγραφικό πλάτος: <input type='text' name='latitude' value='0' ><br>
			Γεωγραφικό μήκος: <input type='text' name='longitude' value='0' ><br>
			Τύπος μνημείου: <select name='type' >
				<option value='all' selected >Όλα</option>
				<option value='statues' >Αγάλματα / Γλυπτά</option>
				<option value='heroons' >Ηρώα</option>
				<option value='military cemeteries'>Στρατιωτικα Νεκροταφεια</option>
				<option value='museums' >Μουσεία</option>
			</select><br>
			<input type='submit' value='Αναζήτηση' >
		</form>
	</body>
</html>


<?php
	ob_flush();
?>
