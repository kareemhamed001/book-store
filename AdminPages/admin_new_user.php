<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$noNavUser = '';
require_once('../init.php');

require_once('../includes/functions.php');
$error = '';

if (isset($_POST['submit'])) {
    $error =addUser($_POST, 0);
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../CSS/admin_style.css">
    <link rel="stylesheet" href="../CSS/m.css">
</head>

<body>
    <section class="newAdmin">
        <div class="overlay"></div>
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" autocomplete="off">

            <h2>Register</h2>
            <span class="error-span">
                <?php echo $error; ?>
            </span>
            <input type="text" name="userName" placeholder="User Name" class="box" autocomplete="off">

            <input type="text" name="firstName" placeholder="FirstName" class="box" autocomplete="off">
            <span class="error-span">
                <?php //echo $errors['firstName']
                ?>
            </span>
            <input type="text" name="lastName" placeholder="LastName" class="box" autocomplete="off">
            <span class="error-span">
                <?php //echo $errors['lastName']
                ?>
            </span>
            <input type="email" name="email" placeholder="Email" class="box" autocomplete="off">
            <span class="error-span">
                <?php //echo $errors['email']
                ?>
            </span>
            <input type="password" name="password" placeholder="Password" class="box" autocomplete="off">
            <span class="error-span">
                <?php //echo $errors['password']
                ?>
            </span>
            <input type="password" name="confirmPassword" placeholder="Confirm Password" class="box" autocomplete="off">
            <span class="error-span">
                <?php //echo $errors['confirmPassword']
                ?>
            </span>


            <button type="submit" name="submit" value="Register" class="btn green-btn">Register</button>

        </form>
    </section>
</body>

</html>