function getXMLHttp(){
	if(window.XMLHttpRequest){
		return new XMLHttpRequest();
	}else if(window.ActiveXObject){
		return new ActiveXObject("Microsoft.XMLHTTP");
	}else{
		alert("Sorry, Your browser does not support Ajax!");
	}
}

function refresh_comments(){
	var x = document.getElementById('conv_id').value;
	var y = document.getElementById('comment_id').value;

	var http = getXMLHttp();

	http.open("POST", "includes/conversations.php", true);
	http.onreadystatechange = function(){
		if(http.readyState == 4 && http.status == 200){
			if(http.responseText.length > 0){
				if(http.responseText == 1){
					document.getElementById('new_comments_alert').innerHTML = "<a onClick='reveal_new_comments("+x+",0,"+http.responseText+")'>"+http.responseText+" new post</a>";
				}else if(http.responseText == 0){}else{
					document.getElementById('new_comments_alert').innerHTML = "<a onClick='reveal_new_comments("+x+",0,"+http.responseText+")'>"+http.responseText+" new posts</a>";
				}
			}
		}
	}

	http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http.send("refresh_comments="+true+"&conv_id="+x+"&comment_id="+y);
	setTimeout("refresh_comments()", 6000);
}

function switch_to_contact(){
	document.getElementById('contact_list').style.height = "520px";
	document.getElementById('all_users').style.height = "0px";
	document.getElementById('scroll_contacts').style.height = "50px";
	document.getElementById('scroll_everyone').style.height = "0px";
	document.getElementById('invite_new').style.height = "0px";
}

function switch_to_everyone(){
	document.getElementById('contact_list').style.height = "0px";
	document.getElementById('all_users').style.height = "520px";
	document.getElementById('scroll_contacts').style.height = "0px";
	document.getElementById('scroll_everyone').style.height = "50px";
	document.getElementById('invite_new').style.height = "0px";
}

function switch_to_tell_a_friend(){
	document.getElementById('contact_list').style.height = "0px";
	document.getElementById('all_users').style.height = "0px";
	document.getElementById('scroll_contacts').style.height = "0px";
	document.getElementById('scroll_everyone').style.height = "0px";
	document.getElementById('invite_new').style.height = "520px";
}

function switch_to_conversations(){
	document.getElementById('transcript').style.height = "0px";
	document.getElementById('scroll_convs_case').style.height = "50px";
	document.getElementById('scroll_transcript_case').style.height = "0px";
	document.getElementById('conversations').style.height = "544px";
}

function switch_to_transcript(){
	document.getElementById('conversations').style.height = "0px";
	document.getElementById('scroll_convs_case').style.height = "0px";
	document.getElementById('scroll_transcript_case').style.height = "50px";
	document.getElementById('transcript').style.height = "544px";
}

function switch_to_my_conversations(){
	document.getElementById('pub_conversations').style.height = "0px";
	document.getElementById('scroll_pub_case').style.height = "0px";
	document.getElementById('scroll_my_convs_case').style.height = "50px";
	document.getElementById('my_conversations').style.height = "544px";
}

function switch_to_pub_conversations(){
	document.getElementById('pub_conversations').style.height = "544px";
	document.getElementById('scroll_pub_case').style.height = "50px";
	document.getElementById('scroll_my_convs_case').style.height = "0px";
	document.getElementById('my_conversations').style.height = "0px";
}

function textVanish(name){
var x = document.getElementById(name);
	if(x.value == "search"){
	 	x.value = "";
	 	x.style.color = "#000";
	 }
}

function returnSearchText(){
var x = document.getElementById('search_input');
	if(x.value == ""){
	 	x.value = "search";
	 	x.style.color = "#000";
	 }

	var y = document.getElementById('search_input_conv');
	if(y.value == ""){
		y.value = "search";
	 	y.style.color = "#000";
	 }
}

function start_public_conv(){
	var first_comment = document.getElementById('first_comment').value;
	if(first_comment.length <= 250){
		accept_public_conv();
	}else{
		document.getElementById('first_comment_note').innerHTML = "character limit exceeded!";
	}
}


function postCount(post_count, first_comment){
	var x = document.getElementById(post_count);
	var y = document.getElementById(first_comment);
	x.innerHTML = 250 - y.value.length;
	if(x.innerHTML<0){
		x.style.color = "#f55";
	}else{
		x.style.color = "#888";
	}
}

function go_to_conv(conv_id){
	var start_from = 0;
	go_to_comments(conv_id, start_from);
	go_to_current_conv(conv_id);
	go_to_conv_transcript(conv_id);
	display_comment_section_nums(conv_id,0,46);
	set_invite_link();
}

function join_current_conv(conv_id){
	var start_from = 0;
	go_to_comments(conv_id, start_from);
	go_to_conv_transcript(conv_id);
	display_comment_section_nums(conv_id,0,46);
	set_invite_link();
}

