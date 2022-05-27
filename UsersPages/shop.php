<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!(isset($_SESSION['userId']) && isset($_SESSION['userGroup']) && $_SESSION['userGroup'] === 1)) {
    header('Location:../login.php');
}

$noNavAdmin = '';
require_once('../init.php');
require_once($includes.'functions.php');


try {
    $prepare = $con->prepare('SELECT * From books');
    $prepare->execute();
    $result = $prepare->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
}

addToCart();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>shop</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href=<?php echo $css . "styleUser.css" ?>>

</head>

<body>
    <section class="products" id="products">
        <div class="box-container">
            <?php
            if (count($result) > 0) {
                displayBooks($result);
            }
            ?>
        </div>
    </section>
    <script type="text/javascript" src="../js/shop.js">

    </script>
</body>
</html>