<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$noNavAdmin = '';
require('../init.php');
// $userNav = '';
// require('../includeHeader.php');

try {
    $result=array('');
    $prepareOrders = $con->prepare('SELECT * FROM orders o join books b on o.bookId=b.bookId WHERE userId=?');
    if ($prepareOrders->execute(array($_SESSION['userId']))) {
        $result = $prepareOrders->fetchAll(PDO::FETCH_ASSOC);

    }
} catch (Exception $e) {
}

if(isset($_GET['cancelOrder'])){
    try {
        $prepareOrders = $con->prepare('DELETE from orders WHERE orderId=?');
        if ($prepareOrders->execute(array($_GET['orderId']))) { 
            header('location:javascript://history.go(-1)');
            echo'<div class="done-message">
                <div>
                </div>
                <h3>Done</h3>
            </div>';
    
        }
    } catch (Exception $e) {
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../CSS/styleUser.css">
</head>

<body>

    <section class="orders">
        <h2>Your Orders</h2>
        <?php

        if (sizeof($result) != 0) {
            foreach ($result as $value) {
                echo ' <div class="order">

                <div class="info">
                    <h4>Placed On : ' . $value['placeOn'] . '</h4>
                    <h4>Total Price : ' . $value['afterDiscount'] * $value['quantity'] . '$</h4>
                    <h4>Order ID : ' . $value['orderId'] . '</h4>
                    <h4>Placed On:25/10/2022</h4>
                </div>
        
                <div class="content">
                    <img src="../upload/' . $value['coverImage'] . '" alt="orderImage">
                    <div class="details">
                        <h2 class="orderName">' . $value['name'] . '</h2>
        
                        <form action="../UsersPages/seemore.php" method="get">
                                <input type="hidden" name="bookId" value="' . $value['bookId'] . '">
                                <button class="btn green-btn" type="submit" value="Show More" name="showMore">ViewOrder</button>
                        </form>

                            <form action="'.$_SERVER["PHP_SELF"].'" method="get"> 
                                <input type="hidden" name="orderId" value="' . $value['orderId'] . '">
                                <button type="submit" class="option-btn" type="submit" name="cancelOrder" >Cancel order</button>  
                            </form>
                    </div>
        
                </div>
        
            </div>';
            }
        }


        ?>

    </section>
    <script type="text/javascript" src="../js/jquery-3.6.0.min.js"> </script>
    <script type="text/javascript" src="../js/main.js"></script>
</body>

</html>