function join_current_poll(conv_id, num_of_options){
	var start_from = 0;
	go_to_poll_stats(conv_id, start_from, num_of_options);
	//go_to_current_conv(conv_id);
	go_to_conv_transcript(conv_id);
	//highlight_poll(conv_id);
	document.getElementById("comment_section_nums").innerHTML = "";
	//display_comment_section_nums(conv_id,0,46);
	document.getElementById("invite_url").value = "";
}

function go_to_poll(conv_id, num_of_options){
	var start_from = 0;
	go_to_poll_stats(conv_id, start_from, num_of_options);
	go_to_current_conv(conv_id);
	//go_to_conv_transcript(conv_id);
	highlight_poll(conv_id);
	document.getElementById("comment_section_nums").innerHTML = "";
	//display_comment_section_nums(conv_id,0,46);
	document.getElementById("invite_url").value = "";
}

function reveal_new_comments(conv_id, start_from, num_rows){
	go_to_comments(conv_id, start_from);
	display_comment_section_nums(conv_id,0,46);
	add_new_comments(conv_id, num_rows);
	set_comment_id(conv_id);
}

function add_new_comments(conv_id,  num_rows){
	var x = conv_id;
	var y = num_rows;

	var http = getXMLHttp();
	http.open("POST", "includes/conversations.php", true);
	http.onreadystatechange = function(){
		if(http.readyState == 4 && http.status == 200){

			if(http.responseText.length > 0){
				var div01 = document.getElementById("comments_case").cloneNode();
				div01.innerHTML =  http.responseText;
				var div02 = document.getElementById("comments_case");
				div02.insertBefore(div01, div02.firstChild);
			}else{
				document.getElementById("comments_case").innerHTML = "post was unsuccessful...";
			}
		}
	}

	http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http.send("add_new_comments=true&conv_id="+x+"&num_rows="+y);
}

function add_earlier_comments(conv_id,  num_rows, start_from){
	var x = conv_id;
	var y = num_rows;
	var z = start_from;

	var http = getXMLHttp();
	http.open("POST", "includes/conversations.php", true);
	http.onreadystatechange = function(){
		if(http.readyState == 4 && http.status == 200){
			if(http.responseText.length > 0){
				var data = http.responseText.split("[BRK]");
				var div01 = document.getElementById("comments_case").cloneNode();
				div01.innerHTML =  data[0];
				var div02 = document.getElementById("comments_case");
				div02.insertBefore(div01, div01.nextSibling);
				document.getElementById("more_transcript_comments").innerHTML = data[1];
			}
		}
	}

	http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http.send("add_earlier_comments=true&conv_id="+x+"&num_rows="+y+"&start_from="+z);
}

function add_earlier_convs(user_id, connect_user_id, conv_id,  num_rows, start_from){
	var a = user_id;
	var b = connect_user_id;
	var x = conv_id;
	var y = num_rows;
	var z = start_from;

	var http = getXMLHttp();

	http.open("POST", "includes/conversations.php", true);
	http.onreadystatechange = function(){
		if(http.readyState == 4 && http.status == 200){
			if(http.responseText.length > 0){
				var data = http.responseText.split("[BRK]");
				var div01 = document.getElementById("convs_case").cloneNode();
				div01.innerHTML =  data[0];
				var div02 = document.getElementById("convs_case");
				div02.insertBefore(div01, div01.nextSibling);
				document.getElementById("more_current_user_convs").innerHTML = data[1];
			}
		}
	}

	http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http.send("add_earlier_convs=true&user_id="+a+"&connect_user_id="+b+"&conv_id="+x+"&num_rows="+y+"&start_from="+z);
}

