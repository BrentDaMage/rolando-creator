var background = 1;

function swapRolandoPart(obj)
{
	var asset_path = "images/character_assets/";
	var part = obj.alt;
	var classes = obj.className.split(" ")
	var group = classes[1];
	var canvas = $("#rolando_canvas")[0];
	
	// REMOVE ANY CURRENT ELEMENTS ON THE CANVAS
	var destroy = $("#rolando_canvas").find(".rolando_" + group);
	destroy.remove();
	
	// REMOVE ANY CLOTHING IF SPIKEY BODY
	if(group == "body")
	{
		$(".disabled_category").remove();
		if(part.match("08|09|10|11|12|13|14"))
		{
			$("#rolando_canvas").find(".rolando_pants").hide();
			$("#rolando_canvas").find(".rolando_head").hide();
			$('<div class="disabled_category disabled_clothes">&nbsp;</div>').prependTo(".pants_accordion_content");
			$('<div class="disabled_category disabled_headgear">&nbsp;</div>').prependTo(".head_accordion_content");
		}
		else
		{
			$("#rolando_canvas").find(".rolando_pants").show();
			$("#rolando_canvas").find(".rolando_head").show();
		}
	}
	
	// ADD IN THE NEW ELEMENT TO THE CANVAS UNLESS "NONE"
	if(part != "none")
	{
		var newPart = "<img src='" + asset_path + part + ".png' class='rolando_" + group + " rolando_part' id='" + part + "' />";
		$("#"+group+"-value")[0].value = part;
		$("#rolando_canvas").append(newPart);
	}
	// Set form value
	$("#"+group+"-value")[0].value = part;
}

function vote(id, vote)
{
	$.post("vote.php", {id: id, thumb: vote},
		function(data) {
			if(data.status == "success") {
				// REMOVE ONCLICK EVENT LISTENER
				$("#rolando-"+id+" .btn-thumbs-down, #rolando-"+id+" .btn-thumbs-up").each(function() {
					$(this)[0].onclick="";
				});

				// SET BUTTON STATUS STYLES
				if(vote == 1) { //VOTE UP
					$("#rolando-"+id+" .btn-thumbs-up")[0].src="images/thumbs_up_inactive.png";
				} else { // VOTE DOWN
					$("#rolando-"+id+" .btn-thumbs-down")[0].src="images/thumbs_up_inactive.png";
				}

				// HANDLE CHANGING VOTE INFO
				if(data.votes == 0) {
					$("#rolando-"+id+" .votes").removeClass('positive negative');
				} else if(data.votes > 0) {
					$("#rolando-"+id+" .votes").addClass('positive');
					$("#rolando-"+id+" .votes").removeClass('negative');
				} else {
					$("#rolando-"+id+" .votes").addClass('negative');
					$("#rolando-"+id+" .votes").removeClass('positive');
				}
				
				// SET PLURALIZATION
				if(data.votes == 1 || data.votes == -1) $("#rolando-"+id+" .vote_pluralization").text('');
				else $("#rolando-"+id+" .vote_pluralization").text('s');
				
				$("#rolando-"+id+" .votes").text(data.votes);
			}
		},
	"json");
}

function tweet()
{
	$.post("tweet.php", {
			twitter_username: $('#twitter-username').attr('value'),
			twitter_password: $('#twitter-password').attr('value'),
			rolando_name: $('#hidden-rolando-name').attr('value'),
			bitly_link: $('#hidden-bitly').attr('value'),
		},
		function(xml) {
			$("error",xml).each(function(i) {
				alert($(this).text());
			});
			$("text",xml).each(function(i) {
				// SUCCESSFUL TWEET!
				$('#btn-twitter-post').replaceWith('<img id="btn-twitter-success" src="images/btn_twitter_success.png" />');
			});
		});
	return false;
}

function limitChars(textid, limit, infodiv)
{
	var text = $('#'+textid).val(); 
	var textlength = text.length;

	if(textlength > limit) {
	 		$('#'+textid).val(text.substr(0,limit));	
	 		return false;
	} else {
			$('#' + infodiv).html('<span class="notice">You have '+ (limit - textlength) +' characters left.</span>');
			return true;
	}
}

function limitChars(textid, limit)
{
	var text = $('#'+textid).val();
	var textlength = text.length;

	if(textlength > limit) {
	 		$('#'+textid).val(text.substr(0,limit));	
	 		return false;
	} else {
			return true;
	}
}

function changeImage(num)
{
	$("#gallery").empty();
	var img = '<img src="rolando_img.php?&bg=0'+num+'&time='+new Date().getTime()+'" class="user_img" id="img_'+num+'" />';
	$("#gallery").append(img);
	background=num;
}

function validate_rolando_name_description()
{
	// GET FORM FIELDS
	var name = $('#rolando-name');
	var description = $('#twitter-message-textarea');
	var whyroll = $('#why-you-roll-textarea');
	var email = $('#rolando-email');
	
	// VALIDATION CHECKING
	if(name.attr('value') =="" || name.attr('value') =="Name Your Rolando")
	{
		// ACTIVATE ACCORDION
		if(!name.parent().parent().prev().hasClass('ui-state-active'))
			name.parent().parent().prev().click();

		name.focus();
		name.select();
		return false;
	}
	else if(description.attr('value') === "This Rolando doesn't have much to say.")
	{
		// ACTIVATE ACCORDION
		if(!description.parent().parent().prev().hasClass('ui-state-active'))
			description.parent().parent().prev().click();

		description.focus();
		description.select();
		return false;
	}
	else if(whyroll.attr('value') == "" || whyroll.attr('value') === "Tell us why you roll")
	{
		// ACTIVATE ACCORDION
		if(!whyroll.parent().parent().prev().hasClass('ui-state-active'))
			whyroll.parent().parent().prev().click();

		whyroll.focus();
		whyroll.select();
		return false;
	}
	else if( email.attr('value') !== "Email Address" &&
			 email.attr('value') !== "" &&
			!email.attr('value').match(/^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/))
	{
		// ACTIVATE ACCORDION
		if(! email.parent().parent().prev().hasClass('ui-state-active'))
			email.parent().parent().prev().click();

		email.focus();
		email.select();
		return false;
	}
	else
	{
		return true;
	}
}

function validate_email(field, feedback)
{
	if(field.value !== "")
	{
		var filter = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		if (filter.test(field.value)) {
			feedback[0].src="images/success.png";
		} else {
			feedback[0].src="images/error.png";
		}
		feedback[0].style.display='block';
	} else {
		feedback[0].style.display='none';
	}
		
}

function clearText(field)
{
    if (field.defaultValue == field.value) field.value = '';
    else if (field.value == '') field.value = field.defaultValue;
}

function clearTextAllowBlank(field)
{
    if (field.defaultValue == field.value) field.value = '';
}

function update_rolando_description(text)
{
		$("#gallery").empty();
		var img = '<img src="rolando_img.php?&bg=0'+background+'&rolando_description='+text+'" class="user_img" id="img_'+background+'" />';
		$("#gallery").append(img);
}
