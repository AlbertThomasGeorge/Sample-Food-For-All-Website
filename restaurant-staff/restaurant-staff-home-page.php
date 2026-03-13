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
    <link rel="stylesheet" href="../css/restaurant-staff-home-page.css">
</head>
<body>
    <h1>Welcome <?php echo $_SESSION['restaurant_staff_name']; ?> to Food For All Website</h1>
    <p>Please don't donate between 23:55 and 00:05</p>   
    <button><a href='donate-freely.php'>Donate if wish 💕</a></button><br>
    <div id="links">
        <div>
            <a href="rules-for-restaurant-staff.php">Revise Rules</a>
        </div>
        <div id="orders-from-restaurant"><a href="orders-from-restaurant.php">Orders From<br>Restaurant</a></div>
        <div id="orders-from-not-restaurant"><a href="orders-from-not-restaurant.php">Orders From Not Restaurant</div>
        <div id="self-order-for-a-person-who-cannot-afford"><a href="items-display-for-self-order-for-poor.php">Self Order<br>for a person<br>who cannot afford</a></div>
        <div><a href="delete-item-home-page.php">Delete Items</a></div>
        <div>
            <a href="needs-added-by-admin.php">View Needs<br>Added By Admin</a>
        </div>
        <div>
            <a href="restaurant-staff-logout.php">Logout</a>
        </div>
    </div>
</body>
</html>