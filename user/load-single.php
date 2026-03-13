<?php
    include "../config.php";
    $is_in_restaurant = mysqli_real_escape_string($conn, $_POST['is_there_in_restaurant']);
    $itemid = mysqli_real_escape_string($conn, $_POST['item_id']);
    $sql = "SELECT * FROM items WHERE item_id = {$itemid}";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
            $filename = mysqli_real_escape_string($conn, $row['image_file']);
            $itemname = mysqli_real_escape_string($conn, $row['item_name']);
            $path = "../admin/images of items/".$filename;
            echo "<div class='item'>
                     <h3>{$itemname}</h3>
                     <img src='".$path."'>".
                     "<div><div><button class='quantity-increase'>+</button></div><div><button class='quantity-decrease'>-</button></div></div>
                     <input type='number' placeholder = 'Quantity Shown Here' value='".$_POST['qty']."' class='input'><button class='proceed'><a href='donate-if-you-wish.php?item_id=".$_POST['item_id']."&quantity=".$_POST['qty']."&is_in_restaurant=".$is_in_restaurant."'>Click here to proceed</a></button>
                 </div>";
        }
    }
    else{
        echo "Item Deleted";
    }
?>