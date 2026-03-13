<?php
    session_start();
    include "../config.php";
    $unix_time = mysqli_real_escape_string($conn, $_POST['unixtime']);
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);
    $bank = mysqli_real_escape_string($conn, $_POST['bank_used']);
    $restaurant_staff_name = mysqli_real_escape_string($conn, $_SESSION['restaurant_staff_name']);
    $sql1 = "SELECT * FROM  restaurant_staff WHERE restaurant_staff_name = '{$restaurant_staff_name}'";
    $result1 = mysqli_query($conn, $sql1);
    if($result1){
        $row1 = mysqli_fetch_assoc($result1);
        $restaurantstaffid = $row1['restaurant_staff_id'];
        $person_id = $row1['restaurant_staff_id'].'R';
    }
    $sql2 = "INSERT INTO free_donations(amount_donated, unix_timestamp, person_id, bank) VALUES ({$amount}, {$unix_time}, '{$person_id}', '{$bank}')";
    if(mysqli_query($conn, $sql2)){
        mysqli_begin_transaction($conn);
        $sql3 = "SELECT * FROM funds FOR UPDATE";
        $result3 = mysqli_query($conn, $sql3);
        $row3 = mysqli_fetch_assoc($result3);
        $X = mysqli_real_escape_string($conn, $row3['X']);
        $Y = mysqli_real_escape_string($conn, $row3['Y']);
        if(($Y == 0) && ($X > $amount)){
            $new_X = $X - $amount;
            $sql4 = "UPDATE funds SET X={$new_X}, Y=0";
            if(mysqli_query($conn, $sql4)){
                mysqli_commit($conn);
                echo 1;
            }
        }
        else if(($Y == 0) && ($X < $amount)){
            $sql5 = "UPDATE free_donations SET amount_donated = {$X} WHERE unix_timestamp = {$unix_time} AND person_id = '{$person_id}'";
            if(mysqli_query($conn, $sql5)){
                // refund extra amount in real life
                $sql6 = "UPDATE funds SET X=0, Y=0";
                if(mysqli_query($conn, $sql6)){
                    mysqli_commit($conn);
                    echo 1;
                }
            }
        }
        else if(($Y == 0) && ($X == $amount)){
            $sql7 = "UPDATE funds SET X=0, Y=0";
            if(mysqli_query($conn, $sql7)){
                mysqli_commit($conn);
                echo 1;
            }
        }
        else if($Y == $amount){
            $sql8 = "UPDATE funds SET X={$X}, Y=0";
            if(mysqli_query($conn, $sql8)){
                mysqli_commit($conn);
                echo 1;
            }
        }
        else if($Y > $amount){
            $new_Y = $Y - $amount;
            $sql9 = "UPDATE funds SET X={$X}, Y={$new_Y}";
            if(mysqli_query($conn, $sql9)){
                mysqli_commit($conn);
                echo 1;
            }
        }
        else if(($Y < $amount) && ($X < ($amount - $Y))){
            $total_funds = $X + $Y;
            $sql10 = "UPDATE free_donations SET amount_donated = {$total_funds} WHERE unix_timestamp = {$unix_time} AND person_id = '{$person_id}'";
            if(mysqli_query($conn, $sql10)){
                // refund excess donation
                $new_Y = 0;
                $new_X = 0;
                $sql11 = "UPDATE funds SET X={$new_X}, Y={$new_Y}";
                if(mysqli_query($conn, $sql11)){
                    mysqli_commit($conn);
                    echo 1;
                }
            }
        }
        else if(($Y < $amount) && ($X >= ($amount - $Y))){
            $new_Y = 0;
            $X_fund_covered = $amount-$Y;
            $new_X = $X - $X_fund_covered;
            $sql12 = "UPDATE funds SET X={$new_X}, Y={$new_Y}";
            if(mysqli_query($conn, $sql12)){
                mysqli_commit($conn);
                echo 1;
            }
        }
    }
?>