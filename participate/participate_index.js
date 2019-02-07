
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

			http.open("POST", "../includes/conversations.php", true);

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

		function go_to_conv(conv_id){
			var start_from = 0;
			go_to_comments(conv_id, start_from);
			go_to_current_conv(conv_id);
			go_to_conv_transcript(conv_id);
			display_comment_section_nums(conv_id);
		}

		function join_current_conv(conv_id){
			var start_from = 0;
			go_to_comments(conv_id, start_from);
			go_to_conv_transcript(conv_id);
			display_comment_section_nums(conv_id);
		}

		function reveal_new_comments(conv_id, start_from, num_rows){
			go_to_participate_comments(conv_id, start_from);
			display_comment_section_nums(conv_id);
			add_new_comments(conv_id, num_rows);
			set_comment_id(conv_id);
		}

		function add_new_comments(conv_id,  num_rows){
			var x = conv_id;
			var y = num_rows;

			var http = getXMLHttp();

			http.open("POST", "../includes/conversations.php", true);
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

		//jumps to a set of 9 comments
		function go_to_comments(conv_id, start_from){
			var x = conv_id;
			var y = start_from;
			document.getElementById('conv_id').value = conv_id;

			var http = getXMLHttp();

			http.open("POST", "../includes/conversations.php", true);
			http.onreadystatechange = function(){
				if(http.readyState == 4 && http.status == 200){
					//place all comments under current conversation i.e. transcript
					document.getElementById('new_comments_alert').innerHTML = "";

					if(http.responseText.length > 0){
						var comments = http.responseText.split("[BRK]");

						document.getElementById("main_box01_participate").style.height = "180px";
						document.getElementById("main_box01_participate").style.marginTop = "0px";
						document.getElementById("main_box01_participate").style.marginBottom = "0px";
						document.getElementById("main_box01_participate").innerHTML = "";
						document.getElementById("main_box02").innerHTML = "";
						document.getElementById("main_box03").innerHTML = "";
						document.getElementById("main_box04").innerHTML = "";
						document.getElementById("main_box05").innerHTML = "";
						document.getElementById("main_box06").innerHTML = "";
						document.getElementById("main_box07").innerHTML = "";
						document.getElementById("main_box08").innerHTML = "";
						document.getElementById("main_box09").innerHTML = "";

						if(comments[0].length > 0){
							document.getElementById("main_box01_participate").innerHTML  = comments[0];
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
						document.getElementById("main_box01_participate").style.height = "180px";
						document.getElementById("main_box01_participate").style.marginTop = "0px";
						document.getElementById("main_box01_participate").style.marginBottom = "0px";
						document.getElementById("main_box01_participate").innerHTML = "<div id='convo_comment'>no comments yet<br /><br />you are welcome <br />to make the first comment...</div>";
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


		//jumps to a set of 9 comments for participators
		function go_to_participate_comments(conv_id, start_from){
			var x = conv_id;
			var y = start_from;
			document.getElementById('conv_id').value = conv_id;

			var http = getXMLHttp();

			http.open("POST", "../includes/conversations.php", true);
			http.onreadystatechange = function(){
				if(http.readyState == 4 && http.status == 200){
					//place all comments under current conversation i.e. transcript
					document.getElementById('new_comments_alert').innerHTML = "";

					if(http.responseText.length > 0){
						var comments = http.responseText.split("[BRK]");

						document.getElementById("main_box01_participate").style.height = "180px";
						document.getElementById("main_box01_participate").style.marginTop = "0px";
						document.getElementById("main_box01_participate").style.marginBottom = "0px";
						document.getElementById("main_box01_participate").innerHTML = "";
						document.getElementById("main_box02").innerHTML = "";
						document.getElementById("main_box03").innerHTML = "";
						document.getElementById("main_box04").innerHTML = "";
						document.getElementById("main_box05").innerHTML = "";
						document.getElementById("main_box06").innerHTML = "";
						document.getElementById("main_box07").innerHTML = "";
						document.getElementById("main_box08").innerHTML = "";
						document.getElementById("main_box09").innerHTML = "";

						if(comments[0].length > 0){
							document.getElementById("main_box01_participate").innerHTML  = comments[0];
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
						
						set_comment_id(conv_id);
					}else{
						document.getElementById("main_box01_participate").style.height = "180px";
						document.getElementById("main_box01_participate").style.marginTop = "0px";
						document.getElementById("main_box01_participate").style.marginBottom = "0px";
						document.getElementById("main_box01_participate").innerHTML = "<div id='convo_comment'>no comments yet<br /><br />you are welcome <br />to make the first comment...</div>";
						document.getElementById("main_box02").innerHTML = "";
						document.getElementById("main_box03").innerHTML = "";
						document.getElementById("main_box04").innerHTML = "";
						document.getElementById("main_box05").innerHTML = "";
						document.getElementById("main_box06").innerHTML = "";
						document.getElementById("main_box07").innerHTML = "";
						document.getElementById("main_box08").innerHTML = "";
						document.getElementById("main_box09").innerHTML = "";
						
						set_comment_id(conv_id);
					}
				}
			}

			http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			http.send("go_to_participate_comments=true&conv_id="+x+"&start_from="+y);
		}


function display_participate_comment_section_nums(conv_id, start_from, num_rows){
	var x = conv_id;
	var y = start_from;
	var z = num_rows;

	var http = getXMLHttp();

	http.open("POST", "../includes/conversations.php", true);
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
	http.send("display_participate_comment_section_nums=true&conv_id="+x+"&start_from="+y+"&num_rows="+z);
}


		function display_participate_comment_section_nums(conv_id){
			var x = conv_id;

			var http = getXMLHttp();

			http.open("POST", "../includes/conversations.php", true);
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
			http.send("display_participate_comment_section_nums=true&conv_id="+x);
		}


		function set_comment_id(conv_id){
			var x = conv_id;

			var http = getXMLHttp();

			http.open("POST", "../includes/conversations.php", true);

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
			var x = document.getElementById('conv_id').value;
			if(x > 0){
				var y = document.getElementById('comment').value;
				var z = anonymous_id_js;

				var http = getXMLHttp();

				http.open("POST", "../includes/conversations.php", true);

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


		function scroll_up_comment(box_num){
			var y = "convo_comment"+box_num;
			var z = parseInt(document.getElementById(y).style.marginTop);
			if(z <= 0){
				z = z + 30;
				document.getElementById(y).style.marginTop = z+"px";
				if(z > 0){
					document.getElementById(y).style.marginTop = "0px";
					//setTimeout(function(){scroll_up_comment(box_num);}, 1000);
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


		function participate_signup(){
			var http = getXMLHttp();

			http.open("POST", "../includes/signup.php", true);

			http.onreadystatechange = function(){
				if(http.readyState == 4 && http.status == 200){
					//document.getElementById('welcome_box06').innerHTML = "<div id='signup_note'>signing up...</div>";
					document.getElementById('participate_signup_signin').style.marginTop = "-100px";
					document.getElementById('participate_signup_signin').style.marginBottom = "-100px";
					document.getElementById('participate_signup_signin').style.width = "200px";
					document.getElementById('participate_signup_signin').style.height = "240px";
					document.getElementById('participate_signup_signin').innerHTML = http.responseText;
				}
			}

			http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			http.send("participate_signup="+true);
		}


		function participate_signin(){
			var http = getXMLHttp();

			http.open("POST", "../includes/signin.php", true);

			http.onreadystatechange = function(){
				if(http.readyState == 4 && http.status == 200){
					document.getElementById('participate_signup_signin').style.marginTop = "-100px";
					document.getElementById('participate_signup_signin').style.marginBottom = "-100px";
					document.getElementById('participate_signup_signin').style.width = "80px";
					document.getElementById('participate_signup_signin').style.height = "140px";
					document.getElementById('participate_signup_signin').innerHTML = http.responseText;
				}
			}

			http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			http.send("participate_signin="+true);
		}


		function participate_signin_err(){
			var http = getXMLHttp();

			http.open("POST", "../includes/signin.php", true);

			http.onreadystatechange = function(){
				if(http.readyState == 4 && http.status == 200){
					document.getElementById('participate_signup_signin').style.marginTop = "-100px";
					document.getElementById('participate_signup_signin').style.marginBottom = "-100px";
					document.getElementById('participate_signup_signin').style.width = "80px";
					document.getElementById('participate_signup_signin').style.height = "140px";
					document.getElementById('participate_signup_signin').innerHTML = http.responseText;
				}
			}

			http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			http.send("participate_signin_err="+true);
		}


		function forget_pass(){
			var http = getXMLHttp();

			http.open("POST", "../includes/signin.php", true);

			http.onreadystatechange = function(){
				if(http.readyState == 4 && http.status == 200){
					document.getElementById('participate_signup_signin').innerHTML = http.responseText;
				}
			}

			http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			http.send("forget_pass="+true);
		}


		function retrieve_pass(user_email){
			var user_email = document.getElementById(user_email).value;
			var http = getXMLHttp();

			http.open("POST", "../includes/signin.php", true);

			http.onreadystatechange = function(){
				if(http.readyState == 4 && http.status == 200){
					document.getElementById('participate_signup_signin').innerHTML = http.responseText;
				}
			}

			http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			http.send("retrieve_pass=true&user_email="+user_email);
		}


		function participate_close_signup(){
			document.getElementById('participate_signup_signin').style.marginTop = "-20px";
			document.getElementById('participate_signup_signin').style.marginBottom = "-90px";
			document.getElementById('participate_signup_signin').style.width = "80px";
			document.getElementById('participate_signup_signin').style.height = "0px";
			document.getElementById('participate_signup_signin').innerHTML = "<div id='participate_signup_lbl'><a onClick='participate_signup()'>sign up</a></div>or<div id='participate_signin_lbl'><a onClick='participate_signin()'>sign in</a></div>";
		}


		function textVanish(name){
		var x = document.getElementById(name);
			if(x.value == "username or email"){
			 	x.value = "";
			 	x.style.color = "#000";
			 }

			if(x.value == "username"){
			 	x.value = "";
			 	x.style.color = "#000";
			 }

			if(x.value == "password"){
				x.value = "";
				x.type = "password"
			 	x.style.color = "#000";
			}

			if(x.value == "last name"){
			 	x.value = "";
			 	x.style.color = "#000";
			 }

			if(x.value == "first name"){
			 	x.value = "";
			 	x.style.color = "#000";
			 }

			if(x.value == "email"){
			 	x.value = "";
			 	x.style.color = "#000";
			 }

			if(x.value == "city"){
			 	x.value = "";
			 	x.style.color = "#000";
			 }

			if(x.value == "country"){
			 	x.value = "";
			 	x.style.color = "#000";
			 }

		}

		function ueTextAppear(){
			if(document.getElementById('username_email').value == ""){
			 	document.getElementById('username_email').value = "username or email";
			 	document.getElementById('username_email').style.color = "#888";
			}

			if(document.getElementById('password').value == ""){
			 	document.getElementById('password').value = "password";
			 	document.getElementById('password').style.color = "#888";
			 	document.getElementById('password').type = "text";
			}
		}


		function textAppear(){
			if(document.getElementById('username').value == ""){
			 	document.getElementById('username').value = "username";
			 	document.getElementById('username').style.color = "#888";
			}

			if(document.getElementById('password').value == ""){
			 	document.getElementById('password').value = "password";
			 	document.getElementById('password').style.color = "#888";
			 	document.getElementById('password').type = "text";
			}

			if(document.getElementById('fname').value == ""){
			 	document.getElementById('fname').value = "first name";
			 	document.getElementById('fname').style.color = "#888";
			}

			if(document.getElementById('lname').value == ""){
			 	document.getElementById('lname').value = "last name";
			 	document.getElementById('lname').style.color = "#888";
			}

			if(document.getElementById('email').value == ""){
			 	document.getElementById('email').value = "email";
			 	document.getElementById('email').style.color = "#888";
			}

			if(document.getElementById('city').value == ""){
			 	document.getElementById('city').value = "city";
			 	document.getElementById('city').style.color = "#888";
			}

			if(document.getElementById('country').value == ""){
			 	document.getElementById('country').value = "country";
			 	document.getElementById('country').style.color = "#888";
			}

		}


function checkUsername(){
	var user = document.getElementById('username').value;
	var http = getXMLHttp();

	http.open("POST", "../includes/check_signup.php", true);

	http.onreadystatechange = function(){
		if(http.readyState == 4 && http.status == 200){
			if(http.responseText == "exists"){
				document.getElementById('username_err').innerHTML = "<div id='signup_err'>username is taken!</div>";
				document.getElementById('username').style.border = "1px solid orange";
				document.getElementById('check_username').value = "no";
			}else if(http.responseText == "none"){
				document.getElementById('username_err').innerHTML = "";
				//document.getElementById('username').style.border = "1px solid orange";
				document.getElementById('check_username').value = "no";
			}else if(http.responseText == "cool!"){
				document.getElementById('username_err').innerHTML = "";
				document.getElementById('username').style.border = "1px solid #afc";
				if(checkChars("username", "username_err")){
					document.getElementById('check_username').value = "yes";
				}
			}
		}
	}

	http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http.send("check_username=true&username="+user);
}

function checkEmail(){
	var email = document.getElementById('email').value;
	var http = getXMLHttp();

	http.open("POST", "../includes/check_signup.php", true);

	http.onreadystatechange = function(){
		if(http.readyState == 4 && http.status == 200){
			if(http.responseText == "exists"){
				document.getElementById('email_err').innerHTML = "<div id='signup_err'>email already in use!</div>";
				document.getElementById('email').style.border = "1px solid orange";
				document.getElementById('check_email').value = "no";
			}else if(http.responseText == "none"){
				document.getElementById('email_err').innerHTML = "";
				//document.getElementById('email').style.border = "1px solid orange";
				document.getElementById('check_email').value = "no";
			}else{
				var check_email = document.getElementById('email').value;
				if(check_email.match(/^[a-zA-Z0-9 _@.]+$/) && check_email.match(/@/) && check_email.match(/\./)){
					if(check_email.match(/^[_0-9]/)){
						document.getElementById("email_err").innerHTML = "<div id='signup_err'>start with letters only please!</div>";
						document.getElementById("email").style.border = "1px solid orange";
					}else{
						document.getElementById("email_err").innerHTML = "";
						document.getElementById("email").style.border = "1px solid #afc";
						document.getElementById('check_email').value = "yes";
					}
				}else if(!check_email.match(/^[a-zA-Z0-9 _@.]+$/)){
					document.getElementById("email_err").innerHTML = "<div id='signup_err'>email is invalid</div>";
					document.getElementById("email").style.border = "1px solid orange";
				}else{
					document.getElementById("email_err").innerHTML = "<div id='signup_err'>email is invalid</div>";
					document.getElementById("email").style.border = "1px solid orange";
				}
			}
		}
	}

	http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http.send("check_email=true&email="+email);
}

function checkChars(input_txt, char_err){
	var inp_txt = document.getElementById(input_txt).value;
	if(inp_txt.match(/^[a-zA-Z0-9 _,]+$/)){
		if(inp_txt.match(/^[_0-9]/)){
			document.getElementById(char_err).innerHTML = "<div id='signup_err'>start with letters only please!</div>";
			document.getElementById(input_txt).style.border = "1px solid orange";
		}else if(!inp_txt.match(/^first name/) && !inp_txt.match(/^last name/) && !inp_txt.match(/^username/) && !inp_txt.match(/^password/) && !inp_txt.match(/^email/) && !inp_txt.match(/^city/) && !inp_txt.match(/^country/)){
			document.getElementById(char_err).innerHTML = "";
			document.getElementById(input_txt).style.border = "1px solid #afc";
			return true;
		}
	}else if(!inp_txt.match(/^[a-zA-Z0-9 _,]+$/)){
		if(!inp_txt.match(/^first name/) && !inp_txt.match(/^last name/) && !inp_txt.match(/^username/) && !inp_txt.match(/^password/) && !inp_txt.match(/^email/) && !inp_txt.match(/^city/) && !inp_txt.match(/^country/)){
			if(inp_txt.match(/^email/) && !inp_txt.match(/^@./)){
				document.getElementById(char_err).innerHTML = "<div id='signup_err'>letters and numbers please</div>";
				document.getElementById(input_txt).style.border = "1px solid orange";
			}
		}
	}else{
		document.getElementById(char_err).innerHTML = "";
	}
}


		function participate_register(){
			var pass = document.getElementById('password').value;
			var fname = document.getElementById('fname').value;
			var day = document.getElementById('day').value;
			var month = document.getElementById('month').value;
			var year = document.getElementById('year').value;
			var city = document.getElementById('city').value;
			var country = document.getElementById('country').value;
			var agreeterms = document.getElementById('agreeterms').checked;
			var check = 0;

			if(pass != "password" && fname != "first name" && day != "Day" && day != "" && month != "Month" && month != "" && year != "Year" && year != "" && city != "city" && country != "country"){
				check = check+1;
			}

			if(checkChars("password", "password_err") && checkChars("fname", "name_err") && checkChars("city", "city_country_err") && checkChars("country", "city_country_err")){
				check = check+1;
			}

			if(document.getElementById('check_username').value == "yes" && document.getElementById('check_email').value == "yes" && check == 2){
				if(!agreeterms){
					document.getElementById('agreeterms_err').innerHTML = "<div id='signup_err'>you need to agree to the terms and conditions</div>";
				}else{
					accept_register();
				}
			}else{
				document.getElementById('signup_err_top').innerHTML = "<div id='err'>please correct all errors</div>";
			}

		}


/************************************************************************************************

functions that use conv_id

************************************************************************************************/

		function accept_register(){
			var http = getXMLHttp();

			var conv_id = conv_id_js;
			var user = document.getElementById('username').value;
			var pass = document.getElementById('password').value;
			var fname = document.getElementById('fname').value;
			var lname = document.getElementById('lname').value;
			if(lname == "last name"){
				lname = "";
			}
			var email = document.getElementById('email').value;
			var gender = document.getElementById('gender').value;
			var day = document.getElementById('day').value;
			var month = document.getElementById('month').value;
			var year = document.getElementById('year').value;
			var city = document.getElementById('city').value;
			var country = document.getElementById('country').value;
			var agreeterms = document.getElementById('agreeterms').checked;

			http.open("POST", "../includes/signup.php", true);

			http.onreadystatechange = function(){
				if(http.readyState == 4 && http.status == 200){
					if(http.responseText.length > 0){
						document.getElementById('participate_signup_signin').style.marginTop = "-20px";
						document.getElementById('participate_signup_signin').style.marginRight = "-70px";
						document.getElementById('participate_signup_signin').style.marginLeft = "-40px";
						document.getElementById('participate_signup_signin').style.width = "260px";
						document.getElementById('participate_signup_signin').innerHTML = "<div id='confirm_link'>great! finally a confirmation link has been sent to you email "+http.responseText+" <br /><br />click on it to confirm you account and get started.</div>";
					}
				}
			}

			http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			http.send("participate_register=true&conv_id="+conv_id+"&user="+user+"&pass="+pass+"&fname="+fname+"&lname="+lname+"&email="+email+"&gender="+gender+"&day="+day+"&month="+month+"&year="+year+"&city="+city+"&country="+country+"&agreeterms="+agreeterms);
		}