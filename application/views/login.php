<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel = "stylesheet" type = "text/css" href = "<?php echo base_url('static/css/style.css')?>" />
</head>
<body>
	<div class="main">
		<div class="card-login">
		        <img src="<?php echo base_url('static/images/logo.png');?>" width="100" height="100"/>
		        <h1>Magic Number</h1>
		        <p>Please provide your mobile number</p>
				<form method ="POST" action="">
				  <label for="fphonenumber">Phone Number:</label>
				  <input type="tel" id="mobile_number" name="mobile_number" placeholder="+2567...">
				  <input type="submit" value="Submit">
				</form> 
	</div>
	</div>
</body>
</html>