//jumps to a set of 9 comments
function go_to_comments(conv_id, start_from){
	var x = conv_id;
	var y = start_from;
	document.getElementById('conv_id').value = conv_id;

	var http = getXMLHttp();

	http.open("POST", "includes/conversations.php", true);
	http.onreadystatechange = function(){
		if(http.readyState == 4 && http.status == 200){
			//place all comments under current conversation i.e. transcript
			document.getElementById('new_comments_alert').innerHTML = "";

			if(http.responseText.length > 0){
				var comments = http.responseText.split("[BRK]");
				document.getElementById("main_box01").style.height = "180px";
				document.getElementById("main_box01").style.marginTop = "0px";
				document.getElementById("main_box01").style.marginBottom = "0px";
				document.getElementById("main_box01").innerHTML = "";
				document.getElementById("main_box02").innerHTML = "";
				document.getElementById("main_box03").innerHTML = "";
				document.getElementById("main_box04").innerHTML = "";
				document.getElementById("main_box05").innerHTML = "";
				document.getElementById("main_box06").innerHTML = "";
				document.getElementById("main_box07").innerHTML = "";
				document.getElementById("main_box08").innerHTML = "";
				document.getElementById("main_box09").innerHTML = "";

				if(comments[0].length > 0){
					document.getElementById("main_box01").innerHTML  = comments[0];
				}

				if(comments[1].length > 0){
					document.getElementById("main_box02").innerHTML  = comments[1];
				}

				if(comments[2].length > 0){
					document.getElementById("main_box03").innerHTML  = comments[2];
				}

				if(comments[3].length > 0){
					document.getElementById("main_box04").innerHTML  = comments[3];
				}

				if(comments[4].length > 0){
					document.getElementById("main_box05").innerHTML  = comments[4];
				}

				if(comments[5].length > 0){
					document.getElementById("main_box06").innerHTML  = comments[5];
				}

				if(comments[6].length > 0){
					document.getElementById("main_box07").innerHTML  = comments[6];
				}

				if(comments[7].length > 0){
					document.getElementById("main_box08").innerHTML  = comments[7];
				}
				if(comments[8].length > 0){
					document.getElementById("main_box09").innerHTML  = comments[8];
				}
			}else{
				document.getElementById("main_box01").style.height = "180px";
				document.getElementById("main_box01").style.marginTop = "0px";
				document.getElementById("main_box01").style.marginBottom = "0px";
				document.getElementById("main_box01").innerHTML = "<div id='convo_comment'>waiting for your comments...</div>";
				document.getElementById("main_box02").innerHTML = "";
				document.getElementById("main_box03").innerHTML = "";
				document.getElementById("main_box04").innerHTML = "";
				document.getElementById("main_box05").innerHTML = "";
				document.getElementById("main_box06").innerHTML = "";
				document.getElementById("main_box07").innerHTML = "";
				document.getElementById("main_box08").innerHTML = "";
				document.getElementById("main_box09").innerHTML = "";
			}
		}
	}

	http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http.send("go_to_comments=true&conv_id="+x+"&start_from="+y);
}

function go_to_poll_stats(conv_id, start_from, num_of_options){
	var x = conv_id;
	var y = start_from;
	var z = num_of_options;
	var user_id = user_id_js;
	document.getElementById('conv_id').value = conv_id;

	var http = getXMLHttp();

	http.open("POST", "includes/conversations.php", true);
	http.onreadystatechange = function(){
		if(http.readyState == 4 && http.status == 200){
			//place all comments under current conversation i.e. transcript
			document.getElementById('new_comments_alert').innerHTML = "";

			if(http.responseText.length > 0){
				var poll_stats = http.responseText.split("[BRK]");
				document.getElementById("main_box01").style.height = "180px";
				document.getElementById("main_box01").style.marginTop = "0px";
				document.getElementById("main_box01").style.marginBottom = "0px";
				document.getElementById("main_box01").innerHTML = "";
				document.getElementById("main_box02").innerHTML = "";
				document.getElementById("main_box03").innerHTML = "";
				document.getElementById("main_box04").innerHTML = "";
				document.getElementById("main_box05").innerHTML = "";
				document.getElementById("main_box06").innerHTML = "";
				document.getElementById("main_box07").innerHTML = "";
				document.getElementById("main_box08").innerHTML = "";
				document.getElementById("main_box09").innerHTML = "";

				if(poll_stats[0].length > 0){
					document.getElementById("main_box01").innerHTML  = poll_stats[0];
					var poll_height = document.getElementById("poll_case").offsetHeight;
					if(poll_height > 170){
						var marg_bott = 170 - poll_height;
						document.getElementById("main_box01").style.height = poll_height+"px";
						document.getElementById("main_box01").style.zIndex = "50";
						document.getElementById("main_box01").style.marginBottom = marg_bott+"px";						
					}
					document.getElementById("main_box05").innerHTML  = poll_stats[1];
				}

				if(poll_stats[1].length > 0){
					document.getElementById("main_box05").innerHTML  = poll_stats[1];
				}
			}else{
				document.getElementById("main_box01").style.height = "180px";
				document.getElementById("main_box01").style.marginTop = "0px";
				document.getElementById("main_box01").style.marginBottom = "0px";
				document.getElementById("main_box01").innerHTML = "<div id='convo_comment'>sorry, there seem to be something wrong wit this poll...</div>";
				document.getElementById("main_box02").innerHTML = "";
				document.getElementById("main_box03").innerHTML = "";
				document.getElementById("main_box04").innerHTML = "";
				document.getElementById("main_box05").innerHTML = "";
				document.getElementById("main_box06").innerHTML = "";
				document.getElementById("main_box07").innerHTML = "";
				document.getElementById("main_box08").innerHTML = "";
				document.getElementById("main_box09").innerHTML = "";
			}
		}
	}

	http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http.send("go_to_poll_stats=true&user_id="+user_id+"&conv_id="+x+"&start_from="+y+"&num_of_options="+z);
}

function display_comment_section_nums(conv_id, start_from, num_rows){
	var x = conv_id;
	var y = start_from;
	var z = num_rows;

	var http = getXMLHttp();

	http.open("POST", "includes/conversations.php", true);
	http.onreadystatechange = function(){
		if(http.readyState == 4 && http.status == 200){
			if(http.responseText.length > 0){
				document.getElementById("comment_section_nums").innerHTML = http.responseText;
			}else{
				document.getElementById("comment_section_nums").innerHTML = "";
			}
		}
	}

	http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http.send("display_comment_section_nums=true&conv_id="+x+"&start_from="+y+"&num_rows="+z);
}

