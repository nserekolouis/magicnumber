$(document).ready(function() {
	var pollInterval;

      var modal = document.getElementById("myModal");

      var span = document.getElementsByClassName("close")[0];

      var ring = document.getElementById("ring");

      var pstatus = document.getElementById("pstatus");

      var u;

      var success;

	$("#myform").submit(function(e) {
      e.preventDefault();
      modal.style.display = "inline-block";
      var network = document.getElementById("network").value;
      if(network.localeCompare('MTN') == 0){
            var contact = document.getElementById("contact").value;
            var amount = document.getElementById("amount").value;
            var user = document.getElementById("user").value;
            u = user;
            const data = {
                  contact:contact,
                  amount:amount,
                  network:'MTN'
            }
            makeRequest(data,user,amount);

      }else if(network.localeCompare("Airtel") == 0){
            var contact = document.getElementById("contact").value;
            var amount = document.getElementById("amount").value;
            var code = document.getElementById("code").value;
            var user = document.getElementById("user").value;
            u = user;
            const data = {
                  contact:contact,
                  amount:amount,
                  secret_code:code,
                  network:'Airtel'
            }
            makeRequest(data,user,amount);
      }

      function makeRequest(data,user,amount){
                  const url = 'http://192.168.8.101/main/make_deposit_request/';
                  $.post(url,data,function(data,status){
                        console.log(data);
                        console.log(user);
                        console.log(amount);

                        var json = JSON.parse(data);
                        var status_code = json.status_code;
                        if(status_code == 1){
                        var order_number = json.order_number;
                        const url = 'http://192.168.8.101/main/add_transaction';
                        const data = {
                              users:user,
                              reference:order_number,
                              amount:amount
                        }
                        console.log(data);
                        $.post(url,data,function(data,status){
                              var json = JSON.parse(data);
                              console.log(data);
                        });
                        pollInterval = setInterval(function(){
                          poll(order_number,user);
                        }, 2000);
                        }
                  });


                  function poll(order_number,user){
                        const url = 'http://www.sudopay.xyz/node/get_transaction_status';
                        const data = {
                              id:order_number
                        }

                        $.post(url,data,function(data,status){
                              //modal.style.display = "none";
                              console.log(data);
                              var json = JSON.parse(data);
                              var status = json.status;
                              var amount = json.amount;
                              if(status.localeCompare('failed')==0){
                                    //modal.style.display = "none";
                                    success = 0;
                                    ring.style.visibility = "hidden";
                                    pstatus.innerHTML = "Payment Failed"; 
                                    clearInterval(pollInterval);
                                    //var json = JSON.parse(data);
                                    //var amount = json.amount;
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
                                          //alert(json);
                                    });
                              }else if(status.localeCompare('delivered')==0){
                                    //modal.style.display = "none";
                                    success = 1;
                                    ring.style.visibility = "hidden";
                                    pstatus.innerHTML = "Payment Successful"; 
                                    clearInterval(pollInterval);
                                    // var json = JSON.parse(data);
                                    // var amount = json.amount;
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
                                          //alert(json);
                                    });
                              }else{
                                    //------------------------------
                                    //alert('network not supported');
                              }
                        });
                  }
      }
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
});