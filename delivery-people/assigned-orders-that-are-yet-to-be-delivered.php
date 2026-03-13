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
    <link rel="stylesheet" href="../css/assigned-orders-that-are-yet-to-be-delivered.css">
</head>
<body>
    <h1>Deliveries Assigned to you that are left</h1>
    <div id="submit-date">
        <input type="text" placeholder="yyyy-mm-dd">
        <button class='get-orders'>Get Orders Assigned to me whose delivery is not completed</button>
    </div>
    <div class="error"></div>
    <div id="info"><tbody></tbody></div>
    <div class='go-back'><a href='delivery-person-logout.php'>Click to Logout if need break</a></div>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script>
        $(document).ready(function(){
                              let date;
                              let has_clicked_begin_delivery_button = false;
                              let has_already_started_delivery_before = 'still under checking';
                              function loadOrdersToBeDelivered(date){
                                  // async is set to false so that ajax behaves synchronously which is not it's default behaviour
                                  $.ajax({
                                              url: "load-orders-to-be-delivered.php",
                                              type: "POST",
                                              data: {date: date},
                                              async: false,
                                              dataType: "JSON",
                                              success: function(data){
                                                           if(data.status == false){
                                                               $('#info').html("<h2>No orders that were assigned to you whose delivery is not done exists for the date provided</h2>");
                                                           }
                                                           else{
                                                               let has_any_delivery_process_started = false;
                                                               let latitude_of_restaurant;
                                                               let longitude_of_restaurant;
                                                               let latitude_from_where_start;
                                                               let longitude_from_where_start;
                                                               let latitude_to_go;
                                                               let longitude_to_go;
                                                               let user_whose_delivery_started;
                                                               let latitude_of_destination;
                                                               let longitude_of_destination;
                                                               let latitude_of_source;
                                                               let longitude_of_source;
                                                               let order_status;
                                                               $.each(data, function(key, value){
                                                                                if(key == 'orders'){
                                                                                    $.each(value, function(innerkey, innervalue){
                                                                                        if(innervalue.order_status == 'STARTED DELIVERY PROCESS'){
                                                                                            user_whose_delivery_started = innervalue.user_name;
                                                                                            latitude_of_destination = innervalue.latitude_to_deliver;
                                                                                            longitude_of_destination = innervalue.longitude_to_deliver;
                                                                                            order_status = innervalue.order_status;
                                                                                            has_any_delivery_process_started = true;
                                                                                            return false; // similar to break;
                                                                                        } 
                                                                                    });
                                                                                }    
                                                               });
                                                               has_already_started_delivery_before = false;
                                                               $.each(data, function(key1, value1){
                                                                                if(key1 == 'location_of_restaurant'){
                                                                                    $.each(value1, function(innerkey1, innervalue1){
                                                                                        latitude_of_restaurant = innervalue1.latitude;
                                                                                        longitude_of_restaurant = innervalue1.longitude;
                                                                                        $.each(data, function(key2, value2){
                                                                                            if(key2 == 'location_tracker'){
                                                                                                $.each(value2, function(innerkey2, innervalue2){
                                                                                                    latitude_from_where_start = innervalue2.latitude_of_source;
                                                                                                    longitude_from_where_start = innervalue2.longitude_of_source;
                                                                                                    latitude_to_go = innervalue2.latitude_of_destination;
                                                                                                    longitude_to_go = innervalue2.longitude_of_destination;
                                                                                                    if(((Number(latitude_from_where_start) != Number(latitude_of_restaurant)) || (Number(longitude_from_where_start) != Number(longitude_of_restaurant))) || ((latitude_to_go !== null) && (longitude_to_go !== null))){
                                                                                                        has_already_started_delivery_before = true;
                                                                                                    }
                                                                                                });
                                                                                            }
                                                                                        });
                                                                                    });
                                                                                }      
                                                               });
                                                               if((!has_any_delivery_process_started) && (!has_clicked_begin_delivery_button)){
                                                                   $('#info').html("<button class='click-when-you-begin-delivery-from-restaurant'>Click when you begin delivery from restaurant</button><table><tr><th>User Name</th><th>Order Status</th><th>Click if delivery started</th><th>Get Directions</th><th>Click if delivery completed</th></tr>");
                                                                   $.each(data, function(key, value){
                                                                                    if(key == 'orders'){
                                                                                        $.each(value, function(innerkey, innervalue){  
                                                                                                          if(innervalue.order_status == 'COMPLETED DELIVERY'){
                                                                                                              $('#info tbody').append("<tr><td>" + innervalue.user_name + "</td><td>" + innervalue.order_status + "</td><td><button class='click-if-delivery-started' data-date='" + date + "' data-user_name='" + innervalue.user_name + "' data-latitude_to_deliver='" + innervalue.latitude_to_deliver + "' data-longitude_to_deliver='" + innervalue.longitude_to_deliver + "' disabled>Delivery Started</button></td><td>GET DIRECTIONS</td><td><button class='click-if-delivery-completed' data-date='"+ date + "' data-user_name='" + innervalue.user_name + "' disabled>Delivery Completed</button></td></tr>");
                                                                                                          }
                                                                                                          else{
                                                                                                              $('#info tbody').append("<tr><td>" + innervalue.user_name + "</td><td>" + innervalue.order_status + "</td><td><button class='click-if-delivery-started' data-date='" + date + "' data-user_name='" + innervalue.user_name + "' data-latitude_to_deliver='" + innervalue.latitude_to_deliver + "' data-longitude_to_deliver='" + innervalue.longitude_to_deliver + "'>Delivery Started</button></td><td>GET DIRECTIONS</td><td><button class='click-if-delivery-completed' data-date='"+ date + "' data-user_name='" + innervalue.user_name + "' disabled>Delivery Completed</button></td></tr>");
                                                                                                          }
                                                                                        });
                                                                                    }
                                                                   });
                                                                   $('#info').append('</table>');
                                                               }
                                                               else if((!has_any_delivery_process_started) && (has_clicked_begin_delivery_button)){
                                                                   $('#info').html("<button class='click-when-you-begin-delivery-from-restaurant' disabled>Click when you begin delivery from restaurant</button><table><tr><th>User Name</th><th>Order Status</th><th>Click if delivery started</th><th>Get Directions</th><th>Click if delivery completed</th></tr>");
                                                                   $.each(data, function(key, value){
                                                                                    if(key == 'orders'){
                                                                                        $.each(value, function(innerkey, innervalue){
                                                                                                          if(innervalue.order_status == 'COMPLETED DELIVERY'){         
                                                                                                              $('#info tbody').append("<tr><td>" + innervalue.user_name + "</td><td>" + innervalue.order_status + "</td><td><button class='click-if-delivery-started' data-date='" + date + "' data-user_name='" + innervalue.user_name + "' data-latitude_to_deliver='" + innervalue.latitude_to_deliver + "' data-longitude_to_deliver='" + innervalue.longitude_to_deliver + "' disabled>Delivery Started</button></td><td>GET DIRECTIONS</td><td><button class='click-if-delivery-completed' data-date='"+ date + "' data-user_name='" + innervalue.user_name + "' disabled>Delivery Completed</button></td></tr>");
                                                                                                          }
                                                                                                          else{
                                                                                                              $('#info tbody').append("<tr><td>" + innervalue.user_name + "</td><td>" + innervalue.order_status + "</td><td><button class='click-if-delivery-started' data-date='" + date + "' data-user_name='" + innervalue.user_name + "' data-latitude_to_deliver='" + innervalue.latitude_to_deliver + "' data-longitude_to_deliver='" + innervalue.longitude_to_deliver + "'>Delivery Started</button></td><td>GET DIRECTIONS</td><td><button class='click-if-delivery-completed' data-date='"+ date + "' data-user_name='" + innervalue.user_name + "' disabled>Delivery Completed</button></td></tr>");
                                                                                                          }
                                                                                        });
                                                                                    }
                                                                   });
                                                                   $('#info').append("</table>");
                                                               }       
                                                               else if(has_any_delivery_process_started){
                                                                   $('#info').html("<button class='click-when-you-begin-delivery-from-restaurant' disabled>Click when you begin delivery from restaurant</button><table><tr><th>User Name</th><th>Order Status</th><th>Click if delivery started</th><th>Get Directions</th><th>Click if delivery completed</th></tr>");
                                                                   $.each(data, function(key1, value1){
                                                                                    if(key1 == 'orders'){
                                                                                        $.each(value1, function(innerkey1, innervalue1){
                                                                                                          if((innervalue1.user_name == user_whose_delivery_started)&&(innervalue1.latitude_to_deliver == latitude_of_destination)&&(innervalue1.longitude_to_deliver == longitude_of_destination)){
                                                                                                              $.each(data, function(key2, value2){
                                                                                                                               if(key2 == 'location_tracker'){
                                                                                                                                   $.each(value2, function(innerkey2, innervalue2){
                                                                                                                                                      latitude_of_source = innervalue2.latitude_of_source;
                                                                                                                                                      longitude_of_source = innervalue2.longitude_of_source;  
                                                                                                                                   });
                                                                                                                               }
                                                                                                              });
                                                                                                              $('#info tbody').append("<tr><td>" + user_whose_delivery_started + "</td><td>" + order_status + "</td><td><button class='click-if-delivery-started' data-date='" + date + "' data-user_name='" + user_whose_delivery_started + "' data-latitude_to_deliver='" + latitude_of_source + "' data-longitude_to_deliver='" + longitude_of_source + "' disabled>Delivery Started</button></td><td><a href='https://www.google.com/maps/dir/?api=1&origin=" + latitude_of_source + "," + longitude_of_source + "&destination=" + latitude_of_destination + "," + longitude_of_destination + "' target='_blank'>GET DIRECTIONS</a></td><td><button class='click-if-delivery-completed' data-date='"+ date + "' data-user_name='" + user_whose_delivery_started + "'>Delivery Completed</button></td></tr>");
                                                                                                          }
                                                                                                          else{
                                                                                                              $('#info tbody').append("<tr><td>" + innervalue1.user_name + "</td><td>" + innervalue1.order_status + "</td><td><button class='click-if-delivery-started' data-date='" + date + "' data-user_name='" + innervalue1.user_name + "' data-latitude_to_deliver='" + innervalue1.latitude_to_deliver + "' data-longitude_to_deliver='" + innervalue1.longitude_to_deliver + "' disabled>Delivery Started</button></td><td>GET DIRECTIONS</td><td><button class='click-if-delivery-completed' data-date='"+ date + "' data-user_name='" + innervalue1.user_name + "' disabled>Delivery Completed</button></td></tr>")
                                                                                                          }
                                                                                        });
                                                                                    }
                                                                   });
                                                                   $('#info').append("</table>");
                                                               }
                                                           }
                                                       }                                     
                                  });
                              }
                              $('.get-orders').click(function(){
                                                         date = $('input').val();
                                                         if(date == ''){
                                                             $('.error').html('Enter a date before submitting').show();
                                                             setTimeout(function(){
                                                                            $('.error').html('').hide();
                                                                        }, 1000);
                                                         }
                                                         else{
                                                             $('.error').html('').hide();
                                                             loadOrdersToBeDelivered(date);
                                                             delivery_core_logic();
                                                         }
                                                     });  
                              $('input').on("input", function(){
                                                         $('#info').html('');
                                                     });     
                              function delivery_core_logic(){                                                                           
                                  if((has_already_started_delivery_before == false) && (has_already_started_delivery_before != 'still under checking')){
                                      $(document).off("click", ".click-when-you-begin-delivery-from-restaurant").on("click", ".click-when-you-begin-delivery-from-restaurant", function(){
                                                                                                                                                                                   $.ajax({
                                                                                                                                                                                               url: "set-source-location-as-restaurant.php",
                                                                                                                                                                                               type: "GET",
                                                                                                                                                                                               success: function(data){
                                                                                                                                                                                                            if(data == 1){
                                                                                                                                                                                                                has_clicked_begin_delivery_button = true;
                                                                                                                                                                                                                loadOrdersToBeDelivered(date); 
                                                                                                                                                                                                                $(document).off("click", ".click-if-delivery-started").one("click", ".click-if-delivery-started", function(){
                                                                                                                                                                                                                                                                                                                      let date = $(this).data('date');
                                                                                                                                                                                                                                                                                                                      let user_name = $(this).data('user_name');
                                                                                                                                                                                                                                                                                                                      let latitude_to_deliver = $(this).data('latitude_to_deliver');
                                                                                                                                                                                                                                                                                                                      let longitude_to_deliver = $(this).data('longitude_to_deliver');
                                                                                                                                                                                                                                                                                                                      $.ajax({
                                                                                                                                                                                                                                                                                                                                  url: "set-destination-location-after-first-delivery-started-and-update-order-status.php",
                                                                                                                                                                                                                                                                                                                                  type: "POST",
                                                                                                                                                                                                                                                                                                                                  data: {date: date, username: user_name, latitudetodeliver: latitude_to_deliver, longitudetodeliver: longitude_to_deliver},
                                                                                                                                                                                                                                                                                                                                  success: function(data){
                                                                                                                                                                                                                                                                                                                                               if(data == 1){
                                                                                                                                                                                                                                                                                                                                                   loadOrdersToBeDelivered(date);
                                                                                                                                                                                                                                                                                                                                                   delivery_core_logic();
                                                                                                                                                                                                                                                                                                                                               }
                                                                                                                                                                                                                                                                                                                                           }
                                                                                                                                                                                                                                                                                                                      });
                                                                                                                                                                                                                                                                                                                  });
                                                                                                                                                                                                            }           
                                                                                                                                                                                                        }
                                                                                                                                                                                   });
                                                                                                                                                                               });
                                  }
                                  else if(has_already_started_delivery_before == true){
                                      $(document).off("click", ".click-when-you-begin-delivery-from-restaurant").on("click", ".click-when-you-begin-delivery-from-restaurant", function(){
                                                                                                                                                                                   $.ajax({
                                                                                                                                                                                               url: "set-source-location-as-restaurant.php",
                                                                                                                                                                                               type: "GET",
                                                                                                                                                                                               success: function(data){
                                                                                                                                                                                                            if(data == 1){
                                                                                                                                                                                                                has_clicked_begin_delivery_button = true;
                                                                                                                                                                                                                loadOrdersToBeDelivered(date); 
                                                                                                                                                                                                                delivery_core_logic();
                                                                                                                                                                                                                $(document).off("click", ".click-if-delivery-started").one("click", ".click-if-delivery-started", function(){
                                                                                                                                                                                                                                                                                                                      let date = $(this).data('date');
                                                                                                                                                                                                                                                                                                                      let user_name = $(this).data('user_name');
                                                                                                                                                                                                                                                                                                                      let latitude_to_deliver = $(this).data('latitude_to_deliver');
                                                                                                                                                                                                                                                                                                                      let longitude_to_deliver = $(this).data('longitude_to_deliver');
                                                                                                                                                                                                                                                                                                                      $.ajax({
                                                                                                                                                                                                                                                                                                                                  url: "set-destination-location-after-first-delivery-started-and-update-order-status.php",
                                                                                                                                                                                                                                                                                                                                  type: "POST",
                                                                                                                                                                                                                                                                                                                                  data: {date: date, username: user_name, latitudetodeliver: latitude_to_deliver, longitudetodeliver: longitude_to_deliver},
                                                                                                                                                                                                                                                                                                                                  success: function(data){
                                                                                                                                                                                                                                                                                                                                               if(data == 1){
                                                                                                                                                                                                                                                                                                                                                   loadOrdersToBeDelivered(date);
                                                                                                                                                                                                                                                                                                                                                   $(document).off("click", ".click-if-delivery-started").on("click", ".click-if-delivery-started", function(){                                                          
                                                                                                                                                                                                                                                                                                                                                                                                                                                        let date = $(this).data('date');
                                                                                                                                                                                                                                                                                                                                                                                                                                                        let user_name = $(this).data('user_name');
                                                                                                                                                                                                                                                                                                                                                                                                                                                        let latitude_to_deliver = $(this).data('latitude_to_deliver');
                                                                                                                                                                                                                                                                                                                                                                                                                                                        let longitude_to_deliver = $(this).data('longitude_to_deliver');
                                                                                                                                                                                                                                                                                                                                                                                                                                                        $.ajax({
                                                                                                                                                                                                                                                                                                                                                                                                                                                                    url: "set-source-location-and-destination-location-after-second-delivery-started-and-update-order-status.php",
                                                                                                                                                                                                                                                                                                                                                                                                                                                                    type: "POST",
                                                                                                                                                                                                                                                                                                                                                                                                                                                                    data: {latitudetodeliver: latitude_to_deliver, longitudetodeliver: longitude_to_deliver, username: user_name, date: date},
                                                                                                                                                                                                                                                                                                                                                                                                                                                                    success: function(data){
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 loadOrdersToBeDelivered(date); 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                             }
                                                                                                                                                                                                                                                                                                                                                                                                                                                        });                       
                                                                                                                                                                                                                                                                                                                                                                                                                                                    });
                                                                                                                                                                                                                                                                                                                                               }
                                                                                                                                                                                                                                                                                                                                           }
                                                                                                                                                                                                                                                                                                                      });
                                                                                                                                                                                                                                                                                                                  });
                                                                                                                                                                                                            }
                                                                                                                                                                                                        }  
                                                                                                                                                                                   });
                                                                                                                                                                               });
                                      $(document).off("click", ".click-if-delivery-started").on("click", ".click-if-delivery-started", function(){                                                          
                                                                                                                                           let date = $(this).data('date');
                                                                                                                                           let user_name = $(this).data('user_name');
                                                                                                                                           let latitude_to_deliver = $(this).data('latitude_to_deliver');
                                                                                                                                           let longitude_to_deliver = $(this).data('longitude_to_deliver');
                                                                                                                                           $.ajax({
                                                                                                                                                       url: "set-source-location-and-destination-location-after-second-delivery-started-and-update-order-status.php",
                                                                                                                                                       type: "POST",
                                                                                                                                                       data: {latitudetodeliver: latitude_to_deliver, longitudetodeliver: longitude_to_deliver, username: user_name, date: date},
                                                                                                                                                       success: function(data){
                                                                                                                                                                    loadOrdersToBeDelivered(date); 
                                                                                                                                                                }
                                                                                                                                           });                       
                                                                                                                                       });
                                      $(document).off("click", ".click-if-delivery-completed").on("click", ".click-if-delivery-completed", function(){
                                                                                                                                               let date = $(this).data('date');
                                                                                                                                               let user_name = $(this).data('user_name');
                                                                                                                                               $.ajax({
                                                                                                                                                           url: "update-order-status-after-delivery-completion.php",
                                                                                                                                                           type: "POST",
                                                                                                                                                           data: {date: date, username: user_name},
                                                                                                                                                           success: function(data){
                                                                                                                                                                        if(data == 1){
                                                                                                                                                                            loadOrdersToBeDelivered(date);
                                                                                                                                                                        }
                                                                                                                                                                    }
                                                                                                                                               });
                                                                                                                                           });
                                  }
                              }
                          });
    </script>
</body>