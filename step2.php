<?php include('_header.php'); ?>
<div id="content">
	<div class="iphone_container">
		<a href="step1.php"><img id="header" src="images/header-step2.jpg" /></a>
		<div id="gallery">
			<img src="rolando_img.php" class="user_img" id="img_1" />
		</div>
		<form id="step2_form" method="POST" action="rolando.php" onsubmit="return validate_rolando_name_description();">
		<div id="accordion">
			<h6><a>Choose Background</a></h6>
		    <div>
				<img src="images/character_assets/background-01.png" alt="background-01" class="background alpha" onclick="changeImage(1);">
				<img src="images/character_assets/background-02.png" alt="background-02" class="background" onclick="changeImage(2);" />
				<img src="images/character_assets/background-03.png" alt="background-03" class="background" onclick="changeImage(3);" />
				<img src="images/character_assets/background-04.png" alt="background-04" class="background" onclick="changeImage(4);" />
				<img src="images/character_assets/background-05.png" alt="background-05" class="background" onclick="changeImage(5);" />
				<input type="hidden" name="background" id="background-value" value="background">
		    </div>			
			<h6><a>Name &amp; Story</a></h6>
		    <div>
					<div class="rolando-textfield rolando-input">
						<input size="35" type="text" name="rolando_name" value="Name Your Rolando" id="rolando-name" maxlength="24" onfocus="clearText(this)" onBlur="clearText(this)" />
					</div>
					<div id="twitter-message" class="rolando-textarea">
						<textarea class="rolando-input" id="twitter-message-textarea" name="rolando_description">This Rolando doesn't have much to say.</textarea>
						<div id="description-feedback"></div>
					</div>
					<div id="why-you-roll" class="rolando-textarea">
						<textarea class="rolando-input" id="why-you-roll-textarea" name="rolando_why_you_roll">Tell us why you roll</textarea>
						<div id="why-you-roll"></div>
					</div>
										<input class="rolando-textfield rolando-input" type="text" name="rolando_gamername" value="Plus+ Gamername" id="rolando-gamername" maxlength="24" onfocus="clearText(this)" onBlur="clearTextAllowBlank(this)" />
		    </div>
		</div>
		<input type="hidden" name="step" value="step-2" id="status">
		<div id="bottom-nav">
			<a href="step1.php"><img src="images/btn-prev.gif" class="btn-prev" /></a>
			<input type="image" class="btn-next" name="submit" id="submit" src="images/btn-next.gif" alt="Finish your Rolando!">
		</div>
		</form>
	</div>
</div>
<?php include('_footer.php');?>
