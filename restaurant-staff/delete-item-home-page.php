<?php
    include "session-timeout-check.php";
    include "../table-correction.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="1"> <!--every one second page is automatically refreshed-->
    <title>Restaurant Staff</title>
    <link rel="stylesheet" href="../css/edit-or-delete-item-or-person-home-page.css">
</head>
<body>
    <h1 class="h1-heading">Click at the Item to Delete if there</h1>
    <?php
        include "../config.php";
        $sql = "SELECT * FROM items";
        $result = mysqli_query($conn, $sql) or die("Query Failed");
        if(mysqli_num_rows($result)>0){ 
    ?>
            <table class="table-style">
                <thead>
                    <th>Item Name</th>
                </thead>
                <tbody>
                    <?php 
                        while($row=mysqli_fetch_assoc($result)){ 
                    ?>
                            <tr>
                                <td><a href="delete-item.php?itemid=<?php echo $row['item_id']; ?>&itemfile=<?php echo $row['image_file']; ?>"><?php echo $row['item_name']; ?></a></td>
                            </tr>
                    <?php
                        }
                    ?>
                </tbody>
            </table>
    <?php
        }
        else{
            echo "<p style='font-size:10px; color:blue; text-align:center; margin:10px 0;'>No items 🥺</p>";  
        }
        mysqli_close($conn);
    ?>
</body>