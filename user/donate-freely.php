<?php
    include "session-timeout-check.php";
    include "../table-correction.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User</title>
    <link rel="stylesheet" href="../css/donate-freely.css">
</head>
<body>
    <div id="boxes-showing-funds">
        <div class="funds">
            <h2>Funds Needed to Donate Food</h2>
            <div class="fund">Loading...</div>
        </div>
        <div class="funds">
            <h2 id="description">Estimate of Funds for people involved.<br><br>Please open <a href="needs-of-people-involved.php">Needs</a> link to learn about needs</h2>
            <div class="fund">Loading...</div>
        </div>
    </div>
    <div id="donation-container">
        <input type="number" min='1' placeholder="Enter amount to donate in ₹">
        <input type="text" placeholder="Enter bank through which want to donate">
        <button>Donate</button>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script>
        $(document).ready(function(){
                              function loadFundsNeededToDonateFood(){
                                  $.ajax({
                                              url: "load-funds-needed-to-donate-food.php",
                                              type: "GET",
                                              success: function(data){
                                                           $('.fund:first').html('₹' + data);                      
                                                       }
                                  });
                              }
                              function loadFundsForPeopleInvolved(){
                                  $.ajax({
                                              url: "load-funds-for-people-involved.php",
                                              type: "GET",
                                              success: function(data){
                                                           $('.fund:last').html('₹' + data);
                                                           loadFundsNeededToDonateFood();
                                                       }
                                  });
                              }
                              loadFundsForPeopleInvolved();
                              setInterval(loadFundsForPeopleInvolved, 1000);
                              $('input:first').on("input", function(){
                                                               // '' < 1 is converted to 0 < 1 by js automatically
                                                               if($(this).val() < 1){
                                                                   $(this).val(1);
                                                               }
                                                           });
                              $('button').click(function(){
                                                    let amount_donate = Number($('input:first').val());
                                                    let time_ms = Date.now(); // unix time
                                                    let bank = $('input:last').val();
                                                    $.ajax({
                                                                url: "response-maker-after-donate-freely.php",
                                                                type: "POST",
                                                                data: {unixtime: time_ms, amount: amount_donate, bank_used: bank},
                                                                success: function(data){
                                                                             if(data == 1){
                                                                                 window.location.href = "user-home-page.php";
                                                                             } 
                                                                         }
                                                    }); 
                                                });
                          });
    </script>
</body>
</html>