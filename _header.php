<?php
session_start();

include('includes/debug.php');
include('includes/db.php');
include("includes/json.php");

if(basename($_SERVER['SCRIPT_FILENAME']) == "index.php" || isset($_GET['new'])) {
	session_unset();
}

if(! isset($_SESSION['token'])) {
	$_SESSION['token'] = md5(uniqid(rand(), TRUE));
}

// Get current Rolando Step
if(isset($_POST['step'])) {
	$_SESSION['step'] = $_POST['step'];
	if($_POST['step'] == "step-1") {
		// SET THE BODY PARTS FOR THE USERS SESSION
		foreach( $_POST as $k => $v) {
			$_SESSION[$k]=cleanup($v,false);
		}
	}
}

// Get rolando token from url and populate session for the data.
if(isset($_GET['id'])) {
	if(preg_match('#^[a-f0-9]{32}$#i', $_GET['id'])) {
		$_SESSION['visitor'] = true;
		$SQL = "SELECT token, name, description, bitly, votes from rolando where token='".$_GET['id']."'";
		$result = mysql_query($SQL,$conn);
		$rolando = mysql_fetch_assoc($result);
		if(!$rolando) {
			$error="No Rolando here!";
		} else {
			$_SESSION['rolando_name'] = $rolando['name'];
			$_SESSION['rolando_description'] = $rolando['description'];
			$_SESSION['token'] = $rolando['token'];
			$_SESSION['bitly'] = $rolando['bitly'];
		}
	} else {
		$error="No Rolando here!";
	}
}

if(isset($_POST['rolando_name']) && isset($_POST['rolando_description'])) {
	// SANITIZE INPUT
	$_SESSION['rolando_name'] = $_POST['rolando_name'];
	$_SESSION['rolando_description'] = $_POST['rolando_description'];
	$_SESSION['rolando_why_you_roll'] = $_POST['rolando_why_you_roll'];
	if($_POST['rolando_gamername'] == 'Plus+ Gamername') $_POST['rolando_gamername'] = "";
	$_SESSION['rolando_gamername'] = $_POST['rolando_gamername'];

	// ADD NEW SESSION VALUES
	if(isset($_SESSION['created']) && $_SESSION['created']==true) {
		// UPDATE ROLANDO IN DB
		$SQL = "UPDATE rolando set
			name = '".safe($_SESSION['rolando_name'])."',
			description = '".safe($_SESSION['rolando_description'])."',
			why_you_roll = '".safe($_SESSION['rolando_why_you_roll'])."',
			email = '".safe($_SESSION['rolando_email'])."',
			body = '".$_SESSION['body']."',
			head = '".$_SESSION['head']."',
			pants = '".$_SESSION['pants']."',
			eyes = '".$_SESSION['eyes']."',
			eyebrows = '".$_SESSION['eyebrows']."',
			mouth = '".$_SESSION['mouth']."',
			freckles = '".$_SESSION['freckles']."',
			moustache = '".$_SESSION['moustache']."',
			gamername = '".safe($_SESSION['rolando_gamername'])."',
			background = '".$_SESSION['bg']."' where token='".$_SESSION['token']."'";
			mysql_query($SQL,$conn) or die(mysql_error());		
	}
	else
	{ 	
		// SET BITLY LINK
		if(!isset($_SESSION['bitly'])) {
			setBitly();
		}

		//INSERT ROLANDO INTO DB
		$SQL = "INSERT INTO rolando(
			name, 
			description,
			why_you_roll,
			email,
			token,
			body,
			head,
			pants,
			eyes,
			eyebrows,
			mouth,
			freckles,
			moustache,
			background,
			gamername,
			bitly )
		VALUES(
			'".safe($_SESSION['rolando_name'])."',
			'".safe($_SESSION['rolando_description'])."',
			'".safe($_SESSION['rolando_why_you_roll'])."',
			'".safe($_SESSION['rolando_email'])."',
			'".$_SESSION['token']."',
			'".$_SESSION['body']."',
			'".$_SESSION['head']."',
			'".$_SESSION['pants']."',
			'".$_SESSION['eyes']."',
			'".$_SESSION['eyebrows']."',
			'".$_SESSION['mouth']."',
			'".$_SESSION['freckles']."',
			'".$_SESSION['moustache']."',
			'".$_SESSION['bg']."',
			'".safe($_SESSION['rolando_gamername'])."',
			'".$_SESSION['bitly']."'			
			)";
		$_SESSION['created'] = mysql_query($SQL,$conn) or die(mysql_error());
	}
}

