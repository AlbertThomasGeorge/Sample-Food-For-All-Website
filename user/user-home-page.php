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
    <link rel="stylesheet" href="../css/user-home-page.css">
</head>
<body>
    <h1>Welcome <?php echo $_SESSION['user_name']; ?> to Food For All Website</h1>
    <p>Please don't donate or order between 23:55 and 00:05</p>   
    <button><a href='donate-freely.php'>Donate if wish 💕</a></button><br>
    <input type = "checkbox">
    <label>Are you in restaurant?</label>
    <div id="links">
        <div>
            <a href="intro-to-user.php">Please learn about us first</a>
        </div>
        <div id="available_items"></div>
        <div>
            <a href="needs-of-people-involved.php">Needs</a>
        </div>
        <div id="user-location">
            <a href="user-location.php"></a>
        </div>
        <div>
            <a href="user-logout.php">Logout</a>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script>
        $(document).ready(function(){
                              $.ajax({
                                          url: "location-added-or-not-check.php",
                                          type: "GET",
                                          success: function(data){
                                                       $('#user-location a').html(data);
                                                   }   
                              });
                              function available_items_link(){
                                  if($('input:checkbox').is(':checked')){
                                      $('#available_items').html("<a href='items-display.php?is_in_restaurant=yes'>Available Items</a>");
                                  }
                                  else{
                                      $('#available_items').html("<a href='items-display.php?is_in_restaurant=no'>Available Items</a>");
                                  }
                              }
                              available_items_link();
                              setInterval(available_items_link, 500);
                          });
    </script>
</body>
</html>