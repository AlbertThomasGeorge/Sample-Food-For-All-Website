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
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
    <link rel="stylesheet" href="../css/location.css">
</head>
<body>
    <?php
        if(isset($_POST['add-saved-location'])){
            include "../config.php";
            $username = mysqli_real_escape_string($conn, $_SESSION['user_name']);
            $latitude = mysqli_real_escape_string($conn, $_POST["latitude"]);
            $longitude = mysqli_real_escape_string($conn, $_POST["longitude"]);
            $sql = "UPDATE users SET latitude = {$latitude}, longitude = {$longitude} WHERE user_name = '{$username}'";
            if(mysqli_query($conn, $sql)){
                mysqli_close($conn);
                header("Location: http://localhost/MINI-PROJECT/user/user-home-page.php");
            }
            else{
                mysqli_close($conn);
                echo "<p style='font-size:10px; color:red; text-align:center; margin:10px 0;'>Location of Restaurant not added 🥺</p>";
                die();
            }
        }
    ?>
    <h1 class="h1-heading">Add or Edit your Location <?php echo $_SESSION['user_name']; ?></h1>
    <div id="map"></div>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">
        <input type="submit" name="add-saved-location" value="Save Selected Location" class="inputclass"> 
    </form>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="../js/location.js"></script>
</body>