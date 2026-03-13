<?php
    function haversineDistance($lat1, $lon1, $lat2, $lon2) {
        $earthRadius = 6371; // Earth radius in kilometers  
        // Convert degrees to radians
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);
        // Differences
        $dLat = $lat2 - $lat1;
        $dLon = $lon2 - $lon1;
        // Haversine formula
        $a = sin($dLat/2) * sin($dLat/2) + cos($lat1) * cos($lat2) * sin($dLon/2) * sin($dLon/2);
        $b = 2 * atan2(sqrt($a), sqrt(1-$a));
        $distance = $earthRadius * $b;
        return $distance; // distance in km
    }
    // 5 DIV 2 = 2, similar to 5//2 i.e. 2
    include "../config.php";
    $date = mysqli_real_escape_string($conn, $_POST['date_input']);
    $sql = "SELECT order_id, item_name, quantity, user_id, cost_price_total, amount_donated, bank, order_status FROM orders WHERE DATE(FROM_UNIXTIME(unix_timestamp DIV 1000)) = '{$date}' AND are_you_in_restaurant = 'no'"; // indian date got
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
        if(!isset($_POST['order_id'])){
            echo "<table><tr><th>User Name</th><th>Item Name</th><th>Quantity</th><th>Total Cost Price</th><th>Amount Donated</th><th>Bank</th><th>Approx Distance from<br>current restaurant location</th><th>Order Status</th><th>Click if Prepared</th><th>Assign</th></tr>";
            while($row = mysqli_fetch_assoc($result)){
                $order_id = mysqli_real_escape_string($conn, $row['order_id']);
                $order_status = mysqli_real_escape_string($conn, $row['order_status']);
                $bank = mysqli_real_escape_string($conn, $row['bank']);
                $sql1 = "SELECT user_name FROM users WHERE user_id = {$row['user_id']}";
                $result1 = mysqli_query($conn, $sql1);
                if($result1 && (mysqli_num_rows($result1) > 0)){
                    $row1 = mysqli_fetch_assoc($result1);
                    $user_name = mysqli_real_escape_string($conn, $row1['user_name']);
                    $sql2 = "SELECT latitude_to_deliver, longitude_to_deliver, latitude_of_restaurant, longitude_of_restaurant FROM orders WHERE order_id = {$row['order_id']}";
                    $result2 = mysqli_query($conn, $sql2);
                    $row2 = mysqli_fetch_assoc($result2);
                    $latitude_to_deliver = $row2['latitude_to_deliver'];
                    $longitude_to_deliver = $row2['longitude_to_deliver'];
                    $latitude_of_restaurant = $row2['latitude_of_restaurant'];
                    $longitude_of_restaurant = $row2['longitude_of_restaurant'];
                    $distance = haversineDistance($latitude_of_restaurant, $longitude_of_restaurant, $latitude_to_deliver, $longitude_to_deliver);
                    if($order_status == "PREPARED"){
                        echo "<tr><td>{$user_name}</td><td>{$row['item_name']}</td><td>{$row['quantity']}</td><td>{$row['cost_price_total']}</td><td>{$row['amount_donated']}</td><td>{$bank}</td><td>{$distance} km</td><td>{$order_status}</td><td><button class='prepared' data-order_id='{$order_id}' disabled>Prepared</button></td><td><button class='assign-deliverer'><a href='assign-to-delivery-person.php?order_id={$order_id}'>Assign To Delivery Person</a></button></td></tr>";
                    }
                    else if(($order_status == "ASSIGNED DELIVERER") || ($order_status == "STARTED DELIVERY PROCESS") || ($order_status == "COMPLETED DELIVERY")){
                        echo "<tr><td>{$user_name}</td><td>{$row['item_name']}</td><td>{$row['quantity']}</td><td>{$row['cost_price_total']}</td><td>{$row['amount_donated']}</td><td>{$bank}</td><td>{$distance} km</td><td>{$order_status}</td><td><button class='prepared' data-order_id='{$order_id}' disabled>Prepared</button></td><td><button class='assign-deliverer' disabled>Assign To Delivery Person</button></td></tr>";
                    }
                    else if($order_status == "PENDING"){
                        echo "<tr><td>{$user_name}</td><td>{$row['item_name']}</td><td>{$row['quantity']}</td><td>{$row['cost_price_total']}</td><td>{$row['amount_donated']}</td><td>{$bank}</td><td>{$distance} km</td><td>{$order_status}</td><td><button class='prepared' data-order_id='{$order_id}'>Prepared</button></td><td><button class='assign-deliverer' disabled>Assign To Delivery Person</button></td></tr>";
                    }
                }
            }
            echo "</table>";
        }
        else{
            $sql1 = "UPDATE orders SET order_status = 'PREPARED' WHERE order_id = {$_POST['order_id']}";
            $result1 = mysqli_query($conn, $sql1);
            if($result1){
                echo "<table><tr><th>User Name</th><th>Item Name</th><th>Quantity</th><th>Total Cost Price</th><th>Amount Donated</th><th>Bank</th><th>Approx Distance</th><th>Order Status</th><th>Click if Prepared</th><th>Assign</th></tr>";
                $sql2 = "SELECT order_id, item_name, quantity, user_id, cost_price_total, amount_donated, bank, order_status FROM orders WHERE DATE(FROM_UNIXTIME(unix_timestamp DIV 1000)) = '{$date}' AND are_you_in_restaurant = 'no'";
                $result2 = mysqli_query($conn, $sql2);
                while($row2 = mysqli_fetch_assoc($result2)){
                    $order_id = mysqli_real_escape_string($conn, $row2['order_id']);
                    $order_status = mysqli_real_escape_string($conn, $row2['order_status']);
                    $bank = mysqli_real_escape_string($conn, $row2['bank']);
                    $sql3 = "SELECT user_name FROM users WHERE user_id = {$row2['user_id']}";
                    $result3 = mysqli_query($conn, $sql3);
                    if($result3 && (mysqli_num_rows($result3) > 0)){
                        $row3 = mysqli_fetch_assoc($result3);
                        $user_name = mysqli_real_escape_string($conn, $row3['user_name']);
                        $sql4 = "SELECT latitude_to_deliver, longitude_to_deliver, latitude_of_restaurant, longitude_of_restaurant FROM orders WHERE order_id = {$order_id}";
                        $result4 = mysqli_query($conn, $sql4);
                        $row4 = mysqli_fetch_assoc($result4);
                        $latitude_to_deliver = $row4['latitude_to_deliver'];
                        $longitude_to_deliver = $row4['longitude_to_deliver'];
                        $latitude_of_restaurant = $row4['latitude_of_restaurant'];
                        $longitude_of_restaurant = $row4['longitude_of_restaurant'];
                        $distance = haversineDistance($latitude_of_restaurant, $longitude_of_restaurant, $latitude_to_deliver, $longitude_to_deliver); 
                        if($order_status == "PREPARED"){
                            echo "<tr><td>{$user_name}</td><td>{$row2['item_name']}</td><td>{$row2['quantity']}</td><td>{$row2['cost_price_total']}</td><td>{$row2['amount_donated']}</td><td>{$bank}</td><td>{$distance} km</td><td>{$order_status}</td><td><button class='prepared' data-order_id='{$order_id}' disabled>Prepared</button></td><td><button class='assign-deliverer'><a href='assign-to-delivery-person.php?order_id={$order_id}'>Assign To Delivery Person</a></button></td></tr>";
                        }
                        else if(($order_status == "ASSIGNED DELIVERER") || ($order_status == "STARTED DELIVERY PROCESS") || ($order_status == "COMPLETED DELIVERY")){
                            echo "<tr><td>{$user_name}</td><td>{$row2['item_name']}</td><td>{$row2['quantity']}</td><td>{$row2['cost_price_total']}</td><td>{$row2['amount_donated']}</td><td>{$bank}</td><td>{$distance} km</td><td>{$order_status}</td><td><button class='prepared' data-order_id='{$order_id}' disabled>Prepared</button></td><td><button class='assign-deliverer' disabled>Assign To Delivery Person</button></td></tr>";
                        }
                        else if($order_status == "PENDING"){
                            echo "<tr><td>{$user_name}</td><td>{$row2['item_name']}</td><td>{$row2['quantity']}</td><td>{$row2['cost_price_total']}</td><td>{$row2['amount_donated']}</td><td>{$bank}</td><td>{$distance} km</td><td>{$order_status}</td><td><button class='prepared' data-order_id='{$order_id}'>Prepared</button></td><td><button class='assign-deliverer' disabled>Assign To Delivery Person</button></td></tr>";
                        }      
                    }
                }
                echo "</table>";
            }
        }
    }
    else{
        echo "<h2>No Orders From Outside Restaurant</h2>";
    }
?>