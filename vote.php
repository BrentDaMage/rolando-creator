<?php
include "includes/db.php";
include "includes/debug.php";
include "includes/utils.php";

$data = array();

if(!isset($_POST['thumb']) || !(isset($_POST['id']))) {
	$data['status'] = "error";
	$data['error'] = "Incorrect parameters.";
} else if(!ereg("[0-1]",$_POST['thumb']) || !ereg("[a-zA-Z1-9]",$_POST['id'])) {
	$data['status'] = "error";
	$data['error'] = "Incorrect data format";
} else {
	$thumb = $_POST['thumb'];
	$token = $_POST['id'];
	
	$SQL = "SELECT token, votes FROM rolando WHERE token='$token' LIMIT 1";
	$rolando = mysql_fetch_assoc(mysql_query($SQL,$conn));
	
	if(count($rolando) > 0) {
		// CHECK THAT WE HAVENT ALREADY VOTED
		if(!isset($_COOKIE[$token])) {
			$votes = $rolando['votes'];
			($thumb == 1) ? $votes++ : $votes--;
			$SQL = "UPDATE rolando SET votes='$votes' where token='$token'";
			$set_vote = mysql_query($SQL, $conn);
			$data['status'] = "success";
			$data['votes'] = $votes;
			// SET USER COOKIE FOR RI
			setcookie($token, $thumb, time()+60*60*24*365*10, '/');
		} else {
			$data['status'] = "duplicate";
			$data['error'] = "Already voted for this rolando";
		}
	} else {
		$data['status'] = "error";
		$data['error'] = "Error - No Rolando $token";
	}
}

// RETURN JSON
echo array2json($data);

?>