function go_to_conv_transcript(conv_id){
	var x = conv_id;
	var y = "convs"+conv_id;
	document.getElementById('conv_id').value = conv_id;

	var http = getXMLHttp();

	http.open("POST", "includes/conversations.php", true);
	http.onreadystatechange = function(){
		if(http.readyState == 4 && http.status == 200){
			if(http.responseText.length > 0){
				document.getElementById("transcript_case_scroll").innerHTML = http.responseText;
			}else{
				document.getElementById("transcript_case_scroll").innerHTML = "waiting for your comments...";
			}
			set_comment_id(conv_id);

			var convs = document.getElementById("conversations_case_scroll").getElementsByTagName('div');
			for(var i=0; i<convs.length; i++){
				convs[i].style.backgroundColor = "#fafaff";
			}

			if(document.getElementById(y).getElementsByTagName('div')){
				var conv = document.getElementById(y).getElementsByTagName('div');
				for(var i=0; i<conv.length; i++){
					conv[i].style.backgroundColor = "#acf";
				}
			}
		}
	}

	http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http.send("go_to_conv_transcript=true&conv_id="+x);
}

function highlight_poll(conv_id){
	var x = conv_id;
	var y = "convs"+conv_id;
	document.getElementById('conv_id').value = conv_id;
	set_comment_id(conv_id);
	
	var convs = document.getElementById("conversations_case_scroll").getElementsByTagName('div');
	for(var i=0; i<convs.length; i++){
		convs[i].style.backgroundColor = "#fafaff";
	}

	if(document.getElementById(y).getElementsByTagName('div')){
		var conv = document.getElementById(y).getElementsByTagName('div');
		for(var i=0; i<conv.length; i++){
			conv[i].style.backgroundColor = "#acf";
		}
	}
}

function set_comment_id(conv_id){
	var x = conv_id;

	var http = getXMLHttp();

	http.open("POST", "includes/conversations.php", true);

	http.onreadystatechange = function(){
		if(http.readyState == 4 && http.status == 200){
			if(http.responseText.length > 0){
				document.getElementById("comment_id").value = http.responseText;
			}
		}
	}

	http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http.send("set_comment_id=true&conv_id="+x);
}

function incHeight(txtarea){
	var x = document.getElementById(txtarea);
	var y = document.getElementById("post");
	x.style.height = "34px";
	y.style.marginTop = "7px";
}


function redHeight(txtarea){
	var x = document.getElementById(txtarea);
	var y = document.getElementById("post");
	if(x.value == ""){
		x.style.height = "20px";
		y.style.marginTop = "0px";
	}
}


function post_comment(){
	var conv_id = document.getElementById('conv_id').value;
	if(conv_id == 0){
		document.getElementById('conv_notice').style.color = "#25f";
	}else{
		accept_post_comment();
	}
}

/****************
		function see_full_comment(box_num){
			var x = "see_all_convo_comments"+box_num;
			var y = "convo_comment"+box_num;
			var z = "main_box0"+box_num;
			document.getElementById(x).style.height = "100%";
			var new_height = document.getElementById(x).offsetHeight;
			var old_height = document.getElementById(y).offsetHeight;
			var diff = new_height - old_height;

			document.getElementById(y).style.height = "0px";
			document.getElementById(y).style.overflow = "hidden";
			document.getElementById(z).style.height = "100%";
			var new_height = document.getElementById(z).offsetHeight;
			new_height = new_height - 180;
			new_height = -1 * new_height;
			document.getElementById(z).style.marginBottom = new_height+"px";
			document.getElementById(x).style.zIndex = "15";
			document.getElementById(y).style.zIndex = "15";
			document.getElementById(z).style.zIndex = "15";
			document.getElementById(z).style.overflow = "none"

			var overflow_height = document.getElementById(x).offsetHeight;
			if(overflow_height > 320){
				document.getElementById(z).style.height = "400px";
				document.getElementById(x).style.height = "320px";
				document.getElementById(x).style.width = "196px";
				document.getElementById(z).style.overflowX = "hidden";
				document.getElementById(x).style.overflowY = "scroll";
				document.getElementById(z).style.marginBottom = "-220px";
			}
		}

function see_trunct_comment(box_num){
	var x = "see_all_convo_comments"+box_num;
	var y = "convo_comment"+box_num;
	var z = "main_box0"+box_num;
	document.getElementById(x).style.height = "0px";
	document.getElementById(y).style.height = "100px";
	document.getElementById(x).style.overflow = "hidden";
	document.getElementById(z).style.height = "180px";
	document.getElementById(z).style.marginBottom = "0px";
	document.getElementById(x).style.zIndex = "1";
	document.getElementById(y).style.zIndex = "1";
	document.getElementById(z).style.zIndex = "1";
	document.getElementById(z).style.overflow = "hidden";
}
***************/

