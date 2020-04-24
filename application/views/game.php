<!DOCTYPE html>
<html>
<head>
	<title>Magic Number</title>
	<meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- jQuery library -->
  <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <!-- Latest compiled JavaScript -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel = "stylesheet" type = "text/css" href = "<?php echo base_url('static/css/style.css')?>" />
      <style>
          /*the container must be positioned relative:*/
          .custom-select {
                position: relative;
                font-family: Arial;
                text-align: center;
                display: inline-block;
                margin-top: 10px;
          }
          .custom-select select {
            display: none; /*hide original SELECT element:*/
          }

          .select-selected {
            background-color: #ffffff;
            height: 37px;
            width: 80px;
            padding: 14px;
          }

          /*style the arrow inside the select element:*/
          .select-selected:after {
            position: absolute;
            content: "";
            top: 20px;
            right: 10px;
            width: 0;
            height: 0;
            border: 6px solid #268df900;
            border-color: #268df9 #268df900 #268df900 transparent;
          }

          /*point the arrow upwards when the select box is open (active):*/
          .select-selected.select-arrow-active:after {
            border-color: #268df900 #268df900 #268df9 #268df900;
            top: 16px;
          }

          /*style the items (options), including the selected item:*/
          .select-items div,.select-selected {
            color: #000000;
            border: 1px solid #248df9;
            border-color: #248df9 #248df9 rgb(36, 141, 249) #248df9;
            cursor: pointer;
            user-select: none;
            padding: 6px;
            font-size: 18px;
            background-color: white
          }

          /*style items (options):*/
          .select-items {
            position: absolute;
            background-color: DodgerBlue;
            top: 100%;
            left: 0;
            right: 0;
            z-index: 99;
          }

          /*hide the items when the select box is closed:*/
          .select-hide {
            display: none;
          }

          .select-items div:hover, .same-as-selected {
            background-color: rgba(0, 0, 0, 0.1);
          }
      </style>
</head>
<body style="background-color: white;">
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
<div class="main">
  <table>
    <tr>
    <?php foreach($games as $key => $game):?>
       <th><?php echo date_format(new DateTime($game['datetime']),"H:i");?></br><?php echo $game['num'];?></th>
    <?php endforeach; ?>
    </tr>
</table>
   <div>
      <p id="guess" style="width: 20%; display: inline;font-size:25px;">Guess the next winning number from 1 to 9.</p></br>
      <p style="width: 20%; display: inline;font-size:25px;">Amount to play for:</p>
      <div class="custom-select" style="width:90px; padding:5px;">
        <select>
          <option value="1">500</option>
          <option value="2">1000</option>
          <option value="3">2000</option>
          <option value="4">3000</option>
          <option value="5">4000</option>
          <option value="6">5000</option>
          <option value="7">6000</option>
          <option value="8">7000</option>
          <option value="9">8000</option>
          <option value="10">9000</option>
          <option value="11">10000</option>
        </select>
    </div> 
   </div>
   <div class="info">
       <p id="results" style="font-size: 30px;"></p>
       <p id="counter">REMAINING TIME!!!</p>
       <p id="demo" style="font-size: 30px;"></p>
       <p id="pred"><p>
   </div>
  <input id="wnumber" type="number" name="wnumber" min="1" max="9"/></br>
  <button id="submit"class="button" type="submit" onclick="myPrediction()">Submit</button> </br>
  <button style="display: none;" id="submit2" class="button" type="submit" onclick="myRefresh()">Play again
  </button>
</div>


