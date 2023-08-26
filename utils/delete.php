 

<?php
require('connection.php');



$id =  $_GET['id'];


$del = "DELETE FROM cat_list WHERE cat_id=$id";

if ($query = $conn->query($del) == true) {
   header('location:list_cat.php');
}

?>             