function scroll_up(scroll_case){
	if(scroll_case == 2){
		var y = "everyone";
	}else if(scroll_case == 4){
		var y = "in_convs_case";
	}else if(scroll_case == 6){
		var y = "in_public_case";
	}else if(scroll_case == 1){
		var y = "contacts_case";
	}else if(scroll_case == 5){
		var y = "in_comments_case";
	}else if(scroll_case == 7){
		var y = "in_my_convs_case";
	}
	var z = parseInt(document.getElementById(y).style.marginTop);
	if(!z){
		z=0;
	}
	if(z <= 0){
		z = z + 30;
		document.getElementById(y).style.marginTop = z+"px";
		if(z > 0){
			document.getElementById(y).style.marginTop = "0px";
			//setTimeout(function(){scroll_up(box_num);}, 1000);
		}
	}
}


function scroll_up_comment(box_num){
	var y = "convo_comment"+box_num;
	var z = parseInt(document.getElementById(y).style.marginTop);
	if(z <= 0){
		z = z + 30;
		document.getElementById(y).style.marginTop = z+"px";
		if(z > 0){
			document.getElementById(y).style.marginTop = "0px";
			//setTimeout(function(){scroll_up(box_num);}, 1000);
		}
	}
}


function scroll_down(scroll_case){
	if(scroll_case == 2){
		var y = "everyone";
		x=460;
	}else if(scroll_case == 4){
		var y = "in_convs_case";
		x=460;
	}else if(scroll_case == 6){
		var y = "in_public_case";
		x=465;
	}else if(scroll_case == 1){
		var y = "contacts_case";
		x=520;
	}else if(scroll_case == 5){
		var y = "in_comments_case";
		x=470;
	}else if(scroll_case == 7){
		var y = "in_my_convs_case";
		x=465;
	}
	var z = parseInt(document.getElementById(y).style.marginTop);
	if(!z){
		z=0;
	}
	z = z - 20;
	var maxmarg = parseInt(document.getElementById(y).offsetHeight) - x;
	maxmarg = -1 * maxmarg;
	if(z > maxmarg){
		z = z - 30;
		document.getElementById(y).style.marginTop = z+"px";
		if(z < maxmarg){
			document.getElementById(y).style.marginTop = maxmarg+"px";
			//id = setTimeout("scroll_down(1)", 1000);
		}
	}
}


function scroll_down_comment(box_num){
	var y = "convo_comment"+box_num;
	var z = parseInt(document.getElementById(y).style.marginTop);
	var maxmarg = parseInt(document.getElementById(y).offsetHeight) -106;
	maxmarg = -1 * maxmarg;
	if(z > maxmarg){
		z = z - 30;
		document.getElementById(y).style.marginTop = z+"px";
		if(z < maxmarg){
			document.getElementById(y).style.marginTop = maxmarg+"px";
			//setTimeout(function(){scroll_down_comment(box_num)}, 1000);
		}
	}
}

/***********************************************************************************

functions using user_id

************************************************************************************/

function suggestSearchEveryone(){
	var text = document.getElementById('search_input').value;
	var user_id = user_id_js;

	var http = getXMLHttp();

	if(text.length == 0){
		http.open("POST", "includes/search_everyone.php", true);

		http.onreadystatechange = function(){
			if(http.readyState == 4 && http.status == 200){
				if(http.responseText.length > 0){
					document.getElementById('everyone').innerHTML = http.responseText;
					document.getElementById('everyone').style.marginTop = "0px";
				}
			}
		}

		http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		http.send("search="+search+"&user_id="+user_id+"&text="+text);
	}else{
		http.open("POST", "includes/search_everyone.php", true);

		http.onreadystatechange = function(){
			if(http.readyState == 4 && http.status == 200){
				if(http.responseText.length > 0){
					document.getElementById('everyone').innerHTML = http.responseText;
					document.getElementById('everyone').style.marginTop = "0px";
					document.getElementById('more_everyone').innerHTML = "";
				}else{
					document.getElementById('everyone').innerHTML = "<div id='no_matches'>sorry, no name matches this search</div>";
					document.getElementById('more_everyone').innerHTML = "";
				}
			}
		}

		http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		http.send("search="+search+"&user_id="+user_id+"&text="+text);
	}
}

function suggestSearchConversations(){
	var text = document.getElementById('search_input_conv').value;
	var user_id = user_id_js;

	var http = getXMLHttp();

	http.open("POST", "includes/search_conversations.php", true);

	http.onreadystatechange = function(){
		if(http.readyState == 4 && http.status == 200){
			if(http.responseText.length > 0){
				document.getElementById('pub_conversations_case_scroll').innerHTML = http.responseText;
			}else{
				document.getElementById('pub_conversations_case_scroll').innerHTML = "<div id='no_matches'>sorry, no public conversation matches this search</div>";
			}
		}
	}

	http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http.send("search="+search+"&user_id="+user_id+"&text="+text);
}

