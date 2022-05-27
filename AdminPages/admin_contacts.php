<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
$noNavUser = '';
include('../init.php');

if(isset($_POST['deleteMessage'])){
    try{
        $prepareDeleteMessages=$con->prepare('delete from contact where contactId=?');
        if($prepareDeleteMessages->execute([$_POST['id']])){
            header('location:javascript://history.go(1)');
        }

    }catch(Exception $e){

    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>messages</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom admin css file link  -->
    <link rel="stylesheet" href=<?php echo $css . 'admin_style.css' ?>>

</head>

<body>



    <section class="messages">

        <h1 class="title"> messages </h1>

        <div class="box-container">
            <?php

                try{
                    $prepareMessages=$con->prepare('select * from contact join users on users.userId=contact.userId');
                    
                    if($prepareMessages->execute()){
                        
                        $result=$prepareMessages->fetchAll(PDO::FETCH_ASSOC);
                        if(count($result)>0){
                            foreach($result as $value){
                                echo('
                                <div class="box">
                    <p> user id :'.$value['userId'].' <span>
    
                        </span> </p>
                    <p> name :'.$value['firstName'].' '.$value['lastName'].' <span>
    
                        </span> </p>
                    <p> number :'.$value['phoneNumber'].' <span>
    
                        </span> </p>
                    <p> email : '.$value['email'].'<span>
    
                        </span> </p>
                    <p> message :'.$value['message'].' <span>
    
                        </span> </p>

                        <form action="'.$_SERVER['PHP_SELF'].'" method="post">

                        <input type="hidden" name="id" value="'.$value['contactId'].'">
                        <button name="deleteMessage" type="submit" class="option-btn">delete message</button>

                        </form>
                    
                </div>
                                
                                ');
                            }
                        }
                    }
                }catch(Exception $e){

                }
            ?>

        </div>

    </section>
    <!-- custom admin js file link  -->

    <script src="../js/admin_script.js">
    </script>
</body>

</html>