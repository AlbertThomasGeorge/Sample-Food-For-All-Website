<?php
    include "session-timeout-check.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Panel</title>
    <link rel="stylesheet" href="../css/add-land-rent-if-there.css">
</head>
<body>
    <h1 class="h1-heading">Add Rent if there</h1>
    <div class="add-rent-if-there-container">
        <form>
            <div>
                <label class="addrentperdayifthere">Rent Per Day if there</label>
                <input type="text" name="rent-for-today-if-there">
                <?php date_default_timezone_set("Asia/Kolkata"); ?>
                <input type="hidden" name="current-date" value="<?php echo date("Y-m-d"); ?>">
            </div>
            <input type="submit" name="save" value="Save" class="inputclass">
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script>
        $(document).ready(function(){
                              $('form').submit(function(event){
                                                   event.preventDefault();
                                                   $.ajax({
                                                               url: "load-funds-and-add-rent.php", 
                                                               type: "POST",
                                                               data: $('form').serialize(),
                                                               success: function(data){
                                                                            window.location.href =  "admin-home-page.php"
                                                                        }
                                                   });
                              });
                          });
    </script>
</body>