function connect(conn_user_id){
	var x = document.getElementById("connect"+conn_user_id);
	var y = user_id_js;
	var z = conn_user_id;

	var http = getXMLHttp();

	http.open("POST", "includes/contacts.php", true);

	http.onreadystatechange = function(){
		if(http.readyState == 4 && http.status == 200){
			if(http.responseText.length > 0){
				document.getElementById("connect"+conn_user_id).innerHTML = "";
				document.getElementById("request_sent_msg").innerHTML = http.responseText;
			}
		}
	}
			
	http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http.send("connect=true&user_id="+y+"&connect_user_id="+z);
}


function add_to_contacts(conn_user_id){
	var y = user_id_js;
	var z = conn_user_id;

	var http = getXMLHttp();

	http.open("POST", "includes/contacts.php", true);

	http.onreadystatechange = function(){
		if(http.readyState == 4 && http.status == 200){
			if(http.responseText.length > 0){
				document.getElementById("contact_list_case").innerHTML = http.responseText;
			}
		}
	}

	http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http.send("add=true&user_id="+y+"&connect_user_id="+z);
}

function ignore_contact(conn_user_id){
	var y = user_id_js;
	var z = conn_user_id;

	var http = getXMLHttp();

	http.open("POST", "includes/contacts.php", true);

	http.onreadystatechange = function(){
		if(http.readyState == 4 && http.status == 200){
			if(http.responseText.length > 0){
				document.getElementById("contact_list_case").innerHTML = http.responseText;
			}
		}
	}

	http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http.send("ignore=true&user_id="+y+"&connect_user_id="+z);
}

function go_to_user(conn_user_id){
	var y = user_id_js;
	var z = conn_user_id;

	var http = getXMLHttp();

	http.open("POST", "includes/conversations.php", true);

	http.onreadystatechange = function(){
		if(http.readyState == 4 && http.status == 200){
			if(http.responseText.length > 0){
				document.getElementById('new_comments_alert').innerHTML = "";
				document.getElementById("comment_section_nums").innerHTML = "";
				var data = http.responseText.split("[BRK]");
				document.getElementById("main_box01").style.height = "212px";
				document.getElementById("main_box01").style.marginTop = "-32px";
				document.getElementById("main_box01").style.marginBottom = "-2px";
				if(data.length == 2){
					document.getElementById("main_box01").innerHTML = data[0];
					document.getElementById("conversations_case_scroll").innerHTML = data[1];
				}else{
					document.getElementById("main_box01").innerHTML = data[0]+data[1];
					document.getElementById("conversations_case_scroll").innerHTML = data[2];
				}
				document.getElementById("main_box02").innerHTML = "";
				document.getElementById("main_box03").innerHTML = "";
				document.getElementById("main_box04").innerHTML = "";
				document.getElementById("main_box05").innerHTML = "";
				document.getElementById("main_box06").innerHTML = "";
				document.getElementById("main_box07").innerHTML = "";
				document.getElementById("main_box08").innerHTML = "";
				document.getElementById("main_box09").innerHTML = "";
			}
		}
	}

	http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http.send("go_to_user=true&user_id="+y+"&connect_user_id="+z);
}

function start_convo_private(conn_user_id){
	var y = user_id_js;
	var z = conn_user_id;

	var http = getXMLHttp();

	http.open("POST", "includes/conversations.php", true);

	http.onreadystatechange = function(){
		if(http.readyState == 4 && http.status == 200){
			if(http.responseText.length > 0){
				document.getElementById("main_box01").innerHTML = http.responseText;
			}
		}
	}

	http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http.send("private_conv=true&user_id="+y+"&connect_user_id="+z);
}

function start_convo_public(conn_user_id){
	var y = user_id_js;
	var z = conn_user_id;

	var http = getXMLHttp();

	http.open("POST", "includes/conversations.php", true);
			
	http.onreadystatechange = function(){
		if(http.readyState == 4 && http.status == 200){
			if(http.responseText.length > 0){
				document.getElementById("main_box02").innerHTML = http.responseText;
			}
		}
	}

	http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http.send("public_conv=true&user_id="+y+"&connect_user_id="+z);
}

function accept_public_conv(){
	var y = user_id_js;
	var first_comment = document.getElementById('first_comment').value;
	var first_comment = encodeURIComponent(first_comment);

	var http = getXMLHttp();

	http.open("POST", "includes/conversations.php", true);
	
	http.onreadystatechange = function(){
		if(http.readyState == 4 && http.status == 200){
			if(http.responseText.length > 0){
				var z = http.responseText;
				document.getElementById('conv_id').value = z;
				}
			go_to_public_conv(z);
			go_to_current_conv(z);
			document.getElementById("main_box02").innerHTML = "<div id='convo_select_contacts'>conversation started...post your comments</div>";
		}
	}

	http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http.send("start_public_conv=true&user_id="+y+"&first_comment="+first_comment);
}

