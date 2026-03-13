<?php
    include "login-header.php"; 
?>
<body>
    <h1 class="h1-heading">Delivery Person First Time Login Page</h1>
    <div class="login-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method ="POST">
            <?php
                if(isset($_POST['login'])){
                    include "config.php";
                    $deliverypersonname = mysqli_real_escape_string($conn, $_POST['name']);
                    $password = mysqli_real_escape_string($conn, md5($_POST['passwd']));
                    $sql = "SELECT delivery_person_id, delivery_person_name FROM delivery_people WHERE delivery_person_name = '{$deliverypersonname}' AND `password` = '{$password}'";
                    $result = mysqli_query($conn, $sql) or die("Query Failed");
                    if(mysqli_num_rows($result)>0){
                        while($row=mysqli_fetch_assoc($result)){
                            $deliverypersonid = mysqli_real_escape_string($conn, $row['delivery_person_id']);
                            mysqli_close($conn);
                            header("Location: http://localhost/MINI-PROJECT/delivery-person-change-password.php?deliverypersonid={$deliverypersonid}");
                        }
                    }
                    else{
                        echo "<p style='font-size:10px; color:red; text-align:center; margin:10px 0;'>Delivery Person Name and Password did not match. Try Again!</p>";
                    }
                    mysqli_close($conn);
                }
            ?>
            <div>
                <label class="name-label">Enter your name</label>
                <input type="text" name="name" placeholder="Type your name here">
            </div>
            <div>
                <label class="password-label">Enter your password</label>
                <input type="password" name="passwd">
            </div>
            <input type="submit" name="login" value="login to change password" class="inputclass">
        </form>
    </div>
</body>