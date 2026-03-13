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
    <link rel="stylesheet" href="../css/donate-if-you-wish.css">
</head>
<body>
    <div id="boxes-showing-funds">
        <div class="funds">
            <h2>Funds Needed to Donate Food</h2>
            <div class="fund">Loading...</div>
        </div>
        <div class="funds">
            <h2 id="description">Estimate of Funds for people involved.<br><br>Please open <a href="needs-of-people-involved.php" class='needs'>Needs</a> link to learn about needs</h2>
            <div class="fund">Loading...</div>
        </div>
    </div>
    <h3></h3>
    <div id="buttons-container">
        <button>Double Click here to Donate if wish and order</button>
        <button>Get Free, can't afford 🥺</button>
    </div>
    <div class="error"></div>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script>
        $(document).ready(function(){
                              let total_cost_price;
                              let quantity = <?php echo $_GET['quantity']; ?>;
                              let itemid = <?php echo $_GET['item_id']; ?>;
                              let is_there_in_restaurant = "<?php echo $_GET['is_in_restaurant'] ?>";
                              $('button:first').prop('disabled', true);
                              $.ajax({
                                          url: "calculate-cost-price-purely.php",
                                          type: "POST",
                                          data: {quantity_total: quantity, item_id: itemid},
                                          success: function(data){
                                                       if(data != 'Item Deleted'){
                                                           $('button:first').prop('disabled', false);
                                                           $('h3').html(data);
                                                           total_cost_price = $('h3 span').text(); 
                                                           $('button:first').click(function(){
                                                                                       $('button:first').html("<a href='donate-not-freely.php?total_cost_price=" + total_cost_price + "&quantity=" + quantity + "&itemid=" + itemid + "&is_in_restaurant=" + is_there_in_restaurant + "' class='click-once'>Click once to donate</a>");           
                                                                                   });
                                                           $('button:last').click(function(){
                                                                                      let time_ms = Date.now(); // unix time
                                                                                      $.ajax({
                                                                                                  url: "free-order.php",
                                                                                                  type: "POST",
                                                                                                  data: {unix: time_ms, totalcostprice: total_cost_price, item_id: itemid, qty: quantity, is_present_in_restaurant: is_there_in_restaurant},
                                                                                                  success: function(data){
                                                                                                               if(data == 1){
                                                                                                                   window.location.href = "user-home-page.php";
                                                                                                               }
                                                                                                               else if(data == 0){
                                                                                                                   $('.error').html("Location to Deliver not added.<br>Click Link <a href='user-location.php' class='location'>Location</a> to add location to deliver.").show();
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