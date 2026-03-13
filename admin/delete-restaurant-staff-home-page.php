<?php
    include "session-timeout-check.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Panel</title>
    <link rel="stylesheet" href="../css/edit-or-delete-item-or-person-home-page.css">
</head>
<body>
    <h1 class="h1-heading">Click at the Restaurant Staff to Delete if there</h1>
    <?php
        include "../config.php";
        $sql = "SELECT restaurant_staff_id, restaurant_staff_name FROM restaurant_staff";
        $result = mysqli_query($conn, $sql) or die("Query Failed");
        if(mysqli_num_rows($result)>0){ 
    ?>
            <table class="table-style">
                <thead>
                    <th>Person Name</th>
                </thead>
                <tbody>
                    <?php 
                        while($row=mysqli_fetch_assoc($result)){ 
                    ?>
                            <tr>
                                <td><a href="delete-restaurant-staff.php?restaurantstaffid=<?php echo $row['restaurant_staff_id']; ?>"><?php echo $row['restaurant_staff_name']; ?></a></td>
                            </tr>
                    <?php
                        }
                    ?>
                </tbody>
            </table>
    <?php
        }
        else{
            echo "<p style='font-size:10px; color:blue; text-align:center; margin:10px 0;'>No Restaurant Staff 🥺</p>";  
        }
        mysqli_close($conn);
    ?>
</body>
