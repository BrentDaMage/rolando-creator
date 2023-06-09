<?php
$DEBUG = false;

if($DEBUG) {
	// Turn on error reporting
	error_reporting(E_ALL);
	ini_set("display_errors", 7);

	//FirePHP include
	require_once('FirePHPCore/fb.php');
	ob_start(); // Enable output_buffering for FirePHP
}
?>