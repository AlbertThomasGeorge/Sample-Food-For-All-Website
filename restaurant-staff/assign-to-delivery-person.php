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
    <link rel="stylesheet" href="../css/assign-to-delivery-person.css">
</head>
<body>
    <div id="container">
        <div id="select-delivery-person-to-assign-order">
            <label>Select Delivery Person To assign order</label>
            <select id="delivery-people"></select>
        </div>
        <div id="assign"><button>Assign Deliverer</button></div>
    </div>
    <div class="error"></div>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script>
        let selected = "";
        $(document).ready(function(){
                              function loadDeliveryPeople(){
                                  $.ajax({
                                              url: "load-delivery-people.php",
                                              type: "GET",
                                              dataType: "JSON",
                                              success: function(data){
                                                           if(data.status == false){
                                                               $('#delivery-people').html('<option value="">Select Delivery Person</option>');
                                                           }
                                                           else{
                                                               $('#delivery-people').html('<option value="" disabled selected>Select Delivery Person</option>');
                                                               $.each(data, function(key, value){
                                                                                if(value.delivery_person_id == selected){
                                                                                    $('#delivery-people').append('<option value="' + value.delivery_person_id + '" selected>' + value.delivery_person_name + '</option>'); 
                                                                                }
                                                                                else{
                                                                                    $('#delivery-people').append('<option value="' + value.delivery_person_id + '">' + value.delivery_person_name + '</option>'); 
                                                                                }             
                                                               });
                                                           }
                                                       }
                                  })
                              }
                              loadDeliveryPeople(selected);
                              $('#delivery-people').change(function(){
                                                               selected = $(this).val();
                                                           });
                              setInterval(function(){
                                              loadDeliveryPeople(selected);
                                          }, 5000);
                              $('button').click(function(){
                                                    let order_id = <?php echo $_GET['order_id']; ?>;
                                                    selected = $('#delivery-people').val();
                                                    if(selected != ""){
                                                        $.ajax({
                                                                    url: "add-delivery-person-responsibility.php?delivery_person_id="+selected+"&order_id="+order_id,
                                                                    type: "GET",
                                                                    success: function(data){
                                                                                if(data == 1){
                                                                                    window.location.href = "orders-from-not-restaurant.php";
                                                                                }
                                                                                else{
                                                                                    $('.error').html("Failed").show();
                                                                                    setTimeout(function(){
                                                                                                    $('.error').hide();
                                                                                                }, 1000);
                                                                                }
                                                                            }
                                                        });
                                                    }
                                                    else{
                                                        $('.error').html("Select a delivery person").show();
                                                        setTimeout(function(){
                                                                       $('.error').hide();
                                                                   }, 1000);
                                                    }
                                                });
                          });
    </script>
</body>