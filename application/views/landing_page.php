<!DOCTYPE html>
<html>
<head>
	<title>Magic Number</title>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel = "stylesheet" type = "text/css" href = "<?php echo base_url('static/css/style.css')?>" />
</head>
<body>
	<div class="main">
		<div class="card">
		<img src="<?php echo base_url('static/images/logo.png');?>" width="100" height="100"/>
		<h1>Magic Number</h1>
		<p>Many people are winning money playing this game. Play this simple game and earn some money just by
		telling us the number we are going to show next.</p>
		<p>Click here to login to play.</p>
		<form action="/auth/login">
		    <button class="button" type="submit">Login</button>
	    </form>
	</div>
	</div>
</body>
</html>