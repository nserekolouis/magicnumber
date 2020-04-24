$(document).ready(function() {
	var pollInterval;

      var modal = document.getElementById("myModal");

      var span = document.getElementsByClassName("close")[0];

      var ring = document.getElementById("ring");

      var pstatus = document.getElementById("pstatus");

      var myformcode = document.getElementById("myformcode");

      var u;

      var success;

      var contact;

      var amount;


	$("#myform").submit(function(e) {
      e.preventDefault();
            modal.style.display = "inline-block";
            contact = document.getElementById("contact").value;
            amount = document.getElementById("amount").value;
            var user = document.getElementById("user").value;
            u = user;
            const data = {
                  amount:amount,
                  user:user
            }
            console.log(data);
            const url = 'http://192.168.8.101/main/make_withdraw';
            $.post(url,data,function(data,status){
                  var json = JSON.parse(data);
                  console.log(json);
                  var status_code = json.status_code;
                  if(status_code.localeCompare('1') == 0){
                        ring.style.visibility = "hidden";
                        pstatus.innerHTML = "Put token sent to you by sms."; 
                        myformcode.style.display = "inline-block";
                  }else if(status_code.localeCompare('0') == 0){
                        ring.style.visibility = "hidden";
                        pstatus.innerHTML = "Balance not enough!!"; 
                  }
            });
      });

      span.onclick = function() {
            modal.style.display = "none";
            clearInterval(pollInterval);
            console.log(success);
            if(success == 0){
                  window.location.replace("http://192.168.8.101/main/topup/?number="+u);
            }else if (success == 1) {
                  window.location.replace("http://192.168.8.101/main/load_game/?number="+u);
            }
            
      }

      // When the user clicks anywhere outside of the modal, close it
      window.onclick = function(event) {
            if (event.target == modal) {
                  modal.style.display = "none";
            }
      }

      $("#myformcode").submit(function(e) {
          e.preventDefault();
          ring.style.visibility = "visible";
          pstatus.innerHTML = "Processing payment";
          myformcode.style.display = "none";

          var withdraw_code = document.getElementById("withdraw_code").value;
          var user = document.getElementById("user").value;
          console.log(user);

          const url = 'http://192.168.8.101/main/verify_token';
          const data = {
            user_number:user,
            withdraw_code:withdraw_code,
            withdraw_contact:contact,
            withdraw_amount:amount
          }

          $.post(url,data,function(data,status){
                var json = JSON.parse(data);
                console.log(json);
                var status_code = json.status_code;
                if(status_code.localeCompare('1')==0){
                      //check status
                      var order_number = json.order_number;
                      pollInterval = setInterval(function(){
                          poll(order_number);
                      }, 2000);

                }else if(status_code.localeCompare('0')==0){
                      ring.style.visibility = "hidden";
                      pstatus.innerHTML = "Wrong token!!";
                }
          });

          function poll(order_number){
                const url = 'http://www.sudopay.xyz/node/get_transaction_status';
                const data = {
                      id:order_number
                }

                $.post(url,data,function(data,status){
                      console.log(data);
                      var json = JSON.parse(data);
                      var status = json.status;
                      if(status.localeCompare('failed')==0){
                           success = 0;
                           ring.style.visibility = "hidden";
                           pstatus.innerHTML = "Payment Failed"; 
                           clearInterval(pollInterval);
                           const url = 'http://192.168.8.101/main/update_transaction';
                           const data = {
                                 user:user,
                                 reference:order_number,
                                 status:'0',
                                 amount
                           }
                           $.post(url,data,function(data,status){
                                 var json = JSON.parse(data);
                                 console.log(data);
                           });
                      }else if(status.localeCompare('delivered')==0){
                          success = 1;
                          ring.style.visibility = "hidden";
                          pstatus.innerHTML = "Payment Successful"; 
                          clearInterval(pollInterval);
                          const url = 'http://192.168.8.101/main/update_transaction';
                          const data = {
                                user:user,
                                reference:order_number,
                                status:'2',
                                amount
                          }
                          $.post(url,data,function(data,status){
                                var json = JSON.parse(data);
                                console.log(data);
                          });
                      }
                });
          } 
      });

});