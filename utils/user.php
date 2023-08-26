<?php

$conn = new mysqli('localhost','root','','mydbt');
if (!$conn){
    echo 'Not connected';
}

$empmsg_fn= "";
$empmsg_ln= "";
$empmsg_en= "";
$empmsg_pn= "";
$empmsg_pan= "";

 if(isset($_POST['submit'])){
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $passwordagain = $_POST['passwordagain'];
    $md5password 	= md5($password);
    if(empty($firstname)){
        $empmsg_fn= "Fill the form";
    }
    if(empty($lastname)){
        $empmsg_ln= "Fill the form";
    }
    if(empty($email)){
        $empmsg_en= "Fill the form";
    }
    if(empty($password)){
        $empmsg_pn= "Fill the form";
    }
    if(empty($passwordagain)){
        $empmsg_pan= "Fill the form";
    }

    if(!empty($firstname) && !empty($lastname) && !empty($email) && !empty($password) && !empty($passwordagain)){
        if($password === $passwordagain){
           
            $sql = "INSERT INTO user(firstname, lastname, email, password) 
                    VALUES('$firstname', '$lastname', '$email', '$md5password')";
        
            if($conn->query($sql) == TRUE){
                header('location:login.php?usercreated');
            
            }
            else{
                echo 'data not inserted';
            }
        }
    }


 }
?>

<html>
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">

</head>
<body>
<div class='container'style="margin-top:50px">
    <div class="row">
        <div class="col-4">

        </div>
        <div class="col-4">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <div class="mt-2">
                    <label class="form-label">First Name</label>
                    <input type="text" class="form-control" name="firstname" value="<?php if (isset($_POST['submit'])){echo $firstname;} ?>">
                    <?php if (isset($_POST['submit'])){
                        echo $empmsg_fn; }
                    ?>
                </div>
                <div class="mt-2">
                    <label class="form-label">Last Name</label>
                    <input type="text" class="form-control" name="lastname"value="<?php if (isset($_POST['submit'])){echo $lastname;} ?>">
                    <?php if (isset($_POST['submit'])){
                        echo $empmsg_ln; }
                    ?>
                </div>
                <div class="mt-2">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email"value="<?php if (isset($_POST['submit'])){echo $email;} ?>">
                    <?php if (isset($_POST['submit'])){
                        echo $empmsg_en; }
                    ?>
                </div>
                <div class="mt-2">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password">
                    <?php if (isset($_POST['submit'])){
                        echo $empmsg_pn; }
                    ?>
                </div>
                <div class="mt-2">
                    <label class="form-label">Password Again</label>
                    <input type="password" class="form-control" name="passwordagain">
                    <?php if (isset($_POST['submit'])){
                        echo $empmsg_pan; }
                    ?>
                </div>
                <div class="mt-2">
                    <button class="btn btn-success" name="submit">Submit</button>
                    
                </div>
            </form>
            <h5> have an account? <a href="login.php">Register</a></h5>

        </div>
        <div class="col-4">

        </div>
        
            


    </div>
</div>




</body>
    


</html>

