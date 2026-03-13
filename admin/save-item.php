<?php
    if(isset($_FILES['item-file'])){
        $errors = array();
        if ($_FILES['item-file']['error'] !== 0) {
            $errors[] = "Maybe File upload failed beacuse server could not upload such huge file. Consider uploading file that is 1mb or lower."; // if execution of statement in if condition occurs, the string "File upload failed. File may be too large for the server." is appended to the array named errors
        }
        $file_name = $_FILES['item-file']['name'];
        $file_size = $_FILES['item-file']['size'];
        $file_tmp = $_FILES['item-file']['tmp_name'];
        $file_type = $_FILES['item-file']['type'];
        $file_parts  = explode('.', $file_name);
        $file_ext = strtolower(end($file_parts));
        $extensions = ['jpg', 'jpeg', 'png'];
        if(in_array($file_ext, $extensions)===false){
            $errors[] = "This extension file is not alowed. Please choose a JPG or PNG file."; 
        }
        if($file_size > 1048576){
            $errors[] = "File size must be 1MB or lower."; 
        }
        if(empty($errors)===true){
            $new_name = time()."-".$file_name; // file name is changed to a new name, this is done to avoid problem if the file name of an image that was selected already exists in database 
            $target = "images of items/".$new_name;
        }
        else{
            echo "<pre>";
            print_r($errors);
            echo "</pre>";
            die();
        }
    }
    include "../config.php";
    $itemname = mysqli_real_escape_string($conn, $_POST['item-name']);
    $costpricepurely = mysqli_real_escape_string($conn, $_POST['cost-price']);
    $fname = mysqli_real_escape_string($conn, $new_name);
    $flag = 0;
    $sql = "SELECT item_name FROM items";
    $result = mysqli_query($conn, $sql);
    if($result){
        while($row = mysqli_fetch_assoc($result)){
            if($row['item_name']==$_POST['item-name']){
                echo "<p style='font-size:10px; color:red; text-align:center; margin:10px 0;'>An item with the name previously given already exists. Try again by going back!</p>";
                $flag = 1;
            }
        }
        if(!$flag){
            $sqlquery = "INSERT into items (item_name, only_cost_price, image_file) VALUES ('{$itemname}', {$costpricepurely}, '{$fname}')";
            if(mysqli_query($conn, $sqlquery)){
                move_uploaded_file($file_tmp, $target);
                echo "<p style='font-size:10px; color:green; text-align:center; margin:10px 0;'>Successfully inserted item 😃, if need add one more, go back</p>";
            }
            else{
                echo "<p style='font-size:10px; color:red; text-align:center; margin:10px 0;'>Unsuccessful 🥺, go back</p>";
            }
        }
    }
    mysqli_close($conn);
?> 