<?php
    session_start();
    include "../config.php";
    $sql = "SELECT * FROM location_of_restaurant";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $latitude_of_restaurant = $row['latitude'];
    $longitude_of_restaurant = $row['longitude'];
    $unix_time = mysqli_real_escape_string($conn, $_POST['unix']);
    $total_cost_price =  mysqli_real_escape_string($conn, $_POST['totalcostprice']);
    $itemid = mysqli_real_escape_string($conn, $_POST['item_id']);
    $quantity = mysqli_real_escape_string($conn, $_POST['qty']);
    mysqli_begin_transaction($conn);
    $sql1 = "SELECT * FROM funds FOR UPDATE";
    $result1 = mysqli_query($conn, $sql1);
    $row1 = mysqli_fetch_assoc($result1);
    $X = mysqli_real_escape_string($conn, $row1['X']);
    $Y = mysqli_real_escape_string($conn, $row1['Y']);
    $sql2 = "SELECT * FROM items WHERE item_id = {$itemid}";
    $result2 = mysqli_query($conn, $sql2);
    if($result2 && (mysqli_num_rows($result2) == 1)){
        $row2 = mysqli_fetch_assoc($result2);
        $item_name = mysqli_real_escape_string($conn, $row2['item_name']);
    }
    else{
        mysqli_commit($conn);
        echo "Item Deleted Recently";
        die();
    }
    $sql3 = "INSERT INTO orders(item_name, quantity, user_id, cost_price_total, amount_donated, bank, unix_timestamp, are_you_in_restaurant, latitude_to_deliver, longitude_to_deliver, latitude_of_restaurant, longitude_of_restaurant) VALUES ('{$item_name}', {$quantity}, 'NA', {$total_cost_price}, 0, 'NA', {$unix_time}, 'yes', {$latitude_of_restaurant}, {$longitude_of_restaurant}, {$latitude_of_restaurant}, {$longitude_of_restaurant})";
    $result3 = mysqli_query($conn, $sql3);
    if($result3){
        $sql4 = "SELECT * FROM max_X";
        $result4 = mysqli_query($conn, $sql4);
        $row4 = mysqli_fetch_assoc($result4);
        $max_X = mysqli_real_escape_string($conn, $row4['maximum_X_value']);
        if(($X==0) && ($Y==0)){
            if($max_X >= $total_cost_price){
                $new_X = $total_cost_price + 0;
                $sql5 = "UPDATE funds SET X={$new_X}, Y={$Y}";
                if(mysqli_query($conn, $sql5)){
                    mysqli_commit($conn);
                    echo 1;
                }
            }
            else{
                $new_X = $max_X;
                $new_Y = $total_cost_price - $max_X;
                $sql6 = "UPDATE funds SET X={$new_X}, Y={$new_Y}";
                if(mysqli_query($conn, $sql6)){
                    mysqli_commit($conn);
                    echo 1;
                }
            }
        }
        else if(($X > 0) && ($Y==0) && (($max_X - $X) > $total_cost_price)){
            $new_X = $total_cost_price + $X;
            $sql7 = "UPDATE funds SET X={$new_X}, Y=0";
            if(mysqli_query($conn, $sql7)){
                mysqli_commit($conn);
                echo 1;
            }
        }
        else if(($X > 0) && ($Y==0) && (($max_X - $X) == $total_cost_price)){
            $new_X = $max_X;
            $sql8 = "UPDATE funds SET X={$new_X}, Y=0";
            if(mysqli_query($conn, $sql8)){
                mysqli_commit($conn);
                echo 1;
            }
        }
        else if(($X > 0) && ($Y==0) && (($max_X - $X) < $total_cost_price)){
            $new_X = $max_X;
            $new_Y = $total_cost_price - ($max_X - $X);
            $sql9 = "UPDATE funds SET X={$new_X}, Y={$new_Y}";
            if(mysqli_query($conn, $sql9)){
                mysqli_commit($conn);
                echo 1;
            }
        }
        else if(($Y >= 0) && ($X==$max_X)){
            $new_Y = $Y + $total_cost_price;
            $sql10 = "UPDATE funds SET X={$X}, Y={$new_Y}";
            if(mysqli_query($conn, $sql10)){
                mysqli_commit($conn);
                echo 1;
            }
        }
    }
?>