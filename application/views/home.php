<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Bodamovies | Sign In</title>
  <!-- Compiled and minified CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.96.1/css/materialize.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

  <!-- Compiled and minified JavaScript -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script> 
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.96.1/js/materialize.min.js"></script>
          
</head>
<body>
	<div class="container">
     <div class="row">
      <div class="col s12 m6 offset-m3 offset-13">
          <div align="center">
		        <h2> Bodamovies </h2>
	       </div>
      </div>
    </div>    
		<div class="row">
<!-- 	<a class="waves-effect waves-light btn-large">By:-Bhargav</a> -->
			<div class="col s12 m6 offset-m3 l6 offset-l3">
         <div align="center">
           <?php 
            if($this->session->userdata('sess_logged_in')==0){?>
             <a href="<?=$google_login_url?>" class="waves-effect waves-light btn red"><i class="fa fa-google left"></i>Google login</a>
            <?php }else{?>
              <a href="<?=base_url()?>auth2/logout" class="waves-effect waves-light btn red"><i class="fa fa-google left"></i>Google logout</a>
            <?php }
            ?>
           </form> 
        </div>
			</div>
		</div>
    <div class="row">
      <div class="col s12 m6 offset-m3 offset-13">
        <div align="center">
          
          <label style="color:black;">Make sure you have OTT access!</label></br>
           <a href="<?=base_url('auth2/fblogin')?>" class="waves-effect waves btn blue"><i class="fa fa-facebook-f"></i> Facebook login </a>
        </div>
      </div>
    </div>    
	</div>
</body>
</html>