function setBitly(){  
	//BITLY ACCOUNT INFORMATION
	$login = "bawigga";
	$api_key = "R_b7e2418b9723f349f398cf6eec2d38fe";
	
	$longurl="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."?id=".$_SESSION['token'];
	$encoded_url = urlencode($longurl);
	$url = "http://api.bit.ly/shorten?version=2.0.1&longUrl=$encoded_url&login=$login&apiKey=$api_key";  
	$s = curl_init();  
	curl_setopt($s,CURLOPT_URL, $url);  
	curl_setopt($s,CURLOPT_HEADER,false);  
	curl_setopt($s,CURLOPT_RETURNTRANSFER,1);  
	$result = curl_exec($s);
	if(/*ereg*/preg_match('403 Forbidden',$result)) {
		// USE AN ALTERNATE SERVICE
		setisgd($encoded_url);
	} else {
		curl_close($s);  
		$obj = json_decode($result, true);
		$_SESSION['bitly'] = $obj['results'][$longurl]['shortUrl'];
	}
}

/*
http://api.bit.ly/shorten?version=2.0.1&longUrl=http%3A%2F%2Froblonium.com%2F&login=bawigga&apiKey=R_b7e2418b9723f349f398cf6eec2d38fe
{"errorCode": 0, "errorMessage": "", "results": {"http://roblonium.com/": {"userHash": "2xkmaaq", "shortKeywordUrl": "", "hash": "39eCIhm", "shortCNAMEUrl": "https://bit.ly/2xkmaaq", "shortUrl": "https://bit.ly/2xkmaaq"}}, "statusCode": "OK"}
*/

function setisgd($encoded_url) {
	$s = curl_init();  
	$url = "http://is.gd/api.php?longurl=$encoded_url";
	curl_setopt($s,CURLOPT_URL, $url);
	curl_setopt($s,CURLOPT_HEADER,false);  
	curl_setopt($s,CURLOPT_RETURNTRANSFER,1);  
	$result = curl_exec($s);
	$_SESSION['bitly'] = $result;
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">	
	<meta content="width=320; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" name="viewport"/>
	<meta name="title" content="Rolando Character Creator"/>
	<meta name="description" content="I just created my very own custom Rolando character. Check it out!"/>
	<link rel="image_src" href="rolandos/thumbs/-medium.png"/>
	<title>Rolando Character Creator</title>
	<link rel="shortcut icon" href="/favicon.ico" />
	<link rel="stylesheet" href="css/960.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="css/rolando.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="css/smoothness/jquery-ui-1.7.1.custom.css" type="text/css" media="screen" title="jquery">

	<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.7.1.custom.min.js"></script>
	<script src="js/rolando.js" type="text/javascript"></script>
	<script src="js/cufon-yui.js" type="text/javascript"></script>
	<script type="text/javascript" src="js/vag-black.font.js"></script>

	<?phpif($DEBUG):?>
	<link rel="stylesheet" href="css/debug.css" type="text/css" media="screen" />
	<script src="js/debug.js" type="text/javascript"></script>
	<?phpendif?>

	<script type="text/javascript">
		jQuery(document).ready(function(){
			// SETUP ACCORDIONS
			$("#accordion").accordion({
				autoHeight: false,
				alwaysOpen: false
			});
			
			// LIMIT CHARACTERS FOR TWITTER MESSAGE FIELD
			$(function(){
				$('#twitter-message-textarea').keyup(function(){
					limitChars('twitter-message-textarea', 140, 'twitter-feedback');
					update_rolando_description(this.value, 1);
				})
			});
			
			// STORY ON STEP 2
			$(function(){
				$('#twitter-message-textarea').keyup(function(){
					limitChars('why-you-roll-textarea', 140, 'description-feedback');
				})
			});
			
			// STORY ON STEP 2
			$(function(){
				$('#rolando-email').keyup(function(){
					validate_email(this, $('#rolando-email-validity'));
				})
			});			
			
			// CUFON TEXT REPLACEMENTS
			Cufon.replace('h2', {textShadow: '#744a3f 0px 1px, #81331D 0px 0px'});
			Cufon.replace('h3', {textShadow: '#ffffff 0px 1px, #81331D 0px 0px'});
			Cufon.replace('.rolando-listing-header h2');
			Cufon.replace('h6');
			Cufon.replace('ul');
			Cufon.replace('ol');
			Cufon.replace('p');
			Cufon.replace('.accolades blockquote');
		});
		$(".object_item").live("click", function() {
			swapRolandoPart($(this).context);
		});

	</script>

</head>
<body>

<div id="background"></div>
	
			<div id="frame">
			<div class="inner-frame">
				<div class="frame-top"><a href="/">Launch Website</a></div>
		
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-5771267-16");
pageTracker._trackPageview();
} catch(err) {}</script>