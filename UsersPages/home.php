<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>home</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href= "../css/styleUser.css" >

</head>

<body>
    <?php $noNavAdmin = '';
        include('../init.php');
        require($includes . 'functions.php');
    ?>

    <section class="home">

        <div class="content">
            <h3>Hand Picked Book to your door.</h3>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Excepturi, quod? Reiciendis ut porro iste totam.
            </p>
            <a href="shop.php" class="btn">Discover Books</a>
        </div>

    </section>


    <section class="products products-home" id="products">
        <h1 class="title">latest products</h1>

        <div class="box-container">
            <?php
                try {
                    $prepareFetch = $con->prepare('SELECT * FROM books ORDER BY bookId DESC limit 4 ');

                    if ($prepareFetch->execute()) {
                        $result = $prepareFetch->fetchAll(PDO::FETCH_ASSOC);
                    }
                } catch (PDOException $e) {
                }
                
                
                if (count($result) > 0) {
                    displayBooks($result);
                }

                addToCart();
                ?>
            <div class="load-more">
                <a href="shop.php" class="btn">load more</a>
            </div>
        </div>
        
        
    </section>

    <section class="about" id="about">
        
        <h2>about us</h2>
        
        <p>created by</p>
        <div class="flex">
            <div class="box">
                <div class="social-and-image">
                <img src="../images/alaa.jpeg" alt="">
                <div class="social-icons">
        
                <a href="https://www.facebook.com/alaa.elbosaily" target="_blank"><i class="fa-brands fa-facebook"></i></a>
                <a href="#" target="_blank"><i class="fa-brands fa-twitter"></i></a>
                <!-- <a href="#"target="_blank"><i class="fa-brands fa-linkedIn"></i></a> -->
                <a href="#"target="_blank"><i class="fa-brands fa-instagram"></i></a>
        
                </div>
                </div>
                
                <h3>ِAlaa Taha</h3>
            </div>
            
            <div class="box">
                <div class="social-and-image">
                <img src="../images/kareem.jpg" alt="">
                <div class="social-icons">

                <a href="https://www.facebook.com/kareemhamid001/" target="_blank"><i class="fa-brands fa-facebook"></i></a>
                <a href="https://twitter.com/KAREEMTURK001" target="_blank"><i class="fa-brands fa-twitter"></i></a>
                <!-- <a href="https://www.linkedin.com/in/kareem-turk-a4a9301a2/"target="_blank"><i class="fa-brands fa-linkedIn"></i></a> -->
                <a href="https://www.instagram.com/kareem_hamid/"target="_blank"><i class="fa-brands fa-instagram"></i></a>

                </div>
                </div>
                
                <h3>kareem Hamed </h3>
            </div>

            <div class="box">
                <div class="social-and-image">
                <img src="../images/david.jpeg" alt="">
                <div class="social-icons">

                <a href="" target="_blank"><i class="fa-brands fa-facebook"></i></a>
                <a href="" target="_blank"><i class="fa-brands fa-twitter"></i></a>
                <!-- <a href=""target="_blank"><i class="fa-brands fa-linkedIn"></i></a> -->
                <a href=""target="_blank"><i class="fa-brands fa-instagram"></i></a>

                </div>
                </div>
                
                <h3>Ahmed Mahmoud  </h3>
            </div>

            <div class="box">
                <div class="social-and-image">
                <img src="../images/mohamedAshraf.jpeg" alt="">
                <div class="social-icons">

                <a href="" target="_blank"><i class="fa-brands fa-facebook"></i></a>
                <a href="" target="_blank"><i class="fa-brands fa-twitter"></i></a>
                <!-- <a href=""target="_blank"><i class="fa-brands fa-linkedIn"></i></a> -->
                <a href=""target="_blank"><i class="fa-brands fa-instagram"></i></a>

                </div>
                </div>
                
                <h3>Mohamed Ashraf</h3>
            </div>

            <div class="box">
                <div class="social-and-image">
                <img src="../images/mohamedKhaled.jpeg" alt="">
                <div class="social-icons">

                <a href="" target="_blank"><i class="fa-brands fa-facebook"></i></a>
                <a href="" target="_blank"><i class="fa-brands fa-twitter"></i></a>
                <!-- <a href=""target="_blank"><i class="fa-brands fa-linkedIn"></i></a> -->
                <a href=""target="_blank"><i class="fa-brands fa-instagram"></i></a>

                </div>
                </div>
                
                <h3>Mohamed Khaled</h3>
            </div>

            

        </div>

    </section>

    <!-- <section class="home-contact">

        <div class="content">
            <h3>have any questions?</h3>
            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Atque cumque exercitationem repellendus,
                amet
                ullam voluptatibus?</p>
            <a href="contact.html" class="btn">contact us</a>
        </div>

    </section> -->

    <!-- custom js file link  -->
    <script src="js/script.js"></script>
    <?php
     $footer = '';
    $noNavAdmin = '';
    $noNavUser = '';
    include('../init.php') 
    ?>

</body>

</html>