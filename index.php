<?php
session_start();
$noNavAdmin = '';
$noNavUser = '';
require('init.php');



?>

<!DOCTYPE html>
<html>
<head>
	<title>Login & Registration Form</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="CSS/login11.css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700,800&display=swap" rel="stylesheet">
</head>
<body background="">
  <div class="cont">
    <div class="form sign-in">
      <h2>Sign In</h2>


      <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
      <label>
      <span id='errorSpan' >Incorrect email or password</span>
        <span>Email Address</span>
        <input type="email" name="email" placeholder="enter your email" required>
      </label>
      <label>
        <span>Password</span>
        <input type="password" name="password" placeholder="enter your password" required >

      </label>
      <button class="submit" type="submit" name="login">Sign In</button>
      </form>
    
    </div>

    <div class="sub-cont">
      <div class="img">
        <div class="img-text m-up">
          <h1>New here?</h1>
          <p>sign up and discover</p>
        </div>
        <div class="img-text m-in">
          <h1>One of us?</h1>
          <p>just sign in</p>
        </div>
        <div class="img-btn">
          <span class="m-up">Sign Up</span>
          <span class="m-in">Sign In</span>
        </div>
      </div>
      <div class="form sign-up">
        <h2>Sign Up</h2>

        <form action="<?php echo $_SERVER['PHP_SELF']?>" method='post'>

        <label>
          <span>Name</span>
          <input type="text" name="userName">
        </label>
        <label>
          <span>Email</span>
          <input type="email" name="email">
        </label>
        <label>
          <span>Password</span>
          <input type="password" name="password">
        </label>
        <label>
          <span>Confirm Password</span>
          <input type="password" name="confirmPassword">
        </label>
        <button type="submit" name="signup" class="submit">Sign Up Now</button>
        </form>

      </div>
    </div>
  </div>
<script>
document.querySelector('.img-btn').addEventListener('click', function()
	{
		document.querySelector('.cont').classList.toggle('s-signup')
	}
);
</script>  

  </body>
</html>


<?php
$errors=[];

if (isset($_POST['login'])) {
  
  if (empty($_POST['email'])) {
      $errors['email'] = 'Required*';
  } else {
      $values['email'] = htmlspecialchars($_POST['email']);
  }

  if (empty($_POST['password'])) {
      $errors['password'] = 'Required*';
  } else {
      $values['password'] = sha1(htmlspecialchars($_POST['password']));
  }

  if (!empty($values['email']) || empty($values['password'])) {

      try {
          $prepare = $con->prepare("SELECT * FROM users WHERE email=?  AND password=? ");
          $prepare->execute(array($values['email'], $values['password']));
          $count = $prepare->rowCount();
          $result = $prepare->fetchAll(PDO::FETCH_ASSOC);

          if ($count > 0) {
            
              $userId = $result[0]['userId'];
              $userGroup = $result[0]['userGroup'];
              
              $_SESSION['userId'] = $userId;
              $_SESSION['userGroup'] = $userGroup;


              if ($userGroup == 1) {
                  $prepareLog=$con->prepare('Insert into log (userId,time,message)Values(?,now(),?)');
                  if($prepareLog->execute(array($userId,'in'))){
                      header('Location:UsersPages/home.php');
                  }
              } elseif ($userGroup == 0) {
                  $prepareLog=$con->prepare('Insert into log (userId,time,message)Values(?,now(),?)');
                  if($prepareLog->execute(array($userId,'in'))){
                      header("Location:AdminPages/index.php");
                  }
              }elseif ($userGroup == 2) {
                $prepareLog=$con->prepare('Insert into log (userId,time,message)Values(?,now(),?)');
                if($prepareLog->execute(array($userId,'in'))){
                    header("Location:AdminPages/index.php");
                }
            }
          } else {
              $errors['global'] = 'Incorrect email or password';
              echo('<script >
              let errorSpan=document.getElementById("errorSpan");
              errorSpan.style.display="block";
              setInterval(function () {
                errorSpan.style.display="none";
              }, 3000);
              </script>');
          }
      } catch (PDOException $e) {
      }
  }
}



if (isset($_POST['signup'])) {

    if (empty($_POST['userName'])) {
        $errors['userName'] = 'Required*';
    } else {
        $values['userName'] = htmlspecialchars($_POST['userName']);
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

    if (!(empty($values['userName'])|| empty($values['email']) || empty($values['password']) || empty($values['confirmPassword']))) {

        if ($values['password'] === $values['confirmPassword']) {
            try {
                
                $prepare = $con->prepare("SELECT * FROM users WHERE email=? ");
                $prepare->execute(array($values['email']));
                $count = $prepare->rowCount();

                $prepareUserName = $con->prepare("SELECT * FROM users WHERE userName=? ");
                $prepareUserName->execute(array($values['userName']));
                $countUserName = $prepareUserName->rowCount();

                if ($countUserName > 0) {
                    $errors[0]= 'This user name is exist';
                }
                if ($count > 0) {
                  $errors[1] ='This email is exist';
                }

                if (!($count > 0 || $countUserName > 0)) {
                    try {
                        $prepare = $con->prepare('INSERT INTO users(userName,email,password,userGroup) VALUES(?,?,sha1(?),?)');
                        if ($prepare->execute(array($values['userName'], $values['email'], $values['password'], 1))) {
                            echo('<script>alert("Created successfully")</script>');
                        } else {
                            
                        }
                    } catch (PDOException $e) {
                    }
                } else {
                  $errors[0]= 'This email and user name are exist';
                }
            } catch (Exception $e) {
            }
        } else {
            $errors['confirmPassword'] = 'not matched';
        }
    } else {
      $errors[0]= 'Fill All Fields';
        // return $this->errors;
    }
}
?>