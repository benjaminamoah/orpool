		function getXMLHttp(){
			if(window.XMLHttpRequest){
				return new XMLHttpRequest();
			}else if(window.ActiveXObject){
				return new ActiveXObject("Microsoft.XMLHTTP");
			}else{
				alert("Sorry, Your browser does not support Ajax!");
			}
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

	http.open("POST", "includes/check_signup.php", true);

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

	http.open("POST", "includes/check_signup.php", true);

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
	if(inp_txt.match(/^[a-zA-Z0-9 _]+$/)){
		if(inp_txt.match(/^[_0-9]/)){
			document.getElementById(char_err).innerHTML = "<div id='signup_err'>start with letters only please!</div>";
			document.getElementById(input_txt).style.border = "1px solid orange";
		}else if(!inp_txt.match(/^first name/) && !inp_txt.match(/^last name/) && !inp_txt.match(/^username/) && !inp_txt.match(/^password/) && !inp_txt.match(/^email/) && !inp_txt.match(/^city/) && !inp_txt.match(/^country/)){
			document.getElementById(char_err).innerHTML = "";
			document.getElementById(input_txt).style.border = "1px solid #afc";
			return true;
		}
	}else if(!inp_txt.match(/^[a-zA-Z0-9 _]+$/)){
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

		function signup(){
			var http = getXMLHttp();

			http.open("POST", "includes/signup.php", true);

			http.onreadystatechange = function(){
				if(http.readyState == 4 && http.status == 200){
					document.getElementById('welcome_box06').innerHTML = "<div id='signup_note'>signing up...</div>";
					document.getElementById('welcome_box05').style.marginTop = "-100px";
					document.getElementById('welcome_box05').style.marginBottom = "-100px";
					document.getElementById('welcome_box05').innerHTML = http.responseText;
				}
			}

			http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			http.send("signup="+true);
		}


		function forget_pass(){
			var http = getXMLHttp();

			http.open("POST", "includes/signin.php", true);

			http.onreadystatechange = function(){
				if(http.readyState == 4 && http.status == 200){
					document.getElementById('welcome_box06').innerHTML = "<div id='signup_note'>type in your username or email...</div>";
					document.getElementById('welcome_box05').innerHTML = http.responseText;
				}
			}

			http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			http.send("forget_pass="+true);
		}


		function retrieve_pass(user_email){
			var user_email = document.getElementById(user_email).value;
			var http = getXMLHttp();

			http.open("POST", "includes/signin.php", true);

			http.onreadystatechange = function(){
				if(http.readyState == 4 && http.status == 200){
					document.getElementById('welcome_box06').innerHTML = "<div id='signup_note'>password retrieved...</div>";
					document.getElementById('welcome_box05').innerHTML = http.responseText;
				}
			}

			http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			http.send("retrieve_pass=true&user_email="+user_email);
		}


		function close_signup(){
			var http = getXMLHttp();

			http.open("POST", "includes/signup.php", true);

			http.onreadystatechange = function(){
				if(http.readyState == 4 && http.status == 200){
					var data = http.responseText.split("[BRK]");
					document.getElementById('welcome_box06').innerHTML = "<div id='signup_note'>signing up...</div>";
					document.getElementById('welcome_box05').style.marginTop = "-20px";
					document.getElementById('welcome_box05').style.marginBottom = "-80px";
					document.getElementById('welcome_box05').innerHTML = data[0];
					document.getElementById('welcome_box06').innerHTML = data[1];
				}
			}

			http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			http.send("close_signup="+true);
		}


		function signupdetail(name){
			var x = document.getElementById(name);

			if(name == "fname"){
				document.getElementById('welcome_box06').innerHTML = "<div id='or'>your <b>first name</b>. this will be on your profile page and displayed with your posts</div>";
			}

			if(name == "lname"){
				document.getElementById('welcome_box06').innerHTML = "<div id='or'>your <b>last name</b>. it would also be on your profile page and displayed with your posts. this is <b>not required</b></div>";
			}

			if(name == "username"){
				document.getElementById('welcome_box06').innerHTML = "<div id='or'>your <b>username</b>. this may be displayed with your posts.</div>";
			}

			if(name == "password"){
				document.getElementById('welcome_box06').innerHTML = "<div id='or'>choose a good <b>password</b>. it may contain numbers, upper and lower case letters</div>";
			}

			if(name == "email"){
				document.getElementById('welcome_box06').innerHTML = "<div id='or'>your <b>email</b>. in case you forget your password, it will be sent to this email address</div>";
			}

			if(name == "gender"){
				document.getElementById('welcome_box06').innerHTML = "<div id='or'>are you <b>male</b> or <b>female</b>?  this is <br /><b>not required</b></div>";
			}

			if(name == "day"){
				document.getElementById('welcome_box06').innerHTML = "<div id='or'>your <b>date of birth</b>. on what <b>day</b> of the month were you born?</div>";
			}

			if(name == "month"){
				document.getElementById('welcome_box06').innerHTML = "<div id='or'>your <b>date of birth</b>. in which <b>month</b> were you born?</div>";
			}

			if(name == "year"){
				document.getElementById('welcome_box06').innerHTML = "<div id='or'>your <b>date of birth</b>. in which <b>year</b> were you born?</div>";
			}

			if(name == "city"){
				document.getElementById('welcome_box06').innerHTML = "<div id='or'>your <b>city</b></div>";
			}

			if(name == "country"){
				document.getElementById('welcome_box06').innerHTML = "<div id='or'>your <b>country</b></div>";
			}

		}

		function register(){
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
		
		function accept_register(){
			var http = getXMLHttp();

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

			http.open("POST", "includes/signup.php", true);

			http.onreadystatechange = function(){
				if(http.readyState == 4 && http.status == 200){
					if(http.responseText.length > 0){
						document.getElementById('welcome_box06').innerHTML = "<div id='signup_note'>one final step...</div>";
						document.getElementById('welcome_box05').style.marginTop = "-20px";
						document.getElementById('welcome_box05').style.marginRight = "-70px";
						document.getElementById('welcome_box05').style.marginLeft = "-40px";
						document.getElementById('welcome_box05').style.width = "310px";
						document.getElementById('welcome_box05').innerHTML = "<div id='confirm_link'>great! finally a confirmation link has been sent to your email "+http.responseText+" <br /><br />click on it to confirm your account and get started.</div>";
					}
				}
			}

			http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			http.send("register=true&user="+user+"&pass="+pass+"&fname="+fname+"&lname="+lname+"&email="+email+"&gender="+gender+"&day="+day+"&month="+month+"&year="+year+"&city="+city+"&country="+country+"&agreeterms="+agreeterms);
		}