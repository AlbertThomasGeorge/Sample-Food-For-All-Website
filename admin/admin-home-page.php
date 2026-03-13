<?php
    include "session-timeout-check.php";
    include "../table-correction.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN | PANEL</title>
    <link rel="stylesheet" href="../css/admin-home-page.css">
</head>
<body>
    <h1 class="h1-heading">Admin Home Page</h1>
    <div class="links-container">
        <div class="add-person">
            <a href="add-person.php">Add Person</a>
        </div>
        <div class="delete-person">
            <div class="delete-restaurant-staff">
                <a href="delete-restaurant-staff-home-page.php">Delete Restaurant Staff</a>
            </div>
            <div class="delete-delivery-person">
                <a href="delete-delivery-person-home-page.php">Delete Delivery Person</a>
            </div>
        </div>
        <div class="item">
            <div class="add-item">
                <a href="add-item.php">Add Item</a>
            </div>
            <div class="edit-item">
                <a href="edit-item-home-page.php">Edit Item</a>
            </div>
        </div>
        <?php
            include "../config.php";
            date_default_timezone_set("Asia/Kolkata");
            $current_date = date("Y-m-d");
            $sql1 = "SELECT * FROM rent WHERE date_of_rent = '{$current_date}'";
            $result1 = mysqli_query($conn, $sql1);
            if(mysqli_num_rows($result1) == 0){
        ?>
                <div class="add-land-rent-if-there">
                    <a href="add-land-rent-if-there.php">Add land rent if there</a>
                </div>
        <?php
            }
            mysqli_close($conn);
        ?>
        <?php
            include "../config.php";
            $sql2 = "SELECT * FROM location_of_restaurant";
            $result2 = mysqli_query($conn, $sql2);
            if(mysqli_num_rows($result2) == 0){
        ?>
                <div class="add-location">
                    <a href="add-location.php">Add Location of Restaurant</a>
                </div>
        <?php
            }
            else if(mysqli_num_rows($result2) == 1){
        ?>
                <div class="edit-location">
                    <a href="edit-location.php">Edit Location of Restaurant</a>
                </div>
        <?php
            }
            mysqli_close($conn);
        ?>
        <div class="add-restaurant-staff-and-delivery-person-needs-for-day">
            <a href="add-restaurant-staff-and-delivery-person-needs-for-day.php">Add Restaurant Staff and Delivery Person needs for day</a>
        </div>
        <div class="admin-logout">
            <a href="admin-logout.php">Logout</a>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script>
        $(document).ready(function(){
                              $.ajax({
                                          url: "check-if-link-should-be-shown.php",
                                          type: "GET",
                                          success: function(data){
                                                       if(data != 0){
                                                           $(data).insertAfter('.add-restaurant-staff-and-delivery-person-needs-for-day');
                                                       }
                                                   }
                              });
                              $(document).on("click", ".reestimate-funds-if-no-needs-today", function(){
                                                                                                 $.ajax({
                                                                                                             url: "reestimate-funds-if-no-needs-today.php",
                                                                                                             type: "GET",
                                                                                                             success: function(data){
                                                                                                                          if(data == 1){
                                                                                                                              $('.reestimate-funds-if-no-needs-today').remove();
                                                                                                                          }
                                                                                                                      }  
                                                                                                 });
                                                                                             });
                              $(window).on('pageshow', function(event){
                                                           if (event.originalEvent.persisted) {
                                                               // Page was loaded from cache, force reload
                                                               location.reload();
                                                           }
                                                       });
                          });
    </script>  
</body>
</html>
