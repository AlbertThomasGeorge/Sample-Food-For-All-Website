<?php
    session_start();
    include "../config.php";
    $is_there_in_restaurant = mysqli_real_escape_string($conn, $_POST['is_present_in_restaurant']);
    $unix_time = mysqli_real_escape_string($conn, $_POST['unixtime']);
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);
    $bank = mysqli_real_escape_string($conn, $_POST['bank_used']);
    $itemid = mysqli_real_escape_string($conn, $_POST['itemid']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $totalcostprice = mysqli_real_escape_string($conn, $_POST['total_cost_price']);
    $sql1 = "SELECT * FROM items WHERE item_id = {$itemid}";
    $result1 = mysqli_query($conn, $sql1);
    if($result1 && (mysqli_num_rows($result1) == 1)){
        $row1 = mysqli_fetch_assoc($result1);
        $item_name = mysqli_real_escape_string($conn, $row1['item_name']);
    }
    else{
        echo "Item Deleted Recently";
        die();
    }
    $user_name = mysqli_real_escape_string($conn, $_SESSION['user_name']);
    $sql2 = "SELECT user_id FROM users WHERE user_name = '{$user_name}'";
    $result2 = mysqli_query($conn, $sql2);
    if($result2){
        $row2 = mysqli_fetch_assoc($result2);
        $user_id = mysqli_real_escape_string($conn, $row2['user_id']);
    }
    if($is_there_in_restaurant == "no"){
        $sql3 = "SELECT * FROM users WHERE latitude IS NULL AND longitude IS NULL AND user_id = {$user_id}";
        $result3 = mysqli_query($conn, $sql3);
        if(mysqli_num_rows($result3) == 0){
            $sql4 = "SELECT * FROM users WHERE user_id = {$user_id}";
            $result4 = mysqli_query($conn, $sql4);
            $row4 = mysqli_fetch_assoc($result4);
            $latitude_to_deliver = $row4['latitude'];
            $longitude_to_deliver = $row4['longitude'];
            $sql5 = "SELECT * FROM location_of_restaurant";
            $result5 = mysqli_query($conn, $sql5);
            $row5 = mysqli_fetch_assoc($result5);
            $latitude_of_restaurant = $row5['latitude'];
            $longitude_of_restaurant = $row5['longitude'];
            $sql6 = "INSERT INTO orders(item_name, quantity, user_id, cost_price_total, amount_donated, bank, unix_timestamp, are_you_in_restaurant, latitude_to_deliver, longitude_to_deliver, latitude_of_restaurant, longitude_of_restaurant) VALUES ('{$item_name}', {$quantity}, '{$user_id}', {$totalcostprice}, {$amount}, '{$bank}', {$unix_time}, '{$is_there_in_restaurant}', {$latitude_to_deliver}, {$longitude_to_deliver}, {$latitude_of_restaurant}, {$longitude_of_restaurant})";
            if(mysqli_query($conn, $sql6)){
                mysqli_begin_transaction($conn); // a transaction is started
                // SELECT * FROM funds FOR UPDATE <- reading values of funds is locked if there is a transaction that read value, acquired lock to maybe update but hasn't yet commited which in fact leads to release of lock
                $sql7 = "SELECT * FROM funds FOR UPDATE"; 
                $result7 = mysqli_query($conn, $sql7); 
                $row7 = mysqli_fetch_assoc($result7);
                $X = mysqli_real_escape_string($conn, $row7['X']);
                $Y = mysqli_real_escape_string($conn, $row7['Y']);
                if($amount > $totalcostprice){
                    $extra_donated = $amount-$totalcostprice;
                    if(($Y == 0) && ($X > $extra_donated)){
                        $new_X = $X - $extra_donated;
                        $sql8 = "UPDATE funds SET X={$new_X}, Y=0";
                        if(mysqli_query($conn, $sql8)){
                            mysqli_commit($conn);
                            echo 1;
                        }
                    }
                    else if(($Y == 0) && ($X < $extra_donated)){
                        $X = $X + $totalcostprice;
                        $sql9 = "UPDATE orders SET amount_donated = {$X} WHERE unix_timestamp = {$unix_time} AND user_id = {$user_id}";
                        if(mysqli_query($conn, $sql9)){
                            // refund extra amount in real life
                            $sql10 = "UPDATE funds SET X=0, Y=0";
                            if(mysqli_query($conn, $sql10)){
                                mysqli_commit($conn);
                                echo 1;
                            }
                        }
                    }
                    else if(($Y == 0) && ($X == $extra_donated)){
                        $sql11 = "UPDATE funds SET X=0, Y=0";
                        if(mysqli_query($conn, $sql11)){
                            mysqli_commit($conn);
                            echo 1;
                        }
                    }
                    else if($Y == $extra_donated){
                        $sql12 = "UPDATE funds SET X={$X}, Y=0";
                        if(mysqli_query($conn, $sql12)){
                            mysqli_commit($conn);
                            echo 1;
                        }
                    }
                    else if($Y > $extra_donated){
                        $new_Y = $Y - $extra_donated;
                        $sql13 = "UPDATE funds SET X={$X}, Y={$new_Y}";
                        if(mysqli_query($conn, $sql13)){
                            mysqli_commit($conn);
                            echo 1;
                        }
                    }
                    else if(($Y < $extra_donated) && ($X < ($extra_donated - $Y))){
                        $total_funds = $X + $Y + $totalcostprice;
                        $sql14 = "UPDATE orders SET amount_donated = {$total_funds} WHERE unix_timestamp = {$unix_time} AND user_id = {$user_id}";
                        if(mysqli_query($conn, $sql14)){
                            // refund extra amount in real life
                            $sql15 = "UPDATE funds SET X=0, Y=0";
                            if(mysqli_query($conn, $sql15)){
                                mysqli_commit($conn);
                                echo 1;
                            }
                        }
                    }
                    else if(($Y < $extra_donated) && ($X >= ($extra_donated - $Y))){
                        $new_Y = 0;
                        $X_fund_covered = $extra_donated-$Y;
                        $new_X = $X - $X_fund_covered;
                        $sql16 = "UPDATE funds SET X={$new_X}, Y={$new_Y}";
                        if(mysqli_query($conn, $sql16)){
                            mysqli_commit($conn);
                            echo 1;
                        }
                    }
                }
                else if($amount == $totalcostprice){
                    // X and Y remain unchanged
                    mysqli_commit($conn);
                    echo 1;
                }
                else{
                    $donation_deficit = $totalcostprice - $amount;
                    $sql17 = "SELECT * FROM max_X";
                    $result17 = mysqli_query($conn, $sql17);
                    $row17 = mysqli_fetch_assoc($result17);
                    $max_X = $row17['maximum_X_value'];
                    if(($X==0) && ($Y==0)){
                        if($max_X >= $donation_deficit){
                            $new_X = $donation_deficit + 0;
                            $sql18 = "UPDATE funds SET X={$new_X}, Y={$Y}";
                            if(mysqli_query($conn, $sql18)){
                                mysqli_commit($conn);
                                echo 1;
                            }
                        }
                        else{
                            $new_X = $max_X;
                            $new_Y = $donation_deficit - $max_X;
                            $sql19 = "UPDATE funds SET X={$new_X}, Y={$new_Y}";
                            if(mysqli_query($conn, $sql19)){
                                mysqli_commit($conn);
                                echo 1;
                            }
                        }
                    }
                    else if(($X > 0) && ($Y==0) && (($max_X - $X) > $donation_deficit)){
                        $new_X = $donation_deficit + $X;
                        $sql20 = "UPDATE funds SET X={$new_X}, Y=0";
                        if(mysqli_query($conn, $sql20)){
                            mysqli_commit($conn);
                            echo 1;
                        }
                    }
                    else if(($X > 0) && ($Y==0) && (($max_X - $X) == $donation_deficit)){
                        $new_X = $max_X;
                        $sql21 = "UPDATE funds SET X={$new_X}, Y=0";
                        if(mysqli_query($conn, $sql21)){
                            mysqli_commit($conn);
                            echo 1;
                        }
                    }
                    else if(($X > 0) && ($Y==0) && (($max_X - $X) < $donation_deficit)){
                        $new_X = $max_X;
                        $new_Y = $donation_deficit - ($max_X - $X);
                        $sql22 = "UPDATE funds SET X={$new_X}, Y={$new_Y}";
                        if(mysqli_query($conn, $sql22)){
                            mysqli_commit($conn);
                            echo 1;
                        }
                    }
                    else if(($Y >= 0) && ($X==$max_X)){
                        $new_Y = $Y + $donation_deficit;
                        $sql23 = "UPDATE funds SET X={$X}, Y={$new_Y}";
                        if(mysqli_query($conn, $sql23)){
                            mysqli_commit($conn);
                            echo 1;
                        }
                    }
                }
            }
        }
        else{
            echo 0;
        }
    }
    else{
        $sql24 = "SELECT * FROM location_of_restaurant";
        $result24 = mysqli_query($conn, $sql24);
        $row24 = mysqli_fetch_assoc($result24);
        $latitude_of_restaurant = $row24['latitude'];
        $longitude_of_restaurant = $row24['longitude'];
        $sql25 = "INSERT INTO orders(item_name, quantity, user_id, cost_price_total, amount_donated, bank, unix_timestamp, are_you_in_restaurant, latitude_to_deliver, longitude_to_deliver, latitude_of_restaurant, longitude_of_restaurant) VALUES ('{$item_name}', {$quantity}, '{$user_id}', {$totalcostprice}, {$amount}, '{$bank}', {$unix_time}, '{$is_there_in_restaurant}', {$latitude_of_restaurant}, {$longitude_of_restaurant}, {$latitude_of_restaurant}, {$longitude_of_restaurant})";
        if(mysqli_query($conn, $sql25)){
            mysqli_begin_transaction($conn);
            $sql26 = "SELECT * FROM funds FOR UPDATE";
            $result26 = mysqli_query($conn, $sql26);
            $row26 = mysqli_fetch_assoc($result26);
            $X = mysqli_real_escape_string($conn, $row26['X']);
            $Y = mysqli_real_escape_string($conn, $row26['Y']);
            if($amount > $totalcostprice){
                $extra_donated = $amount-$totalcostprice;
                if(($Y == 0) && ($X > $extra_donated)){
                    $new_X = $X - $extra_donated;
                    $sql27 = "UPDATE funds SET X={$new_X}, Y=0";
                    if(mysqli_query($conn, $sql27)){
                        mysqli_commit($conn);
                        echo 1;
                    }
                }
                else if(($Y == 0) && ($X < $extra_donated)){
                    $X = $X + $totalcostprice;
                    $sql28 = "UPDATE orders SET amount_donated = {$X} WHERE unix_timestamp = {$unix_time} AND user_id = {$user_id}";
                    if(mysqli_query($conn, $sql28)){
                        // refund extra amount in real life
                        $sql29 = "UPDATE funds SET X=0, Y=0";
                        if(mysqli_query($conn, $sql29)){
                            mysqli_commit($conn);
                            echo 1;
                        }
                    }
                }
                else if(($Y == 0) && ($X == $extra_donated)){
                    $sql30 = "UPDATE funds SET X=0, Y=0";
                    if(mysqli_query($conn, $sql30)){
                        mysqli_commit($conn);
                        echo 1;
                    }
                }
                else if($Y == $extra_donated){
                    $sql31 = "UPDATE funds SET X={$X}, Y=0";
                    if(mysqli_query($conn, $sql31)){
                        mysqli_commit($conn);
                        echo 1;
                    }
                }
                else if($Y > $extra_donated){
                    $new_Y = $Y - $extra_donated;
                    $sql32 = "UPDATE funds SET X={$X}, Y={$new_Y}";
                    if(mysqli_query($conn, $sql32)){
                        mysqli_commit($conn);
                        echo 1;
                    }
                }
                else if(($Y < $extra_donated) && ($X < ($extra_donated - $Y))){
                    $total_funds = $X + $Y + $totalcostprice;
                    $sql33 = "UPDATE orders SET amount_donated = {$total_funds} WHERE unix_timestamp = {$unix_time} AND user_id = {$user_id}";
                    if(mysqli_query($conn, $sql33)){
                        // refund extra amount in real life
                        $sql34 = "UPDATE funds SET X=0, Y=0";
                        if(mysqli_query($conn, $sql34)){
                            mysqli_commit($conn);
                            echo 1;
                        }
                    }
                }
                else if(($Y < $extra_donated) && ($X >= ($extra_donated - $Y))){
                    $new_Y = 0;
                    $X_fund_covered = $extra_donated-$Y;
                    $new_X = $X - $X_fund_covered;
                    $sql35 = "UPDATE funds SET X={$new_X}, Y={$new_Y}";
                    if(mysqli_query($conn, $sql35)){
                        mysqli_commit($conn);
                        echo 1;
                    }
                }
            }
            else if($amount == $totalcostprice){
                mysqli_commit($conn);
                echo 1;
            }
            else{
                $donation_deficit = $totalcostprice - $amount;
                $sql36 = "SELECT * FROM max_X";
                $result36 = mysqli_query($conn, $sql36);
                $row36 = mysqli_fetch_assoc($result36);
                $max_X = $row36['maximum_X_value'];
                if(($X==0) && ($Y==0)){
                    if($max_X >= $donation_deficit){
                        $new_X = $donation_deficit + 0;
                        $sql37 = "UPDATE funds SET X={$new_X}, Y={$Y}";
                        if(mysqli_query($conn, $sql37)){
                            mysqli_commit($conn);
                            echo 1;
                        }
                    }
                    else{
                        $new_X = $max_X;
                        $new_Y = $donation_deficit - $max_X;
                        $sql38 = "UPDATE funds SET X={$new_X}, Y={$new_Y}";
                        if(mysqli_query($conn, $sql38)){
                            mysqli_commit($conn);
                            echo 1;
                        }
                    }
                }
                else if(($X > 0) && ($Y==0) && (($max_X - $X) > $donation_deficit)){
                    $new_X = $donation_deficit + $X;
                    $sql39 = "UPDATE funds SET X={$new_X}, Y=0";
                    if(mysqli_query($conn, $sql39)){
                        mysqli_commit($conn);
                        echo 1;
                    }
                }
                else if(($X > 0) && ($Y==0) && (($max_X - $X) == $donation_deficit)){
                    $new_X = $max_X;
                    $sql40 = "UPDATE funds SET X={$new_X}, Y=0";
                    if(mysqli_query($conn, $sql40)){
                        mysqli_commit($conn);
                        echo 1;
                    }
                }
                else if(($X > 0) && ($Y==0) && (($max_X - $X) < $donation_deficit)){
                    $new_X = $max_X;
                    $new_Y = $donation_deficit - ($max_X - $X);
                    $sql41 = "UPDATE funds SET X={$new_X}, Y={$new_Y}";
                    if(mysqli_query($conn, $sql41)){
                        mysqli_commit($conn);
                        echo 1;
                    }
                }
                else if(($Y >= 0) && ($X==$max_X)){
                    $new_Y = $Y + $donation_deficit;
                    $sql42 = "UPDATE funds SET X={$X}, Y={$new_Y}";
                    if(mysqli_query($conn, $sql42)){
                        mysqli_commit($conn);
                        echo 1;
                    }
                }
            }
        } 
    }
?>