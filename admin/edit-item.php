<?php
    include "session-timeout-check.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Panel</title>
    <link rel="stylesheet" href="../css/edit-item.css">
</head>
<body>
    <h1 class="h1-heading">Edit Item</h1>
    <div class="edit-item-container">
        <?php
            $itemid = $_GET['itemid'];
            include "../config.php";
            $sql = "SELECT * FROM items WHERE item_id = {$itemid}";
            $result = mysqli_query($conn, $sql);
            if($result){
                while($row = mysqli_fetch_assoc($result)){
        ?>
                    <form action="save-edit-item.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="item-id" value="<?php echo $row['item_id']; ?>">
                        <div>
                            <label class="item-name">Item Name</label>
                            <input type="text" name="name" value="<?php echo $row['item_name']; ?>">
                        </div>
                        <div>
                            <label class="cost-price-purely">Cost Price purely</label>
                            <input type="text" name="costpricepurely" value="<?php echo $row['only_cost_price']; ?>">
                        </div>
                        <div class="file-container">
                            <label class="item-image-file">Item Image File Edit below</label>
                            <input type="file" name="new-item-file">
                        </div>
                        <h2>Present Item Image</h2>
                        <img src="images of items/<?php echo $row['image_file']; ?>" height="150px" width="150px"><br>
                        <input type="hidden" name="old-item-image" value="<?php echo $row['image_file']; ?>">
                        <input type="submit" name="save" value="Save" class="inputclass">
                    </form>
        <?php
                }
            }
            mysqli_close($conn);
        ?>
    </div>
</body>