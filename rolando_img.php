<?php
	session_start();
	include("includes/debug.php");
	include("includes/db.php");
	umask(0000);

	
	// THUMBNAIL GENERATOR METHOD
	function generateThumbnail(&$image, $filename, $width, $height) {
		$thumb = imagecreatetruecolor($width,$height);
		imagesavealpha($thumb, true);
		$transparency = imagecolorallocatealpha($image, 0, 0, 0, 127);
		imagefill($thumb, 0, 0, $transparency);	
		imagealphablending($thumb, true);
		imagecopyresampled($thumb,$image,0,0,0,0,$width,$height,imagesx($image),imagesy($image));
		imagepng($thumb,$filename);
		imagedestroy($thumb);
	}
	
	// SETUP CANVAS
	$rolando_width = 450;
	$rolando_height = 450;
	$rolando = imagecreatetruecolor($rolando_width,$rolando_height);
	imagesavealpha($rolando, true);
  $trans_colour = imagecolorallocatealpha($rolando, 0, 0, 0, 127);
  imagefill($rolando, 0, 0, $trans_colour);	
	imagealphablending($rolando, true);
	
	// PARSE OUT ROLANDO PARTS
	$rolando_parts = array();
	//FB::log($_SESSION);
	//FB::log($_COOKIE);
	if(isset($_SESSION['body'])) $rolando_parts['body'] = $_SESSION['body'];
	if(isset($_SESSION['mouth'])) $rolando_parts['mouth'] = $_SESSION['mouth'];
	if(isset($_SESSION['freckles'])) $rolando_parts['freckles'] = $_SESSION['freckles'];
	if(isset($_SESSION['eyes'])) $rolando_parts['eyes'] = $_SESSION['eyes'];
	if(isset($_SESSION['eyebrows'])) $rolando_parts['eyebrows'] = $_SESSION['eyebrows'];
	if(isset($_SESSION['pants'])) $rolando_parts['pants'] = $_SESSION['pants'];
	if(isset($_SESSION['moustache'])) $rolando_parts['moustache'] = $_SESSION['moustache'];
	if(isset($_SESSION['head'])) $rolando_parts['head'] = $_SESSION['head'];
	
	$spikey_bodies = array(
		"body-08",
		"body-09",
		"body-10",
		"body-11",
		"body-12",
		"body-13",
		"body-14"
	);

	$spikey_body = in_array($rolando_parts['body'],$spikey_bodies);

	// BUILD CHARACTER
	foreach($rolando_parts as $group => $part)
	{		
		if($part == "none") continue;

		// DONT RENDER HEAD OR PANTS IF SPIKEY BODY TYPE
		if($spikey_body && preg_match('/head|pants/',$part)) continue;

		// ADD ASSET TO COMPOSITION
		$part_image = imagecreatefrompng("images/character_assets/$part.png");
		//GET WIDTH AND HEIGHT
		$w = imagesx($part_image);
		$h = imagesy($part_image);
		
		$srcw = $dstw = $w;
		$srch = $dsth = $h;
		
		// SET NEW DIMENSIONS
		$dstw = 400;
		if($w == 520)
		{
			$dstw = 490;
		}
		$dsth = $h * ($dstw/$w);

		$offset_x = ($rolando_width-$dstw)/2;
		$offset_y = ($rolando_height-$dsth)/2;

	 	if(preg_match('/body/',$part)) {
			($spikey_body) ? $offset_y = 50 : $offset_y = 80;
		} else if(preg_match('/mouth/',$part)) {
			$offset_y = 55;
		} else if(preg_match('/freckles/',$part)) {
			$offset_y = 230;
		} else if(preg_match('/pants/',$part)) {
			$offset_y = 65;
		} else if(preg_match('/moustache/',$part)) {
			$offset_y = 50;
		} else if(preg_match('/eyes/',$part)) {
			$offset_y = 55;
		} else if(preg_match('/eyebrows/',$part)) {
			$offset_y = 55;
		} else if(preg_match('/head/',$part)) {
			$offset_y = -25;
		}
		
		imagecopyresampled($rolando,$part_image,$offset_x,$offset_y+15,0,0,$dstw,$dsth,$srcw,$srch);
	}

	// ADD IN BACKGROUND IMAGE & SET USER'S SESSION VARIABLE
	if(isset($_GET['bg']))
	{
		$_SESSION['bg'] = $_GET['bg'];
	}
	elseif(!isset($_SESSION['bg']))
	{
		$_SESSION['bg'] = "01";
	}
	
	$wallpaper = imagecreatefrompng("images/character_assets/background-".$_SESSION['bg'].".png");
	
	// COMBINE ROLANDO INTO BACKGROUND IMAGE
	$dst_rolando_size = 220;
	$offset_x = (320 - $dst_rolando_size)/2;
	imagecopyresampled($wallpaper,$rolando,$offset_x,250,0,0,	$dst_rolando_size,	$dst_rolando_size,$rolando_width,$rolando_width);
	
	// ADD SPEECH BUBBLE TEXT
	if(isset($_GET['rolando_description']) && $_GET['rolando_description'] != "")
	{
		$speech_bubble = imagecreatefrompng("images/character_assets/speech_bubble.png");
		imagecopyresampled($wallpaper,$speech_bubble,0,25,0,0,320,310,320,310);
		$text = stripslashes($_GET['rolando_description']);
		$text = substr($text,0,140);
		$black = imagecolorallocate($wallpaper, 0, 0, 0);
	
		$font = './includes/fonts/VAGRoundedStd-Bold.otf';
		imagettftext($wallpaper, 12, 0, 55, 120, $black, $font, wordwrap($text,25,"\n",true));
	}
	
	// AVATAR GENERATORS
	if(isset($_SESSION['step']) && $_SESSION['step'] == "step-1")
	{
		generateThumbnail($rolando,'rolandos/thumbs/'.$_SESSION['token'].'-medium.png',110,110);
		generateThumbnail($rolando,'rolandos/thumbs/'.$_SESSION['token'].'-small.png',84,84);	
		imagepng($wallpaper, 'rolandos/background/'.$_SESSION['token'].'.jpg');
	}
	
	// IMAGE OUTPUT
	header("Content-type: image/png");
	imagepng($wallpaper);
	imagedestroy($wallpaper);
	imagedestroy($rolando);
	
?>