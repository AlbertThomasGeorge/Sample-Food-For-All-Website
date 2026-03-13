<?php
    // 5 DIV 2 = 2, similar to 5//2 i.e. 2
    include "../config.php";
    $date = mysqli_real_escape_string($conn, $_POST['date_input']);
    $sql = "SELECT order_id, item_name, quantity, user_id, cost_price_total, amount_donated, bank, order_status FROM orders WHERE DATE(FROM_UNIXTIME(unix_timestamp DIV 1000)) = '{$date}' AND are_you_in_restaurant = 'yes'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
        if(!isset($_POST['order_id'])){
            echo "<table><tr><th>User Name</th><th>Item Name</th><th>Quantity</th><th>Total Cost Price</th><th>Amount Donated</th><th>Bank</th><th>Order Status</th><th>Click if Prepared and Delivered</th></tr>";
            while($row = mysqli_fetch_assoc($result)){
                $order_id = mysqli_real_escape_string($conn, $row['order_id']);
                $bank = mysqli_real_escape_string($conn, $row['bank']);
                $order_status = mysqli_real_escape_string($conn, $row['order_status']);
                $sql_query = "SELECT user_name FROM users WHERE user_id = {$row['user_id']}";
                $query_result = mysqli_query($conn, $sql_query);
                if($query_result && (mysqli_num_rows($query_result) > 0)){
                    $row_of_result = mysqli_fetch_assoc($query_result);
                    $user_name = mysqli_real_escape_string($conn, $row_of_result['user_name']);
                    echo "<tr><td>{$user_name}</td><td>{$row['item_name']}</td><td>{$row['quantity']}</td><td>{$row['cost_price_total']}</td><td>{$row['amount_donated']}</td><td>{$bank}</td><td>{$order_status}</td><td><button class='prepared-and-delivered' data-order_id='{$order_id}'>Prepared and Delivered</button></td></tr>";
                }
                else{
                    $user_name = "Poor User";
                    echo "<tr><td>{$user_name}</td><td>{$row['item_name']}</td><td>{$row['quantity']}</td><td>{$row['cost_price_total']}</td><td>{$row['amount_donated']}</td><td>{$bank}</td><td>{$order_status}</td><td><button class='prepared-and-delivered' data-order_id='{$order_id}'>Prepared and Delivered</button></td></tr>";
                }
            }
            echo "</table>";
        }
        else{
            $sql_query_1 = "UPDATE orders SET order_status = 'PREPARED AND DELIVERED' WHERE order_id = {$_POST['order_id']}";
            $result_1 = mysqli_query($conn, $sql_query_1);
            if($result_1){
                echo "<table><tr><th>User Name</th><th>Item Name</th><th>Quantity</th><th>Total Cost Price</th><th>Amount Donated</th><th>Bank</th><th>Order Status</th><th>Click if Prepared and Delivered</th></tr>";
                $sql_query_2 = "SELECT order_id, item_name, quantity, user_id, cost_price_total, amount_donated, bank, order_status FROM orders WHERE DATE(FROM_UNIXTIME(unix_timestamp DIV 1000)) = '{$date}' AND are_you_in_restaurant = 'yes'";
                $result_2 = mysqli_query($conn, $sql_query_2);
                while($row_2 = mysqli_fetch_assoc($result_2)){
                    $order_id = mysqli_real_escape_string($conn, $row_2['order_id']);
                    $bank = mysqli_real_escape_string($conn, $row_2['bank']);
                    $order_status = mysqli_real_escape_string($conn, $row_2['order_status']);
                    $sql_query_3 = "SELECT user_name FROM users WHERE user_id = {$row_2['user_id']}";
                    $result_3 = mysqli_query($conn, $sql_query_3);
                    if($result_3 && (mysqli_num_rows($result_3) > 0)){
                        $row_3 = mysqli_fetch_assoc($result_3);
                        $user_name = mysqli_real_escape_string($conn, $row_3['user_name']);
                        echo "<tr><td>{$user_name}</td><td>{$row_2['item_name']}</td><td>{$row_2['quantity']}</td><td>{$row_2['cost_price_total']}</td><td>{$row_2['amount_donated']}</td><td>{$bank}</td><td>{$order_status}</td><td><button class='prepared-and-delivered' data-order_id='{$order_id}'>Prepared and Delivered</button></td></tr>";
                    }
                    else{
                        $user_name = "Poor User";
                        echo "<tr><td>{$user_name}</td><td>{$row_2['item_name']}</td><td>{$row_2['quantity']}</td><td>{$row_2['cost_price_total']}</td><td>{$row_2['amount_donated']}</td><td>{$bank}</td><td>{$order_status}</td><td><button class='prepared-and-delivered' data-order_id='{$order_id}'>Prepared and Delivered</button></td></tr>";
                    }
                }
                echo "</table>";    
            }
        }
    }
    else{
        echo "<h2>No Orders From Restaurant</h2>";
    }
?>