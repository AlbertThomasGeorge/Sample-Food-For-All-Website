<?php
    include "session-timeout-check.php";
    include "../table-correction.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Staff</title>
    <link rel="stylesheet" href="../css/items-display-for-self-order-for-poor.css">
</head>
<body>
    <div id="boxes-showing-funds">
        <div class="funds">
            <h2>Funds Needed to Donate Food</h2>
            <div class="fund">Loading...</div>
        </div>
        <div class="funds">
            <h2 id="description">Estimate of Funds for people involved.<br><br>Please open <a href="needs-added-by-admin.php">Needs</a> link to learn about needs</h2>
            <div class="fund">Loading...</div>
        </div>
    </div>
    <div id="items-available"></div>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script>
        $(document).ready(function(){
                              function loadItems(){
                                  $.ajax({
                                              url: "load-items.php",
                                              type: "GET",
                                              success: function(data){
                                                           $('#items-available').html(data);
                                                       }
                                  });
                              }
                              loadItems();
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
                              setInterval(loadItems, 1000);
                          });
    </script>
</body>