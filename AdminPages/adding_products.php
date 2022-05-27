<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!(isset($_SESSION['userId']) && isset($_SESSION['userGroup']) && $_SESSION['userGroup'] === 0)) {
    header('Location:../index.php');
}else{
    $userId=$_SESSION['userId'];
}


$noNavUser = '';
include('../init.php');
require_once('../includes/functions.php');

$errors = [
    'generalError' => '',
    'productName' => '',
    'productAuthor' => '',
    'productpublisherName' => '',
    'productDescription' => '',
    'productCategory' => '',
    'productCode' => '',
    'productPrice' => '',
    'productQuantity' => '',
    'image' => '',
    'discount' => ''
];
$values = [
];

if (isset($_POST['addProduct'])) {

    if (empty($_POST['productName'])) {
        $errors['productName'] = 'Required';
    } else {
        $values['productName'] = $_POST['productName'];
    }

    if (empty($_POST['productAuthor'])) {
        $errors['productAuthor'] = 'Required';
    } else {
        $values['productAuthor'] = $_POST['productAuthor'];
    }

    if (empty($_POST['productpublisherName'])) {
        $errors['productpublisherName'] = 'Required';
    } else {
        $values['productpublisherName'] = $_POST['productpublisherName'];
    }

    if (empty($_POST['productDescription'])) {
        $errors['productDescription'] = 'Required';
    } else {
        $values['productDescription'] = $_POST['productDescription'];
    }

    if (empty($_POST['productCategory'])) {
        $errors['productCategory'] = 'Required';
    } else {
        $values['productCategory'] = $_POST['productCategory'];
    }

    if (empty($_POST['productCode'])) {
        $errors['productCode'] = 'Required';
    } else {
        $values['productCode'] = $_POST['productCode'];
    }

    if (empty($_POST['productPrice'])) {
        $errors['productPrice'] = 'Required';
    } else {
        $values['productPrice'] = $_POST['productPrice'];
    }

    if (empty($_POST['productQuantity'])) {
        $errors['productQuantity'] = 'Required';
    } else {
        $values['productQuantity'] = $_POST['productQuantity'];
    }

    if (empty($_POST['discount'])) {
        $values['discount'] = 0;
    } else {
        $values['discount'] = $_POST['discount'];
    }



    if (!(empty($_FILES['file']['name']))) {
        $images = $_FILES['file']['name'];
        $tmp_dir = $_FILES['file']['tmp_name'];
        $imageSize = $_FILES['file']['size'];

        $upload_dir = '../upload/';
        $imgExt = strtolower(pathinfo($images, PATHINFO_EXTENSION));
        $valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'pdf');
        $picfile = basename($_FILES['file']['name']) . rand(1000, 1000000) . "." . $imgExt;

        if (move_uploaded_file($tmp_dir, $upload_dir . $picfile)) {
            $values['image'] = $picfile;
        } else {
            $errors['image'] = 'Failed to upload';
        }
    } else {
        $errors['image'] = 'Required';
    }

    if (!(empty($values['productName']) || empty($values['productAuthor'])
        || empty($values['productpublisherName']) || empty($values['productDescription'])
        || empty($values['productCategory']) || empty($values['productCode'])
        || empty($values['productPrice']) || empty($values['productQuantity']) || empty($_FILES['file']['name']))) {
        try {
        
            $prepareCheck = $con->prepare('SELECT * FROM books WHERE name=?');
            $prepareCheck->execute(array($values['productName']));
            
            $checkedCount = $prepareCheck->rowcount();


            if ($checkedCount > 0) {
                $errors['generalError'] = 'This book is exist';
            } else {
                
                $prepareInsert = $con->prepare('INSERT INTO books(adminId,name,coverImage,authorName,publisherName,description,category,code,price,stock,publishingDate,discount,afterDiscount)  VALUES(?,?,?,?,?,?,?,?,?,?,now(),?,price-(price*discount))');
                
                if($prepareInsert->execute(array($userId,$values['productName'], $values['image'], $values['productAuthor'], $values['productpublisherName'], $values['productDescription'], $values['productCategory'], $values['productCode'], $values['productPrice'], $values['productQuantity'], floatval($values['discount'] / 100) ))){
                    $errors['generalError'] = 'Done';
                }else{
                    $errors['generalError'] = 'failed';
                }

                
            }
        } catch (PDOException $e) {
            echo $e;
        }
    }
}