<script>
var x, i, j, selElmnt, a, b, c;
var stake = 500;
/*look for any elements with the class "custom-select":*/
x = document.getElementsByClassName("custom-select");
for (i = 0; i < x.length; i++) {
  selElmnt = x[i].getElementsByTagName("select")[0];
  /*for each element, create a new DIV that will act as the selected item:*/
  a = document.createElement("DIV");
  a.setAttribute("class", "select-selected");
  a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
  x[i].appendChild(a);
  /*for each element, create a new DIV that will contain the option list:*/
  b = document.createElement("DIV");
  b.setAttribute("class", "select-items select-hide");
  for (j = 1; j < selElmnt.length; j++) {
    /*for each option in the original select element,
    create a new DIV that will act as an option item:*/
    c = document.createElement("DIV");
    c.innerHTML = selElmnt.options[j].innerHTML;
    c.addEventListener("click", function(e) {
        /*when an item is clicked, update the original select box,
        and the selected item:*/
        var y, i, k, s, h;
        s = this.parentNode.parentNode.getElementsByTagName("select")[0];
        

        h = this.parentNode.previousSibling;
        for (i = 0; i < s.length; i++) {
          if (s.options[i].innerHTML == this.innerHTML) {
            s.selectedIndex = i;
            h.innerHTML = this.innerHTML;
            console.log(this.innerHTML);
            stake = this.innerHTML;
            y = this.parentNode.getElementsByClassName("same-as-selected");
            for (k = 0; k < y.length; k++) {
              y[k].removeAttribute("class");
            }
            this.setAttribute("class", "same-as-selected");
            break;
          }
        }
        h.click();
    });
    b.appendChild(c);
  }
  x[i].appendChild(b);
  a.addEventListener("click", function(e) {
      /*when the select box is clicked, close any other select boxes,
      and open/close the current select box:*/
      e.stopPropagation();
      closeAllSelect(this);
      this.nextSibling.classList.toggle("select-hide");
      this.classList.toggle("select-arrow-active");
    });
}
function closeAllSelect(elmnt) {
  /*a function that will close all select boxes in the document,
  except the current select box:*/
  var x, y, i, arrNo = [];
  x = document.getElementsByClassName("select-items");
  y = document.getElementsByClassName("select-selected");
  for (i = 0; i < y.length; i++) {
    if (elmnt == y[i]) {
      arrNo.push(i)
    } else {
      y[i].classList.remove("select-arrow-active");
    }
  }
  for (i = 0; i < x.length; i++) {
    if (arrNo.indexOf(i)) {
      x[i].classList.add("select-hide");
    }
  }
}
/*if the user clicks anywhere outside the select box,
then close all select boxes:*/
document.addEventListener("click", closeAllSelect);


    var pred = 0;
    var st = <?php echo json_encode($stoptime);?>;
    var mn = <?php echo json_encode($mobile_number);?>;
    var py = <?php echo json_encode($play);?>;
    var credit = <?php echo json_encode($credit);?>;

    console.log(stake);
    

    

    //var pwon = document.getElementById("won");
    //console.log(pwon);

    //var plost = document.getElementById("lost");
    //console.log(plost);

    // Set the date we're counting down to
    var countDownDate = new Date(st).getTime();

    // Update the count down every 1 second
    var x = setInterval(function() {

    // Get today's date and time
    var now = new Date().getTime();
    
    // Find the distance between now and the count down date
    var distance = countDownDate - now;
    
    // Time calculations for days, hours, minutes and seconds
    //var days = Math.floor(distance / (1000 * 60 * 60 * 24));
    //var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
    // Output the result in an element with id="demo"
   document.getElementById("demo").innerHTML = seconds + "s ";
    
  // If the count down is over, write some text 
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demo").innerHTML = "TIME OUT!!!";
    if(parseInt(pred) > 0){
      console.log('1');
      const s = 'http://192.168.8.101/main/get_prediction/';
      const data = {
        prediction:pred,
        mobile_number:mn,
        play:py,
        stake:stake
      }
      $.post(s,data,function(data,status){
        var json = JSON.parse(data);
        var wnumber = json.wnumber;
        console.log(wnumber);
        var prediction = json.prediction;
         console.log(prediction);
        if(wnumber == prediction){
          document.getElementById("results").innerHTML = "Congratulation, you Won 500sh!!! </br> Answer is "+wnumber;
        }else{
          document.getElementById("results").innerHTML = "SORRY you lost!!! </br> Answer is "+wnumber;
        }
      });
    
    document.getElementById("pred").style.display = "none";
    document.getElementById("wnumber").style.display = "none";
    document.getElementById("submit").style.display = "none";
    document.getElementById("counter").style.display = "none";
    document.getElementById("demo").style.display = "none";
    document.getElementById("submit2").style.display = "inline-block";
    }
    
    document.getElementById("pred").style.display = "none";
    document.getElementById("wnumber").style.display = "none";
    document.getElementById("submit").style.display = "none";
    document.getElementById("counter").style.display = "none";
    document.getElementById("submit2").style.display = "inline-block";
  }
}, 1000);

function myPrediction(){
  if(parseInt(credit) >= parseInt(stake)){
    pred = document.getElementById('wnumber').value;
    document.getElementById('pred').innerHTML = "Your prediction is " + pred;
  }else{
    document.getElementById('wnumber').value='';
    var amount = stake-credit;
    alert('Please load '+amount+' to play to win '+stake);
  }
}

function myRefresh(){
  location.reload();
}
</script>



</body>
</html>