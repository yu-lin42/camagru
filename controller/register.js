window.onload = () => {
	document.getElementById("submit").addEventListener('click', submit);
}

function submit()
{
	var request = new XMLHttpRequest();
	request.onreadystatechange = function () {
		if (request.readyState == 4)
		{
			var div = document.getElementById("errors");
			div.innerHTML = "";
			div.style.display = "none";
			if (request.status == 400)
			{
				div.style.display = "block";
				var errors = request.responseText.split(":");
				errors.forEach((err) => {
					if (err)
					{
						div.innerHTML += err + "<br/>";
					}
				});
			}
			if (request.status == 200)
			{
				var registerbox = document.getElementById("registerbox");
				registerbox.style.display = "none";
				var success = document.getElementById("form");
				success.innerHTML = "An email has been sent for verification.";
				div.style.display = "none";
				//hide form
				//show success
			}
			console.log(request.responseText);
		}
	}
	var username = document.getElementById("username");
	var email = document.getElementById("email");
	var password = document.getElementById("password");
	var confirm = document.getElementById("confirm");

	if (confirm.value !== password.value)
	{
		var div = document.getElementById("errors");
		div.style.display = "block";
		div.innerHTML = "";
		div.innerHTML += "Passwords don\'t match" + "<br/>";
		return;
	}
	request.open("POST", "/" + server_location + "/model/newuser.php");
	request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded")
	request.send(
		"mail=" + email.value +
		"&usr=" + username.value +
		"&pwd=" + password.value +
		"&pwd-repeat=" + confirm.value
	);
}