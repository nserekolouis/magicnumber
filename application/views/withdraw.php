<!DOCTYPE html>
<html>
<head>
	<title>Withdraw</title>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel = "stylesheet" type = "text/css" href = "<?php echo base_url('static/css/style.css')?>" />
    <!-- jQuery library -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <style>
      input[type=number]{
           margin-top: 5px;
           padding: 6px;
           height: 35px;
           font-size: 15px;
           text-align:left;
           width: 383px;
      }
      label{
            margin-top: 5px;
            width: 18%;
      }
      input[type=tel]{
            width: 383px;
      }
    </style>
</head>
<body>
	 <ul>
      <li style="float: left;">
          <img src="<?php echo base_url('static/images/logo.png');?>" width="20" height="20"/>
      </li> 
      <li><a href="<?php echo base_url('/');?>"><?php echo $mobile_number?></a></li>
      <?php if($credit > 0):?>
      <li><a href="<?php echo base_url('main/topup/?number='.$mobile_number);?>">
                Amount(<?php echo $credit; ?>)</a></li>
      <?php else:?>
      <li><a href="<?php echo base_url('main/topup/?number='.$mobile_number);?>">Amount(0.0)</a></li>
      <?php endif;?>
      <li><a href="<?php echo base_url('main/Withdraw/?number='.$mobile_number);?>">Withdraw</a></li>
  </ul>
</body>
<div class="main">
  <div class="form">
    <form id="myform">
      <label style="width:74%; text-align: initial;">Withdraw</label></br>
      <input type="tel" name="contact" id="contact" placeholder="07..." required></br>
      <input type="number" name="amount" id="amount" placeholder="0.0" required></br>
      <input type="text" name="network" id="network" value="MTN" hidden></br>
      <input type="tel" name="user" id="user" value="<?php echo $mobile_number?>" hidden></br>
      <button class="button" type="submit" style="float:right; width:100px; height:35px;">
      Submit</button>
    </form>
  </div>
      <center>
          <!-- Modal content -->
          <div id="myModal" class="modal">
          <!-- Modal content -->
          <div class="modal-content">
          <div class="modal-header">
            <span class="close">&times;</span>
            <h3 id="pstatus">Processing payment...</h3>
          </div>
          <div class="modal-body">
              <div id ="ring" class="lds-dual-ring"></div>
              <div class="form" style="width: auto;">
                <form id="myformcode" style="display: none">
                  <label style="width: 100%;">Verification code</label></br>
                  <input style="text-align: center;" type="number" name="withdraw_code" id="withdraw_code" placeholder="4-digits" required ></br>
                  <input type="tel" name="user" id="user" value="<?php echo $mobile_number?>" hidden></br>
                  <button class="button" type="submit" style="float:right; width:100px; height:35px;">Submit</button>
                </form>
              </div>
          <div class="modal-footer">
            <h3>Modal Footer</h3>
          </div>
        </div>
      </center>
    </div>
</div>
<script src="<?php echo base_url('static/js/script2.js');?>"></script>
</html>