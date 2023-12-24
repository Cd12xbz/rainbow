<?php
session_start();

include '../class_db.php';
$db = new database();

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

//echo $username.' '.$passwd;
$sql = "SELECT * FROM login 
        WHERE username='$username'
        AND password =md5('$password')";
            if($db->jumrec($sql)==0) {
            echo 'Username dan Password sesuai';
            }
            else{
            echo 'Username dan Password tidak sesuai';
            $_SESSION['username'] = $username;
            //echo $_SESSION['username];
            header("Location: admin.php");
            exit();
        }
    }
?>