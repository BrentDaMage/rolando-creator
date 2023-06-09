<?php include('_header.php'); ?>
<div id="content">
	<div class="iphone_container">
		<a href="/rolando-creator/"><img id="header" src="images/header-step1.jpg" /></a>
		<link rel="stylesheet" href="css/parts.css" type="text/css" media="screen">
		<div id="rolando_canvas">
			<?php
			$spikey_body = false;
			if(isset($_SESSION['step']) && $_SESSION['step'] == 'step-1') {
				(/*ereg*/preg_match('08|09|10|11|12|13|14',$_SESSION['body'])) ? $spikey_body = true : $spikey_body = false;
				foreach(array('body', 'head', 'pants', 'eyes', 'eyebrows', 'mouth', 'freckles', 'moustache') as $group) {
					if($_SESSION[$group] == 'none') continue;
					echo '<img id="'.$_SESSION[$group].'" class="rolando_'.$group.' rolando_part" src="images/character_assets/'.$_SESSION[$group].'.png" ';
					if($spikey_body == true)
						if($group == 'head' || $group == 'pants')
							echo 'style="display:none;"';
					echo ' />';
				}
			} else {
			?>
			<img id="body-01" class="rolando_body rolando_part" src="images/character_assets/body-01.png"/>
			<img id="head-01" class="rolando_head rolando_part" src="images/character_assets/head-01.png"/>
			<img id="eyebrows-01" class="rolando_eyebrows rolando_part" src="images/character_assets/eyebrows-01.png"/>
			<img id="eyes-01" class="rolando_eyes rolando_part" src="images/character_assets/eyes-01.png"/>
			<img id="moustache-01" class="rolando_moustache rolando_part" src="images/character_assets/moustache-01.png"/>
			<img id="mouth-01" class="rolando_mouth rolando_part" src="images/character_assets/mouth-01.png"/>
			<img id="freckles-01" class="rolando_freckles rolando_part" src="images/character_assets/freckles-01.png"/>
			<img id="pants-01" class="rolando_pants rolando_part" src="images/character_assets/pants-01.png"/>
			<?php } ?>
		</div>			
		<form action="step2.php" method="POST">
		<div id="accordion">
			<?php
			// GETS ALL FILES OF SOME FILETYPE OUT OF THE CHARACTER ASSETS DIRECTORY
			function file_list($d,$x){
			       foreach(array_diff(scandir($d),array('.','..')) as $f)if(is_file($d.'/'.$f)&&(($x)?preg_match("/$x$/",$f):1))$l[]=$f;
			       return $l;
			}
			function isPartSet($part) {
				(isset($_SESSION[$part])) ? "checked=\"checked\"" : "";
			}
			
			$images = file_list('images/character_assets','.png');
			$assets = array();
			
			$style_hide = ' style="display:none;" ';
			
			// CREATE PARTS ACCORDION
			foreach(array('body', 'eyes', 'eyebrows', 'mouth', 'head', 'pants', 'moustache', 'freckles') as $group) {
				// Group name Overrides
				if($group == "pants") {
					echo '<h6 id="'.$group.'_heading"';
					if($spikey_body) print $style_hide;
					echo '><a href="#">Clothes</a></h6>'.PHP_EOL;
				} elseif($group == "head") {
					echo '<h6 id="'.$group.'_heading"><a href="#">Headgear</a></h6>'.PHP_EOL;
				}else {
					echo '<h6  id="'.$group.'_heading"><a href="#">'.ucwords($group).'</a></h6>'.PHP_EOL;
				}
				echo '<div class="'.$group.'_accordion_content accordion-content">';
				foreach($images as $image) {
					$matches = array();
					if(preg_match("/^(($group)-([0-9]{2}))\.[a-z]+$/",$image,/*&*/$matches)) {
						echo '<img src="images/character_assets/'.$image.'" alt="'.$matches[1].'" class="object_item '.$group.'"';
						(isset($_SESSION[$group]) && $_SESSION[$group] == $matches[1]) ? print("checked=\"checked\" />".PHP_EOL) : print(" />". PHP_EOL);
					}
				}
				if(in_array($group, array('head', 'pants', 'eyebrows', 'mouth', 'freckles', 'moustache')))
					echo '<img src="images/character_assets/none.png" alt="none" class="object_item '.$group.'">'.PHP_EOL;
				echo '<input type="hidden" name="'.$group.'" id="'.$group.'-value" ';
				(isset($_SESSION[$group])) ? print "value=\"".$_SESSION[$group]."\" />".PHP_EOL : print "value=\"$group-01\" />".PHP_EOL;
				echo '</div>';
			}
			
			?>				
		</div>
		<input type="hidden" name="step" value="step-1" id="status">
		<div id="bottom-nav">
			<input type="image" name="submit" id="submit" style="float: right;" src="images/btn-next.gif" alt="Step 2">
		</div>
		</form>
	</div>
</div>
<?php include('_footer.php');?>
