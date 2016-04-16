<?php
	$dbHost	= "localhost";
	$dbUser	= "root";
	$dbPass	= "";
	$dbName = "redStore";

	$conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName) or die ('Πρόβλημα επικοινωνίας με τη mysql');
	mysqli_select_db($conn, $dbName);
	mysqli_query($conn, 'set character set utf8');
	mysqli_query($conn, "SET NAMES 'utf8'");
?>