if (isset($_POST['deleteProduct'])) {
    // $confirmed;
    // echo '<script>confirm("confirm Delete")</script>';

    try {

        $deletePrepare = $con->prepare('delete from books where bookId=?');

        $deletePrepare->execute(array($_POST['hiddenId']));
        echo '<script>window.alert("Deleted Succefully")</script>';
        header('Location:adding_products.php');
    } catch (Exception $e) {
    }
}
if (isset($_POST['delete'])) {
    if (empty($_POST['delete-id'])) {
    } else {

        try {
            $deletePrepare = $con->prepare('delete from books where bookId=?');
            $deletePrepare->execute(array($_POST['delete-id']));
            header('Location:adding_products.php');
        } catch (Exception $e) {
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href=<?php echo $css . "admin_style.css" ?>>

    <link rel=" preconnect " href=" https://fonts.gstatic.com " crossorigin>
    <title>Document</title>
</head>

<body>


    <?php displayDeleteAlert();?>

    <section>
        <h2>Add Products</h2>
        <div class=" container ">

            <form action=" <?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
                <span class="error">
                    <?php echo $errors['generalError'] ?>
                </span>

                <div class="part-container">
                    <div class="form-partition">
                        <input type="text" name="productName" placeholder="Product Name">

                        <span class="error">
                            <?php echo $errors['productName'] ?>
                        </span>

                        <input type="text" name="productAuthor" placeholder="Product Author">
                        <span class="error">
                            <?php echo $errors['productAuthor'] ?>
                        </span>

                        <input type="text" name="productpublisherName" placeholder="Product publisher Name">
                        <span class="error">
                            <?php echo $errors['productpublisherName'] ?>
                        </span>

                        <input type="text" name="productDescription" placeholder="Product Description">
                        <span class="error">
                            <?php echo $errors['productDescription'] ?>
                        </span>
                        <input type="number" min="0" max="100" name="discount" placeholder="Discount">
                        <span class="error">
                            <?php echo $errors['discount'] ?>
                        </span>

                    </div>

                    <div class="form-partition">
                        <select name="productCategory">
                            <option value="Fantasy">Fantasy</option>
                            <option value="Adventure">Adventure</option>
                            <option value="Romance">Romance</option>
                            <option value="Contemporary">Contemporary</option>
                            <option value="Dystopian">Dystopian</option>
                            <option value="Dystopian">Dystopian</option>
                            <option value="Mystery">Mystery</option>
                            <option value="Horror">Horror</option>
                            <option value="Thriller">Thriller</option>
                            <option value="Paranormal">Paranormal</option>
                            <option value="HistoricalFiction">Historical fiction</option>
                            <option value="ScienceFiction">Science Fiction</option>
                            <option value="Children’s">Children’s</option>
                            <option value="Memoir">Memoir</option>
                            <option value="Cooking">Cooking</option>
                            <option value="Art">Art</option>
                            <option value="Self-help/Personal">Self-help/Personal</option>
                            <option value="Development">Development</option>
                            <option value="Motivational">Motivational</option>
                            <option value="Health">Health</option>
                            <option value="History">History</option>
                            <option value="Travel">Travel</option>
                            <option value="Guide/How-to">Guide/How-to</option>
                            <option value="Families&Relationships">Families&Relationships</option>
                            <option value="Humor">Humor</option>


                        </select>
                        <span class="error">
                            <?php echo $errors['productCategory'] ?>
                        </span>
                        <input type="number" name="productCode" placeholder="Product Code">
                        <span class="error">
                            <?php echo $errors['productCode'] ?>
                        </span>
                        <input type="number" name="productPrice" placeholder="Product Price">
                        <span class="error">
                            <?php echo $errors['productPrice'] ?>
                        </span>
                        <input type="text" name="productQuantity" placeholder="Product Quantity">
                        <span class="error">
                            <?php echo $errors['productQuantity'] ?>
                        </span>

                        <input type="file" name="file" require>
                        <span class="error">
                            <?php echo $errors['image'] ?>
                        </span>
                    </div>
                </div>


                <input type="submit" name="addProduct" value="Add Product" class="btn green-btn">
            </form>
        </div>
    </section>

    <section>
        <h2>Added Products</h2>
        <div class="container">
            <?php
            try {
                $prepareCheck = $con->prepare('SELECT * FROM books');
                $prepareCheck->execute();
                $result = $prepareCheck->fetchAll(PDO::FETCH_ASSOC);
                if (count($result) > 0) {
                    foreach ($result as $book) {
                        echo ('<div class="box">
                        <img src="../upload/' . $book['coverImage'] . '" alt="productImage"data-id="' . $book['bookId'] . '">
                        <span class="productName" data-id="' . $book['bookId'] . '">' . $book['name'] . '</span>
                        <span class="productPrice"data-id="' . $book['bookId'] . '">' . $book['price'] . '<span>/$</span></span>
                        
                        <a data-id="' . $book['bookId'] . '"class="red-btn btn delete" target="popup" id="delete-btn">Delete</a>
                        <form action="update.php" method="post">
            <input type="hidden" name="bookId" value="'.$book['bookId'].'">
            <input type="hidden" name="name" value="' . $book['name'] . '">
            <input type="hidden" name="coverImage" value="' . $book['coverImage'] . '">
            
            
            <input type="hidden" name="authorName" value="' . $book['authorName'] . '">
            <input type="hidden" name="publisherName" value="' . $book['publisherName'] . '">
            <input type="hidden" name="description" value="' . $book['description'] . '">
            <input type="hidden" name="category" value="' . $book['category'] . '">
            <input type="hidden" name="code" value="' . $book['code'] . '">
            <input type="hidden" name="price" value="' . $book['price'] . '">
            <input type="hidden" name="stock" value="' . $book['stock'] . '">
            <input type="hidden" name="publishingDate" value="' . $book['publishingDate'] . '">
            <input type="hidden" name="discount" value="' . $book['discount'] * 100 . '">
            <input type="submit" name="updateProduct" value="Update" class="green-btn btn">
            </form>
        </div>');
                    }
                }
            } catch (PDOException $e) {
            } ?>

            <!-- href="delete.php?data=' . $book['bookId'] . '" -->
        </div>
    </section>
    <script src="../js/jquery-3.6.0.min.js">
    </script>
    <script src="../js/admin_script.js">
    </script>
</body>

</html>