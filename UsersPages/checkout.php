<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// if (!(isset($_SESSION['userId']) && isset($_SESSION['userGroup']) && $_SESSION['userGroup'] == 1)) {
//     header('Location:../login.php');
// }

$noNavAdmin='';
require('../init.php');

$errors = array(
    'successGeneral' => '',
    'failedGeneral' => '',
    'number' => '',
    'email' => '',
    'payment' => '',
    'flat' => '',
    'address' => '',
    'city' => '',
    'state' => '',
    'postalCode' => '',
);
$values = array(
    'number' => '',
    'email' => '',
    'payment' => '',
    'flat' => '',
    'address' => '',
    'city' => '',
    'state' => '',
    'postalCode' => '',
);
try {
    $prepareOrder = $con->prepare('INSERT INTO orders (userId,bookId,quantity,placeOn,email,phoneNumber,apartmentNumber,address,city,state,postalCode,paymentMethod,statue)VALUES(?,?,?,now(),?,?,?,?,?,?,?,?,?)');
    $acceptOrdersPrepare = $con->prepare('UPDATE books SET stock=(stock-?) WHERE bookId=? ');

    $prepareBooks = $con->prepare('SELECT * FROM cart JOIN books on cart.bookId=books.bookId WHERE cart.userId=? And books.bookId=?');


    $prepareCart = $con->prepare('SELECT * FROM cart JOIN users Join books on cart.userId=users.userId And books.bookId=cart.bookId WHERE cart.userId=?');
    $prepareCart->execute(array($_SESSION['userId']));
    $cartResult = $prepareCart->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
}

if (isset($_POST['order'])) {

    if (empty($_POST['number'])) {
        $errors['number'] = 'Required';
    } else {
        $values['number'] = $_POST['number'];
    }

    if (empty($_POST['email'])) {
        $values['email'] = $cartResult['email'];
    } else {
        $values['email'] = $_POST['email'];
    }

    if (empty($_POST['payment'])) {
        $errors['payment'] = 'Required';
    } else {
        $values['payment'] = $_POST['payment'];
    }
    if (empty($_POST['flat'])) {
        $errors['flat'] = 'Required';
    } else {
        $values['flat'] = $_POST['flat'];
    }

    if (empty($_POST['address'])) {
        $errors['address'] = 'Required';
    } else {
        $values['address'] = $_POST['address'];
    }

    if (empty($_POST['city'])) {
        $errors['city'] = 'Required';
    } else {
        $values['city'] = $_POST['city'];
    }

    if (empty($_POST['state'])) {
        $errors['state'] = 'Required';
    } else {
        $values['state'] = $_POST['state'];
    }

    if (empty($_POST['postalCode'])) {
        $errors['postalCode'] = 'Required';
    } else {
        $values['postalCode'] = $_POST['postalCode'];
    }

    if (!(empty($_POST['number']) ||
        empty($_POST['email']) ||
        empty($_POST['payment']) ||
        empty($_POST['flat']) ||
        empty($_POST['address']) ||
        empty($_POST['city']) ||
        empty($_POST['state']) ||
        empty($_POST['postalCode']))) {

        try {


            if (count($cartResult) > 0) {

                foreach ($cartResult as $product) {

                    if ($prepareBooks->execute(array($_SESSION['userId'], $product['bookId']))) {
                        $books = $prepareBooks->fetchAll(PDO::FETCH_ASSOC);


                        if (intval($books[0]['stock']) >= intval($product['quantity'])) {
                            if ($prepareOrder->execute(array($_SESSION['userId'], $product['bookId'], $product['quantity'], $values['email'], $values['number'], $values['flat'], $values['address'], $values['city'], $values['state'], $values['postalCode'], $values['payment'], 2))) {

                                if ($acceptOrdersPrepare->execute(array(intval($product['quantity']), intval($product['bookId'])))) {
                                    $prepareDelete = $con->prepare('DELETE FROM cart WHERE cart.userId=? And cart.id=?');
                                    if ($prepareDelete->execute(array($_SESSION['userId'], $product['id']))) {
                                        $errors['successGeneral'] = 'Done :' . $product['name'];
                                    }
                                }
                            }
                        } else {
                            $errors['failedGeneral'] = 'not enougth stock from :' . $product['name'];
                        }
                    }
                }
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>checkout</title>
    <link rel="stylesheet" href=<?php echo $css . "styleUser.css" ?>>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>


    <section class="display-order"></section>

    <section class="checkout">

        <form action="" method="post">
            <h3>place your order</h3>
            <h3><?php echo $errors['successGeneral'] ?></h3>
            <h3><?php echo $errors['failedGeneral'] ?></h3>
            <div class="flex">

                <div class="inputBox">
                    <span>your number :</span>
                    <input type="text" name="number" required placeholder="enter your number">
                </div>
                <div class="inputBox">
                    <span>your email :</span>
                    <input type="text" name="email" required placeholder="enter your email">
                </div>
                <div class="inputBox">
                    <span>payment method :</span>
                    <select class="method" name="payment">
                        <option value="cash on delivery">cash on delivery</option>
                        <option value="paypal">paypal</option>

                    </select>
                </div>
                <div class="inputBox">
                    <span>address line 01 :</span>
                    <input type="number" name="flat" required placeholder="e.g. flat no.">
                </div>
                <div class="inputBox">
                    <span>address line 01 :</span>
                    <input type="text" name="address" required placeholder="e.g. street name.">
                </div>
                <div class="inputBox">
                    <span>city :</span>
                    <input type="text" name="city" required placeholder="e.g. Suez.">
                </div>
                <div class="inputBox">
                    <span>state :</span>
                    <input type="text" name="state" required placeholder="e.g. Suez.">
                </div>

                <div class="inputBox">
                    <span>pin code :</span>
                    <input type="number" min="0" name="postalCode" required placeholder="e.g. 123456.">
                </div>
            </div>
            <input type="submit" value="order now" class="btn" name="order">

        </form>
    </section>

    <?php 
    // $footer = '';
    // $noNavAdmin = '';
    // $noNavUser = '';
    // include('../init.php') 
    
    ?>
    <script type="text/javascript" src="../js/jquery-3.6.0.min.js"> </script>
    <script type="text/javascript" src="../js/main.js"></script>
</body>

</html>