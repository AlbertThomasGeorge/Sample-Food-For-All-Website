<?php
    session_start();
    include "login-header.php";
?>
<body>
    <h1 class="h1-heading">Restaurant Staff Login Page</h1>
    <div class="login-container">
        <?php
            if(isset($_POST['login'])){
                include "config.php";
                $restaurantstaffname = mysqli_real_escape_string($conn, $_POST['name']);
                $password = mysqli_real_escape_string($conn, md5($_POST['passwd']));
                $sql = "SELECT restaurant_staff_id, restaurant_staff_name FROM restaurant_staff WHERE restaurant_staff_name = '{$restaurantstaffname}' AND changed_password = '{$password}'";
                $result = mysqli_query($conn, $sql) or die("Query Failed");
                if(mysqli_num_rows($result)>0){
                    while($row=mysqli_fetch_assoc($result)){
                        $restaurantstaffid = mysqli_real_escape_string($conn, $row['restaurant_staff_id']);
                        $_SESSION['restaurant_staff_name'] = $row['restaurant_staff_name'];
                        $_SESSION['restaurant_staff_id'] = $row['restaurant_staff_id'];
                        mysqli_close($conn);
                        header("Location: http://localhost/MINI-PROJECT/restaurant-staff/restaurant-staff-home-page.php");
                    }
                }
                else{
                    echo "<p style='font-size:10px; color:red; text-align:center; margin:10px 0;'>Restaurant Staff Name and Password did not match. Try Again!</p>";
                }
                mysqli_close($conn);
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
            <input type="submit" name="login" value="login" class="inputclass">
        </form>
    </div>
</body>