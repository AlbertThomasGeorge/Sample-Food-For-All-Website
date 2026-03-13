<?php
    include "../config.php";
    $date = mysqli_real_escape_string($conn, $_POST['date_input']);
    $sql = "SELECT * FROM rent WHERE date_of_rent = '{$date}'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) == 1){
        echo "<table><tr><th>Rent Per Day</th></tr>";
        while($row = mysqli_fetch_assoc($result)){
            echo "<tr><td>".$row['rent_per_day']."</td></tr>";
        }
        echo "</table>";
    }
    else{
        echo "<h2 style='text-align: center'>No Rent</h2>";
    }
?>