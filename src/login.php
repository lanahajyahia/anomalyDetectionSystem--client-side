<?php
include('registration-functions.php');
if (isset($_SESSION["captcha-show"])) {
	if ($_SESSION["captcha-show"] > 2) {
		echo '<style type="text/css">
	.elem-group {
		display: block !important;
	}
	</style>';
	}
}

?>

<!DOCTYPE html>
<html>

<head>
	<title>Log In</title>
	<link rel="stylesheet" type="text/css" href="admin/css/register-style.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
</head>

<body class="login">
	<div class="_8esk">
		<div class="_8esl">
			<div class="_8ice">
				<h1 style="font-family: 'Trebuchet MS', sans-serif;">
					Anomaly Detection System</h1>
			</div>
			<h2 style="font-family: 'Trebuchet MS', sans-serif;" class="login-text">Secure and monitor your web applications against Cross-site scripting and SQL injections.</h2>
		</div>
		<div class="formdiv">
			<form method="post" action="login.php">

				<?php echo display_error(); ?>

				<div class="input-group">
					<input type="text" name="username" placeholder="Username or Email">
				</div>
				<div class="input-group">
					<input type="password" name="password" placeholder="Password">
				</div>
				<div class="elem-group" style="display: none">

					<img src="captcha.php" alt="CAPTCHA" class="captcha-image"><i class="fa fa-refresh fa_custom fa-2x"></i>
					<br>
					<input type="text" id="captcha" name="captcha_challenge" pattern="[A-Z1-9]{6}">
				</div>
				<div class="input-group">
					<button type="submit" class="btn" name="login_btn">Log In</button>
				</div>

			</form>
		</div>
	</div>
</body>



</html>
<script>
	var refreshButton = document.querySelector(".fa-refresh");
	refreshButton.onclick = function() {
		document.querySelector(".captcha-image").src = 'captcha.php?' + Date.now();
	}
</script>