<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// if (!(isset($_SESSION['userId']) && isset($_SESSION['userGroup']) && $_SESSION['userGroup'] === 0)) {
//     header('Location:../index.php');
// }
$noNavUser = '';
include('../init.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>products</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom admin css file link  -->
    <link rel="stylesheet" href=<?php echo  $css . "admin_style.css" ?>>

</head>

<body>



    <section class="add-products">

        <h1 class="title">shop products</h1>

        <form action="" method="post" enctype="multipart/form-data">
            <h3>add product</h3>
            <input type="text" name="name" class="box" placeholder="enter product name" required>
            <input type="number" min="0" name="price" class="box" placeholder="enter product price" required>
            <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>
            <input type="submit" value="add product" name="add_product" class="btn">
        </form>

    </section>

    <!-- product CRUD section ends -->

    <!-- show products  -->

    <section class="show-products">

        <div class="box-container">



            <div class="box">
                <img src="the_happy_lemon.jpg" alt="">
                <p class="happy">happy-lemon</p>
                <div class="name">
                    <happy-lemon>
                </div>
                <div class="price">$15</div>
                <a href="" class="option-btn">update</a>
                <a href="" class="delete-btn" onclick="">delete</a>
            </div>


            <p class="empty"></p>


        </div>

    </section>

    <section class="edit-product-form">



    </section>







    <!-- custom admin js file link  -->
    <script src="../js/admin_script.js">
    </script>

</body>

</html>