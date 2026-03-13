<?php
    include "session-timeout-check.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Panel</title>
    <link rel="stylesheet" href="../css/add-person.css">
</head>
<body>
    <h1 class="h1-heading">Add Person Page</h1>
    <div class="add-person-container">
        <?php
            if(isset($_POST['save'])){
                include "../config.php";
                $personname = mysqli_real_escape_string($conn, $_POST['name']);
                $password = mysqli_real_escape_string($conn, md5($_POST['passwd']));
                $role = mysqli_real_escape_string($conn, $_POST['role']);
                if($role==0){
                    $sql1 = "SELECT restaurant_staff_name, `password` FROM restaurant_staff WHERE restaurant_staff_name = '{$personname}'";
                    $result1 = mysqli_query($conn, $sql1) or die("Query Failed");
                    if(mysqli_num_rows($result1) > 0){
                        echo "<p style='font-size:10px; color:red; text-align:center; margin:10px 0;'>Restaurant staff with same name already exists. Try again changing name slightly!</p>";
                    }
                    else{
                        $sql2 = "INSERT into restaurant_staff (restaurant_staff_name, `password`) VALUES ('{$personname}', '{$password}')";
                        if(mysqli_query($conn, $sql2)){
                            echo "<p style='font-size:10px; color:green; text-align:center; margin:10px 0;'>Restaurant staff added successfully 😃. Add more if wish.</p>";
                        }
                        else{
                            echo "<p style='font-size:10px; color:red; text-align:center; margin:10px 0;'>Restaurant staff not added successfully 🥺.</p>";
                        }
                    }
                }
                else{
                    $sql1 = "SELECT delivery_person_name, `password` FROM delivery_people WHERE delivery_person_name = '{$personname}'";
                    $result1 = mysqli_query($conn, $sql1) or die("Query Failed");
                    if(mysqli_num_rows($result1) > 0){
                        echo "<p style='font-size:10px; color:red; text-align:center; margin:10px 0;'>Delivery Person with same name already exists. Try again changing name slightly!</p>";
                    }
                    else{
                        $sql2 = "INSERT into delivery_people (delivery_person_name, `password`) VALUES ('{$personname}', '{$password}')";
                        if(mysqli_query($conn, $sql2)){
                            echo "<p style='font-size:10px; color:green; text-align:center; margin:10px 0;'>Delivery Person added successfully 😃. Add more if wish.</p>";
                        }
                        else{
                            echo "<p style='font-size:10px; color:red; text-align:center; margin:10px 0;'>Delivery Person not added successfully 🥺</p>";
                        }
                    }
                }
                mysqli_close($conn);
            }
        ?>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method ="POST">
            <div>
                <label class="name-label">Person Name</label>
                <input type="text" name="name">
            </div>
            <div>
                <label class="password-label">Password</label>
                <input type="password" name="passwd">
            </div>
            <div>
                <label class="role-label">Person Role</label>
                <select name="role">
                    <option value="0">Restaurant Staff</option>
                    <option value="1">Delivery Person</option>
                </select>
            </div>
            <input type="submit" name="save" class="inputclass">
        </form>
    </div>
</body>