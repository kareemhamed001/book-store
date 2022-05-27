<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
$noNavAdmin = '';
$noNavUser = '';
require('../init.php');
require($includes . 'connect.php');
require($includes . 'functions.php');

$result = array();
if (isset($_POST['search'])) {
    try {
        $search = strtolower(htmlspecialchars($_POST['search']));
        $prepare = $con->prepare('SELECT * FROM books WHERE name LIKE "%"?"%" OR authorName LIKE "%"?"%" OR publisherName LIKE "%"?"%"OR description LIKE "%"?"%"OR category LIKE "%"?"%" OR code LIKE "%"?"%" OR price LIKE "%"?"%" OR discount*100 LIKE "%"?"%" OR afterDiscount*100 LIKE "%"?"%" ');

        if ($prepare->execute(array($search, $search, $search, $search, $search, $search, $search, $search, $search))) {

            $result = $prepare->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {
    }
}
if (isset($_POST['addToCart'])) {
    addToCart();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>search page</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="../css/styleUser.css">

</head>

<body>



    <div class="heading">
        <h3>search page</h3>
        <p> <a href="home.php">home</a> / search </p>
    </div>

    <section class="search-form">
        <form action="" method="post">
            <input type="text" name="search" placeholder="search products..." class="box">
            <input type="submit" name="submit" value="search" class="btn">
        </form>
    </section>

    <section class="products" id="products">
        <div class="box-container">
            <?php
    
            if (count($result) > 0) {
                displayBooks($result);
            }
            ?>


        </div>

    </section>

    <?php
    //  include '../includes/footer.php'; 
     ?>

    <!-- custom js file link  -->
    <script src="js/script.js"></script>

</body>

</html>