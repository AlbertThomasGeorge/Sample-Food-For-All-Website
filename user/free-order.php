<?php
    session_start();
    include "../config.php";
    $is_in_restaurant = mysqli_real_escape_string($conn, $_POST['is_present_in_restaurant']);
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
    $user_name = mysqli_real_escape_string($conn, $_SESSION['user_name']);
    $sql3 = "SELECT user_id FROM users WHERE user_name = '{$user_name}'";
    $result3 = mysqli_query($conn, $sql3);
    if($result3){
        $row3 = mysqli_fetch_assoc($result3);
        $user_id = mysqli_real_escape_string($conn, $row3['user_id']);
    }
    if($is_in_restaurant == "no"){
        $sql4 = "SELECT * FROM users WHERE latitude IS NULL AND longitude IS NULL AND user_id = {$user_id}";
        $result4 = mysqli_query($conn, $sql4);
        if(mysqli_num_rows($result4) == 0){
            $sql5 = "SELECT * FROM users WHERE user_id = {$user_id}";
            $result5 = mysqli_query($conn, $sql5);
            $row5 = mysqli_fetch_assoc($result5);
            $latitude_to_deliver = $row5['latitude'];
            $longitude_to_deliver = $row5['longitude'];
            $sql6 = "SELECT * FROM location_of_restaurant";
            $result6 = mysqli_query($conn, $sql6);
            $row6 = mysqli_fetch_assoc($result6);
            $latitude_of_restaurant = $row6['latitude'];
            $longitude_of_restaurant = $row6['longitude'];
            $sql7 = "INSERT INTO orders(item_name, quantity, user_id, cost_price_total, amount_donated, bank, unix_timestamp, are_you_in_restaurant, latitude_to_deliver, longitude_to_deliver, latitude_of_restaurant, longitude_of_restaurant) VALUES ('{$item_name}', {$quantity}, '{$user_id}', {$total_cost_price}, 0, 'NA', {$unix_time}, '{$is_in_restaurant}', {$latitude_to_deliver}, {$longitude_to_deliver}, {$latitude_of_restaurant}, {$longitude_of_restaurant})";
            $result7 = mysqli_query($conn, $sql7);
            if($result7){
                $sql8 = "SELECT * FROM max_X";
                $result8 = mysqli_query($conn, $sql8);
                $row8 = mysqli_fetch_assoc($result8);
                $max_X = mysqli_real_escape_string($conn, $row8['maximum_X_value']);
                if(($X==0) && ($Y==0)){
                    if($max_X >= $total_cost_price){
                        $new_X = $total_cost_price + 0;
                        $sql9 = "UPDATE funds SET X={$new_X}, Y={$Y}";
                        if(mysqli_query($conn, $sql9)){
                            mysqli_commit($conn);
                            echo 1;
                        }
                    }
                    else{
                        $new_X = $max_X;
                        $new_Y = $total_cost_price - $max_X;
                        $sql10 = "UPDATE funds SET X={$new_X}, Y={$new_Y}";
                        if(mysqli_query($conn, $sql10)){
                            mysqli_commit($conn);
                            echo 1;
                        }
                    }
                }
                else if(($X > 0) && ($Y==0) && (($max_X - $X) > $total_cost_price)){
                    $new_X = $total_cost_price + $X;
                    $sql11 = "UPDATE funds SET X={$new_X}, Y=0";
                    if(mysqli_query($conn, $sql11)){
                        mysqli_commit($conn);
                        echo 1;
                    }
                }
                else if(($X > 0) && ($Y==0) && (($max_X - $X) == $total_cost_price)){
                    $new_X = $max_X;
                    $sql12 = "UPDATE funds SET X={$new_X}, Y=0";
                    if(mysqli_query($conn, $sql12)){
                        mysqli_commit($conn);
                        echo 1;
                    }
                }
                else if(($X > 0) && ($Y==0) && (($max_X - $X) < $total_cost_price)){
                    $new_X = $max_X;
                    $new_Y = $total_cost_price - ($max_X - $X);
                    $sql13 = "UPDATE funds SET X={$new_X}, Y={$new_Y}";
                    if(mysqli_query($conn, $sql13)){
                        mysqli_commit($conn);
                        echo 1;
                    }
                }
                else if(($Y >= 0) && ($X==$max_X)){
                    $new_Y = $Y + $total_cost_price;
                    $sql14 = "UPDATE funds SET X={$X}, Y={$new_Y}";
                    if(mysqli_query($conn, $sql14)){
                        mysqli_commit($conn);
                        echo 1;
                    }
                }
            }
        }
        else{
            mysqli_commit($conn);
            echo 0;
        }
    }
    else{
        $sql15 = "SELECT * FROM location_of_restaurant";
        $result15 = mysqli_query($conn, $sql15);
        if($result15){
            $row15 = mysqli_fetch_assoc($result15);
            $latitude_of_restaurant = $row15['latitude'];
            $longitude_of_restaurant = $row15['longitude'];
            $sql16 = "INSERT INTO orders(item_name, quantity, user_id, cost_price_total, amount_donated, bank, unix_timestamp, are_you_in_restaurant, latitude_to_deliver, longitude_to_deliver, latitude_of_restaurant, longitude_of_restaurant) VALUES ('{$item_name}', {$quantity}, '{$user_id}', {$total_cost_price}, 0, 'NA', {$unix_time}, '{$is_in_restaurant}', {$latitude_of_restaurant}, {$longitude_of_restaurant}, {$latitude_of_restaurant}, {$longitude_of_restaurant})";
            $result16 = mysqli_query($conn, $sql16);
            if($result16){
                $sql17 = "SELECT * FROM max_X";
                $result17 = mysqli_query($conn, $sql17);
                $row17 = mysqli_fetch_assoc($result17);
                $max_X = mysqli_real_escape_string($conn, $row17['maximum_X_value']);
                if(($X==0) && ($Y==0)){
                    if($max_X >= $total_cost_price){
                        $new_X = $total_cost_price + 0;
                        $sql18 = "UPDATE funds SET X={$new_X}, Y={$Y}";
                        if(mysqli_query($conn, $sql18)){
                            mysqli_commit($conn);
                            echo 1;
                        }
                    }
                    else{
                        $new_X = $max_X;
                        $new_Y = $total_cost_price - $max_X;
                        $sql19 = "UPDATE funds SET X={$new_X}, Y={$new_Y}";
                        if(mysqli_query($conn, $sql19)){
                            mysqli_commit($conn);
                            echo 1;
                        }
                    }
                }
                else if(($X > 0) && ($Y==0) && (($max_X - $X) > $total_cost_price)){
                    $new_X = $total_cost_price + $X;
                    $sql20 = "UPDATE funds SET X={$new_X}, Y=0";
                    if(mysqli_query($conn, $sql20)){
                        mysqli_commit($conn);
                        echo 1;
                    }
                }
                else if(($X > 0) && ($Y==0) && (($max_X - $X) == $total_cost_price)){
                    $new_X = $max_X;
                    $sql21 = "UPDATE funds SET X={$new_X}, Y=0";
                    if(mysqli_query($conn, $sql21)){
                        mysqli_commit($conn);
                        echo 1;
                    }
                }
                else if(($X > 0) && ($Y==0) && (($max_X - $X) < $total_cost_price)){
                    $new_X = $max_X;
                    $new_Y = $total_cost_price - ($max_X - $X);
                    $sql22 = "UPDATE funds SET X={$new_X}, Y={$new_Y}";
                    if(mysqli_query($conn, $sql22)){
                        mysqli_commit($conn);
                        echo 1;
                    }
                }
                else if(($Y >= 0) && ($X==$max_X)){
                    $new_Y = $Y + $total_cost_price;
                    $sql23 = "UPDATE funds SET X={$X}, Y={$new_Y}";
                    if(mysqli_query($conn, $sql23)){
                        mysqli_commit($conn);
                        echo 1;
                    }
                }
            } 
        }    
    }
?>