<?php
    if(empty($_FILES['new-item-file']['name'])){
        $file_name = $_POST['old-item-image'];
    }
    else{
        if(isset($_FILES['new-item-file'])){
            $errors = array();
            if ($_FILES['new-item-file']['error'] !== 0) {
                $errors[] = "Maybe File upload failed beacuse server could not upload such huge file. Consider uploading file that is 1mb or lower."; // if execution of statement in if condition occurs, the string "File upload failed. File may be too large for the server." is appended to the array named errors
            }
            $file_name = $_FILES['new-item-file']['name'];
            $file_size = $_FILES['new-item-file']['size'];
            $file_tmp = $_FILES['new-item-file']['tmp_name'];
            $file_type = $_FILES['new-item-file']['type'];
            $file_parts  = explode('.', $file_name);
            $file_ext = strtolower(end($file_parts));
            $extensions = ['jpg', 'jpeg', 'png'];
            if(in_array($file_ext, $extensions)===false){
                $errors[] = "This extension file is not alowed. Please choose a JPG or PNG file."; 
            }
            if($file_size > 1048576){
                $errors[] = "File size must be 1mb or lower."; 
            }
            if(empty($errors)===true){
                $new_name = time()."-".$file_name;
                $target = "images of items/".$new_name;
                move_uploaded_file($file_tmp, "images of items/".$new_name);
            }
            else{
                echo "<pre>";
                print_r($errors);
                echo "</pre>";
                die();
            }
        }
    }
    if(!(empty($_FILES['new-item-file']['name'])) && file_exists("images of items/".$_POST['old-item-image'])){
        include "../config.php";
        $itemname = mysqli_real_escape_string($conn, $_POST["name"]);
        $onlycostprice = mysqli_real_escape_string($conn, $_POST["costpricepurely"]);
        $itemid = mysqli_real_escape_string($conn, $_POST["item-id"]);
        $sql = "UPDATE items SET item_name = '{$itemname}', only_cost_price = {$onlycostprice}, image_file = '{$new_name}' WHERE item_id = {$itemid}";
        if(mysqli_query($conn, $sql)){
            echo "<p style='font-size:10px; color:green; text-align:center; margin:10px 0;'>Updation over successfully 😃</p>";
            unlink("images of items/".$_POST['old-item-image']);
        }
        else{
            echo "<p style='font-size:10px; color:red; text-align:center; margin:10px 0;'>Updation failed 🥺</p>";
        }
        mysqli_close($conn);
    }
    else{
        include "../config.php";
        $itemname = mysqli_real_escape_string($conn, $_POST["name"]);
        $onlycostprice = mysqli_real_escape_string($conn, $_POST["costpricepurely"]);
        $itemid = mysqli_real_escape_string($conn, $_POST["item-id"]);
        $sql = "UPDATE items SET item_name = '{$itemname}', only_cost_price = {$onlycostprice}, image_file = '{$file_name}' WHERE item_id = {$itemid}";
        if(mysqli_query($conn, $sql)){
            echo "<p style='font-size:10px; color:green; text-align:center; margin:10px 0;'>Updation over successfully 😃</p>";
        }
        else{
            echo "<p style='font-size:10px; color:red; text-align:center; margin:10px 0;'>Updation failed 🥺</p>";
        }
        mysqli_close($conn);
    }
?>