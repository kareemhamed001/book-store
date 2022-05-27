<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!(isset($_SESSION['userId']) && isset($_SESSION['userGroup']) && $_SESSION['userGroup'] === 1)) {
    header('Location:../login.php');
}
$noNavAdmin = '';
include('../init.php');
$result='';
if (isset($_POST['send'])) {
    try {
        $result = 'contact Us';
        $prepareSend = $con->prepare('Insert Into contact (name,phoneNumber,email,userId,message,date) Values(?,?,?,?,?,now()) ');


        if ($prepareSend->execute(array($_POST['name'], $_POST['number'], $_POST['email'], $_SESSION['userId'],$_POST['message']))) {
            $result = 'Done';
        } else {
            $result = 'try again';
        }
    } catch (Exception $e) {
        // echo $e;
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>contact</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href=<?php echo $css . "styleUser.css" ?>>

</head>

<body>


    <section class="contact">

        <form action=" <?php $_SERVER['PHP_SELF'] ?>" method="post">
            <h3>say something!</h3>
            <span style="color: red;font-size: 1.6em;display:inline-block"><?php echo $result; ?></span>
            <input type="text" name="name" required placeholder="enter your name" class="box" required>
            <input type="email" name="email" required placeholder="enter your email" class="box" required>
            <input type="number" name="number" required placeholder="enter your number" class="box" required>
            <textarea name="message" class="box" placeholder="enter your message" id="" cols="30" rows="10"
                required></textarea>
            <input type="submit" value="send message" name="send" class="btn">
        </form>

    </section>

    <!-- custom js file link  -->
    <script src="js/script.js"></script>
    <?php 
    // $footer = '';
    // $noNavAdmin = '';
    // $noNavUser = '';
    // include('../init.php') 
    ?>

</body>

</html>