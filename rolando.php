<?php include('_header.php'); ?>
<div id="content">
	<div class="iphone_container">
		<a href="/rolando-creator/"><img id="header" src="images/header-step3.jpg" /></a>
<?php if(isset($error)): ?>
		<p></p>
<?php else: ?>
		<div class="rolando-listing">
			<div class="rolando-listing-header">
				<h2></h2>
			</div>
			<div class="rolando-listing-container">
				<div class="rolando-info">
					<img class="rolando-thumbnail" src="rolandos/thumbs/<?=$_SESSION['token']?>-small.png" title=""><br />
				</div>
				<div class="rolando-description">
					<p></p>
				</div>
			</div>
		</div>
<?php endif; ?>
		<div id="accordion">
			<h6><a>Play Rolando 2 today for FREE</a></h6>
			<div class="accolades">
				<p>
					How you play is up to you.<br />Download the first chapter of Rolando 2.
					<br /><br />
					<a href="http://click.linksynergy.com/fs-bin/stat?id=U/pLCBMksJs&offerid=146261&type=3&subid=0&tmpid=1826&u1=Web_Rolando2_ngmoco_rolandocreator&RD_PARM1=http%253A%252F%252Fitunes.apple.com%252FWebObjects%252FMZStore.woa%252Fwa%252FviewSoftware%253Fid%253D324320768%2526mt%253D8%2526uo%253D6%2526partnerId%253D30"><img width="180" src="images/app_store_badge.png"></a></p>
					<div class="accolades">
						<h2>Awards</h2>
						<hr />	
						<img src="images/award_ign_editors_choice.png" />
						<blockquote>“If you do not like Rolando 2, you do not like videogames”<br /><br />9 out of 10<br /><br />-IGN</blockquote>
						<hr />
						<img src="images/award_pocket_gamer_gold.gif" />
						<blockquote>"9 out of 10"<br /><br />-Pocket Gamer</blockquote>
						<hr />
						<img src="images/award_app_awards.png" />
						<blockquote>"Best app of the Year - Paid"<br />"Best Adventure Game"<br /><br /> -50,000+ Voters on AppAdvice.com</blockquote>
						<hr />
						<blockquote>4.5 Stars<br /><br />iTunes Average rating</blockquote>
					</div>
			</div>
			<h6><a>Share with Friends</a></h6>
			<div class="share">
				<div class="twitter">
					<h3>Twitter</h3>
					<form onsubmit="return tweet();">
						<input class="rolando-textfield rolando-input" type="text" name="twitter_username" value="username" id="twitter-username" />
						<input class="rolando-textfield rolando-input" type="password" name="twitter_password" value="password" id="twitter-password" />
						<input type="hidden" name="bitly_url" value="" id="hidden-bitly">
						<input type="hidden" name="rolando_name" value="" id="hidden-rolando-name">
											<input type="image" value="Post to Twitter!" onclick="javascript:pageTracker._trackPageview('/twitter_post');" src="images/btn_twitter_post.png" alt="Post to Twitter" id="btn-twitter-post" />
					</form>
				</div>
				<div class="facebook">
				<h3>Facebook</h3>
				<!--<a href="http://www.facebook.com/sharer.php?u=&t=Check%20out%20my%20Rolando%20Character%21" onclick="javascript:pageTracker._trackPageview('/facebook_post');"><img src="images/btn_facebook_share.png" alt="Share on Facebook!" /></a>-->
				<script>function fbs_click() {u=location.href;t=document.title;window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(u)+'&t='+encodeURIComponent(t),'sharer','toolbar=0,status=0,width=626,height=436');return false;}</script><a href="http://www.facebook.com/share.php?u=" onclick="javascript:return fbs_click();pageTracker._trackPageview('/facebook_post');" target="_blank" style="text-decoration:none;"><img src="images/btn_facebook_share.png" alt="Share on Facebook!" /></a>
							</div>
			<h6><a href="#">Rolando iPhone Wallpaper</a></h6>
			<div>
				<h2>Instructions</h2>
				<p>This wallpaper will fit on any iPhone or iPod touch! To save it to your device, touch and hold the image in Mobile Safari, or save it to your iPhoto library and sync from iTunes.</p>
				<img src="rolandos/background/d7aed84dd64bb2d0e458a23945d394c8.jpg">
			</div>
			<h6><a href="">Rolando Profile Picture</a></h6>
			<div>
				<h2>Profile Picture</h2>
				<img src="rolandos/thumbs/d7aed84dd64bb2d0e458a23945d394c8-medium.png" class="right" />
				<p>This profile picture can be downloaded to any iPhone or iPod touch! To save it to your device, touch and hold the image in Mobile Safari, or save it to your iPhoto library and sync from iTunes.</p>
			</div>
		</div>
		
		<a href="view_rolandos.php"><img src="images/roundup.jpg" width="320" height="72" /></a>
	</div>
</div>
<?php include('_footer.php');?>