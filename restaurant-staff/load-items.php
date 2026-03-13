<?php
    include "../config.php";
    $sql = "SELECT * FROM items";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
            $filename = mysqli_real_escape_string($conn, $row['image_file']);
            $path = "../admin/images of items/".$filename;
            $costprice = mysqli_real_escape_string($conn, $row['only_cost_price']);
            $itemid = mysqli_real_escape_string($conn, $row['item_id']);
            echo "<div class='item'>
                     <img src='".$path."'>".
                     "<div class='cost-price'>Cost Price = ₹".$costprice."</div><div class='get'><a href=\"single-item.php?item_id=".$itemid."\">Get</a></div> 
                 </div>";
        }
    }
    else{
        echo "<h2 style='text-align: center'>No Items Available 🥺</h2>";
    }
    // on clicking Get a new page is opened
?>