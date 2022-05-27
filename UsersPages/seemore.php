<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$noNavAdmin = '';
include('../init.php');
require_once($includes.'functions.php');

$values = [];

if (isset($_GET['showMore'])) {
    if(empty($_GET['bookId'])){
        // header('Location:shop.php');
    }else{
        
        $bookId=htmlspecialchars( $_GET['bookId']);
        
        try{
            $prepare=$con->prepare('select * from books where bookId=?');
            
            if($prepare->execute(array($bookId))){
                
                $values=$prepare->fetch(PDO::FETCH_ASSOC);
                
            }

        }catch(Exception $e){

        }
    }

}
addToCart();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/seemore.css">
    <link rel="stylesheet" href="../css/styleUser.css">
    <title>Document</title>
</head>
<body>
        <!-- <h1>MORE INFORMATION ABOUT THE BOOK </h1> -->
    
    <section>
        <div class="div1">
            <h2><?php  echo $values['name']; ?></h2>
            <img src="../upload/<?php  echo $values['coverImage']; ?>" alt="">
        </div>
        <div class="div2">
            
            <p>Author Name: <span><?php echo $values['authorName']; ?></span> </p>
            <br> <hr>
            <p>Book's Content: <span class="loly">  <?php echo $values['description']; ?></span></p>
            
            <p>BOOK Code: <span><?php echo $values['code']; ?></span></p>
            <?php
            if($values['price']==$values['afterDiscount']){
                echo('<p>Book Price: <span>'.$values['price'].' $</span></p> ');
            }else{
                echo('<p>Befor Discount:<span>'.$values['price'].' $</span></p>');
                echo('<p>After Discount:<span>'.$values['afterDiscount'].' $</span></p>');
            }
            
            ?>
            
            <p>Release Date: <span><?php echo $values['publishingDate']; ?></span> </p>
            <form action="<?php echo $_SERVER['PHP_SELF'];?>"method='post'>
            <input type="hidden" name="bookId" value="<?php echo $values['bookId'];?>">
            <input type="number" name="number" value="1" min='1' max="<?php echo $values['stock'];?>" value="<?php echo $values['bookId'];?>" class="qty" >
            <button class="btn" name="addToCart">Add to cart</button>
        </form>
        </div>

    </section> 
</body>
</html>