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
    <link rel="stylesheet" href="../css/orders-from-restaurant.css">
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
    <div id="submit-date">
        <input type="text" placeholder="yyyy-mm-dd">
        <button>Get Orders From Restaurant</button>
    </div>
    <div class="error"></div>
    <div id="orders-from-restaurant"></div>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script>
        $(document).ready(function(){
                              let date;
                              let load_repeatedly = false;
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
                              function loadOrdersFromRestaurant(date, order_identifier){
                                  $.ajax({
                                              url: "load-orders-from-restaurant.php",
                                              type: "POST",
                                              data: {date_input: date, order_id: order_identifier},
                                              success: function(data){
                                                           $('#orders-from-restaurant').html(data);
                                                       }
                                  });
                              }
                              $('button').click(function(){
                                                    date = $('input').val();
                                                    if(date == ''){
                                                        $('.error').html('Enter a date before submitting').show();
                                                        setTimeout(function(){
                                                                       $('.error').html('').hide();
                                                                   }, 1000);
                                                    }
                                                    else{
                                                        load_repeatedly = true;
                                                        $('.error').html('').hide();
                                                        loadOrdersFromRestaurant(date);
                                                    }
                                                });
                              $('input').on("input", function(){
                                                         load_repeatedly = false;
                                                         $('#orders-from-restaurant').html('');
                                                     });
                              $(document).on("click", ".prepared-and-delivered", function(){
                                                                                     let order_id = $(this).data('order_id');
                                                                                     loadOrdersFromRestaurant(date, order_id);
                                                                                 })
                              setInterval(function(){
                                              if(load_repeatedly){
                                                  loadOrdersFromRestaurant(date);
                                              }
                                          }, 1000);
                          });
    </script>
</body>
</html>