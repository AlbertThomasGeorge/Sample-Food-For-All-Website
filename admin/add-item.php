<?php
    include "session-timeout-check.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Panel</title>
    <link rel="stylesheet" href="../css/add-item.css">
</head>
<body>
    <h1 class="h1-heading">Add Item</h1>
    <div class="add-item-container">
        <form action="save-item.php" method ="POST" enctype="multipart/form-data">
            <div>
                <label class="item-name">Item Name</label>
                <input type="text" name="item-name">
            </div>
            <div>
                <label class="cost-price-purely">Cost Price purely</label>
                <input type="text" name="cost-price">
            </div>
            <div class="file-container">
                <label class="item-image-file">Post item image below</label>
                <input type="file" name="item-file">
            </div>
            <input type="submit" name="save" value="Add Item" class="inputclass">
        </form>
    </div>
</body>
