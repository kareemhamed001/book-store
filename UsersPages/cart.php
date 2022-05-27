<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!(isset($_SESSION['userId']) && isset($_SESSION['userGroup']) && $_SESSION['userGroup'] === 1)) {
    header('Location:../login.php');
}
$noNavAdmin = '';

include('../init.php');
require($includes . 'connect.php');

try {
    $prepare = $con->prepare('SELECT * FROM cart JOIN books JOIN users on cart.userId=users.userId AND cart.bookId=books.bookId WHERE cart.userId=? AND books.stock>0');
    $result;
    if ($prepare->execute(array($_SESSION['userId']))) {
        $result = $prepare->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
}
if (isset($_POST['deleteCart'])) {
    try {
        $prepareDelete = $con->prepare('DELETE FROM cart WHERE cart.id=?');
        $result;
        if ($prepareDelete->execute(array($_POST['cart_id']))) {

            header('Location:cart.php');
            echo '<script>window.alert("Done")</script>';
        }
    } catch (PDOException $e) {
    }
}

if (isset($_POST['deleteAll'])) {
    try {
        $prepareDelete = $con->prepare('DELETE FROM cart WHERE cart.userId=?');
        if ($prepareDelete->execute(array($_SESSION['userId']))) {

            header('Location:cart.php');
            echo '<script>window.alert("Done")</script>';
        }
    } catch (PDOException $e) {
    }
}
if (isset($_POST['updateCart'])) {
    try {
        $updateCartPrepare = $con->prepare('UPDATE cart SET quantity=? WHERE cart.bookId=? AND cart.userId=?  ');
        if ($updateCartPrepare->execute(array($_POST['number'], $_POST['bookId'], $_SESSION['userId']))) {

            echo ('<script>window.alert("done")</script>');
            header('Location:cart.php');
        }
    } catch (PDOException $e) {
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cart</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom css file link  -->

    <link rel="stylesheet" href=<?php echo $css . "styleUser.css" ?>>

</head>

<body>





    <section class="shopping-cart">

        <h1 class="title">products added</h1>

        <div class="box-container">


            <?php
            if (count($result) > 0)
                global $totalPrice;
            $totalPrice = 0;
            foreach ($result as $value) {

                $totalPrice += $value['afterDiscount'] * $value['quantity'];
                echo '  <div class="box">
                    <form method="post" action="' . $_SERVER['PHP_SELF'] . '" enctype="multipart/form-data">
                    <input type="hidden" name="cart_id" value="' . $value['id'] . '">
                    <button type="submit" name="deleteCart" class="fas fa-times"></button>
                    </form>
            <img src="../upload/' . $value["coverImage"] . ' "alt="">
            <div class="content">
                <h1>' . $value["name"] . '</h1>
                <div class="price">
                    <h2>$' . $value["afterDiscount"] . '</h2>
                </div>
            </div>
            <form action="" method="post">
                <input type="hidden" name="bookId" value="' . $value["bookId"] . '">
                <input type="number" min="1" max="' . $value['stock'] . '" name="number" value="' . $value['quantity'] . '">
                <input type="submit" name="updateCart" value="update" class="btn">
            </form>
            <div class="sub-total"> sub total :' . $value['afterDiscount'] * $value['quantity'] . ' <span>

                </span> </div>
        </div>';
            }

            ?>


        </div>

        <div style="margin-top: 2rem; text-align:center;">
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
                <input type="submit" name="deleteAll" class="delete-btn >" value="delete all">
            </form>

        </div>

        <div class="cart-total">
            <p>grand total :<?php echo $totalPrice ?> <span>

                </span></p>
            <div class="flex">
                <a href="shop.php" class="btn">continue shopping</a>
                <a href="checkout.php" class="btn >">proceed to
                    checkout</a>
            </div>
        </div>

    </section>

    <script src="js/script.js"></script>
    <script src="../js/navActive.js"></script>
</body>

</html>