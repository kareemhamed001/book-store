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

$errors = [
    'general' => '',
    'bookId' => '',
    'name' => '',
    'coverImage' => '',
    'authorName' => '',
    'publisherName' => '',
    'description' => '',
    'category' => '',
    'code' => '',
    'price' => '',
    'stock' => '',
    'publishingDate' => '',
    'discount' => ''
];
$values = [
    'bookId' => '',
    'name' => '',
    'coverImage' => '',
    'authorName' => '',
    'publisherName' => '',
    'description' => '',
    'category' => '',
    'code' => '',
    'price' => '',
    'stock' => '',
    'publishingDate' => '',
    'discount' => ''

];

if (isset($_POST['updateProduct'])) {

    if (empty($_POST['bookId'])) {
        $errors['bookId'] = 'Empty';
    } else {
        $values['bookId'] = $_POST['bookId'];
    }

    if (empty($_POST['name'])) {
        $errors['name'] = 'Empty';
    } else {
        $values['name'] = $_POST['name'];
    }

    if (empty($_POST['authorName'])) {
        $errors['authorName'] = 'Empty';
    } else {
        $values['authorName'] = $_POST['authorName'];
    }

    if (empty($_POST['publisherName'])) {
        $errors['publisherName'] = 'Empty';
    } else {
        $values['publisherName'] = $_POST['publisherName'];
    }

    if (empty($_POST['description'])) {
        $errors['description'] = 'Empty';
    } else {
        $values['description'] = $_POST['description'];
    }

    if (empty($_POST['category'])) {
        $errors['category'] = 'Empty';
    } else {
        $values['category'] = $_POST['category'];
    }

    if (empty($_POST['code'])) {
        $errors['code'] = 'Empty';
    } else {
        $values['code'] = $_POST['code'];
    }

    if (empty($_POST['price'])) {
        $errors['price'] = 'Empty';
    } else {
        $values['price'] = $_POST['price'];
    }

    if (empty($_POST['stock'])) {
        $errors['stock'] = 'Empty';
    } else {
        $values['stock'] = $_POST['stock'];
    }
    if (empty($_POST['discount'])) {
        $errors['discount'] = 'Empty';
    } else {
        $values['discount'] = $_POST['discount'];
    }
}

