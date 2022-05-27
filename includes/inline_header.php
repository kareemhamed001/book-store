<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!(isset($_SESSION['userId']) && isset($_SESSION['userGroup']) && $_SESSION['userGroup'] === 0)) {
    header('Location:../index.php');
}


require_once('connect.php');
require_once('functions.php');


if (isset($_POST['logout'])) {
    logout();
}
if (isset($_SESSION['userId']) && isset($_SESSION['userGroup']) && $_SESSION['userGroup'] === 0) {
    try {
        $prepare = $con->prepare('SELECT userName,email FROM users WHERE userId=? AND userGroup=?');
        if ($prepare->execute(array($_SESSION['userId'], $_SESSION['userGroup']))) {
            $count = $prepare->rowCount();
            if ($count > 0) {
                $result = $prepare->fetchAll(PDO::FETCH_ASSOC);
            }else{
                logout();
            }
        }
    } catch (PDOException $e) {
    }
}
?>

<header class=" header1 ">

    <p class=" logo ">Admin <span>panel</span></p>

    <nav class=" navbar ">
        <a class=" aa " href=" index.php ">Home</a>
        <a class=" bb " href=" adding_products.php ">Products</a>
        <a class=" cc " href=" admin_orders.php ">Orders</a>
        <a class=" dd " href=" users.php ">Users</a>
        <a class=" ff " href=" admin_contacts.php ">Feedback</a>
    </nav>

    <div class=" icons">
        <i class=" fa-solid fa-user user-icon "></i>
        <i class=" fa-solid fa-bars menu-icon "></i>
    </div>

    <div class=" account-box ">
        <p>usermame: <span><?php echo $result[0]['userName'] ?></span></p>
        <p>email: <span><?php echo $result[0]['email'] ?></span> </p>
        <div>
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
                <input type="submit" name="logout" value="Logout" class="btn red-btn">
            </form>

        </div>
    </div>
</header>