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
    <h1 class="h1-heading">Click at the Delivery Person to Delete if there</h1>
    <?php
        include "../config.php";
        $sql = "SELECT delivery_person_id, delivery_person_name as `name` FROM delivery_people ";
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
                                <td><a href="delete-delivery-person.php?deliverypersonid=<?php echo $row['delivery_person_id']; ?>"><?php echo $row['name']; ?></a></td>
                            </tr>
                    <?php
                        }
                    ?>
                </tbody>
            </table>
    <?php
        }
        else{
            echo "<p style='font-size:10px; color:blue; text-align:center; margin:10px 0;'>No Delivery People 🥺</p>";  
        }
        mysqli_close($conn);
    ?>
</body>