if (isset($_POST['addProduct'])) {

    if (empty($_POST['bookId'])) {
        $errors['bookId'] = 'Required';
    } else {
        $values['bookId'] = $_POST['bookId'];
    }

    if (empty($_POST['name'])) {
        $errors['name'] = 'Required';
    } else {
        $values['name'] = $_POST['name'];
    }

    if (empty($_POST['authorName'])) {
        $errors['authorName'] = 'Required';
    } else {
        $values['authorName'] = $_POST['authorName'];
    }

    if (empty($_POST['publisherName'])) {
        $errors['publisherName'] = 'Required';
    } else {
        $values['publisherName'] = $_POST['publisherName'];
    }

    if (empty($_POST['description'])) {
        $errors['description'] = 'Required';
    } else {
        $values['description'] = $_POST['description'];
    }

    if (empty($_POST['category'])) {
        $errors['category'] = 'Required';
    } else {
        $values['category'] = $_POST['category'];
    }

    if (empty($_POST['code'])) {
        $errors['code'] = 'Required';
    } else {
        $values['code'] = $_POST['code'];
    }

    if (empty($_POST['price'])) {
        $errors['price'] = 'Required';
    } else {
        $values['price'] = $_POST['price'];
    }

    if (empty($_POST['stock'])) {
        $errors['stock'] = 'Required';
    } else {
        $values['stock'] = $_POST['stock'];
    }
    if (empty($_POST['discount'])) {
        $values['discount'] = 0;
    } else {
        $values['discount'] = $_POST['discount'];
    }

    if (!(empty($_FILES['file']['name']))) {
        $image = $_FILES['file']['name'];
        $tmp_dir = $_FILES['file']['tmp_name'];
        $imageSize = $_FILES['file']['size'];

        $upload_dir = '../upload/';
        $imgExt = strtolower(pathinfo($image, PATHINFO_EXTENSION));
        $valid_extensions = array('jpeg', 'jpg', 'png');

        $picfile = basename($_FILES['file']['name']) . rand(1000, 1000000) . "." . $imgExt;
        if (move_uploaded_file($tmp_dir, $upload_dir . $picfile)) {
            $values['coverImage'] = $picfile;
        } else {
                $errors['coverImage'] = 'Failed to upload';
            }
            
    } else {
        $errors['coverImage'] = 'Required';
    }

    if (!(empty($values['name']) || empty($values['authorName'])
        || empty($values['publisherName']) || empty($values['description'])
        || empty($values['category']) || empty($values['code'])
        || empty($values['price']) || empty($values['stock']) || empty($_FILES['file']['name']))) {
        try {


            $prepareUpdate = $con->prepare('UPDATE books SET name=?,coverImage=?,authorName=?,publisherName=?,description=?,category=?,code=?,price=?,stock=?,publishingDate=?,discount=?,afterDiscount=(price-(price*discount)) WHERE bookId=?');

            $prepareUpdate->execute(array($values['name'], $values['coverImage'], $values['authorName'], $values['publisherName'], $values['description'], $values['category'], $values['code'], $values['price'], $values['stock'], $values['publishingDate'], $values['discount'] / 100, $values['bookId']));
            $errors['general'] = 'Done';
            echo '<script>window.alert("Done")</script>';

            header('Location:adding_products.php');
        } catch (PDOException $e) {
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
    <title>Document</title>
    <link rel="stylesheet" href=<?php echo $css . "admin_style.css" ?>>

</head>

<body>
    <section>
        <div class=" container ">
            <form action=" <?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">

                <input type="hidden" name="bookId" placeholder="Product Name" value=<?php echo $values['bookId'] ?>>
                <span class="error">
                    <?php echo htmlspecialchars($errors['general']) ?>
                </span>

                <div class="part-container">
                    <div class="form-partition">
                        <input type="text" name="name" placeholder="Product Name" value=<?php echo $values['name'] ?>>
                        <span class="error">
                            <?php echo htmlspecialchars($errors['name']) ?>
                        </span>
                        <input type="text" name="authorName" placeholder="Product Author" value=<?php echo $values['authorName'] ?>>
                        <span class="error">
                            <?php echo htmlspecialchars($errors['authorName']) ?>
                        </span>
                        <input type="text" name="publisherName" placeholder="Product publisher Name" value=<?php echo $values['publisherName'] ?>>
                        <span class="error">
                            <?php echo htmlspecialchars($errors['publisherName']) ?>
                        </span>
                        <input type="text" name="description" placeholder="Product Description" value=<?php echo $values['description'] ?>>
                        <span class="error">
                            <?php echo htmlspecialchars($errors['description']) ?>
                        </span>
                        <input type="number" name="discount" placeholder="Discount" value=<?php echo $values['discount'] ?>>
                        <span class="error">
                            <?php echo htmlspecialchars($errors['discount']) ?>
                        </span>
                    </div>

                    <div class="form-partition">
                        <input type="text" name="category" placeholder="Product Category" value=<?php echo $values['category'] ?> readonly>
                        <select name="category" value="<?php echo $values['category'] ?>">
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
                            <?php echo htmlspecialchars($errors['category']) ?>
                        </span>
                        <input type="text" name="code" placeholder="Product Code" value=<?php echo $values['code'] ?>>
                        <span class="error">
                            <?php echo htmlspecialchars($errors['code']) ?>
                        </span>
                        <input type="text" name="price" placeholder="Product Price" value=<?php echo $values['price'] ?>>
                        <span class="error">
                            <?php echo htmlspecialchars($errors['price']) ?>
                        </span>
                        <input type="text" name="stock" placeholder="Product Quantity" value=<?php echo $values['stock'] ?>>
                        <span class="error">
                            <?php echo htmlspecialchars($errors['stock']) ?>
                        </span>
                        <input type="file" name="file" value=<?php echo $values['coverImage'] ?> accept="image/*" require>
                        <span class="error">
                            <?php echo $errors['coverImage'] ?>
                        </span>
                    </div>
                </div>
                <input type="submit" name="addProduct" value="Add Product" class="btn option-btn">
            </form>
        </div>
    </section>

</body>

</html>