function go_to_public_conv(conv_id){
	var x = conv_id;
	var y = user_id_js;
	document.getElementById('conv_id').value = conv_id;

	var http = getXMLHttp();

	http.open("POST", "includes/conversations.php", true);
	http.onreadystatechange = function(){
		if(http.readyState == 4 && http.status == 200){
			document.getElementById('new_comments_alert').innerHTML = "";
			go_to_conv_transcript(conv_id);
			if(http.responseText.length > 0){
				document.getElementById("pub_conversations_case_scroll").innerHTML = http.responseText;
			}else{
				document.getElementById("pub_conversations_case_scroll").innerHTML = "waiting for your comments...";
			}
		}
	}

	http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http.send("go_to_public_conv=true&conv_id="+x);
}

function set_invite_link(){
	var x = user_id_js;
	var y = document.getElementById('conv_id').value;
	var z = Math.floor(Math.random()*1001);
	var url = "http://localhost/shuffle/participate/index.php?parti=1&xt="+x+"&nv="+y+"&mr="+z;
	document.getElementById("invite_url").value = url;
}

function add_earlier_public_convs(user_id, num_rows, start_from){
	var x = user_id_js;
	var y = num_rows;
	var z = start_from;

	var http = getXMLHttp();

	http.open("POST", "includes/conversations.php", true);
	http.onreadystatechange = function(){
		if(http.readyState == 4 && http.status == 200){
			if(http.responseText.length > 0){
				var data = http.responseText.split("[BRK]");
				var div01 = document.getElementById("public_case").cloneNode();
				div01.innerHTML =  data[0];
				var div02 = document.getElementById("public_case");
				div02.insertBefore(div01, div01.nextSibling);
				document.getElementById("more_public_convs").innerHTML = data[1];
			}
		}
	}
	http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http.send("add_earlier_public_convs=true&user_id="+x+"&num_rows="+y+"&start_from="+z);
}

function add_earlier_my_convs(user_id,  start_from, end_at){
	var x = user_id_js;
	var y = start_from;
	var z = end_at;

	var http = getXMLHttp();

	http.open("POST", "includes/conversations.php", true);
	http.onreadystatechange = function(){
		if(http.readyState == 4 && http.status == 200){
			if(http.responseText.length > 0){
				var data = http.responseText.split("[BRK]");
				var div01 = document.getElementById("my_convs_case").cloneNode();
				div01.innerHTML =  data[0];
				var div02 = document.getElementById("my_convs_case");
				div02.insertBefore(div01, div01.nextSibling);
				document.getElementById("more_my_convs").innerHTML = data[1];
			}
		}
	}
	http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http.send("add_earlier_my_convs=true&user_id="+x+"&start_from="+y+"&end_at="+z);
}


function show_more_users(user_id,  num_rows, start_from){
	var x = user_id_js;
	var y = num_rows;
	var z = start_from;

	var http = getXMLHttp();

	http.open("POST", "includes/everyone.php", true);
	http.onreadystatechange = function(){
		if(http.readyState == 4 && http.status == 200){
			if(http.responseText.length > 0){
				var data = http.responseText.split("[BRK]");
				var div01 = document.getElementById("everyone_case").cloneNode();
				div01.innerHTML =  data[0];
				var div02 = document.getElementById("everyone_case");
				div02.insertBefore(div01, div01.nextSibling);
				document.getElementById("more_everyone").innerHTML = data[1];
			}
		}
	}

	http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http.send("show_more_users=true&user_id="+x+"&num_rows="+y+"&start_from="+z);
}

//refreshes "conversations by" panel
function go_to_current_conv(conv_id){
	var x = conv_id;
	var y = user_id_js;

	var http = getXMLHttp();

	http.open("POST", "includes/conversations.php", true);
	http.onreadystatechange = function(){
		if(http.readyState == 4 && http.status == 200){
			if(http.responseText.length > 0){
				document.getElementById("conversations_case_scroll").innerHTML = http.responseText;		
				highlight_poll(x);
			}else{
				document.getElementById("conversations_case_scroll").innerHTML = "waiting for your comments...";
				highlight_poll(x);
			}
		}
	}

	http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http.send("go_to_current_conv=true&conv_id="+x+"&user_id="+y);
}


function accept_post_comment(){
	var x = document.getElementById('conv_id').value;
	if(x > 0){
		var y = document.getElementById('comment').value;
		var y = encodeURIComponent(y);
		var z = user_id_js;
		var http = getXMLHttp();

		http.open("POST", "includes/conversations.php", true);

		http.onreadystatechange = function(){
			if(http.readyState == 4 && http.status == 200){
				if(http.responseText.length > 0){
					document.getElementById("comment").value = "";
					document.getElementById("comment").style.height = "20px";
					document.getElementById("post").style.marginTop = "0px";
				}
			}
		}
		http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		http.send("post_comment=true&conv_id="+x+"&comment="+y+"&user_id="+z);
	}else{
		document.getElementById("main_box02").innerHTML = "<div id='convo_select_contacts'>please join a conversation first, or start one youself.</div>";
		document.getElementById("comment").value = "";
		document.getElementById("comment").style.height = "20px";
		document.getElementById("post").style.marginTop = "0px";
	}
}


