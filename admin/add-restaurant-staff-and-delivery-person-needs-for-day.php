<?php
    include "session-timeout-check.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Panel</title>
    <link rel="stylesheet" href="../css/add-restaurant-staff-and-delivery-person-needs-for-day.css">
</head>
<body>
    <h1 class="h1-heading">Add restaurant staff and delivery person needs for day</h1>
    <div class="restaurant-staff-and-delivery-person-needs-for-day">
        <p style='font-size:10px; color:blue; text-align:center; margin:10px 0;'></p>
        <form>
            <div>
                <label class="add-person-or-group-name">Choose person or group name</label>
                <select name="person-or-group-name">
                    <option value="Restaurant Staff + Delivery People">Restaurant Staff + Delivery People</option>
                    <option value="Restaurant Staff">Restaurant Staff</option>
                    <option value="Delivery People">Delivery People</option>
                    <?php
                        include "../config.php";
                        $sqlquery = "SELECT restaurant_staff_name AS `name` FROM restaurant_staff UNION SELECT delivery_person_name AS `name` FROM delivery_people";
                        $resultofsqlquery = mysqli_query($conn, $sqlquery);
                        if($resultofsqlquery){
                            while($rowofoutput = mysqli_fetch_assoc($resultofsqlquery)){
                                $name = mysqli_real_escape_string($conn, $rowofoutput['name']);
                                echo "<option value='".$name."'>".$name."</option>";
                            }
                        }
                        mysqli_close($conn);
                    ?>
                </select>
            </div>
            <div>
                <label class="add-person-or-group-need">Add person or group need</label>
                <input type="text" name="person-or-group-need">
            </div>
            <?php date_default_timezone_set("Asia/Kolkata"); ?>
            <input type="hidden" name="current-date" value="<?php echo date("Y-m-d"); ?>">
            <div>
                <label class="estimate">Estimate</label>
                <input type="text" name="estimate">
            </div>
            <input type="submit" name="save-added-need" value="Save Added Need" class="inputclass">
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script>
        $(document).ready(function(){
                              $('form').submit(function(event){
                                                   event.preventDefault();
                                                   $.ajax({
                                                               url: "load-funds-and-add-need.php", 
                                                               type: "POST",
                                                               data: $('form').serialize(),
                                                               success: function(data){
                                                                            $('form').trigger("reset");
                                                                            $('p').html(data);
                                                                        }  
                                                   });
                                               });
                          });
    </script>
</body>
