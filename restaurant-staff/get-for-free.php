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
    <link rel="stylesheet" href="../css/get-for-free.css">
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
    <h3></h3>
    <div id="buttons-container">
        <button>Get Free, can't afford 🥺</button>
    </div>
    <div class="error"></div>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script>
        $(document).ready(function(){
                              let total_cost_price;
                              let quantity = <?php echo $_GET['quantity']; ?>;
                              let itemid = <?php echo $_GET['item_id']; ?>;
                              $.ajax({
                                          url: "calculate-cost-price-purely.php",
                                          type: "POST",
                                          data: {quantity_total: quantity, item_id: itemid},
                                          success: function(data){
                                                       if(data != 'Item Deleted'){
                                                           $('h3').html(data);
                                                           total_cost_price = $('h3 span').text(); 
                                                           $('button').click(function(){
                                                                                 let time_ms = Date.now(); // unix time
                                                                                 $.ajax({
                                                                                             url: "free-order.php",
                                                                                             type: "POST",
                                                                                             data: {unix: time_ms, totalcostprice: total_cost_price, item_id: itemid, qty: quantity},
                                                                                             success: function(data){
                                                                                                          if(data == 1){
                                                                                                              window.location.href = "restaurant-staff-home-page.php";
                                                                                                          }
                                                                                                          else{
                                                                                                              $('.error').html("Item Deleted Recently.").show();
                                                                                                          } 
                                                                                                      }
                                                                                 });
                                                                             });
                                                       }
                                                       else{
                                                           $('.error').html("Item Deleted Recently.").show(); 
                                                       }
                                                   }
                              });
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
                          });
    </script>
</body>