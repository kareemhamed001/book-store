<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// if (!(isset($_SESSION['userId']) && isset($_SESSION['userGroup']) && $_SESSION['userGroup'] === 0)) {
//     header('Location:../index.php');
// }
$noNavUser = '';
include('../init.php');
require($includes . 'connect.php');
try {
    $fetchOrdersPrepare = $con->prepare('SELECT * FROM orders JOIN users JOIN books on orders.userId=users.userId AND orders.bookId=books.bookId where statue=1 ');
    $fetchOrdersPrepare->execute();
    $pendingResults = $fetchOrdersPrepare->fetchAll(PDO::FETCH_ASSOC);

    $fetchOrdersPrepare = $con->prepare('SELECT * FROM orders JOIN users JOIN books on orders.userId=users.userId AND orders.bookId=books.bookId where statue=2 ');
    $fetchOrdersPrepare->execute();
    $acceptedResults = $fetchOrdersPrepare->fetchAll(PDO::FETCH_ASSOC);

    $fetchOrdersPrepare = $con->prepare('SELECT * FROM orders JOIN users JOIN books on orders.userId=users.userId AND orders.bookId=books.bookId where statue=0 ');
    $fetchOrdersPrepare->execute();
    $refusedResults = $fetchOrdersPrepare->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
}

if (isset($_POST['accept_order'])) {
    try {
        $acceptOrdersPrepare = $con->prepare('UPDATE orders SET statue=2 WHERE orderId=? ');
        $acceptOrdersPrepare->execute(array($_POST['order_id']));

        $acceptOrdersPrepare = $con->prepare('UPDATE books SET stock=(stock-?) WHERE bookId=? ');
        if ($acceptOrdersPrepare->execute(array($_POST['order_quantity'], $_POST['bookId']))) {
            header('Location:admin_orders.php');
        }
    } catch (PDOException $e) {
    }
}
if (isset($_POST['refuse_order'])) {
    try {

        $refuseOrdersPrepare = $con->prepare('UPDATE orders SET statue=0 WHERE orderId=? ');
        $refuseOrdersPrepare->execute(array($_POST['order_id']));
        $refuseOrdersPrepare = $con->prepare('UPDATE books SET stock=(stock+?) WHERE bookId=? ');
        if ($refuseOrdersPrepare->execute(array($_POST['order_quantity'], $_POST['bookId']))) {
            header('Location:admin_orders.php');
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
    <title>orders</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom admin css file link  -->
    <link rel="stylesheet" href=<?php echo  $css . "admin_style.css" ?>>

</head>

<body>


    <section class="orders">

        <h1 class="title">Pending orders</h1>

        <div class="box-container">

            <?php
            foreach ($pendingResults as $value) {
                echo ('<div class="box">
               <p> user id :' . $value['userId'] . ' <span>

                   </span> </p>
               <p> placed on :' . $value['placeOn'] . ' <span>

                   </span> </p>
               <p> name :' . $value['userName'] . ' <span>

                   </span> </p>
               <p> number :' . $value['phoneNumber'] . ' <span>

                   </span> </p>
               <p> email :' . $value['email'] . ' <span>

                   </span> </p>
               <p> address :' . $value['address'] . ' <span>

                   </span> </p>
               <p> total products :' . $value['quantity'] . ' <span>

                   </span> </p>
               <p> total price :' . $value['quantity'] * $value['afterDiscount'] . ' <span>$

                   </span> </p>
               <p> payment method :' . $value['paymentMethod'] . ' <span>

                   </span> </p>
               <form action="' . $_SERVER["PHP_SELF"] . '" method="post"class="myForm">
            <input type="hidden" name="order_id" value=' . $value["orderId"] . '">

            <input type="hidden" name="order_quantity" value=' . $value["quantity"] . '">
            <input type="hidden" name="bookId" value=' . $value["bookId"] . '">
            
            <input type="submit" value="accept" name="accept_order" class="option-btn accept-btn">
            </form>
            <form action="' . $_SERVER["PHP_SELF"] . '" method="post"class="myForm">
                <input type="hidden" name="order_id" value=' . $value["orderId"] . '">
                <input type="submit" value="refuse" name="refuse_order" class="option-btn refuse-btn">
            </form>
        </div>
        ');
            }
            ?>


        </div>


    </section>
    <section class="orders">

        <h1 class="title">Accepted orders</h1>

        <div class="box-container">

            <?php
            foreach ($acceptedResults as $value) {
                echo ('<div class="box">
               <p> user id :' . $value['userId'] . ' <span>

                   </span> </p>
               <p> placed on :' . $value['placeOn'] . ' <span>

                   </span> </p>
               <p> name :' . $value['userName'] . ' <span>

                   </span> </p>
               <p> number :' . $value['userId'] . ' <span>

                   </span> </p>
               <p> email :' . $value['email'] . ' <span>

                   </span> </p>
               <p> address :' . $value['userId'] . ' <span>

                   </span> </p>
               <p> total products :' . $value['quantity'] . ' <span>

                   </span> </p>
               <p> total price :' . $value['quantity'] * $value['price'] . ' <span>$

                   </span> </p>
               <p> payment method :' . $value['userId'] . ' <span>

                   </span> </p>
           
            <form action="' . $_SERVER["PHP_SELF"] . '" method="post" class="myForm">
                <input type="hidden" name="order_id" value=' . $value["orderId"] . '">

                <input type="hidden" name="order_quantity" value=' . $value["quantity"] . '">
                <input type="hidden" name="bookId" value=' . $value["bookId"] . '">

                <input type="submit" value="refuse" name="refuse_order" class="option-btn refuse-btn">
            </form>
        </div>
        ');
            }
            ?>


        </div>


    </section>
    </section>
    <section class="orders">

        <h1 class="title">Refused orders</h1>

        <div class="box-container">

            <?php
            foreach ($refusedResults as $value) {
                echo ('<div class="box">
               <p> user id :' . $value['userId'] . ' <span>

                   </span> </p>
               <p> placed on :' . $value['placeOn'] . ' <span>

                   </span> </p>
               <p> name :' . $value['userName'] . ' <span>

                   </span> </p>
               <p> number :' . $value['userId'] . ' <span>

                   </span> </p>
               <p> email :' . $value['email'] . ' <span>

                   </span> </p>
               <p> address :' . $value['userId'] . ' <span>

                   </span> </p>
               <p> total products :' . $value['quantity'] . ' <span>

                   </span> </p>
               <p> total price :' . $value['quantity'] * $value['price'] . ' <span>$

                   </span> </p>
               <p> payment method :' . $value['userId'] . ' <span>

                   </span> </p>
               <form action="' . $_SERVER["PHP_SELF"] . '" method="post"class="myForm">
            <input type="hidden" name="order_id" value=' . $value["orderId"] . '">

            <input type="hidden" name="order_quantity" value=' . $value["quantity"] . '">
            <input type="hidden" name="bookId" value=' . $value["bookId"] . '">
            
            <input type="submit" value="accept" name="accept_order" class="option-btn accept-btn">
            </form>
         
        </div>
        ');
            }
            ?>


        </div>


    </section>

    <!-- custom admin js file link  -->
    <script src="../js/admin_script.js">
    </script>

</body>

</html>