<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// if (!(isset($_SESSION['userId']) && isset($_SESSION['userGroup']) && $_SESSION['userGroup'] === 0)) {
//     header('Location:../index.php');
// }
$noNavUser = '';
include('../init.php');


try {
    $prepareTotal = $con->prepare("SELECT * FROM users");
    $prepareTotal->execute();
    $totalUsers = $prepareTotal->rowCount();

    $prepareNoramls = $con->prepare("SELECT * FROM users WHERE userGroup=1");
    $prepareNoramls->execute();
    $totalNormals = $prepareNoramls->rowCount();

    $prepareAdmins = $con->prepare("SELECT * FROM users WHERE userGroup=0");
    $prepareAdmins->execute();
    $totalAdmins = $prepareAdmins->rowCount();
} catch (PDOException $e) {
}

try {
    $fetchOrdersPrepare = $con->prepare('SELECT * FROM orders  where statue=1 ');
    $fetchOrdersPrepare->execute();
    $pendingResults = $fetchOrdersPrepare->rowCount();

    $fetchOrdersPrepare = $con->prepare('SELECT * FROM orders  where statue=2');
    $fetchOrdersPrepare->execute();
    $acceptedResults = $fetchOrdersPrepare->rowCount();

    $fetchOrdersPrepare = $con->prepare('SELECT * FROM orders where statue=0 ');
    $fetchOrdersPrepare->execute();
    $refusedResults = $fetchOrdersPrepare->rowCount();

    $fetchTotal = $con->prepare('SELECT * FROM orders  ');
    $fetchTotal->execute();
    $totalOrders = $fetchTotal->rowCount();

    $totalMessagesPrepare = $con->prepare('SELECT * FROM contact');
    $totalMessagesPrepare->execute();
    $totalMessages = $totalMessagesPrepare->rowCount();
} catch (PDOException $e) {
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- <link rel="stylesheet" href=<?php echo $css . "admin_style.css" ?>> -->
    <link rel="stylesheet" href="../CSS/admin_style.css">

    <!-- <link rel="stylesheet" href="<link rel=" preconnect " href=" https://fonts.googleapis.com "> -->
    <link rel=" preconnect " href=" https://fonts.gstatic.com " crossorigin>

</head>

<body>



    <section>
        <h2>Dashboard</h2>
        <div class=" container-statistics ">

            <div class=" box ">
                <span>
                    <?php echo $totalAdmins ?>
                </span>
                <span class=" text-container ">Total Admins</span>
            </div>

            <div class=" box ">
                <span>
                    <?php echo $totalNormals ?>
                </span>
                <span class=" text-container ">Total Normals</span>
            </div>

            <div class=" box ">
                <span>
                    <?php echo $totalUsers ?>
                </span>
                <span class=" text-container ">Total Users</span>
            </div>

            <div class=" box ">
                <span><?php echo $pendingResults ?></span>
                <span class=" text-container ">Pending Orders</span>
            </div>

            <div class=" box ">
                <span><?php echo $acceptedResults ?></span>
                <span class=" text-container ">Accepted Orders</span>
            </div>

            <div class=" box ">
                <span><?php echo $refusedResults ?></span>
                <span class=" text-container ">Canceled Orders</span>
            </div>

            <div class=" box ">
                <span><?php echo $totalOrders ?></span>
                <span class=" text-container ">Total Orders</span>
            </div>

            <div class=" box ">
                <span><?php echo $totalMessages ?></span>
                <span class=" text-container ">Total Messages</span>
            </div>
        </div>

        <script src="../js/admin_script.js"></script>
</body>

</html>