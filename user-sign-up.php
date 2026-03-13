<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign-up</title>
    <link rel="stylesheet" href="css/sign-up.css">
</head>
<body>
    <h1 class="h1-heading">User Sign-up page</h1>
    <div class="sign-up-container">
        <?php
            include "config.php"; 
            if(isset($_POST["save"])){
                $user_name = mysqli_real_escape_string($conn, $_POST['name']);
                $password = mysqli_real_escape_string($conn, md5($_POST['passwd']));
                $confirm_password = mysqli_real_escape_string($conn, md5($_POST['confirm_passwd']));
                $sqlquery1 = "SELECT user_name FROM users WHERE user_name='{$user_name}'";
                $result1 = mysqli_query($conn, $sqlquery1) or die("Query Failed"); 
                if(mysqli_num_rows($result1)>0){
                    echo "<p style='font-size:10px; color:red; text-align:center; margin:10px 0;'>User Name already exists. Try Again!</p>";
                    mysqli_close($conn);
                }
                else if($password !== $confirm_password){
                    echo "<p style='font-size:10px; color:red; text-align:center; margin:10px 0;'>Both Passwords mentioned did not match. Try Again!</p>";
                    mysqli_close($conn);
                }
                else{
                    $sqlquery2 = "INSERT into users(user_name, `password`) VALUES ('{$user_name}', '{$password}')";
                    if(mysqli_query($conn, $sqlquery2)){
                        mysqli_close($conn);
                        header("Location: http://localhost/MINI-PROJECT/user-login.php");
                    }   
                }
            }      
        ?>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method ="POST">
            <div>

                <label class="name-label">Enter your name</label>
                <input type="text" name="name" placeholder="Type your name here">
            </div>
            <div>
                <label class="password-label">Enter your password</label>
                <input type="password" name="passwd">
            </div>
            <div>
                <label class="confirm-password-label">Confirm Password</label>
                <input type="password" name="confirm_passwd">
            </div>
            <input type="submit" name="save" value="sign-up" class="inputclass">
        </form> 
    </div>
</body>
</html>