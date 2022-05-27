
<?php
require_once('connect.php');
if (!(isset($_SESSION['userId']) && isset($_SESSION['userGroup']) && $_SESSION['userGroup'] == 1)) {
    header("HTTP/1.0 404 Not Found");
    echo "<h1>404 Not Found</h1>";
    echo "The page that you have requested could not be found.";
    exit();
}


if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}



require_once('connect.php');


$result = 0;
if (isset($_SESSION['userId']))
    $userId = $_SESSION['userId'];

try {

    $prepare = $con->prepare('SELECT * FROM cart JOIN books ON cart.bookId=books.bookId  WHERE cart.userId=? ');
    if ($prepare->execute(array($userId))) {
        $result = $prepare->rowCount();
    }
} catch (PDOException $e) {
}

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();

    header('Location:../index.php');
    exit();
}

if (isset($_SESSION['userId']) && isset($_SESSION['userGroup']) && $_SESSION['userGroup'] === 1) {
    try {
        $prepare = $con->prepare('SELECT userName,email FROM users WHERE userId=? AND userGroup=?');
        if ($prepare->execute(array($_SESSION['userId'], $_SESSION['userGroup']))) {
            $count = $prepare->rowCount();
            if ($count > 0) {
                $userEmail = $prepare->fetchAll(PDO::FETCH_ASSOC);
            }
        }
    } catch (PDOException $e) {
    }
}
?>
<header >
    
        <a href="home.php" class="logo">Bibliobazaar</a>

        <nav class="navbar">
            <a href="home.php">Home</a>
            <a href="home.php#about">About</a>
            <a href="shop.php">Shop</a>
            <a href="contact.php">Contact</a>
            <a href="orders.php">Orders</a>
        </nav>

        <div class="icons">
            <div id="menu-btn" class="fas fa-bars icon"></div>
            <a href="search_page.php" class="fas fa-search icon"></a>
            <div id="user-btn" class="fas fa-user icon"></div>

            <a href="cart.php"> <i class="fas fa-shopping-cart icon"></i> <span>(<?php echo $result;?>)</span> </a>
        </div>

        <div class=" user-box">
            <table>
                <tr><td>username : </td>  <td>   <?php echo $userEmail[0]['userName'];?></td></tr>
                <tr><td>email    : </td> <td><?php echo $userEmail[0]['email'];?></td></tr>
            </table>
            
            
            <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
            <button name="logout" class="delete-btn">logout</button>
</form>
            
            <a href="../UsersPages/change-password.php" class="option-btn">settings</a>
            <!-- <a href="../UsersPages/add_buisness.php" class="option-btn">convert to buisness</a> -->
        </div>
    </div>
</header>
<script src="https://kit.fontawesome.com/33d59f4782.js" crossorigin="anonymous"></script>
<script src="../js/script.js" crossorigin="anonymous"></script>