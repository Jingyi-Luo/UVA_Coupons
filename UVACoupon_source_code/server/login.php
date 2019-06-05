<?php
    require "dbconnection.php";

    session_start();
    $sql = '';

    if(!isset($_SESSION['user'])){
        $_POST = json_decode(file_get_contents("php://input"),true);
        if(isset($_POST["name"]) && isset($_POST["email"])){
            $sql = "SELECT * FROM coupon_user WHERE name = '$_POST[name]' AND email = '$_POST[email]'";
            $result = mysqli_query($conn, $sql);
            if ($result && mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    break;
                }
                $_SESSION['user'] = $row;
                http_response_code(200);
                echo json_encode($_SESSION['user']);
            }else{
                http_response_code(403);
                die("LOGIN ERROR! USER NOT FOUND");
            }
        }else{
            http_response_code(404);
        }
    }else{
        echo json_encode($_SESSION['user']);
    }
?>