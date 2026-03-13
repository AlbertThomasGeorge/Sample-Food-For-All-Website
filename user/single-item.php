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
    <link rel="stylesheet" href="../css/single-item.css">
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
    <div id="single-image-container"></div>
    <div class='error'></div>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script>
        $(document).ready(function(){
                              let quantity_of_order = 1;
                              let itemid = <?php echo $_GET['item_id']; ?>;
                              let is_in_restaurant = "<?php echo $_GET['is_in_restaurant']; ?>";
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
                              function load(quantity){
                                  $.ajax({
                                              url: "load-single.php",
                                              type: "POST",
                                              data: {item_id: itemid, qty: quantity, is_there_in_restaurant: is_in_restaurant},
                                              success: function(data){
                                                           if(data != 'Item Deleted'){
                                                               $('#single-image-container').html(data);
                                                           }
                                                           else{
                                                               $('.error').text('Item Deleted').show();
                                                           }
                                                       }
                                  });
                              }
                              load(quantity_of_order);
                              $(document).on("click", ".quantity-increase", function(){
                                                                                quantity_of_order = Number($('.input').val()) + 1; // Number used to cast string to int
                                                                                if(quantity_of_order > 0){
                                                                                    load(quantity_of_order);
                                                                                    $('.error').text('').hide();  
                                                                                }
                                                                                else{
                                                                                    $('.error').text("Quantity should be more than 0 to proceed").show();
                                                                                    setTimeout(function(){
                                                                                                   $('.error').slideDown(2000);
                                                                                               }, 1000);
                                                                                }
                                                                            });
                              $(document).on("click", ".quantity-decrease", function(){
                                                                                quantity_of_order = Number($('.input').val()) - 1; // Number used to cast string to int
                                                                                if(quantity_of_order > 0){
                                                                                    load(quantity_of_order);
                                                                                    $('.error').text('').hide();  
                                                                                }
                                                                                else{
                                                                                    $('.error').text("Quantity should be more than 0 to proceed").show();
                                                                                    setTimeout(function(){
                                                                                                   $('.error').slideUp(1000);
                                                                                               }, 1000);
                                                                                }
                                                                            });
                              $(document).on("input", ".input", function(){
                                                                   // '' < 1 is converted to 0 < 1 by js automatically
                                                                   if($(this).val() < 1){
                                                                       $(this).val(1);
                                                                       load(1);
                                                                   }
                                                                   else{
                                                                       quantity_of_order = Number($('.input').val());
                                                                       load(quantity_of_order);
                                                                   }
                                                               });
                          });
    </script>
</body>
</html>