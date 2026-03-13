<?php
    include "../config.php";
    $date = mysqli_real_escape_string($conn, $_POST['date_input']);
    $sql = "SELECT * FROM needs WHERE need_date = '{$date}'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
        echo "<table><tr><th>Person or Group Name</th><th>Person or Group Need</th><th>Estimate</th></tr>";
        while($row = mysqli_fetch_assoc($result)){
            echo "<tr><td>".$row['person_or_group_name']."</td><td>".$row['person_or_group_need']."</td><td>₹".$row['estimate']."</td></tr>";
        }
        echo "</table>";
    }
    else{
        echo "<h2 style='text-align: center'>No Needs</h2>";
    }
?>