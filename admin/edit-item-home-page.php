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
    <h1 class="h1-heading">Click at the Item to Edit if there</h1>
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
                                <td><a href="edit-item.php?itemid=<?php echo $row['item_id']; ?>"><?php echo $row['item_name']; ?></a></td>
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
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script>
        // pageshow event occurs once each time page is shown 
        $(window).on('pageshow', function(event){
                                     if (event.originalEvent.persisted) {
                                         // Page was loaded from cache, force reload
                                         location.reload();
                                     }
                                 });
    </script>
</body>
