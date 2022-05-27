<?php


function addToCart()
{
    require('../includes/connect.php');
    if (isset($_POST['addToCart'])) {
        try {
            $prepare = $con->prepare('SELECT * FROM cart WHERE cart.bookId=? And cart.userId=?');
            $prepare->execute(array($_POST['bookId'], $_SESSION['userId']));
            $count = $prepare->rowCount();
            if (!($count > 0)) {
                $addToCartPrepare = $con->prepare('INSERT INTO cart (bookId,userId,quantity) VALUES (?,?,?)');

                if ($addToCartPrepare->execute(array($_POST['bookId'], $_SESSION['userId'], $_POST['number']))) {
                    header('location:javascript://history.go(1)');
                }
            } else {
                $updateCartPrepare = $con->prepare('UPDATE cart SET quantity=? WHERE cart.bookId=? AND cart.userId=?  ');
                if ($updateCartPrepare->execute(array($_POST['number'], $_POST['bookId'], $_SESSION['userId']))) {
                    echo ('<script>window.alert("done")</script>');
                    header('location:javascript://history.go(1)');
                }
            }
        } catch (PDOException $e) {
        }
    }
}




function getShopBooks()
{
    require('../includes/connect.php');
    try {
        $prepare = $con->prepare('SELECT * From books');
        $prepare->execute();
        $result = $prepare->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    } catch (Exception $e) {
    }
}

function addUser(array $x, $group = 1)
    {
            require('connect.php');

        if (isset($_POST['submit'])) {

            if (empty($_POST['userName'])) {
                $errors['userName'] = 'Required*';
            } else {
                $values['userName'] = htmlspecialchars($_POST['userName']);
            }

            if (empty($_POST['firstName'])) {
                $errors['firstName'] = 'Required*';
            } else {
                $values['firstName'] = htmlspecialchars($_POST['firstName']);
            }

            if (empty($_POST['lastName'])) {
                $errors['lastName'] = 'Required*';
            } else {
                $values['lastName'] = htmlspecialchars($_POST['lastName']);
            }

            if (empty($_POST['email'])) {
                $errors['email'] = 'Required*';
            } else {
                $values['email'] = htmlspecialchars($_POST['email']);
            }

            if (empty($_POST['password'])) {
                $errors['password'] = 'Required*';
            } else {
                $values['password'] = htmlspecialchars($_POST['password']);
            }

            if (empty($_POST['confirmPassword'])) {
                $errors['confirmPassword'] = 'Required*';
            } else {
                $values['confirmPassword'] = htmlspecialchars($_POST['confirmPassword']);
            }

            if (!(empty($values['userName']) || empty($values['firstName']) || empty($values['lastName'])
                || empty($values['email']) || empty($values['password']) || empty($values['confirmPassword']))) {

                if ($values['password'] === $values['confirmPassword']) {
                    try {
                        require('connect.php');
                        $prepare = $con->prepare("SELECT * FROM users WHERE email=? ");
                        $prepare->execute(array($values['email']));
                        $count = $prepare->rowCount();

                        $prepareUserName = $con->prepare("SELECT * FROM users WHERE userName=? ");
                        $prepareUserName->execute(array($values['userName']));
                        $countUserName = $prepareUserName->rowCount();

                        if ($countUserName > 0) {
                            return 'This user name is exist';
                        }
                        if ($count > 0) {
                            return 'This email is exist';
                        }

                        if (!($count > 0 || $countUserName > 0)) {
                            try {
                                $prepare = $con->prepare('INSERT INTO users(userName,firstName,lastName,email,password,userGroup) VALUES(?,?,?,?,sha1(?),?)');
                                if ($prepare->execute(array($values['userName'], $values['firstName'], $values['lastName'], $values['email'], $values['password'], $group))) {
                                    return 'Done';
                                    exit();
                                } else {
                                    return false;
                                }
                            } catch (PDOException $e) {
                            }
                        } else {
                            return 'This email and user name are exist';
                        }
                    } catch (Exception $e) {
                    }
                } else {
                    $errors['confirmPassword'] = 'not matched';
                }
            } else {
                return 'Fill All Fields';
                // return $this->errors;
            }
        }
    }

    function logout(){
        session_unset();
        session_destroy();
        header('Location:../index.php');
        exit();
    }

    function displayBooks($result){
        if (sizeof($result) > 0) {
            foreach ($result as $value) {

                echo ('  <div class="box">
                <div class="image">
                    <img src="../upload/' . $value['coverImage'] . '" alt="">    
                </div>
                <div class="content">
                    <h3>' . $value['name'] . '</h3>
                    <div class="price"> $' . $value['afterDiscount'] . ' <span style="display:block;">$' . $value['price']  . '</span> </div>
                </div>
                <div class="icons">');
                if ($value['stock'] == 0) {
                    echo '  <form action="' . $_SERVER['PHP_SELF'] . '" method="post" >
                    <input type="hidden" name="bookId" value="' . $value['bookId'] . '">
                    <input type="number" min="0" max="' . $value['stock'] . '" name="number" value="1" class="qty">
                    
                </form>';
                } else {
                    echo '  
                    <form action="' . $_SERVER['PHP_SELF'] . '" method="post">
                        <input type="hidden" name="bookId" value="' . $value['bookId'] . '">
                        <input type="number" min="0" max="' . $value['stock'] . '" name="number" value="1" class="qty">
                        <div class="fromContainer">
                        <div>
                        <input type="submit" value="add to cart" name="addToCart" class="btn">
                        
                        </div>
                    </form>

                    <form action="seemore.php" method="get">
                        <input type="hidden" name="bookId" value="' . $value['bookId'] . '">
                        <input type="submit" value="View"  name="showMore" class="btn option-btn">
                    </form>
                    </div>
                ';
                }

                echo ('
            
                        
                    </div>
                </div>'
                );
            }
        }else{
            echo('no');
        }


    }

    function displayDeleteAlert(){
        echo' <div class="delete-popup">

        <div class="delete-content">
            <h2>Confirm Delete?</h2>
            <div>
                <form action="'. $_SERVER['PHP_SELF'] .'" method="post">
                    <input type="hidden" name="delete-id" class="delete-id" value="">
                    <button type="submit" name="delete" data-id="" class="red-btn btn ">YES</button>
                    <button type="submit" name="cancel" class="btn green-btn cancel-btn">NO</button>
                </form>

            </div>
        </div>
    </div>';
    }