function track(conv_id){
	var x = conv_id;
	var y = user_id_js;

	var track_id = "track"+conv_id;

	var http = getXMLHttp();

	http.open("POST", "includes/conversations.php", true);

	http.onreadystatechange = function(){
		if(http.readyState == 4 && http.status == 200){
			if(http.responseText.length > 0){
				document.getElementById(track_id).innerHTML = http.responseText;
			}
		}
	}

	http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http.send("track=true&conv_id="+x+"&user_id="+y);
}


function pub_track(conv_id){
	var x = conv_id;
	var y = user_id_js;

	var track_id = "pub_track"+conv_id;

	var http = getXMLHttp();

	http.open("POST", "includes/conversations.php", true);

	http.onreadystatechange = function(){
		if(http.readyState == 4 && http.status == 200){
			if(http.responseText.length > 0){
				document.getElementById(track_id).innerHTML = http.responseText;
			}
		}
	}

	http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http.send("pub_track=true&conv_id="+x+"&user_id="+y);
}


//onclick on change profile pic: displays upload input field
function change_profile_pic(){
	var x = user_id_js;
	var http = getXMLHttp();
	http.open("POST", "includes/conversations.php", true);
	http.onreadystatechange = function(){
		if(http.readyState == 4 && http.status == 200){
			document.getElementById("main_box02").innerHTML = http.responseText;
			document.getElementById("main_box02").style.minWidth = "180px";
			check_profile_src();
		}
	}

	http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http.send("change_profile_pic=true&user_id="+x);
}


//onclick on save for upload profile pic: uploads image
function profile_pic_upload(){
	document.getElementById("main_box02").innerHTML = "<div style='float:left'><img src='images/ajax-loader.gif' /></div>";
	var x = user_id_js;
	var y = document.getElementById("profile_pic_upload_field").innerHTML;

	var http = getXMLHttp();

	http.open("POST", "includes/image_upload.php", true);
	http.onreadystatechange = function(){
		if(http.readyState == 4 && http.status == 200){
			document.getElementById("main_box02").innerHTML = http.responseText;
			document.getElementById("main_box02").style.minWidth = "180px";
		}
	}
	http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http.send("upload_profile_pic=true&user_id="+x+"&profile_pic_upload_field="+y);
}


//checks every 3 secs to see if profile pic is changed: if it has changed, the new pic is displayed
function check_profile_src(){
	var x = user_id_js;
	var y = document.getElementById("profile_src").value;

	var http = getXMLHttp();
			
	http.open("POST", "includes/conversations.php", true);
	http.onreadystatechange = function(){
		if(http.readyState == 4 && http.status == 200){
			if(http.responseText.length > 0){
				document.getElementById("main_box01").innerHTML = http.responseText;
				document.getElementById("main_box02").innerHTML = "<div id='convo_select_contacts'>image saved...</div>";
			}else{
				setTimeout("check_profile_src()", 3000);
			}
		}
	}

	http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http.send("check_profile_src=true&user_id="+x+"&profile_pic="+y);
}


//onclick on change profile pic: displays upload input field
function add_image(){
	var x = user_id_js;
	var http = getXMLHttp();
	http.open("POST", "includes/conversations.php", true);
	http.onreadystatechange = function(){
		if(http.readyState == 4 && http.status == 200){
			document.getElementById("main_box03").innerHTML = http.responseText;
			document.getElementById("main_box03").style.minWidth = "180px";
			check_profile_src();
		}
	}

	http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http.send("add_image=true&user_id="+x);
}


function how_to_take_poll(){
	document.getElementById("main_box03").innerHTML = "<div id='return' style='float: right' onClick='clear_poll_info()' title='return'></div>add <b>poll:</b> to the beginning of you poll question and put <b>##</b> before each option<br /><br />e.g. poll: which makes the best pet? ##lamas ##dogs ##cats";
}


function clear_poll_info(){
	document.getElementById("main_box03").innerHTML = "";
}


function vote(poll_option_id, conv_id, num_of_options){
	var x = user_id_js;
	var y = poll_option_id;
	var z = conv_id;
	var num_opts = num_of_options;
	
	var http = getXMLHttp();
			
	http.open("POST", "includes/conversations.php", true);
	http.onreadystatechange = function(){
		if(http.readyState == 4 && http.status == 200){
			if(http.responseText.length > 0){
				var poll_stats = http.responseText.split("[BRK]");
				document.getElementById("options_case").innerHTML = poll_stats[0];
				document.getElementById("main_box05").innerHTML = poll_stats[1];
			}
		}
	}

	http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http.send("vote=true&user_id="+x+"&poll_option_id="+y+"&conv_id="+z+"&num_of_options="+num_opts);
}