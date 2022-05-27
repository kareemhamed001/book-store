<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$noNavUser = '';
require_once('../init.php');

if (isset($_GET['deleteAdmin'])) {
    try {
        $prepare = $con->prepare('DELETE FROM users WHERE userId=?');
        
        if ($prepare->execute(array($_GET['userId']))) {
            header('location:users.php');
        }
    } catch (PDOException $e) {
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

    <link rel="stylesheet" href=<?php echo  $css . "admin_style.css" ?>>
    <title>Document</title>
</head>

<body>

    <?php displayDeleteAlert();?>

    <section class="admins">
        <h2>Admins</h2>
        <table>
            <tr>
                <th style="border-top-left-radius:10px;">Id</th>
                <th>Name</th>
                <th>Email</th>
                <th>Full Name</th>
                <th>Type</th>
                <th style="border-top-right-radius:10px;"></th>
            </tr>

            <?php
            try {
                $fetchPrepare = $con->prepare('SELECT userId,userName,email,CONCAT(firstName ," ", lastName) AS fullName FROM admins where userId <>? ');
                $fetchPrepare->execute([$_SESSION['userId']]);
                $result = $fetchPrepare->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
            }
            if (count($result) > 0) {
                foreach ($result as $value) {
                    echo ' <tr>
                <td> ' . htmlspecialchars($value['userId']) . '</td>
            <td>' . htmlspecialchars($value['userName']) . '</td>
            <td>' . htmlspecialchars($value['email']) . '</td>
            <td>' . htmlspecialchars($value['fullName']) . '</td>
            
            <td>Admin</td>
            <td style="text-align:end;">
            <form action="' . $_SERVER['PHP_SELF'] . '" >
            <input type="hidden" name="userId" value="' . $value['userId'] . '" >
            
            <button name="deleteAdmin" class="btn green-btn">Delete</button>
            </form>
            </td>
    
            </tr>';
                }
            }

            ?>
        </table>


        <a href="admin_new_user.php" class="green-btn btn"
            style="align-self:flex-end; margin-top:0.5em;position:sticky;top:0">Add New
            Admin</a>


    </section>
    <section class="admins">
        <h2>Users</h2>
        <table>
            <tr>
                <th style="border-top-left-radius:10px;">Id</th>
                <th>Name</th>
                <th>Email</th>
                <th>Full Name</th>
                <th>Type</th>
                <th style="border-top-right-radius:10px;"></th>
            </tr>

            <?php
            try {
                $fetchPrepare = $con->prepare('SELECT userId,userName,email,CONCAT(firstName ," ", lastName) AS fullName FROM buyers ');
                $fetchPrepare->execute();
                $result = $fetchPrepare->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
            }
            if (count($result) > 0) {
                foreach ($result as $value) {
                    echo ' <tr>
                <td> ' . htmlspecialchars($value['userId']) . '</td>
            <td>' . htmlspecialchars($value['userName']) . '</td>
            <td>' . htmlspecialchars($value['email']) . '</td>
            <td>' . htmlspecialchars($value['fullName']) . '</td>
            
            <td>User</td>
            <td style="text-align:end;">
            <form action="' . $_SERVER['PHP_SELF'] . '" >
            <input type="hidden" name="userId" value="' . $value['userId'] . '" >
            
            <button name="deleteAdmin" class="btn green-btn">Delete</button>
            </form>
            </td>
    
            </tr>';
                }
            }

            ?>
        </table>





    </section>

    <script src="../js/admin_script.js">
    </script>
</body>

</html>