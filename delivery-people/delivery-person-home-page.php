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
    <link rel="stylesheet" href="../css/delivery-person-home-page.css">
</head>
<body>
    <h1>Welcome <?php echo $_SESSION['delivery_person_name']; ?> to Food For All Website</h1>
    <p>Please don't donate between 23:55 and 00:05</p>   
    <button><a href='donate-freely.php'>Donate if wish 💕</a></button><br>
    <div id="links">
        <div>
            <a href="rules-for-delivery-person.php">Revise Rules</a>
        </div>
        <div><a href="assigned-orders-that-are-yet-to-be-delivered.php">Orders Assigned that are yet to be delivered</a></div>
        <div>
            <a href="needs-added-by-admin.php">View Needs<br>Added By Admin</a>
        </div>
        <div>
            <a href="delivery-person-logout.php">Logout</a>
        </div>
    </div>
</body>
</html>