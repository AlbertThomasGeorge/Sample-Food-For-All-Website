<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/change-password.css">
</head>
<body>
    <h1 class="h1-heading">Change Password:- Restaurant Staff</h1>
    <div class="change-password-container">
        <?php
            if(isset($_POST['save-change'])){
                include "config.php";
                $restaurantstaffid = $_GET["restaurantstaffid"];
                $changedpassword = mysqli_real_escape_string($conn, md5($_POST["change-password"]));
                $confirmchangedpassword = mysqli_real_escape_string($conn, md5($_POST["confirm-changed-password"]));
                if($changedpassword == $confirmchangedpassword){     
                    $sql1 = "SELECT `password` FROM restaurant_staff WHERE restaurant_staff_id = {$restaurantstaffid}";
                    $result1 = mysqli_query($conn, $sql1);
                    if($result1){
                        $row1 = mysqli_fetch_assoc($result1);
                        if($row1["password"] == $changedpassword){
                            echo "<p style='font-size:10px; color:red; text-align:center; margin:10px 0;'>Admin provided password and your new password are the same. Please change</p>";
                            mysqli_close($conn);
                        }
                        else{
                            $sql2 = "UPDATE restaurant_staff SET changed_password = '{$changedpassword}' WHERE restaurant_staff_id = {$restaurantstaffid}";
                            if(mysqli_query($conn, $sql2)){
                                mysqli_close($conn);
                                header("Location: http://localhost/MINI-PROJECT/restaurant-staff-login.php");
                            }
                        }
                    }
                }
                else{
                    echo "<p style='font-size:10px; color:red; text-align:center; margin:10px 0;'>Both passwords mentioned did not match</p>";
                    mysqli_close($conn);
                }
            }
        ?>
        <form method ="post">
            <div>
                <label class="change-password">Change Password</label>
                <input type="password" name="change-password">
            </div>
            <div>
                <label class="confirm-changed-password">Confirm Changed Password</label>
                <input type="password" name="confirm-changed-password">
            </div>
            <input type="submit" name="save-change" class="inputclass" value="Change">
        </form>
    </div>
</body>