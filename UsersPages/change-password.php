<?php
$noNavAdmin = '';
$noNavUser = '';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require('../init.php');
require_once('../includes/connect.php');

try {
    $result = array();
    $getUserData = $con->prepare('SELECT * FROM users Where users.userId=?');
    if ($getUserData->execute(array($_SESSION['userId']))) {
        $result = $getUserData->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (Exception $e) {
}

if( isset($_POST['change'])  ){

    try {
        $getUserData = $con->prepare('SELECT password FROM users Where userId=?');
        if ($getUserData->execute(array($_SESSION['userId']))) {
            
            $pass = $getUserData->fetch(PDO::FETCH_ASSOC);
            $old=$pass['password'];
            if(empty($_POST['oldPassword'])){

            }else{
                $oldEntered=sha1($_POST['oldPassword']);
            }
            if(empty($_POST['newPassword'])){
        
            }else{
                $new= sha1($_POST['newPassword']);
            } 
            if(empty($_POST['confirmPassword'])){
        
            }else{
                $confirm=sha1($_POST['confirmPassword']);
            }

            if($oldEntered==$old){

                if($new==$confirm){

                    if($new==$old){
                        echo'<script>alert("Your old password not changed")</script>';
                    }else{
                        try{
                            $updatePrepare=$con->prepare('update users set password=? where userId=?');
                            if($updatePrepare->execute([$new,$_SESSION['userId']])){
                                echo'<script>alert("Your password changed")</script>';
                            }
                        }catch(Exception $e){
            
                        }
                        
                    }
                }else{
                    echo'<script>("Your old confirm password is incorrect")</script>';
                }
                
            }else{
                echo'<script>alert("Your old password is incorrect")</script>';
            }
        }
    } catch (Exception $e) {
    }
    
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Settings</title>
    <link rel="stylesheet" type="text/css" href="../CSS/change.css">

</head>

<body>
    
    <section>
        <form action="<?php echo $_SERVER['PHP_SELF']?>"  method="post">
            <h2>General Account Settings </h2>
            <label>User Name</label>
                <input type="text" placeholder="User Name" value=<?php echo $result[0]['userName'] ?> class="box">


                <label>Email</label>
                <input type="text" placeholder="Email" value=<?php echo $result[0]['email'] ?> class="box">


                <label>Old Password</label>
                <input type="password" name="oldPassword" placeholder="Old Password" class="box">


                <label>New Password</label>
                <input type="password" name="newPassword" placeholder="New Password"  class="box">


                <label>Confirm New Password</label>
                <input type="password" name="confirmPassword"placeholder="Confirm New Password"  class="box">


                <button type="submit" name="change" class="btn">CHANGE</button>

        </form>
    </section>



</body>

</html>