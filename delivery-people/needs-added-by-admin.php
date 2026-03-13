<?php
    include "session-timeout-check.php";
    include "../table-correction.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Person</title>
    <link rel="stylesheet" href="../css/needs-added-by-admin.css">
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
        <button>Get Needs</button>
    </div>
    <div class="error"></div>
    <div id="needs-table"></div>
    <div id="rent-per-day-table"></div>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script>
        $(document).ready(function(){
                              let date;
                              let load_repeatedly = false;
                              function loadNeeds(date){
                                  $.ajax({
                                              url: "load-basic-needs.php",
                                              type: "POST",
                                              data: {date_input: date},
                                              success: function(data){
                                                           $('#needs-table').html(data);
                                                       }
                                  });
                              }  
                              function loadRent(date){
                                  $.ajax({
                                              url: "load-rent.php",
                                              type: "POST",
                                              data: {date_input: date},
                                              success: function(data){
                                                           $('#rent-per-day-table').html(data);
                                                       }
                                  });
                              }   
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
                                                        loadNeeds(date);
                                                        loadRent(date);
                                                    }
                                                });  
                              $('input').on("input", function(){
                                                         load_repeatedly = false;
                                                         $('#needs-table').html('');
                                                         $('#rent-per-day-table').html('');
                                                     });
                              setInterval(function(){
                                              if(load_repeatedly){
                                                  loadNeeds(date);
                                                  loadRent(date);
                                              }
                                          }, 1000);
                          });
    </script>
</body>